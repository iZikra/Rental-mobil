<?php

namespace Tests\Feature\Auth;

use App\Models\Branch;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MitraRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_mitra_can_register_and_all_fields_are_saved(): void
    {
        $payload = [
            'name' => 'Mitra Owner',
            'email' => 'mitra@example.com',
            'no_hp' => '081234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nama_rental' => 'Berkah Jaya Rent',
            'nama_cabang' => 'Cabang Pekanbaru Kota',
            'kota' => 'Pekanbaru',
            'alamat_lengkap' => 'Jl. Contoh No. 1, Pekanbaru',
            'nomor_telepon_cabang' => '081234567890',
        ];

        $response = $this->post('/mitra/register', $payload);

        $this->assertAuthenticated();
        $response->assertRedirect(route('mitra.dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
            'name' => $payload['name'],
            'no_hp' => $payload['no_hp'],
            'role' => 'mitra',
        ]);

        $user = User::where('email', $payload['email'])->firstOrFail();
        $rental = Rental::where('user_id', $user->id)->firstOrFail();

        $this->assertNotNull($user->rental_id);
        $this->assertSame($rental->id, $user->rental_id);

        $this->assertDatabaseHas('rentals', [
            'id' => $rental->id,
            'user_id' => $user->id,
            'nama_rental' => $payload['nama_rental'],
            'alamat' => $payload['alamat_lengkap'],
            'no_telp_bisnis' => $payload['nomor_telepon_cabang'],
            'status' => 'inactive',
        ]);

        $branch = Branch::where('rental_id', $rental->id)->firstOrFail();
        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'rental_id' => $rental->id,
            'nama_cabang' => $payload['nama_cabang'],
            'kota' => $payload['kota'],
            'alamat_lengkap' => $payload['alamat_lengkap'],
            'nomor_telepon_cabang' => $payload['nomor_telepon_cabang'],
        ]);
    }
}
