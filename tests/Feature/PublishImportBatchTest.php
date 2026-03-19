<?php

namespace Tests\Feature;

use App\Actions\Import\PublishImportBatchAction;
use App\Models\ImportBatch;
use App\Models\ImportItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PublishImportBatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_publish_action_upserts_approved_items_and_creates_audit_event(): void
    {
        $admin = User::factory()->admin()->create();

        $batch = ImportBatch::create([
            'supplier_name' => 'Fornecedor Teste',
            'file_path' => 'imports/catalog.pdf',
            'status' => 'review',
            'total_items' => 1,
            'approved_items' => 1,
            'rejected_items' => 0,
        ]);

        ImportItem::create([
            'batch_id' => $batch->id,
            'row_number' => 1,
            'sku' => 'SKU-2',
            'normalized_data' => [
                'sku' => 'SKU-2',
                'name' => 'Produto Teste',
                'price' => 99.9,
                'stock' => 3,
            ],
            'status' => 'approved',
            'errors' => [],
        ]);

        $this->actingAs($admin);
        app(PublishImportBatchAction::class)->execute($batch->fresh());

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-2',
            'name' => 'Produto Teste',
            'source' => 'pdf_import',
        ]);

        $this->assertDatabaseHas('audit_events', [
            'event' => 'import_batch.published',
            'entity_type' => 'import_batch',
            'entity_id' => $batch->id,
            'actor_id' => $admin->id,
        ]);

        $this->assertEquals(1, Product::where('sku', 'SKU-2')->count());
    }

    public function test_publish_is_blocked_when_batch_has_review_pending_items(): void
    {
        $batch = ImportBatch::create([
            'supplier_name' => 'Fornecedor Teste',
            'file_path' => 'imports/catalog.pdf',
            'status' => 'review',
            'total_items' => 1,
            'approved_items' => 0,
            'rejected_items' => 0,
        ]);

        ImportItem::create([
            'batch_id' => $batch->id,
            'row_number' => 1,
            'sku' => 'SKU-PENDING',
            'normalized_data' => ['sku' => 'SKU-PENDING', 'name' => 'Pendente'],
            'status' => 'review',
        ]);

        $this->expectException(ValidationException::class);

        app(PublishImportBatchAction::class)->execute($batch);
    }

    public function test_seller_cannot_publish_batch_by_http_endpoint(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);

        $batch = ImportBatch::create([
            'supplier_name' => 'Fornecedor Teste',
            'file_path' => 'imports/catalog.pdf',
            'status' => 'review',
            'total_items' => 0,
            'approved_items' => 0,
            'rejected_items' => 0,
        ]);

        $this->actingAs($seller)
            ->post(route('admin.imports.publish', $batch))
            ->assertForbidden();
    }
}
