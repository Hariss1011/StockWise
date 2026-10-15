<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToCollection, WithStartRow
{
    public int $importedCount = 0;

    public function startRow(): int
    {
        // Baris pertama berisi header.
        return 2;
    }

    public function collection(Collection $rows): void
    {
        $errors = [];
        $preparedRows = [];
        $seenSkus = [];

        $categories = Category::all()->keyBy(
            fn($category) => mb_strtolower(trim($category->name))
        );

        $suppliers = Supplier::all()->keyBy(
            fn($supplier) => mb_strtolower(trim($supplier->name))
        );

        // Abaikan baris yang benar-benar kosong.
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $values = $row->toArray();

            if (count(array_filter(
                $values,
                fn($value) => $value !== null && trim((string) $value) !== ''
            )) === 0) {
                continue;
            }

            $sku = trim((string) ($row[0] ?? ''));
            $name = trim((string) ($row[1] ?? ''));
            $categoryName = trim((string) ($row[2] ?? ''));
            $supplierName = trim((string) ($row[3] ?? ''));
            $unit = trim((string) ($row[4] ?? ''));
            $purchasePrice = $row[5] ?? null;
            $sellingPrice = $row[6] ?? null;
            $initialStock = $row[7] ?? null;
            $minimumStock = $row[8] ?? null;
            $description = trim((string) ($row[9] ?? ''));

            $addError = function (string $message) use (&$errors, $rowNumber) {
                $errors[] = "Baris {$rowNumber}: {$message}";
            };

            if ($sku === '') {
                $addError('SKU wajib diisi.');
            } elseif (mb_strlen($sku) > 50) {
                $addError('SKU maksimal 50 karakter.');
            } else {
                $normalizedSku = mb_strtolower($sku);

                if (isset($seenSkus[$normalizedSku])) {
                    $addError("SKU {$sku} duplikat di dalam file.");
                }

                $seenSkus[$normalizedSku] = true;
            }

            if ($name === '') {
                $addError('Nama produk wajib diisi.');
            } elseif (mb_strlen($name) > 150) {
                $addError('Nama produk maksimal 150 karakter.');
            }

            $category = $categories->get(mb_strtolower($categoryName));

            if ($categoryName === '' || ! $category) {
                $addError("Kategori '{$categoryName}' tidak ditemukan.");
            }

            $supplier = null;

            if ($supplierName !== '') {
                $supplier = $suppliers->get(mb_strtolower($supplierName));

                if (! $supplier) {
                    $addError("Supplier '{$supplierName}' tidak ditemukan.");
                }
            }

            if ($unit === '') {
                $addError('Satuan wajib diisi.');
            } elseif (mb_strlen($unit) > 30) {
                $addError('Satuan maksimal 30 karakter.');
            }

            $numericFields = [
                'Harga beli' => $purchasePrice,
                'Harga jual' => $sellingPrice,
                'Stok awal' => $initialStock,
                'Stok minimum' => $minimumStock,
            ];

            foreach ($numericFields as $label => $value) {
                if ($value === null || $value === '' || ! is_numeric($value)) {
                    $addError("{$label} wajib berupa angka.");
                } elseif (! is_finite((float) $value)) {
                    $addError("{$label} tidak valid.");
                }
            }

            foreach (
                [
                    'Stok awal' => $initialStock,
                    'Stok minimum' => $minimumStock,
                ] as $label => $value
            ) {
                if (
                    is_numeric($value)
                    && (
                        (float) $value < 0
                        || floor((float) $value) !== (float) $value
                    )
                ) {
                    $addError("{$label} harus berupa bilangan bulat minimal 0.");
                }
            }

            if (
                is_numeric($purchasePrice)
                && (float) $purchasePrice < 0
            ) {
                $addError('Harga beli tidak boleh negatif.');
            }

            if (
                is_numeric($sellingPrice)
                && (float) $sellingPrice < 0
            ) {
                $addError('Harga jual tidak boleh negatif.');
            }

            if (
                is_numeric($purchasePrice)
                && is_numeric($sellingPrice)
                && (
                    (float) $purchasePrice > 9999999999.99
                    || (float) $sellingPrice > 9999999999.99
                )
            ) {
                $addError('Harga melebihi batas yang didukung.');
            }

            if (
                $sku !== ''
                && $name !== ''
                && $category
                && $unit !== ''
                && is_numeric($purchasePrice)
                && is_numeric($sellingPrice)
                && is_numeric($initialStock)
                && is_numeric($minimumStock)
                && (float) $purchasePrice >= 0
                && (float) $sellingPrice >= 0
                && (float) $purchasePrice <= 9999999999.99
                && (float) $sellingPrice <= 9999999999.99
                && (float) $initialStock >= 0
                && floor((float) $initialStock) === (float) $initialStock
                && (float) $minimumStock >= 0
                && floor((float) $minimumStock) === (float) $minimumStock
                && mb_strlen($sku) <= 50
                && mb_strlen($name) <= 150
                && mb_strlen($unit) <= 30
                && ($supplierName === '' || $supplier)
            ) {
                $preparedRows[] = [
                    'sku' => $sku,
                    'name' => $name,
                    'category_id' => $category->id,
                    'supplier_id' => $supplier?->id,
                    'unit' => $unit,
                    'purchase_price' => $purchasePrice,
                    'selling_price' => $sellingPrice,
                    'initial_stock' => (int) $initialStock,
                    'minimum_stock' => (int) $minimumStock,
                    'description' => $description !== '' ? $description : null,
                ];
            }
        }

        // Cek SKU yang sudah ada di database.
        $skus = collect($preparedRows)
            ->pluck('sku')
            ->unique()
            ->values();

        $existingSkus = Product::query()
            ->whereIn('sku', $skus)
            ->pluck('sku')
            ->map(fn($sku) => mb_strtolower($sku))
            ->all();

        foreach ($preparedRows as $product) {
            if (in_array(mb_strtolower($product['sku']), $existingSkus, true)) {
                $errors[] = "SKU {$product['sku']} sudah ada di database.";
            }
        }

        // Jangan menyimpan satu pun data jika ada kesalahan.
        if ($errors !== []) {
            throw ValidationException::withMessages([
                'file' => $errors,
            ]);
        }

        if ($preparedRows === []) {
            throw ValidationException::withMessages([
                'file' => ['File tidak memiliki data produk untuk diimpor.'],
            ]);
        }

        DB::transaction(function () use ($preparedRows) {
            foreach ($preparedRows as $product) {
                Product::create($product);
            }
        });

        $this->importedCount = count($preparedRows);
    }
}
