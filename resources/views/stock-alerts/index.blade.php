@extends('layouts.app')

@section('title', 'Peringatan Stok Minimum')
@section('page-heading', 'Peringatan Stok Minimum')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                    Peringatan Stok Minimum
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Pantau produk yang stoknya habis atau sudah mencapai batas minimum.
                </p>
            </div>

            <a href="{{ route('transactions.in.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                <span>+</span>
                Catat Barang Masuk
            </a>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Produk Perlu Perhatian
                </p>
                <p class="mt-3 text-3xl font-bold text-slate-800">
                    {{ $summary['total'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
                <p class="text-sm font-medium text-red-700">
                    Stok Habis
                </p>
                <p class="mt-3 text-3xl font-bold text-red-700">
                    {{ $summary['empty'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <p class="text-sm font-medium text-amber-700">
                    Stok Rendah
                </p>
                <p class="mt-3 text-3xl font-bold text-amber-700">
                    {{ $summary['low'] }}
                </p>
            </div>
        </div>

        {{-- Pencarian dan filter --}}
        <form method="GET" action="{{ route('stock-alerts.index') }}"
            class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row">

            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama produk atau SKU..."
                class="min-w-0 flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">

            <select name="status"
                class="rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500">
                <option value="all" @selected($status === 'all')>
                    Semua status
                </option>
                <option value="empty" @selected($status === 'empty')>
                    Stok habis
                </option>
                <option value="low" @selected($status === 'low')>
                    Stok rendah
                </option>
            </select>

            <button type="submit"
                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                Terapkan
            </button>

            <a href="{{ route('stock-alerts.index') }}"
                class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Reset
            </a>
        </form>

        {{-- Tabel stok minimum --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-800">
                    Daftar Produk yang Perlu Ditindaklanjuti
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $products->count() }} produk ditemukan berdasarkan filter.
                </p>
            </div>

            @if ($products->isEmpty())
                <div class="px-6 py-14 text-center">
                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-2xl text-emerald-600">
                        ✓
                    </div>
                    <h3 class="mt-4 font-semibold text-slate-800">
                        Tidak ada peringatan stok
                    </h3>
                    <p class="mt-2 text-sm text-slate-500">
                        Tidak ada produk yang sesuai dengan filter dan membutuhkan perhatian.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">Produk</th>
                                <th class="px-5 py-4 font-semibold">Kategori</th>
                                <th class="px-5 py-4 text-right font-semibold">Stok Saat Ini</th>
                                <th class="px-5 py-4 text-right font-semibold">Stok Minimum</th>
                                <th class="px-5 py-4 font-semibold">Status</th>
                                <th class="px-5 py-4 text-right font-semibold">Tindakan</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach ($products as $product)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-800">
                                            {{ $product->name }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $product->sku }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 text-slate-600">
                                        {{ $product->category?->name ?? 'Tanpa kategori' }}
                                    </td>

                                    <td
                                        class="px-5 py-4 text-right font-bold tabular-nums
                                        {{ $product->stock_status === 'empty' ? 'text-red-600' : 'text-amber-600' }}">
                                        {{ number_format($product->current_stock) }}
                                        <span class="font-normal text-slate-500">
                                            {{ $product->unit }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right text-slate-600 tabular-nums">
                                        {{ number_format($product->minimum_stock) }}
                                        {{ $product->unit }}
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($product->stock_status === 'empty')
                                            <span
                                                class="inline-flex rounded-full bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 ring-1 ring-red-200">
                                                Stok Habis
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                                                Stok Rendah
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-100">
                                                Lihat Produk
                                            </a>

                                            <a href="{{ route('transactions.in.create', ['product_id' => $product->id]) }}"
                                                class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700">
                                                + Tambah Stok
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
