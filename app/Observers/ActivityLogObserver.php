<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        $this->record($model, 'created');
    }

    public function updated(Model $model): void
    {
        $this->record($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        $this->record($model, 'deleted');
    }

    private function record(Model $model, string $action): void
    {
        // Abaikan perubahan yang tidak dilakukan dalam sesi pengguna.
        // Misalnya proses seeder atau perintah console.
        if (! Auth::check()) {
            return;
        }

        $module = $this->getModule($model);

        // Abaikan model yang tidak termasuk cakupan audit.
        if ($module === null) {
            return;
        }

        $subjectName = $this->getSubjectName($model);
        $actionLabel = match ($action) {
            'created' => 'menambahkan',
            'updated' => 'memperbarui',
            'deleted' => 'menghapus',
            default => 'mengubah',
        };

        $description = "{$actionLabel} {$module}: {$subjectName}";

        $properties = null;

        if ($action === 'updated') {
            $changes = $model->getChanges();

            // Jangan pernah menyimpan password atau token ke log.
            unset(
                $changes['password'],
                $changes['remember_token'],
                $changes['updated_at']
            );

            if ($changes !== []) {
                $properties = [
                    'changes' => $changes,
                ];
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'subject_type' => class_basename($model),
            'subject_id' => $model->getKey(),
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => substr(
                (string) request()->userAgent(),
                0,
                2000
            ),
        ]);
    }

    private function getModule(Model $model): ?string
    {
        return match (true) {
            $model instanceof Product => 'Produk',
            $model instanceof Category => 'Kategori',
            $model instanceof Supplier => 'Supplier',
            $model instanceof StockIn => 'Stok Masuk',
            $model instanceof StockOut => 'Stok Keluar',
            $model instanceof StockAdjustment => 'Penyesuaian Stok',
            $model instanceof User => 'Pengguna',
            default => null,
        };
    }

    private function getSubjectName(Model $model): string
    {
        if (
            $model instanceof StockIn
            || $model instanceof StockOut
            || $model instanceof StockAdjustment
        ) {
            $product = Product::find($model->getAttribute('product_id'));

            $productName = $product
                ? $this->getSubjectName($product)
                : 'produk tidak ditemukan';

            $quantity = $model instanceof StockAdjustment
                ? $model->getAttribute('quantity_change')
                : $model->getAttribute('quantity');

            return "{$productName} (jumlah: {$quantity})";
        }

        if ($model instanceof User) {
            return (string) (
                $model->getAttribute('name')
                ?? $model->getAttribute('email')
                ?? $model->getKey()
            );
        }

        return (string) (
            $model->getAttribute('name')
            ?? $model->getAttribute('sku')
            ?? $model->getAttribute('email')
            ?? $model->getKey()
        );
    }
}
