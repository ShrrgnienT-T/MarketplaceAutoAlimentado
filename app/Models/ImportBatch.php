<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'file_path',
        'status',
        'uploaded_by',
        'processed_at',
        'published_at',
        'total_items',
        'approved_items',
        'rejected_items',
    ];

    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
            'published_at' => 'datetime',
            'total_items' => 'integer',
            'approved_items' => 'integer',
            'rejected_items' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(ImportItem::class, 'batch_id');
    }
}
