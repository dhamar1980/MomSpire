<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\EnableTwoFactorAuthentication as EnableMomSpireTwoFactorAuthentication;
use App\Actions\Fortify\GenerateNewRecoveryCodes as GenerateMomSpireRecoveryCodes;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication as FortifyEnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes as FortifyGenerateNewRecoveryCodes;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FortifyEnableTwoFactorAuthentication::class, EnableMomSpireTwoFactorAuthentication::class);
        $this->app->bind(FortifyGenerateNewRecoveryCodes::class, GenerateMomSpireRecoveryCodes::class);
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Custom password confirmation using Hash::check (bypass guard issues)
        Fortify::confirmPasswordsUsing(function ($user, string $password) {
            return Hash::check($password, $user->password);
        });

        // Register views
        Fortify::loginView(function () {
            session()->regenerateToken();

            return response()
                ->view('auth.login')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        });

        Fortify::registerView(function () {
            session()->regenerateToken();

            return response()
                ->view('auth.register')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        });

        Fortify::verifyEmailView(function (Request $request) {
            return response()
                ->view('auth.verify-email', [
                    'status' => $request->session()->get('status'),
                ])
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        });

        Fortify::twoFactorChallengeView(function () {
            return response()
                ->view('auth.two-factor-challenge')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'.'.$request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            $loginId = $request->session()->get('login.id', 'guest');

            return Limit::perMinute(5)->by($loginId.'|'.$request->ip());
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');
            return Limit::perMinute(10)->by(($credentialId ?: $request->session()->getId()).'.'.$request->ip());
        });

        $this->app->singleton(LoginResponseContract::class, function ($app) {
            return new class implements LoginResponseContract {
                public function toResponse($request)
                {
                    $user = Auth::user();
                    if ($user) {
                        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
                            return redirect()->route('verification.notice');
                        }

                        if ($user->role === 'admin') {
                            return redirect()->to(url('/admin/dashboard'));
                        }
                        if ($user->role === 'bidan') {
                            return redirect()->to(url('/bidan/dashboard'));
                        }
                        if ($user->role === 'dokter') {
                            return redirect()->to(url('/dokter/dashboard'));
                        }
                        if ($user->role === 'pengguna') {
                            return redirect()->route('pengguna.dashboard');
                        }
                    }
                    // If no valid role, redirect to login
                    return redirect()->route('login');
                }
            };
        });
    }
}
