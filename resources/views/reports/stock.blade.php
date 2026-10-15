```blade
@extends('layouts.app')

@section('title', $mode === 'historical' ? 'Laporan Stok Historis' : 'Laporan Stok Barang')

@section('content')
    <div class="space-y-7">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span>Laporan</span>
                    <span>/</span>
                    <span class="font-medium text-indigo-600">
                        {{ $mode === 'historical' ? 'Stok Historis' : 'Stok Barang' }}
                    </span>
                </div>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                    {{ $mode === 'historical' ? 'Laporan Stok Historis' : 'Laporan Stok Barang' }}
                </h1>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    @if ($mode === 'historical')
                        Lihat kondisi persediaan berdasarkan transaksi sampai tanggal yang dipilih.
                    @else
                        Pantau ketersediaan, pergerakan, dan estimasi nilai persediaan produk saat ini.
                    @endif
                </p>
            </div>

            <a href="{{ route('reports.stock.export', request()->query()) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3h16v-3" />
                </svg>
                Unduh CSV
            </a>
        </div>

        @if ($mode === 'historical')
            <div
                class="flex flex-col gap-2 rounded-xl border border-indigo-100 bg-indigo-50/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-semibold text-indigo-900">
                        Laporan Stok Historis
                    </p>

                    <p class="mt-1 text-sm text-indigo-700">
                        Posisi stok per
                        {{ \Carbon\Carbon::parse($asOfDate)->translatedFormat('d F Y') }}
                    </p>
                </div>

                <p class="text-xs leading-5 text-indigo-600">
                    Nilai persediaan memakai harga beli produk saat ini.
                </p>
            </div>
        @else
            <div class="rounded-xl border border-emerald-100 bg-emerald-50/70 px-5 py-4">
                <p class="font-semibold text-emerald-900">
                    Laporan Stok Terkini
                </p>

                <p class="mt-1 text-sm text-emerald-700">
                    Menampilkan posisi persediaan berdasarkan seluruh transaksi yang tercatat.
                </p>
            </div>
        @endif

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Jumlah Produk</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-800">
                            {{ number_format($summary['product_count']) }}
                        </p>
                        <p class="mt-2 text-xs text-slate-500">Produk sesuai ringkasan laporan</p>
                    </div>
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 transition group-hover:bg-indigo-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Unit Stok</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-800">
                            {{ number_format($summary['total_units']) }}
                        </p>
                        <p class="mt-2 text-xs text-slate-500">Akumulasi unit persediaan</p>
                    </div>
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h10" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md sm:col-span-2 xl:col-span-1">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-500">Nilai Persediaan</p>
                        <p class="mt-3 wrap-break-word text-2xl font-bold tracking-tight text-emerald-700">
                            Rp {{ number_format($summary['inventory_value'], 0, ',', '.') }}
                        </p>
                        <p class="mt-2 text-xs text-slate-500">Estimasi nilai berdasarkan data persediaan</p>
                    </div>
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 2v8m0 0v2m7-6a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- Filter --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M6 10h12m-9 6h6" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-slate-800">Filter Laporan</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Temukan persediaan berdasarkan kriteria tertentu.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('reports.stock') }}" class="p-5">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-6">

                    <div>
                        <label for="search" class="mb-2 block text-sm font-semibold text-slate-700">
                            Cari Produk
                        </label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="7" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 4 4" />
                            </svg>
                            <input id="search" name="search" type="text" value="{{ request('search') }}"
                                placeholder="Nama produk atau SKU"
                                class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                        </div>
                    </div>

                    <div>
                        <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700">
                            Kategori
                        </label>
                        <select id="category_id" name="category_id"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                            <option value="">Semua kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">
                            Status Stok
                        </label>
                        <select id="status" name="status"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                            <option value="all" @selected(request('status', 'all') === 'all')>Semua status</option>
                            <option value="low" @selected(request('status') === 'low')>Stok rendah</option>
                            <option value="normal" @selected(request('status') === 'normal')>Stok normal</option>
                        </select>
                    </div>


                    <div>
                        <label for="mode" class="mb-2 block text-sm font-semibold text-slate-700">
                            Mode Laporan
                        </label>

                        <select id="mode" name="mode"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                            <option value="current" @selected($mode === 'current')>
                                Stok Terkini
                            </option>
                            <option value="historical" @selected($mode === 'historical')>
                                Stok Historis
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="as_of_date" class="mb-2 block text-sm font-semibold text-slate-700">
                            Tanggal Stok
                        </label>

                        <input id="as_of_date" name="as_of_date" type="date" value="{{ $asOfDate }}"
                            max="{{ now()->toDateString() }}"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">

                        <p class="mt-1 text-xs text-slate-400">
                            Digunakan untuk mode historis.
                        </p>
                    </div>

                    <div class="flex items-end gap-3">
                        <button type="submit"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 4 4" />
                            </svg>
                            Filter
                        </button>

                        <a href="{{ route('reports.stock') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </section>

        {{-- Tabel laporan --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800">
                        {{ $mode === 'historical' ? 'Rincian Stok Historis' : 'Rincian Persediaan' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        @if ($mode === 'historical')
                            Rincian stok setiap produk berdasarkan transaksi sampai
                            {{ \Carbon\Carbon::parse($asOfDate)->translatedFormat('d F Y') }}.
                        @else
                            Detail stok, pergerakan barang, dan nilai persediaan setiap produk.
                        @endif
                    </p>
                </div>

                <span class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                    {{ $products->total() }} produk
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-300 text-left text-sm">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-4 py-4 font-semibold">SKU</th>
                            <th class="px-4 py-4 font-semibold">Produk</th>
                            <th class="px-4 py-4 text-right font-semibold">Stok Awal</th>
                            <th class="px-4 py-4 text-right font-semibold">Masuk</th>
                            <th class="px-4 py-4 text-right font-semibold">Keluar</th>
                            <th class="px-4 py-4 text-right font-semibold">Penyesuaian</th>
                            <th class="px-4 py-4 text-right font-semibold">Stok Akhir</th>
                            <th class="px-4 py-4 text-right font-semibold">Harga Beli</th>
                            <th class="px-4 py-4 text-right font-semibold">Nilai Persediaan</th>
                            <th class="px-4 py-4 text-center font-semibold">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($products as $product)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-4 py-4">
                                    <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">
                                        {{ $product->sku }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-800">{{ $product->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $product->category?->name ?? '-' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-right text-slate-600">
                                    {{ number_format($product->initial_stock) }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-right text-emerald-700">
                                    +{{ number_format($product->stock_ins_sum_quantity ?? 0) }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-right text-blue-700">
                                    -{{ number_format($product->stock_outs_sum_quantity ?? 0) }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-right text-slate-600">
                                    {{ ($product->stock_adjustments_sum_quantity_change ?? 0) > 0 ? '+' : '' }}{{ number_format($product->stock_adjustments_sum_quantity_change ?? 0) }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-right font-bold text-slate-800">
                                    {{ number_format($product->current_stock) }}
                                    <span class="text-xs font-normal text-slate-500">{{ $product->unit }}</span>
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-right text-slate-600">
                                    Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-right font-semibold text-slate-800">
                                    Rp {{ number_format($product->inventory_value, 0, ',', '.') }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-center">
                                    @if ($product->current_stock <= $product->minimum_stock)
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            Stok rendah
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1.5 text-xs font-semibold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Normal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-14 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <circle cx="11" cy="11" r="7" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 4 4" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 font-semibold text-slate-700">Produk tidak ditemukan</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Coba ubah kata kunci atau kriteria filter laporan.
                                    </p>
                                    <a href="{{ route('reports.stock') }}"
                                        class="mt-3 inline-flex text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                                        Reset filter
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $products->links() }}
                </div>
            @endif
        </section>

    </div>
@endsection
```
