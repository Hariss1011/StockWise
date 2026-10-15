@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-heading', 'Dashboard')

@section('content')
    <div class="space-y-8">

        {{-- Header --}}
        <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600">
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                    INVENTORY OVERVIEW
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Dashboard
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Pantau kondisi persediaan dan pergerakan stok secara menyeluruh.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('transactions.in.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <span class="text-lg text-emerald-600">+</span>
                    Barang Masuk
                </a>

                <a href="{{ route('transactions.out.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    <span class="text-lg">−</span>
                    Barang Keluar
                </a>
            </div>
        </section>

        {{-- Kartu ringkasan --}}
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Produk</p>
                        <p class="mt-3 text-3xl font-bold text-slate-900">
                            {{ number_format($summary['total_products'], 0, ',', '.') }}
                        </p>
                        <p class="mt-2 text-xs text-slate-500">
                            {{ number_format($summary['total_categories'], 0, ',', '.') }} kategori terdaftar
                        </p>
                    </div>
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-600">▣</span>
                </div>
            </div>

            <div
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Unit Stok</p>
                        <p class="mt-3 text-3xl font-bold text-slate-900">
                            {{ number_format($summary['total_current_stock'], 0, ',', '.') }}
                        </p>
                        <p class="mt-2 text-xs text-slate-500">Jumlah stok seluruh produk</p>
                    </div>
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-xl text-sky-600">▤</span>
                </div>
            </div>

            <a href="{{ route('products.index') }}"
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Stok Perlu Perhatian</p>
                        <p
                            class="mt-3 text-3xl font-bold {{ $summary['low_stock_count'] > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ number_format($summary['low_stock_count'], 0, ',', '.') }}
                        </p>
                        <p class="mt-2 text-xs text-slate-500">Produk di batas minimum atau lebih rendah</p>
                    </div>
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-xl text-amber-600">!</span>
                </div>
            </a>

            <div
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Nilai Persediaan</p>
                        <p class="mt-3 break-words text-2xl font-bold text-slate-900">
                            Rp {{ number_format($summary['inventory_value'], 0, ',', '.') }}
                        </p>
                        <p class="mt-2 text-xs text-slate-500">Estimasi stok × harga beli</p>
                    </div>
                    <span
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-sm font-bold text-violet-600">Rp</span>
                </div>
            </div>

        </section>

        {{-- Grafik pergerakan stok --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Pergerakan Stok</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Perbandingan unit barang masuk dan barang keluar.
                    </p>
                </div>

                <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-center gap-2">
                    <label for="period" class="text-sm text-slate-500">Periode:</label>
                    <select id="period" name="period" onchange="this.form.submit()"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="7" @selected($days === 7)>7 hari</option>
                        <option value="30" @selected($days === 30)>30 hari</option>
                        <option value="90" @selected($days === 90)>90 hari</option>
                        <option value="365" @selected($days === 365)>365 hari</option>
                    </select>
                </form>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl bg-emerald-50 p-4">
                    <p class="text-sm font-medium text-emerald-700">Barang Masuk</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-700">
                        {{ number_format($summary['total_stock_in'], 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-emerald-700/80">Unit selama {{ $days }} hari</p>
                </div>

                <div class="rounded-xl bg-rose-50 p-4">
                    <p class="text-sm font-medium text-rose-700">Barang Keluar</p>
                    <p class="mt-2 text-2xl font-bold text-rose-700">
                        {{ number_format($summary['total_stock_out'], 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-rose-700/80">Unit selama {{ $days }} hari</p>
                </div>

                <div class="rounded-xl bg-indigo-50 p-4">
                    <p class="text-sm font-medium text-indigo-700">Jumlah Transaksi</p>
                    <p class="mt-2 text-2xl font-bold text-indigo-700">
                        {{ number_format($summary['period_transaction_count'], 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-indigo-700/80">Transaksi masuk dan keluar</p>
                </div>
            </div>

            <div class="relative mt-6 h-72 w-full sm:h-80">
                <canvas id="stockMovementChart" role="img"
                    aria-label="Grafik perbandingan stok masuk dan stok keluar"></canvas>
            </div>
        </section>

        {{-- Daftar stok minimum --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div
                class="flex flex-col justify-between gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:p-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 font-bold text-amber-600">!</span>
                        <h2 class="font-bold text-slate-900">Produk Perlu Perhatian</h2>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">
                        Produk yang stok aktualnya sudah mencapai atau berada di bawah batas minimum.
                    </p>
                </div>

                <a href="{{ route('products.index') }}"
                    class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">
                    Lihat semua produk →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-semibold">SKU</th>
                            <th class="px-6 py-4 font-semibold">Nama Produk</th>
                            <th class="px-6 py-4 font-semibold">Stok Saat Ini</th>
                            <th class="px-6 py-4 font-semibold">Batas Minimum</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($lowStockProducts as $item)
                            <tr class="transition hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">
                                    {{ $item['product']->sku }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $item['product']->name }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 font-semibold {{ $item['current_stock'] <= 0 ? 'text-rose-600' : 'text-amber-600' }}">
                                    {{ number_format($item['current_stock'], 0, ',', '.') }}
                                    {{ $item['product']->unit }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                    {{ number_format($item['product']->minimum_stock, 0, ',', '.') }}
                                    {{ $item['product']->unit }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item['current_stock'] <= 0)
                                        <span
                                            class="inline-flex rounded-full bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-200">
                                            Stok habis
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-200">
                                            Stok rendah
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-xl font-bold text-emerald-600">
                                        ✓
                                    </div>
                                    <p class="mt-3 font-semibold text-slate-800">Semua stok dalam kondisi aman</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Tidak ada produk yang mencapai batas minimum.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($summary['low_stock_count'] > 5)
                <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-3">
                    <p class="text-xs text-slate-500">
                        Menampilkan 5 dari {{ $summary['low_stock_count'] }} produk yang perlu perhatian.
                    </p>
                </div>
            @endif
        </section>

        {{-- Aktivitas terbaru --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div
                class="flex flex-col justify-between gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:p-6">
                <div>
                    <h2 class="font-bold text-slate-900">Aktivitas Terbaru</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Catatan aktivitas pengguna di StockWise.
                    </p>
                </div>

                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('activity-logs.index') }}"
                        class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">
                        Lihat semua aktivitas →
                    </a>
                @endif
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($recentActivities as $activity)
                    <div class="flex items-start gap-3 px-5 py-4 sm:px-6">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-sm font-bold text-indigo-600">
                            {{ strtoupper(substr($activity->user_name ?? 'S', 0, 1)) }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800">
                                {{ $activity->description }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $activity->user_name ?? 'Pengguna tidak tersedia' }}
                                <span class="mx-1">·</span>
                                {{ ucfirst($activity->module) }}
                            </p>
                        </div>

                        <time class="shrink-0 text-right text-xs text-slate-400"
                            title="{{ $activity->created_at?->format('d-m-Y H:i:s') }}">
                            {{ $activity->created_at?->format('d M, H:i') }}
                        </time>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <p class="font-semibold text-slate-800">Belum ada aktivitas</p>
                        <p class="mt-1 text-sm text-slate-500">
                            Aktivitas pengguna akan muncul di sini setelah tercatat.
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Akses cepat --}}
        <section>
            <div class="mb-4">
                <h2 class="text-lg font-bold text-slate-900">Akses Cepat</h2>
                <p class="mt-1 text-sm text-slate-500">Buka halaman yang sering digunakan.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a href="{{ route('products.index') }}"
                    class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                    <span class="font-semibold text-slate-800 group-hover:text-indigo-600">Kelola Produk →</span>
                    <p class="mt-2 text-xs text-slate-500">Data barang dan batas stok minimum</p>
                </a>

                <a href="{{ route('transactions.index') }}"
                    class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-200 hover:shadow-md">
                    <span class="font-semibold text-slate-800 group-hover:text-emerald-600">Riwayat Transaksi →</span>
                    <p class="mt-2 text-xs text-slate-500">Pantau pergerakan barang</p>
                </a>

                <a href="{{ route('reports.stock') }}"
                    class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-violet-200 hover:shadow-md">
                    <span class="font-semibold text-slate-800 group-hover:text-violet-600">Laporan Stok →</span>
                    <p class="mt-2 text-xs text-slate-500">Periksa data dan ringkasan persediaan</p>
                </a>
            </div>
        </section>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('stockMovementChart');

            if (!canvas || typeof Chart === 'undefined') {
                return;
            }

            const labels = @json($chartLabels);
            const stockIn = @json($chartStockIn);
            const stockOut = @json($chartStockOut);

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Barang Masuk',
                            data: stockIn,
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5, 150, 105, 0.10)',
                            borderWidth: 2,
                            pointRadius: labels.length > 90 ? 0 : 3,
                            pointHoverRadius: 5,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Barang Keluar',
                            data: stockOut,
                            borderColor: '#E11D48',
                            backgroundColor: 'rgba(225, 29, 72, 0.06)',
                            borderWidth: 2,
                            pointRadius: labels.length > 90 ? 0 : 3,
                            pointHoverRadius: 5,
                            tension: 0.3,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' +
                                        new Intl.NumberFormat('id-ID').format(context.parsed.y) +
                                        ' unit';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxTicksLimit: 10,
                                maxRotation: 0
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            title: {
                                display: true,
                                text: 'Jumlah unit'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
