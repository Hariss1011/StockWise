<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOut extends Model
{
    protected $fillable = [
        'product_id',
        'reference_number',
        'quantity',
        'recipient',
        'transaction_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'transaction_date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
