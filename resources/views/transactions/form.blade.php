```blade
@extends('layouts.app')

@section('title', 'Transaksi Stok')

@section('content')
    @php
        $isIn = $type === 'in';
        $isOut = $type === 'out';
        $isAdjustment = $type === 'adjustment';

        $title = $isIn ? 'Barang Masuk' : ($isOut ? 'Barang Keluar' : 'Penyesuaian Stok');

        $action = $isIn
            ? route('transactions.in.store')
            : ($isOut
                ? route('transactions.out.store')
                : route('transactions.adjustments.store'));

        $dateField = $isAdjustment ? 'adjustment_date' : 'transaction_date';
        $quantityField = $isAdjustment ? 'quantity_change' : 'quantity';

        $accent = $isIn ? 'emerald' : ($isOut ? 'blue' : 'amber');

        $accentClasses = [
            'emerald' => [
                'icon' => 'bg-emerald-50 text-emerald-600',
                'badge' => 'bg-emerald-50 text-emerald-700',
                'button' => 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500',
            ],
            'blue' => [
                'icon' => 'bg-blue-50 text-blue-600',
                'badge' => 'bg-blue-50 text-blue-700',
                'button' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
            ],
            'amber' => [
                'icon' => 'bg-amber-50 text-amber-600',
                'badge' => 'bg-amber-50 text-amber-700',
                'button' => 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-400',
            ],
        ];

        $accentStyle = $accentClasses[$accent];

        $inputClass =
            'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10';

        $labelClass = 'mb-2 block text-sm font-semibold text-slate-700';
    @endphp

    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('transactions.index') }}" class="transition hover:text-indigo-600">
                Transaksi Stok
            </a>
            <span>/</span>
            <span class="font-medium text-indigo-600">{{ $title }}</span>
        </div>

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                {{ $title }}
            </h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                @if ($isIn)
                    Catat penerimaan barang dari supplier untuk memperbarui persediaan.
                @elseif ($isOut)
                    Catat pengeluaran barang dan pastikan stok mencukupi.
                @else
                    Koreksi jumlah stok berdasarkan hasil stok opname atau kondisi aktual.
                @endif
            </p>
        </div>

        {{-- Form card --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex items-center gap-4 border-b border-slate-100 px-5 py-5 sm:px-8">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $accentStyle['icon'] }}">
                    @if ($isIn)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    @elseif ($isOut)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m0 0 7-7m-7 7 7 7" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4m0 4h.01M10.3 3.86 2.82 17a2 2 0 0 0 1.74 3h14.88a2 2 0 0 0 1.74-3L13.7 3.86a2 2 0 0 0-3.4 0Z" />
                        </svg>
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <h2 class="font-semibold text-slate-800">Informasi Transaksi</h2>
                    <p class="mt-1 text-xs text-slate-500">Lengkapi informasi di bawah ini dengan benar.</p>
                </div>

                <span
                    class="hidden rounded-full px-3 py-1.5 text-xs font-semibold sm:inline-flex {{ $accentStyle['badge'] }}">
                    {{ $title }}
                </span>
            </div>

            @if ($products->isEmpty())
                <div class="p-6 sm:p-8">
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4m0 4h.01M10.3 3.86 2.82 17a2 2 0 0 0 1.74 3h14.88a2 2 0 0 0 1.74-3L13.7 3.86a2 2 0 0 0-3.4 0Z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-amber-900">Belum ada produk tersedia</p>
                                <p class="mt-1 text-sm leading-6 text-amber-800">
                                    Tambahkan produk terlebih dahulu sebelum mencatat transaksi stok.
                                </p>
                                <a href="{{ route('products.create') }}"
                                    class="mt-3 inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-700">
                                    Tambah Produk
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <form action="{{ $action }}" method="POST" class="p-5 sm:p-8">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4m0 4h.01M10.3 3.86 2.82 17a2 2 0 0 0 1.74 3h14.88a2 2 0 0 0 1.74-3L13.7 3.86a2 2 0 0 0-3.4 0Z" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-red-800">Periksa kembali data transaksi</p>
                                    <p class="mt-1 text-sm text-red-700">
                                        Beberapa kolom perlu diperbaiki sebelum data dapat disimpan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">

                        {{-- Produk --}}
                        <div class="md:col-span-2">
                            <label for="product_id" class="{{ $labelClass }}">
                                Produk <span class="text-red-500">*</span>
                            </label>
                            <select id="product_id" name="product_id" required
                                class="{{ $inputClass }} {{ $errors->has('product_id') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/10' : '' }}">
                                <option value="">Pilih produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected(old('product_id', $selectedProductId ?? '') == $product->id)>
                                        {{ $product->sku }} — {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jumlah --}}
                        <div>
                            <label for="{{ $quantityField }}" class="{{ $labelClass }}">
                                {{ $isAdjustment ? 'Perubahan Stok' : 'Jumlah Barang' }}
                                <span class="text-red-500">*</span>
                            </label>
                            <input id="{{ $quantityField }}" name="{{ $quantityField }}" type="number" step="1"
                                required min="{{ $isAdjustment ? '-2147483647' : '1' }}" max="2147483647"
                                value="{{ old($quantityField) }}"
                                placeholder="{{ $isAdjustment ? 'Contoh: -2 atau 5' : 'Masukkan jumlah barang' }}"
                                class="{{ $inputClass }} {{ $errors->has($quantityField) ? 'border-red-400' : '' }}">
                            @error($quantityField)
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror

                            @if ($isAdjustment)
                                <p class="mt-2 text-xs leading-5 text-slate-500">
                                    Gunakan angka positif untuk menambah stok dan angka negatif untuk mengurangi stok.
                                </p>
                            @endif
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label for="{{ $dateField }}" class="{{ $labelClass }}">
                                Tanggal Transaksi <span class="text-red-500">*</span>
                            </label>
                            <input id="{{ $dateField }}" name="{{ $dateField }}" type="date" required
                                max="{{ now()->toDateString() }}" value="{{ old($dateField, now()->toDateString()) }}"
                                class="{{ $inputClass }} {{ $errors->has($dateField) ? 'border-red-400' : '' }}">
                            @error($dateField)
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nomor referensi --}}
                        @if ($isIn || $isOut)
                            <div class="md:col-span-2">
                                <label for="reference_number" class="{{ $labelClass }}">
                                    Nomor Referensi
                                    <span class="font-normal text-slate-400">(Opsional)</span>
                                </label>
                                <input id="reference_number" name="reference_number" type="text" maxlength="100"
                                    value="{{ old('reference_number') }}" placeholder="Contoh: PO-2026-001"
                                    class="{{ $inputClass }} {{ $errors->has('reference_number') ? 'border-red-400' : '' }}">
                                @error('reference_number')
                                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        {{-- Harga beli --}}
                        @if ($isIn)
                            <div class="md:col-span-2">
                                <label for="unit_cost" class="{{ $labelClass }}">
                                    Harga Beli per Unit
                                    <span class="font-normal text-slate-400">(Opsional)</span>
                                </label>
                                <div class="flex overflow-hidden rounded-xl shadow-sm">
                                    <span
                                        class="inline-flex items-center border border-r-0 border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-500">
                                        Rp
                                    </span>
                                    <input id="unit_cost" name="unit_cost" type="number" min="0" step="0.01"
                                        value="{{ old('unit_cost') }}" placeholder="0"
                                        class="{{ $inputClass }} rounded-l-none">
                                </div>
                                @error('unit_cost')
                                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        {{-- Penerima --}}
                        @if ($isOut)
                            <div class="md:col-span-2">
                                <label for="recipient" class="{{ $labelClass }}">
                                    Penerima
                                    <span class="font-normal text-slate-400">(Opsional)</span>
                                </label>
                                <input id="recipient" name="recipient" type="text" maxlength="150"
                                    value="{{ old('recipient') }}" placeholder="Nama penerima atau bagian terkait"
                                    class="{{ $inputClass }} {{ $errors->has('recipient') ? 'border-red-400' : '' }}">
                                @error('recipient')
                                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        {{-- Alasan penyesuaian --}}
                        @if ($isAdjustment)
                            <div class="md:col-span-2">
                                <label for="reason" class="{{ $labelClass }}">
                                    Alasan Penyesuaian <span class="text-red-500">*</span>
                                </label>
                                <select id="reason" name="reason" required
                                    class="{{ $inputClass }} {{ $errors->has('reason') ? 'border-red-400' : '' }}">
                                    <option value="">Pilih alasan penyesuaian</option>
                                    @foreach (['Stok opname', 'Barang rusak', 'Barang hilang', 'Kesalahan pencatatan', 'Lainnya'] as $reason)
                                        <option value="{{ $reason }}" @selected(old('reason') === $reason)>
                                            {{ $reason }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reason')
                                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        {{-- Catatan --}}
                        <div class="md:col-span-2">
                            <label for="notes" class="{{ $labelClass }}">
                                Catatan
                                <span class="font-normal text-slate-400">(Opsional)</span>
                            </label>
                            <textarea id="notes" name="notes" rows="4"
                                placeholder="Tambahkan informasi pendukung jika diperlukan..." class="{{ $inputClass }} resize-y">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer form --}}
                    <div
                        class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-slate-500">
                            <span class="text-red-500">*</span> Wajib diisi
                        </p>

                        <div class="flex flex-col-reverse gap-3 sm:flex-row">
                            <a href="{{ route('transactions.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                Batal
                            </a>

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white shadow-sm transition focus:outline-none focus:ring-4 {{ $accentStyle['button'] }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>

        {{-- Informasi tambahan --}}
        <div class="flex items-start gap-3 rounded-xl border border-indigo-100 bg-indigo-50/70 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-indigo-600" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M12 14a4 4 0 1 0-4-4" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v-1a3 3 0 1 0-3-3" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-indigo-900">Pastikan data transaksi sesuai</p>
                <p class="mt-1 text-sm leading-6 text-indigo-800">
                    Periksa produk, jumlah, tanggal, dan informasi pendukung sebelum menyimpan transaksi.
                    Penyesuaian stok sebaiknya digunakan untuk mencatat selisih stok aktual dengan stok sistem.
                </p>
            </div>
        </div>
    </div>
@endsection
```
