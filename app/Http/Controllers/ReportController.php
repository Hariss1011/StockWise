<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{

    public function stock(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status' => ['nullable', 'in:all,low,normal'],
            'mode' => ['nullable', 'in:current,historical'],
            'as_of_date' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $mode = $filters['mode'] ?? 'current';
        $asOfDate = $filters['as_of_date'] ?? now()->toDateString();
        $status = $filters['status'] ?? 'all';

        // Query dasar produk.
        $query = Product::with('category');

        // Pada laporan historis, tampilkan hanya produk
        // yang sudah dibuat pada atau sebelum tanggal laporan.
        if ($mode === 'historical') {
            $query->whereDate('created_at', '<=', $asOfDate);
        }

        // Hitung pergerakan stok sesuai mode laporan.
        if ($mode === 'historical') {
            $query
                ->withSum([
                    'stockIns as stock_ins_sum_quantity' => function ($q) use ($asOfDate) {
                        $q->whereDate('transaction_date', '<=', $asOfDate);
                    },
                ], 'quantity')
                ->withSum([
                    'stockOuts as stock_outs_sum_quantity' => function ($q) use ($asOfDate) {
                        $q->whereDate('transaction_date', '<=', $asOfDate);
                    },
                ], 'quantity')
                ->withSum([
                    'stockAdjustments as stock_adjustments_sum_quantity_change' => function ($q) use ($asOfDate) {
                        $q->whereDate('adjustment_date', '<=', $asOfDate);
                    },
                ], 'quantity_change');
        } else {
            $query
                ->withSum('stockIns', 'quantity')
                ->withSum('stockOuts', 'quantity')
                ->withSum('stockAdjustments', 'quantity_change');
        }

        // Filter pencarian produk.
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter kategori.
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Ambil data dari database menjadi Collection.
        $products = $query->get();

        // Hitung stok dan nilai persediaan setiap produk.
        $products->transform(function ($product) {
            $stockIn = (int) ($product->stock_ins_sum_quantity ?? 0);
            $stockOut = (int) ($product->stock_outs_sum_quantity ?? 0);
            $adjustment = (int) (
                $product->stock_adjustments_sum_quantity_change ?? 0
            );

            $product->current_stock =
                (int) $product->initial_stock
                + $stockIn
                - $stockOut
                + $adjustment;

            $product->inventory_value =
                $product->current_stock * (float) $product->purchase_price;

            return $product;
        });

        // Filter berdasarkan status stok setelah stok dihitung.
        if ($status === 'low') {
            $products = $products->filter(
                fn($product) => $product->current_stock <= $product->minimum_stock
            );
        } elseif ($status === 'normal') {
            $products = $products->filter(
                fn($product) => $product->current_stock > $product->minimum_stock
            );
        }

        $products = $products->values();

        // Ringkasan sesuai hasil filter.
        $summary = [
            'product_count' => $products->count(),
            'total_units' => $products->sum('current_stock'),
            'inventory_value' => $products->sum('inventory_value'),
        ];

        // Pagination manual karena stok dihitung di Collection.
        $perPage = 15;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $paginatedProducts = new LengthAwarePaginator(
            $products->forPage($page, $perPage)->values(),
            $products->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('reports.stock', [
            'products' => $paginatedProducts,
            'categories' => Category::orderBy('name')->get(),
            'summary' => $summary,
            'mode' => $mode,
            'asOfDate' => $asOfDate,
        ]);
    }

    public function transactions(Request $request)
    {
        $filters = $this->transactionFilters($request);

        $dateFrom = $filters['date_from'];
        $dateTo = $filters['date_to'];
        $type = $filters['type'];

        $stockIns = StockIn::with('product')
            ->when(
                $dateFrom,
                fn($q) => $q->whereDate('transaction_date', '>=', $dateFrom)
            )
            ->when(
                $dateTo,
                fn($q) => $q->whereDate('transaction_date', '<=', $dateTo)
            )
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(10, ['*'], 'masuk')
            ->withQueryString();

        $stockOuts = StockOut::with('product')
            ->when(
                $dateFrom,
                fn($q) => $q->whereDate('transaction_date', '>=', $dateFrom)
            )
            ->when(
                $dateTo,
                fn($q) => $q->whereDate('transaction_date', '<=', $dateTo)
            )
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(10, ['*'], 'keluar')
            ->withQueryString();

        $adjustments = StockAdjustment::with('product')
            ->when(
                $dateFrom,
                fn($q) => $q->whereDate('adjustment_date', '>=', $dateFrom)
            )
            ->when(
                $dateTo,
                fn($q) => $q->whereDate('adjustment_date', '<=', $dateTo)
            )
            ->latest('adjustment_date')
            ->latest('id')
            ->paginate(10, ['*'], 'penyesuaian')
            ->withQueryString();

        return view('reports.transactions', compact(
            'stockIns',
            'stockOuts',
            'adjustments',
            'dateFrom',
            'dateTo',
            'type'
        ));
    }



    public function exportStock(Request $request): StreamedResponse
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status' => ['nullable', 'in:all,low,normal'],
            'mode' => ['nullable', 'in:current,historical'],
            'as_of_date' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $mode = $filters['mode'] ?? 'current';
        $asOfDate = $filters['as_of_date'] ?? now()->toDateString();
        $status = $filters['status'] ?? 'all';

        $query = Product::with('category');

        // Samakan hasil ekspor dengan laporan historis.
        if ($mode === 'historical') {
            $query->whereDate('created_at', '<=', $asOfDate);
        }

        if ($mode === 'historical') {
            $query
                ->withSum([
                    'stockIns as stock_ins_sum_quantity' => function ($q) use ($asOfDate) {
                        $q->whereDate('transaction_date', '<=', $asOfDate);
                    },
                ], 'quantity')
                ->withSum([
                    'stockOuts as stock_outs_sum_quantity' => function ($q) use ($asOfDate) {
                        $q->whereDate('transaction_date', '<=', $asOfDate);
                    },
                ], 'quantity')
                ->withSum([
                    'stockAdjustments as stock_adjustments_sum_quantity_change' => function ($q) use ($asOfDate) {
                        $q->whereDate('adjustment_date', '<=', $asOfDate);
                    },
                ], 'quantity_change');
        } else {
            $query
                ->withSum('stockIns', 'quantity')
                ->withSum('stockOuts', 'quantity')
                ->withSum('stockAdjustments', 'quantity_change');
        }

        // Terapkan filter pencarian dan kategori.
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Ambil data dan hitung atribut stok.
        $products = $query->get();

        $products->transform(function ($product) {
            $stockIn = (int) ($product->stock_ins_sum_quantity ?? 0);
            $stockOut = (int) ($product->stock_outs_sum_quantity ?? 0);
            $adjustment = (int) (
                $product->stock_adjustments_sum_quantity_change ?? 0
            );

            $product->current_stock =
                (int) $product->initial_stock
                + $stockIn
                - $stockOut
                + $adjustment;

            $product->inventory_value =
                $product->current_stock * (float) $product->purchase_price;

            return $product;
        });

        // Terapkan filter status.
        if ($status === 'low') {
            $products = $products->filter(
                fn($product) => $product->current_stock <= $product->minimum_stock
            );
        } elseif ($status === 'normal') {
            $products = $products->filter(
                fn($product) => $product->current_stock > $product->minimum_stock
            );
        }

        $filename = $mode === 'historical'
            ? "laporan-stok-historis-{$asOfDate}.csv"
            : 'laporan-stok-terkini.csv';

        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');

            // BOM untuk kompatibilitas UTF-8 di Excel.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'SKU',
                'Nama Produk',
                'Kategori',
                'Stok Awal',
                'Barang Masuk',
                'Barang Keluar',
                'Penyesuaian',
                'Stok Akhir',
                'Stok Minimum',
                'Harga Beli',
                'Nilai Persediaan',
                'Status Stok',
            ]);

            foreach ($products as $product) {
                $stockIn = (int) ($product->stock_ins_sum_quantity ?? 0);
                $stockOut = (int) ($product->stock_outs_sum_quantity ?? 0);
                $adjustment = (int) (
                    $product->stock_adjustments_sum_quantity_change ?? 0
                );

                fputcsv($handle, [
                    "\t" . $this->safeCsvText($product->sku),
                    $this->safeCsvText($product->name),
                    $this->safeCsvText($product->category?->name ?? '-'),
                    $product->initial_stock,
                    $stockIn,
                    $stockOut,
                    $adjustment,
                    $product->current_stock,
                    $product->minimum_stock,
                    $product->purchase_price,
                    $product->inventory_value,
                    $product->current_stock <= $product->minimum_stock
                        ? 'Stok rendah'
                        : 'Normal',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportTransactions(Request $request): StreamedResponse
    {

        $filters = $this->transactionFilters($request);

        $dateFrom = $filters['date_from'];
        $dateTo = $filters['date_to'];
        $type = $filters['type'];


        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;
        $type = $filters['type'] ?? 'all';

        $stockIns = collect();
        $stockOuts = collect();
        $adjustments = collect();

        if ($type === 'all' || $type === 'in') {
            $stockIns = StockIn::with('product')
                ->when(
                    $dateFrom,
                    fn($q) =>
                    $q->whereDate('transaction_date', '>=', $dateFrom)
                )
                ->when(
                    $dateTo,
                    fn($q) =>
                    $q->whereDate('transaction_date', '<=', $dateTo)
                )
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->get();
        }

        if ($type === 'all' || $type === 'out') {
            $stockOuts = StockOut::with('product')
                ->when(
                    $dateFrom,
                    fn($q) =>
                    $q->whereDate('transaction_date', '>=', $dateFrom)
                )
                ->when(
                    $dateTo,
                    fn($q) =>
                    $q->whereDate('transaction_date', '<=', $dateTo)
                )
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->get();
        }

        if ($type === 'all' || $type === 'adjustment') {
            $adjustments = StockAdjustment::with('product')
                ->when(
                    $dateFrom,
                    fn($q) =>
                    $q->whereDate('adjustment_date', '>=', $dateFrom)
                )
                ->when(
                    $dateTo,
                    fn($q) =>
                    $q->whereDate('adjustment_date', '<=', $dateTo)
                )
                ->orderBy('adjustment_date')
                ->orderBy('id')
                ->get();
        }

        $filename = 'laporan-transaksi-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use (
            $stockIns,
            $stockOuts,
            $adjustments
        ) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Jenis Transaksi',
                'Tanggal',
                'Nomor Referensi',
                'SKU',
                'Nama Produk',
                'Jumlah',
                'Satuan',
                'Detail',
                'Catatan',
            ]);

            foreach ($stockIns as $item) {
                fputcsv($handle, [
                    'Barang Masuk',
                    $item->transaction_date->format('Y-m-d'),
                    "\t" . $this->safeCsvText($item->reference_number ?? ''),
                    "\t" . $this->safeCsvText($item->product->sku),
                    $this->safeCsvText($item->product->name),
                    $item->quantity,
                    $this->safeCsvText($item->product->unit),
                    $item->unit_cost === null
                        ? ''
                        : 'Biaya/unit: ' . $item->unit_cost,
                    $this->safeCsvText($item->notes ?? ''),
                ]);
            }

            foreach ($stockOuts as $item) {
                fputcsv($handle, [
                    'Barang Keluar',
                    $item->transaction_date->format('Y-m-d'),
                    $this->safeCsvText($item->reference_number ?? ''),
                    "\t" . $this->safeCsvText($item->product->sku),
                    $this->safeCsvText($item->product->name),
                    $item->quantity,
                    $this->safeCsvText($item->product->unit),
                    'Penerima: ' . $this->safeCsvText($item->recipient ?? ''),
                    $this->safeCsvText($item->notes ?? ''),
                ]);
            }

            foreach ($adjustments as $item) {
                fputcsv($handle, [
                    'Penyesuaian',
                    $item->adjustment_date->format('Y-m-d'),
                    '',
                    "\t" . $this->safeCsvText($item->product->sku),
                    $this->safeCsvText($item->product->name),
                    $item->quantity_change,
                    $this->safeCsvText($item->product->unit),
                    'Alasan: ' . $this->safeCsvText($item->reason),
                    $this->safeCsvText($item->notes ?? ''),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
    private function transactionFilters(Request $request): array
    {
        $filters = $request->validate([
            'date_from' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from',
                'before_or_equal:today',
            ],
            'type' => ['nullable', 'in:all,in,out,adjustment'],
        ]);

        $dateTo = $filters['date_to'] ?? now()->toDateString();

        // Jika tanggal awal tidak diisi, gunakan awal bulan
        // berdasarkan tanggal akhir yang dipilih.
        $dateFrom = $filters['date_from']
            ?? \Carbon\Carbon::parse($dateTo)->startOfMonth()->toDateString();

        return [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'type' => $filters['type'] ?? 'all',
        ];
    }

    public function activitySummary(Request $request)
    {
        $filters = $this->transactionFilters($request);

        $dateFrom = $filters['date_from'];
        $dateTo = $filters['date_to'];

        $stockIns = StockIn::with('product')
            ->whereDate('transaction_date', '>=', $dateFrom)
            ->whereDate('transaction_date', '<=', $dateTo)
            ->get();

        $stockOuts = StockOut::with('product')
            ->whereDate('transaction_date', '>=', $dateFrom)
            ->whereDate('transaction_date', '<=', $dateTo)
            ->get();

        $adjustments = StockAdjustment::with('product')
            ->whereDate('adjustment_date', '>=', $dateFrom)
            ->whereDate('adjustment_date', '<=', $dateTo)
            ->get();

        $stockInsByProduct = $stockIns
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;

                return (object) [
                    'product_id' => $product?->id,
                    'sku' => $product?->sku ?? '-',
                    'product_name' => $product?->name ?? 'Produk dihapus',
                    'unit' => $product?->unit ?? '-',
                    'total_quantity' => $items->sum('quantity'),
                    'transaction_count' => $items->count(),
                ];
            })
            ->sortBy('product_name')
            ->values();

        $stockOutsByProduct = $stockOuts
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;

                return (object) [
                    'product_id' => $product?->id,
                    'sku' => $product?->sku ?? '-',
                    'product_name' => $product?->name ?? 'Produk dihapus',
                    'unit' => $product?->unit ?? '-',
                    'total_quantity' => $items->sum('quantity'),
                    'transaction_count' => $items->count(),
                ];
            })
            ->sortBy('product_name')
            ->values();

        $adjustmentsByProduct = $adjustments
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;

                return (object) [
                    'product_id' => $product?->id,
                    'sku' => $product?->sku ?? '-',
                    'product_name' => $product?->name ?? 'Produk dihapus',
                    'unit' => $product?->unit ?? '-',
                    'total_quantity' => $items->sum('quantity_change'),
                    'transaction_count' => $items->count(),
                ];
            })
            ->sortBy('product_name')
            ->values();

        $productIds = $stockIns->pluck('product_id')
            ->merge($stockOuts->pluck('product_id'))
            ->merge($adjustments->pluck('product_id'))
            ->unique();

        $totalStockIn = (int) $stockIns->sum('quantity');
        $totalStockOut = (int) $stockOuts->sum('quantity');
        $adjustmentNet = (int) $adjustments->sum('quantity_change');

        $summary = [
            'transaction_count' => $stockIns->count()
                + $stockOuts->count()
                + $adjustments->count(),

            'product_count' => $productIds->count(),

            'stock_in' => $totalStockIn,

            'stock_out' => $totalStockOut,

            'adjustment_net' => $adjustmentNet,

            'net_change' => $totalStockIn
                - $totalStockOut
                + $adjustmentNet,
        ];

        return view('reports.activity-summary', compact(
            'dateFrom',
            'dateTo',
            'summary',
            'stockInsByProduct',
            'stockOutsByProduct',
            'adjustmentsByProduct'
        ));
    }

    public function exportActivitySummary(Request $request)
    {
        $filters = $this->transactionFilters($request);

        $dateFrom = $filters['date_from'];
        $dateTo = $filters['date_to'];

        $stockIns = StockIn::with('product')
            ->whereDate('transaction_date', '>=', $dateFrom)
            ->whereDate('transaction_date', '<=', $dateTo)
            ->get();

        $stockOuts = StockOut::with('product')
            ->whereDate('transaction_date', '>=', $dateFrom)
            ->whereDate('transaction_date', '<=', $dateTo)
            ->get();

        $adjustments = StockAdjustment::with('product')
            ->whereDate('adjustment_date', '>=', $dateFrom)
            ->whereDate('adjustment_date', '<=', $dateTo)
            ->get();

        $filename = "rekap-aktivitas-{$dateFrom}-sampai-{$dateTo}.csv";

        return response()->streamDownload(function () use (
            $stockIns,
            $stockOuts,
            $adjustments
        ) {
            $handle = fopen('php://output', 'w');

            // BOM agar karakter CSV terbaca dengan baik di Excel.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['REKAP AKTIVITAS PERIODE']);
            fputcsv($handle, []);
            fputcsv($handle, [
                'Jenis Aktivitas',
                'Jumlah Transaksi',
                'Total Perubahan Stok',
            ]);

            fputcsv($handle, [
                'Barang Masuk',
                $stockIns->count(),
                $stockIns->sum('quantity'),
            ]);

            fputcsv($handle, [
                'Barang Keluar',
                $stockOuts->count(),
                $stockOuts->sum('quantity'),
            ]);

            fputcsv($handle, [
                'Penyesuaian Stok',
                $adjustments->count(),
                $adjustments->sum('quantity_change'),
            ]);

            fputcsv($handle, []);
            fputcsv($handle, [
                'Rincian Per Produk',
            ]);

            fputcsv($handle, [
                'Jenis Aktivitas',
                'SKU',
                'Nama Produk',
                'Total Kuantitas',
                'Frekuensi Transaksi',
                'Satuan',
            ]);

            $writeDetails = function ($items, $type, $quantityField) use ($handle) {
                $items->groupBy('product_id')->each(function ($group) use (
                    $handle,
                    $type,
                    $quantityField
                ) {
                    $product = $group->first()->product;

                    fputcsv($handle, [
                        $type,
                        "\t" . $this->safeCsvText($product?->sku ?? '-'),
                        $this->safeCsvText(
                            $product?->name ?? 'Produk dihapus'
                        ),
                        $group->sum($quantityField),
                        $group->count(),
                        $product?->unit ?? '-',
                    ]);
                });
            };

            $writeDetails($stockIns, 'Barang Masuk', 'quantity');
            $writeDetails($stockOuts, 'Barang Keluar', 'quantity');
            $writeDetails($adjustments, 'Penyesuaian', 'quantity_change');

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Mencegah teks yang diawali karakter formula
     * diperlakukan sebagai formula saat dibuka di spreadsheet.
     */
    private function safeCsvText(?string $value): string
    {
        $value = (string) $value;

        if (preg_match('/^[\s]*[=+\-@]/u', $value)) {
            return "'" . $value;
        }

        return $value;
    }
}
