<?php

namespace App\Actions\Import;

use App\Models\AuditEvent;
use App\Models\ImportBatch;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PublishImportBatchAction
{
    public function execute(ImportBatch $batch): void
    {
        $this->assertBatchIsPublishable($batch);

        DB::transaction(function () use ($batch): void {
            $approvedItems = $batch->items()->where('status', 'approved')->get();

            foreach ($approvedItems as $item) {
                $data = $item->normalized_data ?? [];
                $name = Arr::get($data, 'name', 'Produto sem nome');
                $sku = Arr::get($data, 'sku');

                if (! $sku) {
                    continue;
                }

                Product::updateOrCreate(
                    ['sku' => $sku],
                    [
                        'name' => $name,
                        'slug' => Str::slug($name.'-'.$sku),
                        'description' => Arr::get($data, 'description'),
                        'price' => Arr::get($data, 'price', 0),
                        'cost' => Arr::get($data, 'cost'),
                        'stock' => Arr::get($data, 'stock', 0),
                        'status' => 'draft',
                        'source' => 'pdf_import',
                    ]
                );

                $item->update(['status' => 'published']);
            }

            $batch->update([
                'status' => 'published',
                'published_at' => now(),
            ]);

            AuditEvent::create([
                'event' => 'import_batch.published',
                'actor_id' => auth()->id(),
                'entity_type' => 'import_batch',
                'entity_id' => $batch->id,
                'metadata' => [
                    'published_items' => $approvedItems->count(),
                ],
            ]);
        });
    }

    private function assertBatchIsPublishable(ImportBatch $batch): void
    {
        $reviewPending = $batch->items()->where('status', 'review')->exists();

        if ($reviewPending) {
            throw ValidationException::withMessages([
                'batch' => 'Existem itens pendentes de revisão. Resolva antes de publicar.',
            ]);
        }

        $approvedWithErrors = $batch->items()
            ->where('status', 'approved')
            ->whereNotNull('errors')
            ->get()
            ->filter(fn ($item) => ! empty($item->errors))
            ->isNotEmpty();

        if ($approvedWithErrors) {
            throw ValidationException::withMessages([
                'batch' => 'Há itens aprovados com erros bloqueantes. Corrija antes de publicar.',
            ]);
        }
    }
}
