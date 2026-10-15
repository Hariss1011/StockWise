<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with(['category', 'supplier'])
            ->withSum('stockIns', 'quantity')
            ->withSum('stockOuts', 'quantity')
            ->withSum('stockAdjustments', 'quantity_change')
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }


    public function create()
    {
        return view('products.form', [
            'product' => new Product(),
            'categories' => Category::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:30'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'initial_stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:30'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ]);

        // Stok awal tidak diubah saat mengedit produk.
        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if (
            $product->stockIns()->exists() ||
            $product->stockOuts()->exists() ||
            $product->stockAdjustments()->exists()
        ) {
            return back()->with(
                'error',
                'Produk memiliki riwayat transaksi sehingga tidak bisa dihapus.'
            );
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
