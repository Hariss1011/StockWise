<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function currentStock(Product $product): int
    {
        $stockIn = $product->stockIns()->sum('quantity');
        $stockOut = $product->stockOuts()->sum('quantity');
        $adjustment = $product->stockAdjustments()->sum('quantity_change');

        return (int) $product->initial_stock
            + (int) $stockIn
            - (int) $stockOut
            + (int) $adjustment;
    }

    public function recordIn(array $data): StockIn
    {
        return DB::transaction(function () use ($data) {
            Product::whereKey($data['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            return StockIn::create($data);
        });
    }

    public function recordOut(array $data): StockOut
    {
        return DB::transaction(function () use ($data) {
            $product = Product::whereKey($data['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($this->currentStock($product) < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok tidak mencukupi untuk transaksi ini.',
                ]);
            }

            return StockOut::create($data);
        });
    }

    public function recordAdjustment(array $data): StockAdjustment
    {
        return DB::transaction(function () use ($data) {
            $product = Product::whereKey($data['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $newStock = $this->currentStock($product)
                + $data['quantity_change'];

            if ($newStock < 0) {
                throw ValidationException::withMessages([
                    'quantity_change' => 'Penyesuaian ditolak karena stok akan menjadi negatif.',
                ]);
            }

            return StockAdjustment::create($data);
        });
    }
}
