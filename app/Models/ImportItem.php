<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'row_number',
        'sku',
        'raw_extraction',
        'normalized_data',
        'errors',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'raw_extraction' => 'array',
            'normalized_data' => 'array',
            'errors' => 'array',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ImportBatch::class, 'batch_id');
    }
}
