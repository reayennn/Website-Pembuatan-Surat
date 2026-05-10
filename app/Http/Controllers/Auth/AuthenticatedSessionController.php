<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('landing-petugas');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->user()->role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } elseif ($request->user()->role === 'kepala_desa') {
            return redirect()->intended(route('kades.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Simpan role sebelum session dihapus
        $role = Auth::user()?->role;

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Admin dan Kepala Desa diarahkan ke halaman login petugas
        if (in_array($role, ['admin', 'kepala_desa'])) {
            return redirect('/petugas');
        }

        // Warga diarahkan ke landing page utama
        return redirect('/');
    }
}
