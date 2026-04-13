<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_rentals_protected(): void
    {
        $response = $this->get('/admin/rentals');
        $response->assertStatus(302);
    }

    public function test_admin_rental_approve_protected(): void
    {
        $response = $this->patch('/admin/rentals/1/approve');
        $response->assertStatus(302);
    }
}