<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\StockAdjustment;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    public function __construct(
        private StockService $stockService
    ) {}

    public function index()
    {
        $stockIns = StockIn::with('product')
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(10, ['*'], 'masuk');

        $stockOuts = StockOut::with('product')
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(10, ['*'], 'keluar');

        $adjustments = StockAdjustment::with('product')
            ->latest('adjustment_date')
            ->latest('id')
            ->paginate(10, ['*'], 'penyesuaian');

        return view('transactions.index', compact(
            'stockIns',
            'stockOuts',
            'adjustments'
        ));
    }


    public function createIn(Request $request)
    {
        $productId = $request->integer('product_id') ?: null;

        return $this->form('in', $productId);
    }


    public function storeIn(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->stockService->recordIn($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function createOut()
    {
        return $this->form('out');
    }

    public function storeOut(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1'],
            'recipient' => ['nullable', 'string', 'max:150'],
            'transaction_date' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->stockService->recordOut($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Barang keluar berhasil dicatat.');
    }

    public function createAdjustment()
    {
        return $this->form('adjustment');
    }

    public function storeAdjustment(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity_change' => [
                'required',
                'integer',
                'not_in:0',
            ],
            'reason' => ['required', 'string', 'max:100'],
            'adjustment_date' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->stockService->recordAdjustment($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Penyesuaian stok berhasil dicatat.');
    }


    private function form(string $type, ?int $selectedProductId = null)
    {
        return view('transactions.form', [
            'type' => $type,
            'products' => Product::orderBy('name')->get(),
            'selectedProductId' => $selectedProductId,
        ]);
    }
}
