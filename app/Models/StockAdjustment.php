<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    protected $fillable = [
        'product_id',
        'quantity_change',
        'reason',
        'adjustment_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity_change' => 'integer',
            'adjustment_date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
