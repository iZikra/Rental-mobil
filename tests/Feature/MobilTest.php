<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class MobilTest extends TestCase
{
    use RefreshDatabase;

    public function test_mitra_mobil_routes_redirect_to_login(): void
    {
        $response = $this->get('/mitra/mobil');
        $response->assertStatus(302);
    }

    public function test_mitra_mobil_create_redirects_to_login(): void
    {
        $response = $this->get('/mitra/mobil/create');
        $response->assertStatus(302);
    }

    public function test_bot_check_cars_redirects(): void
    {
        $response = $this->get('/bot/check-cars');
        $response->assertStatus(302);
    }

    public function test_user_access_mitra_mobil_protected(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/mitra/mobil');
        
        // 403 Forbidden = Role protection WORKS!
        $response->assertStatus(403);
    }
}