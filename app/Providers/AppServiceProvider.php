<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::createUrlUsing(function ($notifiable) {
            $origin = request()?->getSchemeAndHttpHost() ?: config('app.url');

            if ($origin) {
                URL::forceRootUrl($origin);
            }

            try {
                return URL::temporarySignedRoute(
                    'verification.verify.public',
                    now()->addMinutes(config('auth.verification.expire', 60)),
                    [
                        'id' => $notifiable->getKey(),
                        'hash' => sha1($notifiable->getEmailForVerification()),
                    ],
                );
            } finally {
                URL::forceRootUrl(null);
            }
        });
    }
}
