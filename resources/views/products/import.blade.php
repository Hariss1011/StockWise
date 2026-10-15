@extends('layouts.app')

@section('title', 'Impor Produk')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Impor Produk
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Tambahkan banyak produk sekaligus melalui file Excel.
                </p>
            </div>

            <a href="{{ route('products.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300
                       bg-white px-4 py-2.5 text-sm font-medium text-gray-700
                       transition hover:bg-gray-50">
                Kembali ke Produk
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                <h2 class="font-semibold text-red-800">
                    Impor belum berhasil
                </h2>

                <p class="mt-1 text-sm text-red-700">
                    Perbaiki kesalahan berikut, kemudian unggah kembali file Excel.
                    Tidak ada produk yang disimpan dari proses impor yang gagal.
                </p>

                <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    Langkah 1 — Unduh Template
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Gunakan template resmi agar urutan kolom sesuai dengan sistem.
                </p>
            </div>

            <a href="{{ route('products.import.template') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-50 px-4 py-2.5
                       text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3" />
                </svg>
                Unduh Template Excel
            </a>

            <div class="mt-6 border-t border-gray-100 pt-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    Langkah 2 — Isi Data Produk
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Isi data mulai dari baris kedua. Jangan mengubah urutan kolom.
                </p>

                <div class="mt-4 overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                    Kolom
                                </th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                    Ketentuan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">SKU</td>
                                <td class="px-4 py-3 text-gray-600">
                                    Wajib unik. Formatkan sebagai Text jika menggunakan angka dengan nol di depan.
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">Nama Produk</td>
                                <td class="px-4 py-3 text-gray-600">Wajib diisi.</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">Kategori</td>
                                <td class="px-4 py-3 text-gray-600">
                                    Wajib menggunakan nama kategori yang sudah terdaftar.
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">Supplier</td>
                                <td class="px-4 py-3 text-gray-600">
                                    Opsional. Jika diisi, supplier harus sudah terdaftar.
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">Satuan</td>
                                <td class="px-4 py-3 text-gray-600">
                                    Wajib diisi, misalnya unit atau pcs.
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    Harga Beli / Harga Jual
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    Wajib berupa angka, tanpa simbol Rp.
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    Stok Awal / Stok Minimum
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    Wajib berupa bilangan bulat minimal 0.
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">Deskripsi</td>
                                <td class="px-4 py-3 text-gray-600">Opsional.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <form action="{{ route('products.import.store') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                @csrf

                <h2 class="text-lg font-semibold text-gray-900">
                    Langkah 3 — Unggah File
                </h2>

                <div
                    class="mt-3 rounded-xl border-2 border-dashed border-gray-300 p-6
                            transition hover:border-indigo-400">
                    <label for="file" class="block text-sm font-medium text-gray-700">
                        Pilih file Excel (.xlsx)
                    </label>

                    <input id="file" name="file" type="file" accept=".xlsx" required
                        class="mt-3 block w-full cursor-pointer rounded-lg border border-gray-300
                               text-sm text-gray-700
                               file:mr-4 file:border-0 file:bg-gray-100
                               file:px-4 file:py-2.5 file:font-medium
                               file:text-gray-700 hover:file:bg-gray-200" />

                    <p class="mt-2 text-xs text-gray-500">
                        Format .xlsx, maksimal 5 MB.
                    </p>
                </div>

                <div class="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex justify-center rounded-lg border border-gray-300
                               px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="inline-flex justify-center rounded-lg bg-indigo-600 px-5 py-2.5
                               text-sm font-semibold text-white transition hover:bg-indigo-700">
                        Impor Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
