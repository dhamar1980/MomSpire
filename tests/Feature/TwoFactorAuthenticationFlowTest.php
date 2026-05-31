<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\TwoFactorAuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorAuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_redirects_to_two_factor_challenge_when_enabled(): void
    {
        $user = User::factory()->create();
        $this->enableTwoFactorFor($user, ['A1B2-C3D4']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('two-factor-challenge', absolute: false));
        $response->assertSessionHas('login.id', $user->id);
    }

    public function test_recovery_code_logs_user_in_and_is_deleted(): void
    {
        $user = User::factory()->create();
        $this->enableTwoFactorFor($user, ['A1B2-C3D4']);

        $response = $this
            ->withSession(['login.id' => $user->id])
            ->post('/two-factor-challenge', [
                'recovery_code' => 'A1B2-C3D4',
            ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect();
        $this->assertSame([], app(TwoFactorAuthenticationService::class)->recoveryCodes($user->fresh()));
    }

    public function test_pending_two_factor_setup_can_be_confirmed_with_current_totp_code(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('role-password'),
        ]);

        $enableResponse = $this
            ->actingAs($user)
            ->postJson(route('settings.security.two-factor.enable'), [
                'password' => 'role-password',
            ]);

        $enableResponse->assertOk();

        $secret = app(TwoFactorAuthenticationService::class)->decryptSecret($user->fresh());
        $code = (new Google2FA())->getCurrentOtp($secret);

        $confirmResponse = $this->postJson(route('settings.security.two-factor.confirm'), [
            'code' => $code,
        ]);

        $confirmResponse
            ->assertOk()
            ->assertJson([
                'enabled' => true,
                'pending' => false,
            ]);

        $this->assertNotNull($user->fresh()->two_factor_confirmed_at);
    }

    public function test_pending_two_factor_setup_can_detect_and_store_time_drift(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('role-password'),
        ]);

        $enableResponse = $this
            ->actingAs($user)
            ->postJson(route('settings.security.two-factor.enable'), [
                'password' => 'role-password',
            ]);

        $enableResponse->assertOk();

        $secret = app(TwoFactorAuthenticationService::class)->decryptSecret($user->fresh());
        $google2fa = new Google2FA();
        $drift = 120;
        $code = $google2fa->oathTotp($secret, $google2fa->getTimestamp() + $drift);

        $confirmResponse = $this->postJson(route('settings.security.two-factor.confirm'), [
            'code' => $code,
        ]);

        $confirmResponse
            ->assertOk()
            ->assertJson([
                'enabled' => true,
                'pending' => false,
            ]);

        $user->refresh();

        $this->assertNotNull($user->two_factor_confirmed_at);
        $this->assertSame($drift, $user->two_factor_time_drift);
    }

    public function test_two_factor_challenge_accepts_stored_time_drift(): void
    {
        $user = User::factory()->create();
        $secret = $this->enableTwoFactorFor($user, ['A1B2-C3D4']);
        $drift = 120;

        $user->forceFill([
            'two_factor_time_drift' => $drift,
        ])->save();

        $google2fa = new Google2FA();
        $code = $google2fa->oathTotp($secret, $google2fa->getTimestamp() + $drift);

        $response = $this
            ->withSession(['login.id' => $user->id])
            ->post('/two-factor-challenge', [
                'code' => $code,
            ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(url('/pengguna/dashboard'));
    }

    public function test_two_factor_challenge_still_accepts_current_code_when_time_drift_exists(): void
    {
        $user = User::factory()->create();
        $secret = $this->enableTwoFactorFor($user, ['A1B2-C3D4']);

        $user->forceFill([
            'two_factor_time_drift' => 120,
        ])->save();

        $code = (new Google2FA())->getCurrentOtp($secret);

        $response = $this
            ->withSession(['login.id' => $user->id])
            ->post('/two-factor-challenge', [
                'code' => $code,
            ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(url('/pengguna/dashboard'));
    }

    #[DataProvider('roleDashboardProvider')]
    public function test_password_login_redirects_roles_directly_to_their_dashboard(string $role, string $dashboardPath): void
    {
        $user = User::factory()->create([
            'role' => $role,
            'password' => Hash::make('role-password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'role-password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(url($dashboardPath));
    }

    #[DataProvider('roleDashboardProvider')]
    public function test_password_login_ignores_stale_login_intended_url(string $role, string $dashboardPath): void
    {
        $user = User::factory()->create([
            'role' => $role,
            'password' => Hash::make('role-password'),
        ]);

        $response = $this
            ->withSession(['url.intended' => url('/login')])
            ->post('/login', [
                'email' => $user->email,
                'password' => 'role-password',
            ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(url($dashboardPath));
    }

    #[DataProvider('roleProvider')]
    public function test_roles_can_enable_two_factor_from_security_endpoint(string $role): void
    {
        $user = User::factory()->create([
            'role' => $role,
            'password' => Hash::make('role-password'),
        ]);

        $response = $this
            ->actingAs($user)
            ->postJson(route('settings.security.two-factor.enable'), [
                'password' => 'role-password',
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'enabled' => false,
                'pending' => true,
            ])
            ->assertJsonStructure([
                'message',
                'svg',
                'secretKey',
                'recoveryCodes',
            ]);

        $user->refresh();

        $this->assertNotNull($user->two_factor_secret);
        $this->assertNull($user->two_factor_confirmed_at);
        $this->assertCount(10, app(TwoFactorAuthenticationService::class)->recoveryCodes($user));
    }

    #[DataProvider('roleDashboardProvider')]
    public function test_two_factor_challenge_redirects_roles_to_their_dashboard(string $role, string $dashboardPath): void
    {
        $user = User::factory()->create(['role' => $role]);
        $this->enableTwoFactorFor($user, ['A1B2-C3D4']);

        $response = $this
            ->withSession(['login.id' => $user->id])
            ->post('/two-factor-challenge', [
                'recovery_code' => 'A1B2-C3D4',
            ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(url($dashboardPath));
    }

    #[DataProvider('roleDashboardProvider')]
    public function test_two_factor_challenge_ignores_stale_login_intended_url(string $role, string $dashboardPath): void
    {
        $user = User::factory()->create(['role' => $role]);
        $this->enableTwoFactorFor($user, ['A1B2-C3D4']);

        $response = $this
            ->withSession([
                'login.id' => $user->id,
                'url.intended' => url('/login'),
            ])
            ->post('/two-factor-challenge', [
                'recovery_code' => 'A1B2-C3D4',
            ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(url($dashboardPath));
    }

    public static function roleProvider(): array
    {
        return [
            'bidan' => ['bidan'],
            'dokter' => ['dokter'],
            'pengguna' => ['pengguna'],
        ];
    }

    public static function roleDashboardProvider(): array
    {
        return [
            'admin' => ['admin', '/admin/dashboard'],
            'bidan' => ['bidan', '/bidan/dashboard'],
            'dokter' => ['dokter', '/dokter/dashboard'],
            'pengguna' => ['pengguna', '/pengguna/dashboard'],
        ];
    }

    private function enableTwoFactorFor(User $user, array $recoveryCodes): string
    {
        $twoFactor = app(TwoFactorAuthenticationService::class);
        $secret = $twoFactor->generateSecret();

        $user->forceFill([
            'two_factor_secret' => $twoFactor->encryptSecret($secret),
            'two_factor_recovery_codes' => $twoFactor->encryptRecoveryCodes($recoveryCodes),
            'two_factor_confirmed_at' => now(),
        ])->save();

        return $secret;
    }
}
