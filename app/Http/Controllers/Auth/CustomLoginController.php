<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        session()->regenerateToken();

        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $request->email;
        $password = $request->password;

        // Cek apakah email ada di database
        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            // Email tidak ditemukan
            throw ValidationException::withMessages([
                'email' => ['Email tidak ditemukan.'],
            ]);
        }

        // Email ada, cek password
        if (!Hash::check($password, $user->password)) {
            // Password salah
            throw ValidationException::withMessages([
                'password' => ['Password salah.'],
            ]);
        }

        // Login user
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        // Redirect berdasarkan role
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->intended(url('/admin/dashboard'));
        }
        if ($user->role === 'bidan') {
            return redirect()->intended(url('/bidan/dashboard'));
        }
        if ($user->role === 'dokter') {
            return redirect()->intended(url('/dokter/dashboard'));
        }
        if ($user->role === 'pengguna') {
            return redirect()->intended(route('pengguna.dashboard'));
        }

        return redirect()->intended(url('/dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}