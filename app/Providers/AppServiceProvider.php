<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Supplier;
use App\Models\User;
use App\Observers\ActivityLogObserver;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ActivityLogObserver::class);
        Category::observe(ActivityLogObserver::class);
        Supplier::observe(ActivityLogObserver::class);

        StockIn::observe(ActivityLogObserver::class);
        StockOut::observe(ActivityLogObserver::class);
        StockAdjustment::observe(ActivityLogObserver::class);

        User::observe(ActivityLogObserver::class);

        View::composer('layouts.app', function ($view) {
            $products = Product::query()
                ->withSum('stockIns as stock_in_total', 'quantity')
                ->withSum('stockOuts as stock_out_total', 'quantity')
                ->withSum(
                    'stockAdjustments as adjustment_total',
                    'quantity_change'
                )
                ->get();

            $lowStockCount = $products->filter(function ($product) {
                $currentStock = (int) $product->initial_stock
                    + (int) ($product->stock_in_total ?? 0)
                    - (int) ($product->stock_out_total ?? 0)
                    + (int) ($product->adjustment_total ?? 0);

                return $currentStock <= (int) $product->minimum_stock;
            })->count();

            $view->with('lowStockCount', $lowStockCount);
        });
    }
}
