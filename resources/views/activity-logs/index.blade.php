@extends('layouts.app')

@section('title', 'Riwayat Aktivitas')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">
                    Dashboard
                </a>
                <span>/</span>
                <span class="text-gray-700">Riwayat Aktivitas</span>
            </div>

            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                        Riwayat Aktivitas
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Pantau siapa melakukan apa di dalam sistem StockWise.
                    </p>
                </div>

                <div
                    class="inline-flex items-center gap-2 self-start rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-600 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Pencatatan aktivitas
                </div>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Total Aktivitas</p>
                    <span class="rounded-xl bg-indigo-50 p-2 text-indigo-600">↗</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">
                    {{ number_format($totalActivities) }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Seluruh catatan aktivitas</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Aktivitas Hari Ini</p>
                    <span class="rounded-xl bg-sky-50 p-2 text-sky-600">◷</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">
                    {{ number_format($todayActivities) }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Dicatat pada hari ini</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Pelaku Hari Ini</p>
                    <span class="rounded-xl bg-violet-50 p-2 text-violet-600">♙</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">
                    {{ number_format($activeActorsToday) }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Akun berbeda yang tercatat</p>
            </div>
        </div>

        {{-- Daftar aktivitas --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-5 sm:p-6">
                <h2 class="font-semibold text-gray-900">Log Aktivitas</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Gunakan filter untuk menelusuri perubahan data.
                </p>

                <form method="GET" action="{{ route('activity-logs.index') }}"
                    class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">

                    <div class="xl:col-span-2">
                        <label for="search" class="sr-only">Cari aktivitas</label>
                        <input id="search" name="search" type="text" value="{{ request('search') }}"
                            placeholder="Cari nama pengguna atau aktivitas..."
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>

                    <div>
                        <label for="user_id" class="sr-only">Pengguna</label>
                        <select id="user_id" name="user_id"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Pengguna</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="module" class="sr-only">Modul</label>
                        <select id="module" name="module"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Modul</option>
                            @foreach (['Produk', 'Kategori', 'Supplier', 'Stok Masuk', 'Stok Keluar', 'Penyesuaian Stok', 'Pengguna'] as $module)
                                <option value="{{ $module }}" @selected(request('module') === $module)>
                                    {{ $module }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="action" class="sr-only">Tindakan</label>
                        <select id="action" name="action"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Tindakan</option>
                            <option value="created" @selected(request('action') === 'created')>
                                Penambahan
                            </option>
                            <option value="updated" @selected(request('action') === 'updated')>
                                Perubahan
                            </option>
                            <option value="deleted" @selected(request('action') === 'deleted')>
                                Penghapusan
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="from" class="mb-1 block text-xs font-medium text-gray-500">
                            Dari tanggal
                        </label>
                        <input id="from" type="date" name="from" value="{{ request('from') }}"
                            class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>

                    <div>
                        <label for="to" class="mb-1 block text-xs font-medium text-gray-500">
                            Sampai tanggal
                        </label>
                        <input id="to" type="date" name="to" value="{{ request('to') }}"
                            class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>

                    <div class="flex flex-wrap items-end gap-2 sm:col-span-2 xl:col-span-3">
                        <button type="submit"
                            class="rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700">
                            Terapkan Filter
                        </button>

                        <a href="{{ route('activity-logs.index') }}"
                            class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-225 text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Waktu</th>
                            <th class="px-5 py-4 font-semibold">Pengguna</th>
                            <th class="px-5 py-4 font-semibold">Aktivitas</th>
                            <th class="px-5 py-4 font-semibold">Modul</th>
                            <th class="px-5 py-4 font-semibold">Detail</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($logs as $log)
                            <tr class="align-top transition hover:bg-gray-50/80">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-medium text-gray-900">
                                        {{ $log->created_at->format('d M Y') }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $log->created_at->format('H:i:s') }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                                            {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ $log->user_name }}
                                            </p>
                                            <p class="mt-0.5 text-xs text-gray-500">
                                                {{ $log->user?->email ?? 'Akun tidak tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $actionStyles = [
                                            'created' => 'bg-emerald-50 text-emerald-700',
                                            'updated' => 'bg-amber-50 text-amber-700',
                                            'deleted' => 'bg-red-50 text-red-700',
                                        ];

                                        $actionLabels = [
                                            'created' => 'Penambahan',
                                            'updated' => 'Perubahan',
                                            'deleted' => 'Penghapusan',
                                        ];
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $actionStyles[$log->action] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $actionLabels[$log->action] ?? $log->action }}
                                    </span>

                                    <p class="mt-2 max-w-xs leading-5 text-gray-700">
                                        {{ $log->description }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-600">
                                        {{ $log->module }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @if (!empty($log->properties['changes']))
                                        <details class="max-w-xs">
                                            <summary
                                                class="cursor-pointer font-medium text-indigo-600 hover:text-indigo-800">
                                                Lihat perubahan
                                            </summary>

                                            <div class="mt-2 space-y-2 rounded-lg bg-gray-50 p-3">
                                                @foreach ($log->properties['changes'] as $field => $value)
                                                    <div>
                                                        <p class="text-xs font-semibold text-gray-500">
                                                            {{ $field }}
                                                        </p>
                                                        <p class="break-all text-xs text-gray-800">
                                                            {{ is_scalar($value) || $value === null ? ($value === null ? 'kosong' : $value) : json_encode($value) }}
                                                        </p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">
                                        <span class="text-xl">◷</span>
                                    </div>
                                    <p class="mt-3 font-semibold text-gray-900">
                                        Belum ada aktivitas
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Aktivitas baru akan muncul setelah pengguna melakukan perubahan yang tercatat.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="border-t border-gray-100 px-5 py-4 sm:px-6">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
