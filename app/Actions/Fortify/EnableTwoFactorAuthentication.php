<?php

namespace App\Actions\Fortify;

use App\Services\TwoFactorAuthenticationService;
use Laravel\Fortify\Events\TwoFactorAuthenticationEnabled;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication as FortifyEnableTwoFactorAuthentication;

class EnableTwoFactorAuthentication extends FortifyEnableTwoFactorAuthentication
{
    public function __construct(
        private readonly TwoFactorAuthenticationService $twoFactor,
    ) {
    }

    public function __invoke($user, $force = false)
    {
        if (filled($user->two_factor_secret) && $force !== true) {
            return;
        }

        $secret = $this->twoFactor->generateSecret();

        $user->forceFill([
            'two_factor_secret' => $this->twoFactor->encryptSecret($secret),
            'two_factor_recovery_codes' => $this->twoFactor->encryptRecoveryCodes(
                $this->twoFactor->generateRecoveryCodes(),
            ),
            'two_factor_confirmed_at' => null,
        ])->save();

        TwoFactorAuthenticationEnabled::dispatch($user);
    }
}
