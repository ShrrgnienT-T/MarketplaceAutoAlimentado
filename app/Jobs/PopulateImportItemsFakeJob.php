<?php

namespace App\Jobs;

use App\Models\ImportBatch;
use App\Models\ImportItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PopulateImportItemsFakeJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $batchId)
    {
    }

    public function handle(): void
    {
        $batch = ImportBatch::findOrFail($this->batchId);

        $batch->update(['status' => 'processing']);

        $fakeRows = [
            ['sku' => 'IMP-1001', 'name' => 'Filtro de Óleo Pro', 'price' => 49.90, 'stock' => 15],
            ['sku' => 'IMP-1002', 'name' => 'Pastilha de Freio X', 'price' => 159.90, 'stock' => 8],
            ['sku' => null, 'name' => 'Item sem SKU', 'price' => 79.90, 'stock' => 5],
        ];

        foreach ($fakeRows as $index => $row) {
            $errors = [];

            if (! $row['sku']) {
                $errors[] = 'SKU obrigatório ausente';
            }

            ImportItem::create([
                'batch_id' => $batch->id,
                'row_number' => $index + 1,
                'sku' => $row['sku'],
                'raw_extraction' => $row,
                'normalized_data' => $row,
                'errors' => $errors,
                'status' => empty($errors) ? 'approved' : 'review',
            ]);
        }

        $batch->update([
            'status' => 'review',
            'processed_at' => now(),
            'total_items' => count($fakeRows),
            'approved_items' => 2,
            'rejected_items' => 0,
        ]);
    }
}
