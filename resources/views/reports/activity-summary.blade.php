@extends('layouts.app')

@section('title', 'Rekap Aktivitas Periode')
@section('page-heading', 'Rekap Aktivitas Periode')

@section('content')
    <div class="space-y-7">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span>Laporan</span>
                    <span>/</span>
                    <span class="font-medium text-indigo-600">Rekap Aktivitas</span>
                </div>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                    Rekap Aktivitas Periode
                </h1>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Ringkasan pergerakan stok dan aktivitas produk dalam periode tertentu.
                </p>
            </div>

            <a href="{{ route('reports.activity-summary.export', [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ]) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                <span>↓</span>
                Ekspor CSV
            </a>
        </div>

        {{-- Filter periode --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-800">Pilih Periode</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Data dihitung berdasarkan tanggal transaksi, termasuk tanggal awal dan akhir.
                </p>
            </div>

            <form method="GET" action="{{ route('reports.activity-summary') }}"
                class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 xl:grid-cols-4">

                <div>
                    <label for="date_from" class="mb-2 block text-sm font-semibold text-slate-700">
                        Tanggal Awal
                    </label>
                    <input id="date_from" name="date_from" type="date" value="{{ $dateFrom }}"
                        max="{{ now()->toDateString() }}" required
                        class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm text-slate-700 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                </div>

                <div>
                    <label for="date_to" class="mb-2 block text-sm font-semibold text-slate-700">
                        Tanggal Akhir
                    </label>
                    <input id="date_to" name="date_to" type="date" value="{{ $dateTo }}"
                        min="{{ $dateFrom }}" max="{{ now()->toDateString() }}" required
                        class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm text-slate-700 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                </div>

                <div class="flex items-end gap-3 sm:col-span-2">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                        Terapkan Filter
                    </button>

                    <a href="{{ route('reports.activity-summary') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        {{-- Informasi periode --}}
        <div
            class="flex flex-col gap-1 rounded-xl border border-indigo-100 bg-indigo-50/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-indigo-900">Periode laporan</p>
                <p class="mt-1 text-sm text-indigo-700">
                    {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d F Y') }}
                    – {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d F Y') }}
                </p>
            </div>
            <p class="text-xs text-indigo-600">
                Penyesuaian ditampilkan sebagai perubahan bersih.
            </p>
        </div>

        {{-- Kartu ringkasan --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

            @php
                $cards = [
                    [
                        'label' => 'Jumlah Transaksi',
                        'value' => $summary['transaction_count'],
                        'description' => 'Total seluruh aktivitas',
                        'icon' => '⇄',
                        'color' => 'indigo',
                    ],
                    [
                        'label' => 'Produk Terlibat',
                        'value' => $summary['product_count'],
                        'description' => 'Produk unik dalam periode',
                        'icon' => '▣',
                        'color' => 'blue',
                    ],
                    [
                        'label' => 'Barang Masuk',
                        'value' => $summary['stock_in'],
                        'description' => 'Total unit masuk',
                        'icon' => '↓',
                        'color' => 'emerald',
                    ],
                    [
                        'label' => 'Barang Keluar',
                        'value' => $summary['stock_out'],
                        'description' => 'Total unit keluar',
                        'icon' => '↑',
                        'color' => 'sky',
                    ],
                    [
                        'label' => 'Penyesuaian Bersih',
                        'value' => $summary['adjustment_net'],
                        'description' => 'Dampak bersih penyesuaian',
                        'icon' => '±',
                        'color' => 'amber',
                    ],
                    [
                        'label' => 'Perubahan Stok Bersih',
                        'value' => $summary['net_change'],
                        'description' => 'Masuk − keluar + penyesuaian',
                        'icon' => '∑',
                        'color' => 'violet',
                    ],
                ];

                $colorClasses = [
                    'indigo' => 'bg-indigo-50 text-indigo-600',
                    'blue' => 'bg-blue-50 text-blue-600',
                    'emerald' => 'bg-emerald-50 text-emerald-600',
                    'sky' => 'bg-sky-50 text-sky-600',
                    'amber' => 'bg-amber-50 text-amber-600',
                    'violet' => 'bg-violet-50 text-violet-600',
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-slate-500">{{ $card['label'] }}</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-800">
                                {{ number_format($card['value']) }}
                            </p>
                            <p class="mt-2 text-xs text-slate-500">{{ $card['description'] }}</p>
                        </div>

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl text-xl font-bold {{ $colorClasses[$card['color']] }}">
                            {{ $card['icon'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Komponen tabel rekap --}}
        @php
            $sections = [
                [
                    'title' => 'Rekap Barang Masuk',
                    'description' => 'Jumlah barang yang diterima dari setiap produk.',
                    'items' => $stockInsByProduct,
                    'quantity_label' => 'Total Masuk',
                    'quantity_color' => 'text-emerald-700',
                    'empty' => 'Tidak ada transaksi barang masuk pada periode ini.',
                ],
                [
                    'title' => 'Rekap Barang Keluar',
                    'description' => 'Jumlah barang yang dikeluarkan untuk setiap produk.',
                    'items' => $stockOutsByProduct,
                    'quantity_label' => 'Total Keluar',
                    'quantity_color' => 'text-blue-700',
                    'empty' => 'Tidak ada transaksi barang keluar pada periode ini.',
                ],
                [
                    'title' => 'Rekap Penyesuaian Stok',
                    'description' => 'Dampak bersih penyesuaian untuk setiap produk.',
                    'items' => $adjustmentsByProduct,
                    'quantity_label' => 'Perubahan Bersih',
                    'quantity_color' => 'text-slate-700',
                    'empty' => 'Tidak ada penyesuaian stok pada periode ini.',
                ],
            ];
        @endphp

        @foreach ($sections as $section)
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div
                    class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold text-slate-800">{{ $section['title'] }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $section['description'] }}</p>
                    </div>

                    <span class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                        {{ $section['items']->count() }} produk
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px] text-left text-sm">
                        <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">SKU</th>
                                <th class="px-5 py-4 font-semibold">Nama Produk</th>
                                <th class="px-5 py-4 text-right font-semibold">{{ $section['quantity_label'] }}</th>
                                <th class="px-5 py-4 text-right font-semibold">Frekuensi</th>
                                <th class="px-5 py-4 text-center font-semibold">Satuan</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($section['items'] as $item)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span
                                            class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">
                                            {{ $item->sku }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 font-semibold text-slate-800">
                                        {{ $item->product_name }}
                                    </td>

                                    <td
                                        class="whitespace-nowrap px-5 py-4 text-right font-bold {{ $section['quantity_color'] }}">
                                        {{ $item->total_quantity > 0 ? '+' : '' }}{{ number_format($item->total_quantity) }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-slate-600">
                                        {{ number_format($item->transaction_count) }} kali
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-center text-slate-500">
                                        {{ $item->unit }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center">
                                        <p class="font-semibold text-slate-700">Belum ada aktivitas</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $section['empty'] }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach

    </div>
@endsection
