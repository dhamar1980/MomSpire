<?php

namespace App\Services;

use App\Models\User;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthenticationService
{
    private const RECOVERY_CODE_COUNT = 10;
    private const RECOVERY_CODE_CHARS = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public function __construct(
        private readonly TwoFactorAuthenticationProvider $provider,
    ) {
    }

    public function generateSecret(): string
    {
        return $this->provider->generateSecretKey(32);
    }

    public function encryptSecret(string $secret): string
    {
        return Crypt::encryptString($secret);
    }

    public function decryptSecret(User $user): ?string
    {
        if (blank($user->two_factor_secret)) {
            return null;
        }

        try {
            return Crypt::decryptString($user->two_factor_secret);
        } catch (DecryptException) {
            return null;
        }
    }

    public function verifyCode(User $user, string $code): bool
    {
        $secret = $this->decryptSecret($user);
        $code = preg_replace('/\D/', '', $code);
        $window = (int) config('fortify-options.two-factor-authentication.window', 4);
        $google2fa = new Google2FA();

        if (! $this->codeCanBeVerified($user, $secret, $code)) {
            return false;
        }

        \Log::info('2FA verify attempt', [
            'user_id' => $user->id,
            'secret_length' => strlen($secret),
            'code_length' => strlen($code),
            'code_first_chars' => substr($code, 0, 3) . '***',
            'time_drift' => (int) ($user->two_factor_time_drift ?? 0),
        ]);

        $result = $google2fa->verifyKey($secret, $code, $window);

        if (! $result && (int) ($user->two_factor_time_drift ?? 0) !== 0) {
            $result = $google2fa->verifyKey(
                $secret,
                $code,
                $window,
                $google2fa->getTimestamp() + (int) $user->two_factor_time_drift,
            );
        }

        \Log::info('2FA verify result', [
            'user_id' => $user->id,
            'result' => $result,
        ]);

        return $result;
    }

    public function confirmSetupCode(User $user, string $code): bool
    {
        $secret = $this->decryptSecret($user);
        $code = preg_replace('/\D/', '', $code);
        $window = (int) config('fortify-options.two-factor-authentication.window', 4);
        $google2fa = new Google2FA();

        if (! $this->codeCanBeVerified($user, $secret, $code)) {
            return false;
        }

        if ($google2fa->verifyKey($secret, $code, $window)) {
            $this->storeTimeDrift($user, 0);

            return true;
        }

        $drift = $this->detectTimeDrift($google2fa, $secret, $code);

        if ($drift === null) {
            \Log::info('2FA setup verify result', [
                'user_id' => $user->id,
                'result' => false,
                'detected_drift' => null,
            ]);

            return false;
        }

        $this->storeTimeDrift($user, $drift);

        \Log::warning('2FA setup accepted with detected time drift', [
            'user_id' => $user->id,
            'drift_steps' => $drift,
            'drift_seconds' => $drift * 30,
        ]);

        return true;
    }

    public function generateRecoveryCodes(): array
    {
        return collect(range(1, self::RECOVERY_CODE_COUNT))
            ->map(fn () => $this->generateRecoveryCode())
            ->all();
    }

    public function encryptRecoveryCodes(array $codes): string
    {
        return Crypt::encryptString(json_encode(array_values($codes), JSON_THROW_ON_ERROR));
    }

    public function recoveryCodes(User $user): array
    {
        if (blank($user->two_factor_recovery_codes)) {
            return [];
        }

        try {
            $codes = json_decode(
                Crypt::decryptString($user->two_factor_recovery_codes),
                true,
                512,
                JSON_THROW_ON_ERROR,
            );
        } catch (DecryptException|\JsonException) {
            return [];
        }

        return is_array($codes) ? $codes : [];
    }

    public function consumeRecoveryCode(User $user, string $recoveryCode): bool
    {
        $normalizedInput = $this->normalizeRecoveryCode($recoveryCode);
        $codes = $this->recoveryCodes($user);

        foreach ($codes as $index => $storedCode) {
            if (hash_equals($this->normalizeRecoveryCode((string) $storedCode), $normalizedInput)) {
                unset($codes[$index]);

                $user->forceFill([
                    'two_factor_recovery_codes' => $this->encryptRecoveryCodes(array_values($codes)),
                ])->save();

                return true;
            }
        }

        return false;
    }

    public function qrCodeSvg(User $user): string
    {
        $secret = $this->decryptSecret($user);

        if (! $secret) {
            return '';
        }

        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(
                    220,
                    1,
                    null,
                    null,
                    Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(30, 41, 59)),
                ),
                new SvgImageBackEnd(),
            ),
        ))->writeString($this->otpauthUrl($user, $secret));

        $xmlLine = strpos($svg, "\n");

        return trim($xmlLine === false ? $svg : substr($svg, $xmlLine + 1));
    }

    public function otpauthUrl(User $user, string $secret): string
    {
        $issuer = config('app.name', 'MomSpire');
        $label = rawurlencode($issuer).':'.rawurlencode($user->email);

        return sprintf(
            'otpauth://totp/%s?secret=%s&issuer=%s&algorithm=SHA1&digits=6&period=30',
            $label,
            rawurlencode($secret),
            rawurlencode($issuer),
        );
    }

    private function generateRecoveryCode(): string
    {
        return $this->randomRecoveryPart().'-'.$this->randomRecoveryPart();
    }

    private function randomRecoveryPart(): string
    {
        $value = '';
        $max = strlen(self::RECOVERY_CODE_CHARS) - 1;

        for ($i = 0; $i < 4; $i++) {
            $value .= self::RECOVERY_CODE_CHARS[random_int(0, $max)];
        }

        return $value;
    }

    private function normalizeRecoveryCode(string $code): string
    {
        return strtoupper(preg_replace('/[^A-Z0-9]/i', '', $code));
    }

    private function codeCanBeVerified(User $user, ?string $secret, string $code): bool
    {
        if (! $secret) {
            \Log::error('2FA verify failed: secret is null for user ' . $user->id);

            return false;
        }

        if (strlen($code) !== 6) {
            \Log::error('2FA verify failed: code length is ' . strlen($code) . ' for user ' . $user->id);

            return false;
        }

        return true;
    }

    private function detectTimeDrift(Google2FA $google2fa, string $secret, string $code): ?int
    {
        $currentTimestamp = $google2fa->getTimestamp();
        $maxDrift = (int) config('fortify-options.two-factor-authentication.setup_drift_window', 240);

        for ($distance = 1; $distance <= $maxDrift; $distance++) {
            foreach ([$distance, -$distance] as $drift) {
                if (hash_equals($google2fa->oathTotp($secret, $currentTimestamp + $drift), $code)) {
                    return $drift;
                }
            }
        }

        return null;
    }

    private function storeTimeDrift(User $user, int $drift): void
    {
        $user->forceFill([
            'two_factor_time_drift' => $drift,
        ])->save();
    }
}
