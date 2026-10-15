@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">
                        Dashboard
                    </a>
                    <span>/</span>
                    <span class="text-gray-700">Manajemen Pengguna</span>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                    Manajemen Pengguna
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola akun, hak akses, dan status pengguna StockWise.
                </p>
            </div>

            <a href="{{ route('users.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                <span class="text-lg leading-none">+</span>
                Tambah Pengguna
            </a>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div
                class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                <span class="font-bold">✓</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                    <span class="rounded-xl bg-indigo-50 p-2 text-indigo-600">👥</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">
                    {{ number_format($totalUsers) }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Seluruh akun terdaftar</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Akun Aktif</p>
                    <span class="rounded-xl bg-emerald-50 p-2 text-emerald-600">✓</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">
                    {{ number_format($activeUsers) }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Dapat mengakses sistem</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Administrator</p>
                    <span class="rounded-xl bg-violet-50 p-2 text-violet-600">♙</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">
                    {{ number_format($adminCount) }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Admin aktif</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Staff Gudang</p>
                    <span class="rounded-xl bg-amber-50 p-2 text-amber-600">▤</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">
                    {{ number_format($staffCount) }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Staff aktif</p>
            </div>
        </div>

        {{-- Tabel pengguna --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-5 sm:p-6">
                <h2 class="text-base font-semibold text-gray-900">
                    Daftar Akun
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Cari akun dan atur hak akses pengguna.
                </p>

                <form method="GET" action="{{ route('users.index') }}"
                    class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">

                    <div class="sm:col-span-2">
                        <label for="search" class="sr-only">Cari pengguna</label>
                        <input id="search" type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none transition placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>

                    <div>
                        <label for="role" class="sr-only">Filter role</label>
                        <select id="role" name="role"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Role</option>
                            <option value="admin" @selected(request('role') === 'admin')>
                                Admin
                            </option>
                            <option value="staff" @selected(request('role') === 'staff')>
                                Staff
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="sr-only">Filter status</label>
                        <select id="status" name="status"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Status</option>
                            <option value="active" @selected(request('status') === 'active')>
                                Aktif
                            </option>
                            <option value="inactive" @selected(request('status') === 'inactive')>
                                Nonaktif
                            </option>
                        </select>
                    </div>

                    <div class="flex flex-wrap gap-2 sm:col-span-2 xl:col-span-4">
                        <button type="submit"
                            class="rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700">
                            Terapkan Filter
                        </button>

                        <a href="{{ route('users.index') }}"
                            class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-190 text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Pengguna</th>
                            <th class="px-6 py-4 font-semibold">Role</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Terdaftar</th>
                            <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr class="transition hover:bg-gray-50/80">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-700">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-gray-900">
                                                {{ $user->name }}
                                                @if ($user->id === auth()->id())
                                                    <span class="ml-1 text-xs font-normal text-gray-400">
                                                        (Kamu)
                                                    </span>
                                                @endif
                                            </p>
                                            <p class="mt-0.5 truncate text-gray-500">
                                                {{ $user->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if ($user->role === 'admin')
                                        <span
                                            class="inline-flex rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700">
                                            Admin
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">
                                            Staff Gudang
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if ($user->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-gray-500">
                                    {{ $user->created_at?->format('d M Y') ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                            Edit
                                        </a>

                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.toggle-status', $user) }}"
                                                onsubmit="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun ini?')">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="rounded-lg border px-3 py-2 text-xs font-semibold transition
                                                {{ $user->is_active
                                                    ? 'border-red-200 text-red-600 hover:bg-red-50'
                                                    : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">
                                        <span class="text-xl">⌕</span>
                                    </div>
                                    <p class="mt-3 font-semibold text-gray-900">
                                        Pengguna tidak ditemukan
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Coba ubah kata kunci atau filter pencarian.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t border-gray-100 px-5 py-4 sm:px-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
