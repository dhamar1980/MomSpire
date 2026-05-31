<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.google.client_id' => 'google-client-id',
            'services.google.client_secret' => 'google-client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
    }

    public function test_google_redirect_sends_user_to_google(): void
    {
        $provider = Mockery::mock();
        $provider
            ->shouldReceive('redirectUrl')
            ->once()
            ->with('http://localhost:8000/auth/google/callback')
            ->andReturnSelf();
        $provider
            ->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect('https://accounts.google.com/oauth'));

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);

        $response = $this->get(route('auth.google.redirect', ['intent' => 'login']));

        $response->assertRedirect('https://accounts.google.com/oauth');
        $this->assertSame('login', session('auth.google.intent'));
    }

    public function test_google_redirect_preserves_127_host_for_oauth_callback(): void
    {
        $provider = Mockery::mock();
        $provider
            ->shouldReceive('redirectUrl')
            ->once()
            ->with('http://127.0.0.1:8000/auth/google/callback')
            ->andReturnSelf();
        $provider
            ->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect('https://accounts.google.com/oauth'));

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);

        $response = $this->get('http://127.0.0.1:8000/auth/google?intent=login');

        $response->assertRedirect('https://accounts.google.com/oauth');
        $this->assertSame('login', session('auth.google.intent'));
    }

    public function test_google_callback_creates_pengguna_account_and_logs_user_in(): void
    {
        $this->mockGoogleUser([
            'id' => 'google-123',
            'name' => 'Google User',
            'email' => 'google@example.com',
        ]);

        $response = $this->get(route('auth.google.callback'));

        $user = User::where('email', 'google@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('pengguna', $user->role);
        $this->assertNotNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('pengguna.dashboard'));
        $this->assertDatabaseHas('pengguna', [
            'id' => $user->id,
            'user_id' => $user->id,
            'email' => 'google@example.com',
        ]);
    }

    public function test_google_callback_logs_existing_user_in_without_changing_role(): void
    {
        $user = User::factory()->create([
            'email' => 'bidan@example.com',
            'role' => 'bidan',
        ]);

        DB::table('bidan')->insert([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'password' => $user->password,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->mockGoogleUser([
            'id' => 'google-456',
            'name' => 'Bidan Google',
            'email' => 'bidan@example.com',
        ]);

        $response = $this->get(route('auth.google.callback'));

        $this->assertAuthenticatedAs($user);
        $this->assertSame('bidan', $user->fresh()->role);
        $this->assertSame(1, User::where('email', 'bidan@example.com')->count());
        $response->assertRedirect(url('/bidan/dashboard'));
    }

    private function mockGoogleUser(array $attributes): void
    {
        $googleUser = (new SocialiteUser())->map([
            'id' => $attributes['id'],
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'avatar' => $attributes['avatar'] ?? null,
        ]);

        $provider = Mockery::mock();
        $provider
            ->shouldReceive('redirectUrl')
            ->once()
            ->with('http://localhost:8000/auth/google/callback')
            ->andReturnSelf();
        $provider
            ->shouldReceive('user')
            ->once()
            ->andReturn($googleUser);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);
    }
}
