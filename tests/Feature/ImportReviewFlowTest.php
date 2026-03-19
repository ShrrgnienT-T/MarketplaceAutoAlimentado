<?php

namespace Tests\Feature;

use App\Models\AuditEvent;
use App\Models\ImportBatch;
use App\Models\ImportItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportReviewFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_and_reject_review_items(): void
    {
        $admin = User::factory()->admin()->create();

        $batch = ImportBatch::create([
            'supplier_name' => 'Fornecedor Teste',
            'file_path' => 'imports/catalog.pdf',
            'status' => 'review',
            'total_items' => 1,
            'approved_items' => 0,
            'rejected_items' => 0,
        ]);

        $item = ImportItem::create([
            'batch_id' => $batch->id,
            'row_number' => 1,
            'sku' => 'SKU-1',
            'normalized_data' => ['sku' => 'SKU-1', 'name' => 'Item A', 'price' => 10],
            'status' => 'review',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.imports.items.approve', [$batch, $item]))
            ->assertRedirect(route('admin.imports.show', $batch));

        $this->assertDatabaseHas('import_items', [
            'id' => $item->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('audit_events', [
            'event' => 'import_item.approved',
            'entity_type' => 'import_item',
            'entity_id' => $item->id,
            'actor_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.imports.items.reject', [$batch, $item]))
            ->assertRedirect(route('admin.imports.show', $batch));

        $this->assertDatabaseHas('import_items', [
            'id' => $item->id,
            'status' => 'rejected',
        ]);

        $this->assertDatabaseHas('audit_events', [
            'event' => 'import_item.rejected',
            'entity_type' => 'import_item',
            'entity_id' => $item->id,
            'actor_id' => $admin->id,
        ]);
    }

    public function test_seller_cannot_review_import_item(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);

        $batch = ImportBatch::create([
            'supplier_name' => 'Fornecedor Teste',
            'file_path' => 'imports/catalog.pdf',
            'status' => 'review',
            'total_items' => 1,
            'approved_items' => 0,
            'rejected_items' => 0,
        ]);

        $item = ImportItem::create([
            'batch_id' => $batch->id,
            'row_number' => 1,
            'sku' => 'SKU-1',
            'normalized_data' => ['sku' => 'SKU-1', 'name' => 'Item A', 'price' => 10],
            'status' => 'review',
        ]);

        $this->actingAs($seller)
            ->post(route('admin.imports.items.approve', [$batch, $item]))
            ->assertForbidden();
    }
}
