<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductTemplateExport implements FromArray, WithHeadings, WithColumnFormatting
{
    public function headings(): array
    {
        return [
            'SKU',
            'Nama Produk',
            'Kategori',
            'Supplier',
            'Satuan',
            'Harga Beli',
            'Harga Jual',
            'Stok Awal',
            'Stok Minimum',
            'Deskripsi',
        ];
    }

    public function array(): array
    {
        return [];
    }

    public function columnFormats(): array
    {
        // Kolom SKU diperlakukan sebagai teks.
        return [
            'A' => '@',
        ];
    }
}
