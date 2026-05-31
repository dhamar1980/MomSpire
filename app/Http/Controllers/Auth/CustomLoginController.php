<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
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
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Email tidak ditemukan
            throw ValidationException::withMessages([
                'email' => ['Email tidak ditemukan.'],
            ]);
        }

        $matchedPasswordHash = $this->matchedPasswordHash($user, $password);

        if (!$matchedPasswordHash) {
            // Password salah
            throw ValidationException::withMessages([
                'password' => ['Password salah.'],
            ]);
        }

        if (!hash_equals((string) $user->password, $matchedPasswordHash)) {
            $user->forceFill(['password' => $matchedPasswordHash])->save();
        }

        // Jika user sudah mengaktifkan 2FA, lanjutkan ke challenge TOTP.
        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            $request->session()->put('login.id', $user->id);
            $request->session()->put('login.remember', $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('two-factor-challenge');
        }

        // Login user
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->to($this->dashboardPath($user));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function matchedPasswordHash(User $user, string $password): ?string
    {
        if (is_string($user->password) && Hash::check($password, $user->password)) {
            return $user->password;
        }

        $roleHash = $this->roleTablePasswordHash($user);

        if (is_string($roleHash) && Hash::check($password, $roleHash)) {
            return $roleHash;
        }

        return null;
    }

    private function roleTablePasswordHash(User $user): ?string
    {
        $table = match ($user->role) {
            'admin' => 'admin',
            'bidan' => 'bidan',
            'dokter' => 'dokter',
            'pengguna' => 'pengguna',
            default => null,
        };

        if (!$table || !Schema::hasTable($table) || !Schema::hasColumn($table, 'password')) {
            return null;
        }

        $query = DB::table($table);

        if (Schema::hasColumn($table, 'user_id')) {
            $query->where(function ($nested) use ($user) {
                $nested
                    ->where('user_id', $user->id)
                    ->orWhere('email', $user->email);
            });
        } else {
            $query->where('email', $user->email);

            if (Schema::hasColumn($table, 'id')) {
                $query->orWhere('id', $user->id);
            }
        }

        return $query->value('password');
    }

    private function dashboardPath(User $user): string
    {
        return match ($user->role) {
            'admin' => url('/admin/dashboard'),
            'bidan' => url('/bidan/dashboard'),
            'dokter' => url('/dokter/dashboard'),
            'pengguna' => route('pengguna.dashboard'),
            default => url('/dashboard'),
        };
    }
}
