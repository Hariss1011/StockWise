<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! Auth::user()->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun kamu sudah dinonaktifkan.',
                ]);
        }

        if (! in_array(Auth::user()->role, $roles, true)) {
            abort(403, 'Kamu tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
