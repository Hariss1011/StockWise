@extends('layouts.app')

@section('title', 'Produk')
@section('page-heading', 'Manajemen Produk')

@section('content')
    <div class="space-y-6">

        {{-- Header halaman --}}
        <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600">
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                    MASTER DATA
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Manajemen Produk
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Kelola informasi barang, harga, dan ketersediaan stok.
                </p>
            </div>

            <a href="{{ route('products.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-700">
                <span class="text-lg leading-none">+</span>
                Tambah Produk
            </a>
            <a href="{{ route('products.import.create') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-indigo-200
           bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-700
           transition hover:bg-indigo-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L8 8m4-4 4 4M5 15v5h14v-5" />
                </svg>
                Impor Excel
            </a>
            <a href="{{ route('products.import.template') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300
           bg-white px-4 py-2.5 text-sm font-semibold text-gray-700
           transition hover:bg-gray-50">
                Unduh Template
            </a>
        </section>

        {{-- Ringkasan --}}
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-xl font-bold text-indigo-600">
                    ▣
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Produk</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($products->total()) }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Produk yang terdaftar di sistem</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-xl font-bold text-sky-600">
                    ⇄
                </div>
                <div>
                    <p class="text-sm text-slate-500">Halaman Aktif</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ $products->currentPage() }}
                        <span class="text-base font-medium text-slate-400">/ {{ max($products->lastPage(), 1) }}</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Navigasi daftar produk</p>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div
                class="mb-5 rounded-lg border border-green-200 bg-green-50
                px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabel produk --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div
                class="flex flex-col justify-between gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:p-6">
                <div>
                    <h2 class="font-bold text-slate-900">Daftar Produk</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Informasi produk dan status persediaan terkini.
                    </p>
                </div>

                <span
                    class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Data persediaan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-262.5 text-left text-sm">
                    <thead class="bg-slate-50/90 text-[11px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Produk</th>
                            <th class="px-5 py-4 font-semibold">Kategori</th>
                            <th class="px-5 py-4 font-semibold">Supplier</th>
                            <th class="px-5 py-4 font-semibold">Harga Jual</th>
                            <th class="px-5 py-4 font-semibold">Stok Awal</th>
                            <th class="px-5 py-4 font-semibold">Stok Tersedia</th>
                            <th class="px-5 py-4 font-semibold">Stok Minimum</th>
                            <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($products as $product)
                            @php
                                $currentStock =
                                    (int) $product->initial_stock +
                                    (int) ($product->stock_ins_sum_quantity ?? 0) -
                                    (int) ($product->stock_outs_sum_quantity ?? 0) +
                                    (int) ($product->stock_adjustments_sum_quantity_change ?? 0);

                                $isLowStock = $currentStock <= $product->minimum_stock;
                            @endphp

                            <tr class="transition duration-150 hover:bg-indigo-50/30">
                                {{-- Identitas produk --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                                            {{ $isLowStock ? 'bg-amber-50 text-amber-600' : 'bg-indigo-50 text-indigo-600' }}">
                                            <span class="text-lg font-bold">▣</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">
                                                {{ $product->name }}
                                            </p>
                                            <p class="mt-1 font-mono text-xs text-slate-400">
                                                SKU: {{ $product->sku }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kategori --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-600">
                                        {{ $product->category->name }}
                                    </span>
                                </td>

                                {{-- Supplier --}}
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $product->supplier?->name ?? '—' }}
                                </td>

                                {{-- Harga --}}
                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-800">
                                    Rp {{ number_format((float) $product->selling_price, 0, ',', '.') }}
                                </td>

                                {{-- Stok awal --}}
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    {{ number_format((int) $product->initial_stock) }}
                                    <span class="text-xs text-slate-400">{{ $product->unit }}</span>
                                </td>

                                {{-- Stok saat ini --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-bold {{ $isLowStock ? 'text-rose-600' : 'text-slate-800' }}">
                                        {{ number_format($currentStock) }}
                                        <span class="text-xs font-normal text-slate-400">{{ $product->unit }}</span>
                                    </p>
                                    <span
                                        class="mt-1 inline-flex items-center gap-1 text-[11px] {{ $isLowStock ? 'text-rose-600' : 'text-emerald-600' }}">
                                        <span
                                            class="h-1.5 w-1.5 rounded-full {{ $isLowStock ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                                        {{ $isLowStock ? 'Perlu restock' : 'Stok tersedia' }}
                                    </span>
                                </td>

                                {{-- Stok minimum --}}
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    {{ number_format((int) $product->minimum_stock) }}
                                    <span class="text-xs text-slate-400">{{ $product->unit }}</span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                            Edit
                                        </a>

                                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $product->name }}?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="inline-flex items-center justify-center rounded-lg border border-rose-100 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-600 transition hover:border-rose-200 hover:bg-rose-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-400">
                                        ▣
                                    </div>
                                    <p class="mt-4 font-semibold text-slate-800">
                                        Belum ada produk
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Tambahkan produk pertama untuk mulai mengelola persediaan.
                                    </p>
                                    <a href="{{ route('products.create') }}"
                                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                        <span>+</span> Tambah Produk
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($products->hasPages())
                <div
                    class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <p class="text-xs text-slate-500">
                        Menampilkan
                        <span class="font-semibold text-slate-700">{{ $products->firstItem() ?? 0 }}</span>
                        sampai
                        <span class="font-semibold text-slate-700">{{ $products->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-semibold text-slate-700">{{ $products->total() }}</span>
                        produk
                    </p>

                    <div class="text-sm">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif

        </section>
    </div>
@endsection
