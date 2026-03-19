<?php

namespace App\Actions\Import;

use App\Models\AuditEvent;
use App\Models\ImportBatch;
use App\Models\ImportItem;

class ReviewImportItemAction
{
    public function execute(ImportBatch $batch, ImportItem $item, string $decision): void
    {
        if ($item->batch_id !== $batch->id) {
            abort(404);
        }

        if (! in_array($decision, ['approved', 'rejected'], true)) {
            abort(422, 'Decisão inválida');
        }

        if ($item->status === 'published') {
            abort(422, 'Item já publicado não pode ser alterado.');
        }

        $item->update(['status' => $decision]);

        $approved = $batch->items()->where('status', 'approved')->count();
        $rejected = $batch->items()->where('status', 'rejected')->count();

        $batch->update([
            'approved_items' => $approved,
            'rejected_items' => $rejected,
            'total_items' => $batch->items()->count(),
            'status' => 'review',
        ]);

        AuditEvent::create([
            'event' => 'import_item.'.$decision,
            'actor_id' => auth()->id(),
            'entity_type' => 'import_item',
            'entity_id' => $item->id,
            'metadata' => [
                'batch_id' => $batch->id,
                'sku' => $item->sku,
            ],
        ]);
    }
}
