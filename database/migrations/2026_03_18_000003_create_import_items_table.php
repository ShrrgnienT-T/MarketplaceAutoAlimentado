<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('import_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('batch_id')->constrained('import_batches')->cascadeOnDelete();
            $table->unsignedInteger('row_number');
            $table->string('sku')->nullable();
            $table->json('raw_extraction')->nullable();
            $table->json('normalized_data')->nullable();
            $table->json('errors')->nullable();
            $table->enum('status', ['pending', 'review', 'approved', 'rejected', 'published'])->default('review');
            $table->timestamps();

            $table->index(['batch_id', 'status']);
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_items');
    }
};
