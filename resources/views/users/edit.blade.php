@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <a href="{{ route('users.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                ← Kembali ke Manajemen Pengguna
            </a>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900">
                Edit Pengguna
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Perbarui informasi akun {{ $user->name }}.
            </p>
        </div>

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50/70 px-6 py-5">
                <h2 class="font-semibold text-gray-900">Informasi Akun</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Password hanya perlu diisi jika ingin menggantinya.
                </p>
            </div>

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6 p-6">
                @method('PUT')
                @include('users.form', ['user' => $user])
            </form>
        </div>
    </div>
@endsection
