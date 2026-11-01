<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Cari akun berdasarkan username atau email.
        $user = User::where('username', $request->login)
            ->orWhere('email', $request->login)
            ->first();

        // Tolak akun yang tidak ditemukan atau tidak aktif.
        if (!$user || !$user->is_active) {
            return back()
                ->withErrors([
                    'login' => 'Username/email atau password salah, atau akun tidak aktif.',
                ])
                ->onlyInput('login');
        }

        // Autentikasi menggunakan email dan password.
        if (!Auth::attempt([
            'email' => $user->email,
            'password' => $request->password,
        ])) {
            return back()
                ->withErrors([
                    'login' => 'Username/email atau password salah, atau akun tidak aktif.',
                ])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
