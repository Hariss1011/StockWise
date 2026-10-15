@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <a href="{{ route('users.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                ← Kembali ke Manajemen Pengguna
            </a>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900">
                Tambah Pengguna
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Buat akun baru untuk mengakses sistem StockWise.
            </p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50/70 px-6 py-5">
                <h2 class="font-semibold text-gray-900">Informasi Akun</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Lengkapi data pengguna dan tentukan hak aksesnya.
                </p>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="space-y-6 p-6">
                @include('users.form', ['user' => null])
            </form>
        </div>
    </div>
@endsection
