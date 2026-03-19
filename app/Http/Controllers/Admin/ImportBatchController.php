<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Import\CreateImportBatchAction;
use App\Actions\Import\PublishImportBatchAction;
use App\Actions\Import\ReviewImportItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Import\StoreImportBatchRequest;
use App\Models\ImportBatch;
use App\Models\ImportItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ImportBatchController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', ImportBatch::class);

        $batches = ImportBatch::query()->latest()->paginate(15);

        return view('admin.imports.index', compact('batches'));
    }

    public function store(StoreImportBatchRequest $request, CreateImportBatchAction $createImportBatchAction): RedirectResponse
    {
        $this->authorize('create', ImportBatch::class);

        $createImportBatchAction->execute(
            supplierName: $request->string('supplier_name')->toString(),
            pdfFile: $request->file('catalog_pdf'),
            uploadedBy: auth()->id(),
        );

        return redirect()
            ->route('admin.imports.index')
            ->with('success', 'Batch de importação criado e enviado para processamento.');
    }

    public function show(ImportBatch $batch): View
    {
        $this->authorize('view', $batch);

        $batch->load(['items' => fn ($query) => $query->orderBy('row_number')]);

        return view('admin.imports.show', compact('batch'));
    }

    public function publish(ImportBatch $batch, PublishImportBatchAction $publishImportBatchAction): RedirectResponse
    {
        $this->authorize('publish', $batch);

        try {
            $publishImportBatchAction->execute($batch);
        } catch (ValidationException $exception) {
            return redirect()
                ->route('admin.imports.show', $batch)
                ->withErrors($exception->errors());
        }

        return redirect()
            ->route('admin.imports.show', $batch)
            ->with('success', 'Itens aprovados foram publicados em produtos.');
    }

    public function approveItem(ImportBatch $batch, ImportItem $item, ReviewImportItemAction $reviewImportItemAction): RedirectResponse
    {
        $this->authorize('review', $batch);

        $reviewImportItemAction->execute($batch, $item, 'approved');

        return redirect()
            ->route('admin.imports.show', $batch)
            ->with('success', 'Item aprovado com sucesso.');
    }

    public function rejectItem(ImportBatch $batch, ImportItem $item, ReviewImportItemAction $reviewImportItemAction): RedirectResponse
    {
        $this->authorize('review', $batch);

        $reviewImportItemAction->execute($batch, $item, 'rejected');

        return redirect()
            ->route('admin.imports.show', $batch)
            ->with('success', 'Item rejeitado com sucesso.');
    }
}
