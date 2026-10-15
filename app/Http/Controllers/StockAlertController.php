<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockAlertController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $status = $request->input('status', 'all');

        $products = Product::query()
            ->with('category')
            ->withSum('stockIns as stock_in_total', 'quantity')
            ->withSum('stockOuts as stock_out_total', 'quantity')
            ->withSum(
                'stockAdjustments as adjustment_total',
                'quantity_change'
            )
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get()
            ->map(function (Product $product) {
                $currentStock = (int) $product->initial_stock
                    + (int) ($product->stock_in_total ?? 0)
                    - (int) ($product->stock_out_total ?? 0)
                    + (int) ($product->adjustment_total ?? 0);

                $product->current_stock = $currentStock;

                $product->stock_status = $currentStock <= 0
                    ? 'empty'
                    : 'low';

                return $product;
            })
            ->filter(function (Product $product) {
                return $product->current_stock
                    <= (int) $product->minimum_stock;
            });

        $summary = [
            'total' => $products->count(),
            'empty' => $products->where('stock_status', 'empty')->count(),
            'low' => $products->where('stock_status', 'low')->count(),
        ];

        if ($status === 'empty') {
            $products = $products->where('stock_status', 'empty');
        } elseif ($status === 'low') {
            $products = $products->where('stock_status', 'low');
        }

        $products = $products->values();

        return view('stock-alerts.index', compact(
            'products',
            'summary',
            'search',
            'status'
        ));
    }
}
