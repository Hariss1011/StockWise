<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | StockWise</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-xl md:grid-cols-2">

            <section class="hidden flex-col justify-between bg-slate-900 p-10 text-white md:flex">
                <div>
                    <div class="mb-12 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500 text-xl font-bold">
                            S
                        </div>
                        <span class="text-2xl font-bold tracking-tight">StockWise</span>
                    </div>

                    <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-blue-300">
                        Inventory Management
                    </p>

                    <h1 class="text-4xl font-bold leading-tight">
                        Kelola stok lebih mudah, cepat, dan terorganisir.
                    </h1>

                    <p class="mt-5 leading-7 text-slate-300">
                        Pantau persediaan, catat transaksi, dan akses laporan
                        inventaris dalam satu sistem.
                    </p>
                </div>

                <p class="text-sm text-slate-400">
                    StockWise &copy; {{ date('Y') }}
                    · Inventory Management System
                </p>
            </section>

            <section class="p-7 sm:p-10 lg:p-12">
                <div class="mb-8 md:hidden">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-xl font-bold text-white">
                            S
                        </div>
                        <span class="text-2xl font-bold">StockWise</span>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="text-sm font-semibold text-blue-600">
                        SELAMAT DATANG KEMBALI
                    </p>

                    <h2 class="mt-2 text-3xl font-bold tracking-tight">
                        Masuk ke akun
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Masukkan email dan password untuk melanjutkan
                        ke dashboard StockWise.
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold">
                            Alamat email
                        </label>

                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            autocomplete="username" required autofocus placeholder="nama@contoh.com"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold">
                            Password
                        </label>

                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            placeholder="Masukkan password"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>

                    <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" value="1"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        Ingat saya
                    </label>

                    <button type="submit"
                        class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                        Masuk ke StockWise
                    </button>
                </form>

                <p class="mt-8 text-center text-xs leading-5 text-slate-400">
                    Akses hanya untuk pengguna yang memiliki akun StockWise.
                    Hubungi administrator jika membutuhkan akun.
                </p>
            </section>
        </div>
    </main>
</body>

</html>
