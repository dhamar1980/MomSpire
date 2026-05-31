<?php

namespace App\Actions\Fortify;

use App\Services\TwoFactorAuthenticationService;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes as FortifyGenerateNewRecoveryCodes;
use Laravel\Fortify\Events\RecoveryCodesGenerated;

class GenerateNewRecoveryCodes extends FortifyGenerateNewRecoveryCodes
{
    public function __construct(
        private readonly TwoFactorAuthenticationService $twoFactor,
    ) {
    }

    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_recovery_codes' => $this->twoFactor->encryptRecoveryCodes(
                $this->twoFactor->generateRecoveryCodes(),
            ),
        ])->save();

        RecoveryCodesGenerated::dispatch($user);
    }
}
