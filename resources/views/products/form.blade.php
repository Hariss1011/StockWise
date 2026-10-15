@extends('layouts.app')

@section('title', $isEdit ? 'Edit Produk' : 'Tambah Produk')
@section('page-heading', $isEdit ? 'Edit Produk' : 'Tambah Produk')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        <div>
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-indigo-600">
                <span>←</span> Kembali ke daftar produk
            </a>
            <div class="mt-4 flex items-start gap-3">
                <span
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-xl font-bold text-indigo-600">
                    {{ $isEdit ? '✎' : '+' }}
                </span>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        {{ $isEdit ? 'Edit Produk' : 'Tambah Produk Baru' }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $isEdit ? 'Perbarui informasi produk yang sudah terdaftar.' : 'Lengkapi informasi untuk mendaftarkan produk ke StockWise.' }}
                    </p>
                </div>
            </div>
        </div>

        @if ($categories->isEmpty())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <div class="flex items-start gap-3">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-lg font-bold text-amber-700">!</span>
                    <div>
                        <h2 class="font-semibold text-amber-900">Kategori belum tersedia</h2>
                        <p class="mt-1 text-sm leading-6 text-amber-800">
                            Tambahkan minimal satu kategori sebelum membuat produk.
                        </p>
                        <a href="{{ route('categories.create') }}"
                            class="mt-3 inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-700">
                            + Tambah Kategori
                        </a>
                    </div>
                </div>
            </div>
        @else
            <form action="{{ $isEdit ? route('products.update', $product) : route('products.store') }}" method="POST"
                class="space-y-6">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                {{-- Informasi dasar --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                        <h2 class="font-bold text-slate-900">Informasi Produk</h2>
                        <p class="mt-1 text-sm text-slate-500">Identitas dan klasifikasi barang.</p>
                    </div>

                    <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                        <div>
                            <label for="sku" class="mb-2 block text-sm font-semibold text-slate-700">SKU <span
                                    class="text-rose-500">*</span></label>
                            <input id="sku" name="sku" type="text" maxlength="50" required
                                value="{{ old('sku', $product->sku) }}" placeholder="Contoh: BRG-001"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                            @error('sku')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Produk <span
                                    class="text-rose-500">*</span></label>
                            <input id="name" name="name" type="text" maxlength="150" required
                                value="{{ old('name', $product->name) }}" placeholder="Masukkan nama produk"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                            @error('name')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700">Kategori <span
                                    class="text-rose-500">*</span></label>
                            <select id="category_id" name="category_id" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                                <option value="">Pilih kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="supplier_id" class="mb-2 block text-sm font-semibold text-slate-700">Supplier <span
                                    class="font-normal text-slate-400">(opsional)</span></label>
                            <select id="supplier_id" name="supplier_id"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                                <option value="">Belum ditentukan</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id) == $supplier->id)>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi <span
                                    class="font-normal text-slate-400">(opsional)</span></label>
                            <textarea id="description" name="description" rows="3" placeholder="Tambahkan detail atau keterangan produk..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                {{-- Harga --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                        <h2 class="font-bold text-slate-900">Informasi Harga</h2>
                        <p class="mt-1 text-sm text-slate-500">Masukkan harga beli dan harga jual produk.</p>
                    </div>

                    <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                        <div>
                            <label for="purchase_price" class="mb-2 block text-sm font-semibold text-slate-700">Harga Beli
                                <span class="text-rose-500">*</span></label>
                            <div
                                class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50 focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/10">
                                <span
                                    class="flex items-center border-r border-slate-200 bg-slate-100 px-3 text-sm font-semibold text-slate-500">Rp</span>
                                <input id="purchase_price" name="purchase_price" type="number" min="0"
                                    step="0.01" required
                                    value="{{ old('purchase_price', $product->purchase_price ?? 0) }}"
                                    class="min-w-0 w-full bg-transparent px-3 py-3 text-sm outline-none">
                            </div>
                            @error('purchase_price')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="selling_price" class="mb-2 block text-sm font-semibold text-slate-700">Harga Jual
                                <span class="text-rose-500">*</span></label>
                            <div
                                class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50 focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/10">
                                <span
                                    class="flex items-center border-r border-slate-200 bg-slate-100 px-3 text-sm font-semibold text-slate-500">Rp</span>
                                <input id="selling_price" name="selling_price" type="number" min="0" step="0.01"
                                    required value="{{ old('selling_price', $product->selling_price ?? 0) }}"
                                    class="min-w-0 w-full bg-transparent px-3 py-3 text-sm outline-none">
                            </div>
                            @error('selling_price')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                {{-- Persediaan --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                        <h2 class="font-bold text-slate-900">Pengaturan Persediaan</h2>
                        <p class="mt-1 text-sm text-slate-500">Tentukan satuan dan batas minimum stok.</p>
                    </div>

                    <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                        <div>
                            <label for="unit" class="mb-2 block text-sm font-semibold text-slate-700">Satuan <span
                                    class="text-rose-500">*</span></label>
                            <input id="unit" name="unit" type="text" maxlength="30" required
                                value="{{ old('unit', $product->unit ?? 'pcs') }}" placeholder="Contoh: pcs, box, kg"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                            @error('unit')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if (!$isEdit)
                            <div>
                                <label for="initial_stock" class="mb-2 block text-sm font-semibold text-slate-700">Stok
                                    Awal <span class="text-rose-500">*</span></label>
                                <input id="initial_stock" name="initial_stock" type="number" min="0"
                                    step="1" required value="{{ old('initial_stock', 0) }}"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                                @error('initial_stock')
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div>
                            <label for="minimum_stock" class="mb-2 block text-sm font-semibold text-slate-700">Batas
                                Minimum Stok <span class="text-rose-500">*</span></label>
                            <input id="minimum_stock" name="minimum_stock" type="number" min="0" step="1"
                                required value="{{ old('minimum_stock', $product->minimum_stock ?? 5) }}"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                            <p class="mt-2 text-xs text-slate-400">Peringatan akan muncul ketika stok mencapai batas ini.
                            </p>
                            @error('minimum_stock')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                {{-- Tombol --}}
                <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700">
                        <span>✓</span>
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Produk' }}
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection
