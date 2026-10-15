@extends('layouts.app')

@section('title', 'Supplier')
@section('page-heading', 'Manajemen Supplier')

@section('content')
    <div class="space-y-6">

        <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600">
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                    MASTER DATA
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Manajemen Supplier
                </h1>
                <p class="mt-2 text-sm text-slate-500">Kelola informasi pemasok barang dan detail kontaknya.</p>
            </div>

            <a href="{{ route('suppliers.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-700">
                <span class="text-lg leading-none">+</span> Tambah Supplier
            </a>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-xl font-bold text-indigo-600">
                    ♙</div>
                <div>
                    <p class="text-sm text-slate-500">Total Supplier</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($suppliers->total()) }}</p>
                    <p class="mt-1 text-xs text-slate-400">Pemasok yang terdaftar</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-xl font-bold text-sky-600">
                    ≡</div>
                <div>
                    <p class="text-sm text-slate-500">Halaman Aktif</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $suppliers->currentPage() }} <span
                            class="text-base font-medium text-slate-400">/ {{ max($suppliers->lastPage(), 1) }}</span></p>
                    <p class="mt-1 text-xs text-slate-400">Navigasi data supplier</p>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 p-5 sm:p-6">
                <h2 class="font-bold text-slate-900">Daftar Supplier</h2>
                <p class="mt-1 text-sm text-slate-500">Informasi pemasok yang digunakan dalam pengelolaan persediaan.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-237.5 text-left text-sm">
                    <thead class="bg-slate-50/90 text-[11px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="w-20 px-5 py-4 font-semibold">No.</th>
                            <th class="px-5 py-4 font-semibold">Supplier</th>
                            <th class="px-5 py-4 font-semibold">Telepon</th>
                            <th class="px-5 py-4 font-semibold">Email</th>
                            <th class="px-5 py-4 font-semibold">Alamat</th>
                            <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($suppliers as $supplier)
                            <tr class="transition hover:bg-indigo-50/30">
                                <td class="px-5 py-4 text-slate-400">{{ $suppliers->firstItem() + $loop->index }}</td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 font-bold text-indigo-600">♙</span>
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $supplier->name }}</p>
                                            <p class="mt-1 text-xs text-slate-400">Supplier terdaftar</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    @if ($supplier->phone)
                                        <a href="tel:{{ $supplier->phone }}"
                                            class="transition hover:text-indigo-600">{{ $supplier->phone }}</a>
                                    @else
                                        <span class="text-slate-400">Belum tersedia</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    @if ($supplier->email)
                                        <a href="mailto:{{ $supplier->email }}"
                                            class="break-all transition hover:text-indigo-600">{{ $supplier->email }}</a>
                                    @else
                                        <span class="text-slate-400">Belum tersedia</span>
                                    @endif
                                </td>

                                <td class="max-w-xs px-5 py-4 leading-6 text-slate-500">
                                    {{ $supplier->address ?: 'Belum tersedia' }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('suppliers.edit', $supplier) }}"
                                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                            Edit
                                        </a>

                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                                            onsubmit="return confirm('Hapus supplier ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-lg border border-rose-100 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-400">
                                        ♙</div>
                                    <p class="mt-4 font-semibold text-slate-800">Belum ada supplier</p>
                                    <p class="mt-1 text-sm text-slate-500">Tambahkan pemasok untuk menghubungkan produk
                                        dengan supplier.</p>
                                    <a href="{{ route('suppliers.create') }}"
                                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                        + Tambah Supplier
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($suppliers->hasPages())
                <div
                    class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <p class="text-xs text-slate-500">
                        Menampilkan {{ $suppliers->firstItem() ?? 0 }}–{{ $suppliers->lastItem() ?? 0 }}
                        dari {{ $suppliers->total() }} supplier
                    </p>
                    {{ $suppliers->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
