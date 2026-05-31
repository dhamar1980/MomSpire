<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TwoFactorAuthenticationService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    public function __construct(
        private readonly TwoFactorAuthenticationService $twoFactor,
    ) {
    }

    public function security(Request $request)
    {
        $user = $request->user();

        return view('settings.security', [
            'user' => $user,
            'twoFactorEnabled' => $this->hasEnabledTwoFactor($user),
            'twoFactorPending' => filled($user->two_factor_secret) && blank($user->two_factor_confirmed_at),
            'secretKey' => $this->safeSecret($user),
            'qrCodeSvg' => filled($user->two_factor_secret) ? $this->twoFactor->qrCodeSvg($user) : '',
            'recoveryCodes' => $this->twoFactor->recoveryCodes($user),
        ]);
    }

    public function enable(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        $isGoogleSso = $user->google_sso_completed ?? false;
        $hasCustomPassword = $user->has_custom_password ?? false;

        // Google SSO user tanpa password custom → redirect ke set password
        if ($isGoogleSso && !$hasCustomPassword) {
            return $this->response($request, 'Harap atur password terlebih dahulu sebelum mengaktifkan 2FA.', [
                'error' => 'google_sso_no_password',
                'redirect' => route('settings.set-password'),
            ]);
        }

        // Google SSO user yang sudah punya password → skip password verification
        // (mereka sudah ter-verifikasi via Google OAuth)
        // User biasa → wajib verifikasi password
        $password = null;
        if (!$isGoogleSso) {
            $validated = $request->validate([
                'password' => ['required', 'current_password:web'],
            ]);
            $password = $validated['password'];
        }

        $secret = $this->twoFactor->generateSecret();

        $user->forceFill([
            'two_factor_secret' => $this->twoFactor->encryptSecret($secret),
            'two_factor_recovery_codes' => $this->twoFactor->encryptRecoveryCodes(
                $this->twoFactor->generateRecoveryCodes(),
            ),
            'two_factor_confirmed_at' => null,
            'two_factor_time_drift' => 0,
        ])->save();

        if ($password) {
            Auth::logoutOtherDevices($password);
        }
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return $this->response($request, '2FA dibuat. Scan QR code lalu masukkan OTP pertama untuk mengaktifkannya.', [
            'enabled' => false,
            'pending' => true,
            'svg' => $this->twoFactor->qrCodeSvg($user->fresh()),
            'secretKey' => $secret,
            'recoveryCodes' => $this->twoFactor->recoveryCodes($user->fresh()),
        ]);
    }

    public function confirm(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $code = preg_replace('/\D/', '', $validated['code']);

        if (strlen($code) !== 6 || ! $this->twoFactor->confirmSetupCode($request->user(), $code)) {
            throw ValidationException::withMessages([
                'code' => 'Kode OTP tidak valid.',
            ]);
        }

        $request->user()->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->put('auth.two_factor_verified_at', now()->unix());
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return $this->response($request, '2FA berhasil diaktifkan.', [
            'enabled' => true,
            'pending' => false,
        ]);
    }

    public function disable(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        $isGoogleSso = $user->google_sso_completed ?? false;
        $hasCustomPassword = $user->has_custom_password ?? false;

        // User biasa → wajib verifikasi password
        // Google SSO user tanpa password custom → tidak bisa disable 2FA
        // Google SSO user dengan password → skip password verification
        if ($isGoogleSso && !$hasCustomPassword) {
            return $this->response($request, 'Harap atur password terlebih dahulu untuk menonaktifkan 2FA.', [
                'error' => 'google_sso_no_password',
                'redirect' => route('settings.set-password'),
            ]);
        }

        $password = null;
        if (!$isGoogleSso) {
            $validated = $request->validate([
                'password' => ['required', 'current_password:web'],
            ]);
            $password = $validated['password'];
        }

        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        if ($password) {
            Auth::logoutOtherDevices($password);
        }
        $request->session()->forget('auth.two_factor_verified_at');
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return $this->response($request, '2FA berhasil dinonaktifkan.', [
            'enabled' => false,
            'pending' => false,
        ]);
    }

    public function showSetPassword(Request $request)
    {
        return view('auth.set-password');
    }

    public function setPassword(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'has_custom_password' => true,
        ])->save();

        $request->session()->regenerate();

        return $this->response($request, 'Password berhasil diset. Sekarang Anda bisa mengaktifkan 2FA.', [
            'has_custom_password' => true,
        ]);
    }

    public function qrCode(Request $request): JsonResponse
    {
        abort_unless(filled($request->user()->two_factor_secret), 404);

        return response()->json([
            'svg' => $this->twoFactor->qrCodeSvg($request->user()),
            'qr_code' => $this->twoFactor->qrCodeSvg($request->user()),
        ]);
    }

    public function serverTime(Request $request): JsonResponse
    {
        return response()->json([
            'server_time' => now()->toIso8601String(),
            'server_timestamp' => now()->timestamp,
            'server_timezone' => config('app.timezone'),
        ]);
    }

    public function secretKey(Request $request): JsonResponse
    {
        abort_unless(filled($request->user()->two_factor_secret), 404);

        return response()->json([
            'secretKey' => $this->twoFactor->decryptSecret($request->user()),
            'secret_key' => $this->twoFactor->decryptSecret($request->user()),
        ]);
    }

    public function recoveryCodes(Request $request): JsonResponse
    {
        return response()->json($this->twoFactor->recoveryCodes($request->user()));
    }

    public function regenerateRecoveryCodes(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        $isGoogleSso = $user->google_sso_completed ?? false;

        if (!$isGoogleSso) {
            $request->validate([
                'password' => ['required', 'current_password:web'],
            ]);
        }

        abort_unless(filled($request->user()->two_factor_secret), 404);

        $request->user()->forceFill([
            'two_factor_recovery_codes' => $this->twoFactor->encryptRecoveryCodes(
                $this->twoFactor->generateRecoveryCodes(),
            ),
        ])->save();

        return $this->response($request, 'Recovery codes baru berhasil dibuat.', [
            'recoveryCodes' => $this->twoFactor->recoveryCodes($request->user()->fresh()),
        ]);
    }

    public function showChallenge(Request $request)
    {
        if (! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        return response()
            ->view('auth.two-factor-challenge', [
                'email' => optional(User::find($request->session()->get('login.id')))->email,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function verifyChallenge(Request $request): RedirectResponse
    {
        $user = User::find($request->session()->get('login.id'));

        if (! $user) {
            $request->session()->forget(['login.id', 'login.remember']);

            return redirect()->route('login');
        }

        $throttleKey = $this->challengeThrottleKey($request, $user);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'code' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $request->validate([
            'code' => ['nullable', 'string', 'required_without:recovery_code'],
            'recovery_code' => ['nullable', 'string', 'required_without:code'],
        ]);

        $valid = false;

        if (filled($request->input('code'))) {
            $code = preg_replace('/\D/', '', (string) $request->input('code'));
            $valid = strlen($code) === 6 && $this->twoFactor->verifyCode($user, $code);
        }

        if (! $valid && filled($request->input('recovery_code'))) {
            $valid = $this->twoFactor->consumeRecoveryCode($user, (string) $request->input('recovery_code'));
        }

        if (! $valid) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'code' => 'Kode OTP atau recovery code tidak valid.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $remember = (bool) $request->session()->pull('login.remember', false);
        $request->session()->forget('login.id');

        Auth::login($user, $remember);
        $request->session()->regenerate();
        $request->session()->put('auth.two_factor_verified_at', now()->unix());
        $request->session()->forget('url.intended');

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->to($this->dashboardPath($user));
    }

    private function response(Request $request, string $message, array $payload = []): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'csrfToken' => csrf_token(),
                ...$payload,
            ]);
        }

        return back()->with('status', $message);
    }

    private function hasEnabledTwoFactor(User $user): bool
    {
        return filled($user->two_factor_secret) && filled($user->two_factor_confirmed_at);
    }

    private function safeSecret(User $user): ?string
    {
        if (blank($user->two_factor_secret)) {
            return null;
        }

        return $this->twoFactor->decryptSecret($user);
    }

    private function challengeThrottleKey(Request $request, User $user): string
    {
        return 'two-factor-challenge:'.$user->getKey().'|'.$request->ip();
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
