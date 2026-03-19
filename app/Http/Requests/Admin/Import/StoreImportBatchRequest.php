<?php

namespace App\Http\Requests\Admin\Import;

use Illuminate\Foundation\Http\FormRequest;

class StoreImportBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_name' => ['required', 'string', 'max:120'],
            'catalog_pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ];
    }
}
