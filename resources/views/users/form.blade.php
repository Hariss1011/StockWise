@csrf

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
            Nama lengkap <span class="text-red-500">*</span>
        </label>
        <input id="name" type="text" name="name" value="{{ old('name', $user?->name) }}" required maxlength="255"
            autocomplete="name"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            placeholder="Masukkan nama lengkap">

        @error('name')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
            Email <span class="text-red-500">*</span>
        </label>
        <input id="email" type="email" name="email" value="{{ old('email', $user?->email) }}" required
            maxlength="255" autocomplete="email"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            placeholder="nama@perusahaan.com">

        @error('email')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="role" class="mb-2 block text-sm font-medium text-gray-700">
            Role pengguna <span class="text-red-500">*</span>
        </label>
        <select id="role" name="role" required
            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
            <option value="staff" @selected(old('role', $user?->role ?? 'staff') === 'staff')>
                Staff Gudang
            </option>
            <option value="admin" @selected(old('role', $user?->role) === 'admin')>
                Administrator
            </option>
        </select>

        <p class="mt-1.5 text-xs text-gray-500">
            Admin dapat mengelola pengguna dan data master. Staff memiliki akses operasional sesuai pengaturan sistem.
        </p>

        @error('role')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="mb-2 block text-sm font-medium text-gray-700">
            Password
            @if (!$user)
                <span class="text-red-500">*</span>
            @else
                <span class="font-normal text-gray-400">(opsional)</span>
            @endif
        </label>
        <input id="password" type="password" name="password" {{ $user ? '' : 'required' }} minlength="8"
            autocomplete="new-password"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            placeholder="{{ $user ? 'Kosongkan jika tidak diganti' : 'Minimal 8 karakter' }}">

        @error('password')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">
            Konfirmasi password
        </label>
        <input id="password_confirmation" type="password" name="password_confirmation" {{ $user ? '' : 'required' }}
            minlength="8" autocomplete="new-password"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            placeholder="Ulangi password">

        @error('password_confirmation')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
    <a href="{{ route('users.index') }}"
        class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
        Batal
    </a>

    <button type="submit"
        class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
        {{ $user ? 'Simpan Perubahan' : 'Buat Pengguna' }}
    </button>
</div>
