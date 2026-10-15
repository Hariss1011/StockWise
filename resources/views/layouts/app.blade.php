<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">

    <title>@yield('title', 'Dashboard') - StockWise</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    @php
        $user = auth()->user();
        $isAdmin = $user?->role === 'admin';

        $navigation = [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => '▦',
                'active' => request()->routeIs('dashboard'),
            ],
            [
                'label' => 'Produk',
                'route' => 'products.index',
                'icon' => '▣',
                'active' => request()->routeIs('products.*'),
            ],
            [
                'label' => 'Kategori',
                'route' => 'categories.index',
                'icon' => '◫',
                'active' => request()->routeIs('categories.*'),
                'admin' => true,
            ],
            [
                'label' => 'Supplier',
                'route' => 'suppliers.index',
                'icon' => '♙',
                'active' => request()->routeIs('suppliers.*'),
                'admin' => true,
            ],
            [
                'label' => 'Transaksi Stok',
                'route' => 'transactions.index',
                'icon' => '⇄',
                'active' => request()->routeIs('transactions.*'),
            ],
            [
                'label' => 'Peringatan Stok',
                'route' => 'stock-alerts.index',
                'icon' => '⚠',
                'active' => request()->routeIs('stock-alerts.*'),
                'badge' => true,
            ],
            [
                'label' => 'Laporan Stok',
                'route' => 'reports.stock',
                'icon' => '▤',
                'active' => request()->routeIs('reports.stock'),
            ],
            [
                'label' => 'Laporan Transaksi',
                'route' => 'reports.transactions',
                'icon' => '▧',
                'active' => request()->routeIs('reports.transactions'),
            ],
            [
                'label' => 'Rekap Aktivitas',
                'route' => 'reports.activity-summary',
                'icon' => '▥',
                'active' => request()->routeIs('reports.activity-summary*'),
            ],
        ];

        $navigation = collect($navigation)->filter(fn($item) => !($item['admin'] ?? false) || $isAdmin)->values();
    @endphp

    <div class="min-h-screen lg:flex">

        {{-- Sidebar desktop --}}
        <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col bg-slate-950 text-slate-300 lg:flex">

            {{-- Brand --}}
            <a href="{{ route('dashboard') }}"
                class="flex h-20 shrink-0 items-center gap-3 border-b border-white/10 px-6">

                <span
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-500 text-xl font-black text-white shadow-lg shadow-indigo-500/20">
                    S
                </span>

                <span>
                    <span class="block text-xl font-bold tracking-tight text-white">
                        Stock<span class="text-indigo-400">Wise</span>
                    </span>
                    <span class="text-xs text-slate-400">
                        Inventory Management
                    </span>
                </span>
            </a>

            {{-- Menu utama --}}
            <div class="sidebar-scroll min-h-0 flex-1 overflow-y-auto px-4 py-7">
                <p class="mb-3 px-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">
                    Menu Utama
                </p>

                <nav class="space-y-1">
                    @foreach ($navigation as $item)
                        <a href="{{ route($item['route']) }}"
                            @if ($item['active']) aria-current="page" @endif
                            class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                            {{ $item['active']
                                ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-950/30'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                            <span
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-lg
                                {{ $item['active'] ? 'bg-white/15 text-white' : 'text-slate-500 group-hover:text-indigo-300' }}">
                                {{ $item['icon'] }}
                            </span>

                            <span class="min-w-0 flex-1">
                                {{ $item['label'] }}
                            </span>

                            @if ($item['badge'] ?? false)
                                @if (($lowStockCount ?? 0) > 0)
                                    <span
                                        class="inline-flex min-w-6 items-center justify-center rounded-full px-2 py-1 text-[11px] font-bold tabular-nums
                                        {{ $item['active'] ? 'bg-white text-indigo-700' : 'bg-rose-500 text-white' }}">
                                        {{ $lowStockCount > 99 ? '99+' : $lowStockCount }}
                                    </span>
                                @endif
                            @elseif ($item['active'])
                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-white"></span>
                            @endif
                        </a>
                    @endforeach

                    @if ($isAdmin)
                        <div class="my-5 border-t border-white/10"></div>

                        <p class="mb-3 px-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">
                            Administrasi
                        </p>

                        <a href="{{ route('users.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                            {{ request()->routeIs('users.*')
                                ? 'bg-indigo-500 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                            <span class="flex h-8 w-8 items-center justify-center rounded-lg text-lg">
                                ♙
                            </span>

                            <span class="flex-1">Manajemen Pengguna</span>
                        </a>

                        <a href="{{ route('activity-logs.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                            {{ request()->routeIs('activity-logs.*')
                                ? 'bg-indigo-500 text-white'
                                : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                            <span class="flex h-8 w-8 items-center justify-center rounded-lg text-lg">
                                ◷
                            </span>

                            <span class="flex-1">Riwayat Aktivitas</span>
                        </a>
                    @endif
                </nav>
            </div>

            {{-- Informasi sistem --}}
            <div class="shrink-0 p-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <p class="text-xs font-semibold text-white">
                            StockWise System
                        </p>
                    </div>

                    <p class="mt-2 text-xs leading-5 text-slate-400">
                        Sistem manajemen persediaan barang.
                    </p>
                </div>

                <p class="mt-4 text-center text-[11px] text-slate-600">
                    StockWise &copy; {{ date('Y') }}
                </p>
            </div>
        </aside>

        {{-- Area utama --}}
        <div class="min-w-0 flex-1 lg:ml-64">

            {{-- Header --}}
            <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">

                <div class="flex min-h-[68px] items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">

                    <div class="flex min-w-0 items-center gap-3">
                        <a href="{{ route('dashboard') }}" aria-label="StockWise Dashboard"
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-600 font-bold text-white lg:hidden">
                            S
                        </a>

                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-slate-800">
                                @yield('page-heading', 'StockWise')
                            </p>

                            <p class="hidden text-xs text-slate-500 sm:block">
                                Sistem Manajemen Persediaan
                            </p>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-3">

                        {{-- Identitas pengguna --}}
                        <div class="hidden text-right sm:block">
                            <p class="max-w-48 truncate text-sm font-semibold text-slate-700">
                                {{ $user->name }}
                            </p>

                            <p class="text-xs text-slate-500">
                                {{ $isAdmin ? 'Administrator' : 'Staff Gudang' }}
                            </p>
                        </div>

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-50 font-bold text-indigo-700 ring-1 ring-indigo-100">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button type="submit"
                                class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Navigasi mobile --}}
                <nav aria-label="Navigasi utama mobile"
                    class="flex gap-2 overflow-x-auto border-t border-slate-100 px-4 py-3 lg:hidden">

                    @foreach ($navigation as $item)
                        <a href="{{ route($item['route']) }}"
                            @if ($item['active']) aria-current="page" @endif
                            class="inline-flex shrink-0 items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold transition
                            {{ $item['active'] ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">

                            <span>{{ $item['label'] }}</span>

                            @if (($item['badge'] ?? false) && ($lowStockCount ?? 0) > 0)
                                <span
                                    class="inline-flex min-w-5 items-center justify-center rounded-full px-1.5 py-0.5 text-[10px] font-bold tabular-nums
                                    {{ $item['active'] ? 'bg-white text-indigo-700' : 'bg-rose-500 text-white' }}">
                                    {{ $lowStockCount > 99 ? '99+' : $lowStockCount }}
                                </span>
                            @endif
                        </a>
                    @endforeach

                    @if ($isAdmin)
                        <a href="{{ route('users.index') }}"
                            class="shrink-0 rounded-lg px-3 py-2 text-xs font-semibold transition
                            {{ request()->routeIs('users.*')
                                ? 'bg-indigo-600 text-white'
                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Manajemen Pengguna
                        </a>

                        <a href="{{ route('activity-logs.index') }}"
                            class="shrink-0 rounded-lg px-3 py-2 text-xs font-semibold transition
                            {{ request()->routeIs('activity-logs.*')
                                ? 'bg-indigo-600 text-white'
                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Riwayat Aktivitas
                        </a>
                    @endif
                </nav>
            </header>

            {{-- Konten halaman --}}
            <main class="mx-auto w-full max-w-[1600px] p-4 sm:p-6 lg:p-8">

                {{-- Notifikasi berhasil --}}
                @if (session('success'))
                    <div role="status"
                        class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">

                        <span
                            class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 font-bold">
                            ✓
                        </span>

                        <div>
                            <p class="font-semibold">Berhasil</p>
                            <p class="mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Notifikasi kesalahan --}}
                @if (session('error'))
                    <div role="alert"
                        class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">

                        <span
                            class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-100 font-bold">
                            !
                        </span>

                        <div>
                            <p class="font-semibold">Terjadi Kesalahan</p>
                            <p class="mt-0.5">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Validasi --}}
                @if ($errors->any())
                    <div role="alert"
                        class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">

                        <p class="font-semibold">
                            Periksa kembali data yang dimasukkan
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="border-t border-slate-200 bg-white px-6 py-4 lg:px-8">
                <p class="text-center text-xs text-slate-400">
                    StockWise &middot; Sistem Manajemen Stok Barang
                </p>
            </footer>
        </div>
    </div>

    {{-- Script tambahan dari halaman --}}
    @stack('scripts')
</body>

</html>
