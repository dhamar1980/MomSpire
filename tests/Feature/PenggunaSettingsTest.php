<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenggunaSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_settings_page_renders_with_pending_two_factor(): void
    {
        $user = User::factory()->create([
            'role' => 'pengguna',
        ]);

        $user->forceFill([
            'two_factor_secret' => 'pending-secret',
            'two_factor_confirmed_at' => null,
        ])->save();

        Pengguna::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ]);

        $response = $this->actingAs($user)->get('/pengguna/pengaturan');

        $response->assertOk();
        $response->assertSee('2FA sedang menunggu konfirmasi.');
    }
}
