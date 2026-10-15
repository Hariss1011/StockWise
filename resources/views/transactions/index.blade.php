```blade
@extends('layouts.app')

@section('title', 'Transaksi Stok')

@section('content')
    <div class="space-y-8">

        {{-- Header halaman --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span>Inventaris</span>
                    <span>/</span>
                    <span class="font-medium text-indigo-600">Transaksi Stok</span>
                </div>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                    Transaksi Stok
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                    Pantau seluruh aktivitas barang masuk, barang keluar, dan penyesuaian stok.
                </p>
            </div>
        </div>

        {{-- Tombol aksi --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <a href="{{ route('transactions.in.create') }}"
                class="group flex items-center gap-4 rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-slate-800">Barang Masuk</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">
                        Catat penerimaan persediaan
                    </p>
                </div>

                <span class="text-lg text-slate-300 transition group-hover:translate-x-1 group-hover:text-emerald-600">
                    &rarr;
                </span>
            </a>

            <a href="{{ route('transactions.out.create') }}"
                class="group flex items-center gap-4 rounded-2xl border border-blue-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m0 0 7-7m-7 7 7 7" />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-slate-800">Barang Keluar</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">
                        Catat pengeluaran persediaan
                    </p>
                </div>

                <span class="text-lg text-slate-300 transition group-hover:translate-x-1 group-hover:text-blue-600">
                    &rarr;
                </span>
            </a>

            <a href="{{ route('transactions.adjustments.create') }}"
                class="group flex items-center gap-4 rounded-2xl border border-amber-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-md">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition group-hover:bg-amber-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M10.3 3.86 2.82 17a2 2 0 0 0 1.74 3h14.88a2 2 0 0 0 1.74-3L13.7 3.86a2 2 0 0 0-3.4 0Z" />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-slate-800">Penyesuaian Stok</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">
                        Koreksi hasil stok opname
                    </p>
                </div>

                <span class="text-lg text-slate-300 transition group-hover:translate-x-1 group-hover:text-amber-600">
                    &rarr;
                </span>
            </a>
        </div>

        {{-- Barang Masuk --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-800">Barang Masuk</h2>
                        <p class="mt-0.5 text-xs text-slate-500">Riwayat penerimaan barang</p>
                    </div>
                </div>

                <span class="w-fit rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                    {{ $stockIns->total() }} transaksi
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-162.5 text-left text-sm">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Tanggal</th>
                            <th class="px-5 py-4 font-semibold">Referensi</th>
                            <th class="px-5 py-4 font-semibold">Produk</th>
                            <th class="px-5 py-4 text-right font-semibold">Jumlah</th>
                            <th class="px-5 py-4 text-right font-semibold">Harga Beli/Unit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($stockIns as $item)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    {{ $item->transaction_date->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600">
                                        {{ $item->reference_number ?: '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-medium text-slate-800">
                                        {{ $item->product->name }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-emerald-700">
                                    +{{ number_format($item->quantity) }}
                                    <span class="text-xs font-normal text-slate-500">{{ $item->product->unit }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right text-slate-600">
                                    {{ $item->unit_cost !== null ? 'Rp ' . number_format((float) $item->unit_cost, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 font-medium text-slate-700">Belum ada barang masuk</p>
                                    <p class="mt-1 text-sm text-slate-500">Transaksi yang dicatat akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($stockIns->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $stockIns->links() }}
                </div>
            @endif
        </section>

        {{-- Barang Keluar --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m0 0 7-7m-7 7 7 7" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-800">Barang Keluar</h2>
                        <p class="mt-0.5 text-xs text-slate-500">Riwayat pengeluaran barang</p>
                    </div>
                </div>

                <span class="w-fit rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ $stockOuts->total() }} transaksi
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-162.5 text-left text-sm">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Tanggal</th>
                            <th class="px-5 py-4 font-semibold">Referensi</th>
                            <th class="px-5 py-4 font-semibold">Produk</th>
                            <th class="px-5 py-4 text-right font-semibold">Jumlah</th>
                            <th class="px-5 py-4 font-semibold">Penerima</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($stockOuts as $item)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    {{ $item->transaction_date->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600">
                                        {{ $item->reference_number ?: '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-medium text-slate-800">
                                    {{ $item->product->name }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-blue-700">
                                    -{{ number_format($item->quantity) }}
                                    <span class="text-xs font-normal text-slate-500">{{ $item->product->unit }}</span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $item->recipient ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 font-medium text-slate-700">Belum ada barang keluar</p>
                                    <p class="mt-1 text-sm text-slate-500">Transaksi yang dicatat akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($stockOuts->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $stockOuts->links() }}
                </div>
            @endif
        </section>

        {{-- Penyesuaian Stok --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4m0 4h.01M10.3 3.86 2.82 17a2 2 0 0 0 1.74 3h14.88a2 2 0 0 0 1.74-3L13.7 3.86a2 2 0 0 0-3.4 0Z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-800">Penyesuaian Stok</h2>
                        <p class="mt-0.5 text-xs text-slate-500">Riwayat koreksi persediaan</p>
                    </div>
                </div>

                <span class="w-fit rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                    {{ $adjustments->total() }} transaksi
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-175 text-left text-sm">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Tanggal</th>
                            <th class="px-5 py-4 font-semibold">Produk</th>
                            <th class="px-5 py-4 text-right font-semibold">Perubahan</th>
                            <th class="px-5 py-4 font-semibold">Alasan</th>
                            <th class="px-5 py-4 font-semibold">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($adjustments as $item)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    {{ $item->adjustment_date->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4 font-medium text-slate-800">
                                    {{ $item->product->name }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->quantity_change > 0 ? 'bg-emerald-50 text-emerald-700' : ($item->quantity_change < 0 ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $item->quantity_change > 0 ? '+' : '' }}{{ number_format($item->quantity_change) }}
                                        {{ $item->product->unit }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $item->reason }}</td>
                                <td class="max-w-xs px-5 py-4 text-slate-500">
                                    {{ $item->notes ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <p class="font-medium text-slate-700">Belum ada penyesuaian stok</p>
                                    <p class="mt-1 text-sm text-slate-500">Riwayat koreksi stok akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($adjustments->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $adjustments->links() }}
                </div>
            @endif
        </section>

    </div>
@endsection
```
