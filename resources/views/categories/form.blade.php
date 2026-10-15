@extends('layouts.app')

@section('title', $isEdit ? 'Edit Kategori' : 'Tambah Kategori')
@section('page-heading', $isEdit ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">

        <div>
            <a href="{{ route('categories.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-indigo-600">
                <span>←</span> Kembali ke daftar kategori
            </a>
            <div class="mt-4 flex items-start gap-3">
                <span
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-xl font-bold text-indigo-600">◫</span>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        {{ $isEdit ? 'Edit Kategori' : 'Tambah Kategori' }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $isEdit ? 'Perbarui nama atau deskripsi kategori.' : 'Buat kategori untuk mengelompokkan barang berdasarkan jenisnya.' }}
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ $isEdit ? route('categories.update', $category) : route('categories.store') }}" method="POST"
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <h2 class="font-bold text-slate-900">Informasi Kategori</h2>
                <p class="mt-1 text-sm text-slate-500">Isi informasi kategori di bawah ini.</p>
            </div>

            <div class="space-y-6 p-5 sm:p-6">
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Kategori <span class="text-rose-500">*</span>
                    </label>
                    <input id="name" name="name" type="text" maxlength="100" required
                        value="{{ old('name', $category->name) }}" placeholder="Contoh: Elektronik"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">
                    @error('name')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-400">Gunakan nama yang mudah dikenali dan konsisten.</p>
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">
                        Deskripsi <span class="font-normal text-slate-400">(opsional)</span>
                    </label>
                    <textarea id="description" name="description" rows="4"
                        placeholder="Jelaskan jenis barang yang termasuk dalam kategori ini..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div
                class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:px-6">
                <a href="{{ route('categories.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700">
                    <span>✓</span> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Kategori' }}
                </button>
            </div>
        </form>
    </div>
@endsection
