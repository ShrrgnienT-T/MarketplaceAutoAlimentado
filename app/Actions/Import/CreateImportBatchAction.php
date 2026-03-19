<?php

namespace App\Actions\Import;

use App\Jobs\PopulateImportItemsFakeJob;
use App\Models\ImportBatch;
use Illuminate\Http\UploadedFile;

class CreateImportBatchAction
{
    public function execute(string $supplierName, UploadedFile $pdfFile, ?int $uploadedBy = null): ImportBatch
    {
        $filePath = $pdfFile->store('imports');

        $batch = ImportBatch::create([
            'supplier_name' => $supplierName,
            'file_path' => $filePath,
            'status' => 'uploaded',
            'uploaded_by' => $uploadedBy,
        ]);

        PopulateImportItemsFakeJob::dispatch($batch->id);

        return $batch;
    }
}
