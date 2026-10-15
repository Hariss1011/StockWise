@extends('layouts.app')

@section('title', $isEdit ? 'Edit Supplier' : 'Tambah Supplier')
@section('page-heading', $isEdit ? 'Edit Supplier' : 'Tambah Supplier')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">

        <div>
            <a href="{{ route('suppliers.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-indigo-600">
                <span>←</span> Kembali ke daftar supplier
            </a>
            <div class="mt-4 flex items-start gap-3">
                <span
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-xl font-bold text-indigo-600">♙</span>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        {{ $isEdit ? 'Edit Supplier' : 'Tambah Supplier' }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $isEdit ? 'Perbarui informasi pemasok yang terdaftar.' : 'Daftarkan pemasok untuk mempermudah pengelolaan barang.' }}
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ $isEdit ? route('suppliers.update', $supplier) : route('suppliers.store') }}" method="POST"
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <h2 class="font-bold text-slate-900">Informasi Supplier</h2>
                <p class="mt-1 text-sm text-slate-500">Nama pemasok dan informasi kontak.</p>
            </div>

            <div class="space-y-6 p-5 sm:p-6">
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Supplier <span class="text-rose-500">*</span>
                    </label>
                    <input id="name" name="name" type="text" maxlength="150" required
                        value="{{ old('name', $supplier->name) }}" placeholder="Masukkan nama perusahaan atau pemasok"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                    @error('name')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nomor Telepon <span class="font-normal text-slate-400">(opsional)</span>
                        </label>
                        <input id="phone" name="phone" type="text" maxlength="20"
                            value="{{ old('phone', $supplier->phone) }}" placeholder="Contoh: 081234567890"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                        @error('phone')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                            Email <span class="font-normal text-slate-400">(opsional)</span>
                        </label>
                        <input id="email" name="email" type="email" value="{{ old('email', $supplier->email) }}"
                            placeholder="supplier@contoh.com"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                        @error('email')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="mb-2 block text-sm font-semibold text-slate-700">
                        Alamat <span class="font-normal text-slate-400">(opsional)</span>
                    </label>
                    <textarea id="address" name="address" rows="4" placeholder="Masukkan alamat pemasok..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">{{ old('address', $supplier->address) }}</textarea>
                    @error('address')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl border border-sky-100 bg-sky-50 p-4">
                    <div class="flex items-start gap-3">
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-100 font-bold text-sky-700">i</span>
                        <div>
                            <p class="text-sm font-semibold text-sky-900">Tips pengelolaan data</p>
                            <p class="mt-1 text-xs leading-5 text-sky-800">
                                Pastikan nama supplier mudah dikenali. Informasi kontak dapat membantu saat perlu
                                menghubungi pemasok.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:px-6">
                <a href="{{ route('suppliers.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700">
                    <span>✓</span> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Supplier' }}
                </button>
            </div>
        </form>
    </div>
@endsection
