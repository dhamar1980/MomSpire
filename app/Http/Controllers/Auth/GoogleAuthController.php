<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use RuntimeException;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        if (! $this->googleIsConfigured()) {
            return $this->configurationErrorRedirect($request);
        }

        $request->session()->put(
            'auth.google.intent',
            $request->query('intent') === 'register' ? 'register' : 'login',
        );

        return $this->googleProvider($request)->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if (! $this->googleIsConfigured()) {
            return $this->configurationErrorRedirect($request);
        }

        try {
            $googleUser = $this->googleProvider($request)->user();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Login Google dibatalkan atau gagal. Silakan coba lagi.',
                ]);
        }

        if (blank($googleUser->getEmail())) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun Google tidak mengirimkan email. Gunakan akun Google lain.',
                ]);
        }

        try {
            $user = DB::transaction(fn () => $this->findOrCreateUser($googleUser));
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun Google belum bisa diproses. Pastikan email belum terdaftar di data role lain atau hubungi admin.',
                ]);
        }

        // Fix #2: Jika intent adalah register tapi user SUDAH ADA di database,
        // berarti user sudah pernah daftar sebelumnya → redirect ke login
        $intent = $request->session()->get('auth.google.intent');
        if ($intent === 'register' && $user->email_verified_at) {
            // User sudah terdaftar (sudah ada di database sebelumnya)
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun dengan email ini sudah terdaftar. Silakan login dengan Google atau gunakan akun lain.',
                ]);
        }

        $request->session()->forget('auth.google.intent');

        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            $request->session()->put('login.id', $user->id);
            $request->session()->put('login.remember', false);
            $request->session()->regenerate();

            return redirect()->route('two-factor-challenge');
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        return redirect()->to($this->dashboardPath($user));
    }

    private function findOrCreateUser($googleUser): User
    {
        $email = Str::lower($googleUser->getEmail());
        $name = $googleUser->getName()
            ?: $googleUser->getNickname()
            ?: Str::before($email, '@');

        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if ($user) {
            $updates = [];

            if (blank($user->email_verified_at)) {
                $updates['email_verified_at'] = now();
            }

            if (blank($user->name)) {
                $updates['name'] = $name;
            }

            if ($updates !== []) {
                $user->forceFill($updates)->save();
                $user->refresh();
            }

            $this->ensureRoleRecord($user);

            return $user;
        }

        if ($this->emailExistsInRoleTables($email)) {
            throw new RuntimeException('Email exists in role table without users row.');
        }

        $hashedPassword = Hash::make(Str::random(40));

        $user = User::forceCreate([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => $hashedPassword,
            'role' => 'pengguna',
            'google_sso_completed' => true, // Tandai user ini sudah login via Google SSO
        ]);

        $this->ensureRoleRecord($user, $hashedPassword);

        return $user;
    }

    private function ensureRoleRecord(User $user, ?string $hashedPassword = null): void
    {
        $table = $this->roleTable($user);

        if (! $table || ! Schema::hasTable($table)) {
            return;
        }

        $existing = DB::table($table)
            ->where('id', $user->id)
            ->when(
                $table === 'pengguna' && Schema::hasColumn($table, 'user_id'),
                fn ($query) => $query->orWhere('user_id', $user->id),
            )
            ->orWhereRaw('LOWER(email) = ?', [Str::lower($user->email)])
            ->first();

        $record = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $hashedPassword ?: $user->password,
            'updated_at' => now(),
        ];

        if (Schema::hasColumn($table, 'email_verified_at')) {
            $record['email_verified_at'] = $user->email_verified_at;
        }

        if (Schema::hasColumn($table, 'profile_photo_path')) {
            $record['profile_photo_path'] = $user->profile_photo_path;
        }

        if (Schema::hasColumn($table, 'current_team_id')) {
            $record['current_team_id'] = $user->current_team_id;
        }

        if (Schema::hasColumn($table, 'no_telp')) {
            $record['no_telp'] = $user->no_telp;
        }

        if ($table === 'pengguna') {
            if (Schema::hasColumn($table, 'user_id')) {
                $record['user_id'] = $user->id;
            }

            if (Schema::hasColumn($table, 'is_hamil')) {
                $record['is_hamil'] = false;
            }
        }

        DB::table($table)->updateOrInsert(
            ['id' => $existing->id ?? $user->id],
            [
                ...$record,
                'created_at' => $existing->created_at ?? now(),
            ],
        );
    }

    private function emailExistsInRoleTables(string $email): bool
    {
        foreach (['pengguna', 'bidan', 'dokter', 'admin'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            if (DB::table($table)->whereRaw('LOWER(email) = ?', [$email])->exists()) {
                return true;
            }
        }

        return false;
    }

    private function roleTable(User $user): ?string
    {
        return in_array($user->role, ['pengguna', 'bidan', 'dokter', 'admin'], true)
            ? $user->role
            : null;
    }

    private function googleIsConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'));
    }

    private function googleProvider(Request $request)
    {
        return Socialite::driver('google')
            ->redirectUrl($this->googleRedirectUrl($request));
    }

    private function googleRedirectUrl(Request $request): string
    {
        return $request->getSchemeAndHttpHost()
            . route('auth.google.callback', [], false);
    }

    private function configurationErrorRedirect(Request $request): RedirectResponse
    {
        return redirect()
            ->route($request->query('intent') === 'register' ? 'register' : 'login')
            ->withErrors([
                'email' => 'Google SSO belum dikonfigurasi. Isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET di file .env.',
            ]);
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
