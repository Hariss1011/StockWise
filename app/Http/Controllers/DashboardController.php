<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter grafik: 7, 30, 90, atau 365 hari.
        $allowedPeriods = [7, 30, 90, 365];

        $days = (int) $request->query('period', 30);

        if (! in_array($days, $allowedPeriods, true)) {
            $days = 30;
        }

        $startDate = now()
            ->startOfDay()
            ->subDays($days - 1);

        // Hitung stok aktual menggunakan rumus StockService.
        $products = Product::withSum('stockIns', 'quantity')
            ->withSum('stockOuts', 'quantity')
            ->withSum('stockAdjustments', 'quantity_change')
            ->get();

        $stockData = $products->map(function ($product) {
            $currentStock = (int) $product->initial_stock
                + (int) ($product->stock_ins_sum_quantity ?? 0)
                - (int) ($product->stock_outs_sum_quantity ?? 0)
                + (int) ($product->stock_adjustments_sum_quantity_change ?? 0);

            return [
                'product' => $product,
                'current_stock' => $currentStock,
                'inventory_value' => $currentStock
                    * (float) $product->purchase_price,
            ];
        });

        // Produk dengan stok mencapai atau di bawah batas minimum.
        $allLowStockProducts = $stockData
            ->filter(
                fn($item) =>
                $item['current_stock'] <= $item['product']->minimum_stock
            )
            ->sortBy('current_stock')
            ->values();

        $lowStockProducts = $allLowStockProducts->take(5);

        // Transaksi dalam periode yang dipilih.
        $stockInQuery = StockIn::where('created_at', '>=', $startDate);
        $stockOutQuery = StockOut::where('created_at', '>=', $startDate);

        $periodStockIn = (clone $stockInQuery)->sum('quantity');
        $periodStockOut = (clone $stockOutQuery)->sum('quantity');

        $periodTransactionCount =
            (clone $stockInQuery)->count()
            + (clone $stockOutQuery)->count();

        // Agregasi harian untuk grafik.
        $stockInByDate = (clone $stockInQuery)
            ->selectRaw('DATE(created_at) as transaction_date')
            ->selectRaw('SUM(quantity) as total')
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'transaction_date');

        $stockOutByDate = (clone $stockOutQuery)
            ->selectRaw('DATE(created_at) as transaction_date')
            ->selectRaw('SUM(quantity) as total')
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'transaction_date');

        $chartLabels = [];
        $chartStockIn = [];
        $chartStockOut = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateKey = $date->toDateString();

            $chartLabels[] = $date->format('d M');
            $chartStockIn[] = (int) ($stockInByDate[$dateKey] ?? 0);
            $chartStockOut[] = (int) ($stockOutByDate[$dateKey] ?? 0);
        }

        // Ringkasan kondisi persediaan saat ini.
        $summary = [
            'total_products' => $products->count(),
            'total_categories' => Category::count(),
            'total_suppliers' => Supplier::count(),
            'total_current_stock' => $stockData->sum('current_stock'),
            'low_stock_count' => $allLowStockProducts->count(),
            'inventory_value' => $stockData->sum('inventory_value'),
            'total_stock_in' => $periodStockIn,
            'total_stock_out' => $periodStockOut,
            'period_transaction_count' => $periodTransactionCount,
            'total_adjustments' => StockAdjustment::count(),
        ];

        // Aktivitas terbaru yang dicatat oleh ActivityLog.
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact(
            'summary',
            'lowStockProducts',
            'recentActivities',
            'chartLabels',
            'chartStockIn',
            'chartStockOut',
            'days'
        ));
    }
}
