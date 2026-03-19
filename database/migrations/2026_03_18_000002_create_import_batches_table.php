<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('import_batches', function (Blueprint $table): void {
            $table->id();
            $table->string('supplier_name');
            $table->string('file_path');
            $table->enum('status', ['uploaded', 'processing', 'review', 'published', 'failed'])->default('uploaded');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('total_items')->default(0);
            $table->unsignedInteger('approved_items')->default(0);
            $table->unsignedInteger('rejected_items')->default(0);
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_batches');
    }
};
