<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where(
                    'is_active',
                    $request->status === 'active'
                );
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $adminCount = User::where('role', 'admin')
            ->where('is_active', true)
            ->count();
        $staffCount = User::where('role', 'staff')
            ->where('is_active', true)
            ->count();

        return view('users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'adminCount',
            'staffCount'
        ));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ]);

        unset($validated['password_confirmation']);

        $validated['is_active'] = true;

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8),
            ],
        ]);

        $newActiveStatus = $user->is_active;

        if (
            $user->id === $request->user()->id &&
            $validated['role'] !== 'admin'
        ) {
            return back()
                ->withErrors([
                    'role' => 'Kamu tidak bisa mengubah role akun sendiri menjadi Staff.',
                ])
                ->withInput();
        }

        if (
            $user->role === 'admin' &&
            $user->is_active &&
            (
                $validated['role'] !== 'admin' ||
                ! $newActiveStatus
            )
        ) {
            $activeAdmins = User::where('role', 'admin')
                ->where('is_active', true)
                ->count();

            if ($activeAdmins <= 1) {
                return back()
                    ->withErrors([
                        'role' => 'Admin terakhir tidak dapat diubah rolenya.',
                    ])
                    ->withInput();
            }
        }

        unset($validated['password_confirmation']);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'Kamu tidak dapat menonaktifkan akun sendiri.'
            );
        }

        if ($user->role === 'admin' && $user->is_active) {
            $activeAdmins = User::where('role', 'admin')
                ->where('is_active', true)
                ->count();

            if ($activeAdmins <= 1) {
                return back()->with(
                    'error',
                    'Admin terakhir tidak dapat dinonaktifkan.'
                );
            }
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $message = $user->is_active
            ? 'Akun pengguna berhasil diaktifkan.'
            : 'Akun pengguna berhasil dinonaktifkan.';

        return back()->with('success', $message);
    }
}
