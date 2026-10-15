```blade
@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
    <div class="space-y-7">

        {{-- Header --}}
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span>Laporan</span>
                <span>/</span>
                <span class="font-medium text-indigo-600">Transaksi</span>
            </div>

            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                Laporan Transaksi
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Tinjau riwayat pergerakan stok berdasarkan periode dan jenis transaksi.
            </p>
        </div>


        {{-- Informasi periode --}}
        <div
            class="flex flex-col gap-4 rounded-2xl border border-indigo-100 bg-indigo-50/70 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex min-w-0 items-start gap-3">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-indigo-600 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="5" width="18" height="16" rx="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M3 11h18" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="text-sm font-semibold text-indigo-900">
                        Periode Laporan
                    </p>

                    <p class="mt-1 text-sm leading-6 text-indigo-800">
                        {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}
                        <span class="mx-1 text-indigo-400">—</span>
                        {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                    </p>

                    <p class="mt-1 text-xs text-indigo-700">
                        @if ($type === 'all')
                            Menampilkan semua jenis transaksi pada periode terpilih.
                        @elseif ($type === 'in')
                            Menampilkan transaksi barang masuk pada periode terpilih.
                        @elseif ($type === 'out')
                            Menampilkan transaksi barang keluar pada periode terpilih.
                        @else
                            Menampilkan penyesuaian stok pada periode terpilih.
                        @endif
                    </p>
                </div>
            </div>

            <a href="{{ route('reports.transactions.export', [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'type' => $type,
            ]) }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3h16v-3" />
                </svg>
                Ekspor CSV
            </a>
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
                    <h2 class="font-semibold text-slate-800">Filter Transaksi</h2>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Tentukan periode dan jenis transaksi yang ingin ditampilkan.
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('reports.transactions') }}" class="p-5">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                    <div>
                        <label for="date_from" class="mb-2 block text-sm font-semibold text-slate-700">
                            Dari Tanggal
                        </label>
                        <input id="date_from" name="date_from" type="date" value="{{ $dateFrom }}"
                            max="{{ now()->toDateString() }}"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                    </div>

                    <div>
                        <label for="date_to" class="mb-2 block text-sm font-semibold text-slate-700">
                            Sampai Tanggal
                        </label>
                        <input id="date_to" name="date_to" type="date" value="{{ $dateTo }}"
                            max="{{ now()->toDateString() }}"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                    </div>

                    <div>
                        <label for="type" class="mb-2 block text-sm font-semibold text-slate-700">
                            Jenis Transaksi
                        </label>
                        <select id="type" name="type"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                            <option value="all" @selected($type === 'all')>
                                Semua transaksi
                            </option>
                            <option value="in" @selected($type === 'in')>
                                Barang masuk
                            </option>
                            <option value="out" @selected($type === 'out')>
                                Barang keluar
                            </option>
                            <option value="adjustment" @selected($type === 'adjustment')>
                                Penyesuaian stok
                            </option>
                        </select>
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

                        <a href="{{ route('reports.transactions') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </section>

        {{-- Barang Masuk --}}
        @if ($type === 'all' || $type === 'in')
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div
                    class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-semibold text-slate-800">Barang Masuk</h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Riwayat penerimaan persediaan
                            </p>
                        </div>
                    </div>

                    <span class="w-fit rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                        {{ $stockIns->total() }} transaksi
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-212.5 text-left text-sm">
                        <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">Tanggal</th>
                                <th class="px-5 py-4 font-semibold">Referensi</th>
                                <th class="px-5 py-4 font-semibold">Produk</th>
                                <th class="px-5 py-4 text-right font-semibold">Jumlah</th>
                                <th class="px-5 py-4 text-right font-semibold">Biaya per Unit</th>
                                <th class="px-5 py-4 font-semibold">Catatan</th>
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

                                    <td class="px-5 py-4 font-medium text-slate-800">
                                        {{ $item->product->name }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-emerald-700">
                                        +{{ number_format($item->quantity) }}
                                        <span class="text-xs font-normal text-slate-500">{{ $item->product->unit }}</span>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-slate-600">
                                        {{ $item->unit_cost === null ? '-' : 'Rp ' . number_format((float) $item->unit_cost, 0, ',', '.') }}
                                    </td>

                                    <td class="max-w-xs px-5 py-4 text-slate-500">
                                        {{ $item->notes ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <p class="font-semibold text-slate-700">
                                            Tidak ada transaksi barang masuk
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            Coba pilih periode laporan yang berbeda.
                                        </p>
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
        @endif

        {{-- Barang Keluar --}}
        @if ($type === 'all' || $type === 'out')
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div
                    class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m0 0 7-7m-7 7 7 7" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-semibold text-slate-800">Barang Keluar</h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Riwayat pengeluaran persediaan
                            </p>
                        </div>
                    </div>

                    <span class="w-fit rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                        {{ $stockOuts->total() }} transaksi
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-212.5 text-left text-sm">
                        <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">Tanggal</th>
                                <th class="px-5 py-4 font-semibold">Referensi</th>
                                <th class="px-5 py-4 font-semibold">Produk</th>
                                <th class="px-5 py-4 text-right font-semibold">Jumlah</th>
                                <th class="px-5 py-4 font-semibold">Penerima</th>
                                <th class="px-5 py-4 font-semibold">Catatan</th>
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

                                    <td class="max-w-xs px-5 py-4 text-slate-500">
                                        {{ $item->notes ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <p class="font-semibold text-slate-700">
                                            Tidak ada transaksi barang keluar
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            Coba pilih periode laporan yang berbeda.
                                        </p>
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
        @endif

        {{-- Penyesuaian Stok --}}
        @if ($type === 'all' || $type === 'adjustment')
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div
                    class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
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
                            <p class="mt-1 text-xs text-slate-500">
                                Riwayat koreksi jumlah persediaan
                            </p>
                        </div>
                    </div>

                    <span class="w-fit rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">
                        {{ $adjustments->total() }} transaksi
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-187.5 text-left text-sm">
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
                                            class="inline-flex rounded-full px-2.5 py-1.5 text-xs font-semibold {{ $item->quantity_change > 0 ? 'bg-emerald-50 text-emerald-700' : ($item->quantity_change < 0 ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600') }}">
                                            {{ $item->quantity_change > 0 ? '+' : '' }}{{ number_format($item->quantity_change) }}
                                            {{ $item->product->unit }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-slate-600">
                                        {{ $item->reason }}
                                    </td>

                                    <td class="max-w-xs px-5 py-4 text-slate-500">
                                        {{ $item->notes ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center">
                                        <p class="font-semibold text-slate-700">
                                            Tidak ada penyesuaian stok
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            Coba pilih periode laporan yang berbeda.
                                        </p>
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
        @endif

    </div>
@endsection
```
