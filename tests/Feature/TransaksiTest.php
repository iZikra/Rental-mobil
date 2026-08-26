<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Mobil;
use App\Models\Branch;
use App\Models\Rental;
use App\Models\Transaksi;
use Carbon\Carbon;

class TransaksiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public'); // Cegah file gambar tes masuk ke storage asli
    }

    private function createDependencies()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $mitra = User::factory()->create(['role' => 'mitra']);
        
        $rental = Rental::create([
            'user_id' => $mitra->id,
            'nama_rental' => 'Rental Test',
            'slug' => 'rental-test',
            'no_telp_bisnis' => '08123456789',
            'status' => 'active'
        ]);

        $branch = Branch::create([
            'rental_id' => $rental->id,
            'nama_cabang' => 'Cabang Utama',
            'alamat_lengkap' => 'Jl. Test No 1',
            'kota' => 'Jakarta',
            'nomor_telepon_cabang' => '08123456789'
        ]);

        $mobil = Mobil::create([
            'rental_id' => $rental->id,
            'branch_id' => $branch->id,
            'merk' => 'Toyota',
            'model' => 'Avanza',
            'no_plat' => 'B 1234 CD',
            'tipe_mobil' => 'MPV',
            'tahun_buat' => 2020,
            'transmisi' => 'Automatic',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 7,
            'harga_sewa' => 300000,
            'status' => 'tersedia'
        ]);

        return [$user, $mobil];
    }

    /**
     * Skenario 1: Test memesan dengan tanggal masa lalu
     */
    public function test_user_cannot_book_with_past_date()
    {
        [$user, $mobil] = $this->createDependencies();

        $response = $this->actingAs($user)->post('/order/simpan', [
            'mobil_id' => $mobil->id,
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka',
            'tgl_ambil' => Carbon::yesterday()->format('Y-m-d'), // MASA LALU
            'jam_ambil' => '10:00',
            'tgl_kembali' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_kembali' => '10:00',
            'lokasi_ambil' => 'kantor',
            'lokasi_kembali' => 'kantor',
            'sopir' => 'tanpa_sopir',
            'tujuan' => 'Liburan',
            'setuju_sk' => 'on',
            'foto_identitas' => UploadedFile::fake()->create('ktp.jpg', 100, 'image/jpeg'),
            'foto_sim' => UploadedFile::fake()->create('sim.jpg', 100, 'image/jpeg'),
        ]);

        // Harus error validasi dan transaksi tidak boleh tersimpan di Database
        $response->assertSessionHasErrors('tgl_ambil');
        $this->assertDatabaseMissing('transaksis', ['mobil_id' => $mobil->id]);
    }

    /**
     * Skenario 2: Test tanggal kembali mendahului tanggal ambil
     */
    public function test_user_cannot_book_with_return_date_before_take_date()
    {
        [$user, $mobil] = $this->createDependencies();

        $response = $this->actingAs($user)->post('/order/simpan', [
            'mobil_id' => $mobil->id,
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka',
            'tgl_ambil' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_ambil' => '10:00',
            'tgl_kembali' => Carbon::today()->format('Y-m-d'), // LEBIH DULU DARI TGL AMBIL
            'jam_kembali' => '10:00',
            'lokasi_ambil' => 'kantor',
            'lokasi_kembali' => 'kantor',
            'sopir' => 'tanpa_sopir',
            'tujuan' => 'Liburan',
            'setuju_sk' => 'on',
            'foto_identitas' => UploadedFile::fake()->create('ktp.jpg', 100, 'image/jpeg'),
            'foto_sim' => UploadedFile::fake()->create('sim.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('tgl_kembali');
        $this->assertDatabaseMissing('transaksis', ['mobil_id' => $mobil->id]);
    }

    /**
     * Skenario 3: Test user mencoba membatalkan sewa yang sudah dibayar ('Disewa')
     */
    public function test_user_cannot_cancel_paid_booking()
    {
        [$user, $mobil] = $this->createDependencies();

        // Manipulasi database (seolah-olah user sudah membayar)
        $transaksi = Transaksi::create([
            'user_id' => $user->id,
            'mobil_id' => $mobil->id,
            'rental_id' => $mobil->rental_id,
            'branch_id' => $mobil->branch_id,
            'nama' => $user->name,
            'no_hp' => '081234567890',
            'alamat' => 'Test',
            'foto_identitas' => 'test.jpg',
            'foto_sim' => 'test.jpg',
            'tgl_ambil' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_ambil' => '10:00',
            'tgl_kembali' => Carbon::tomorrow()->addDays(2)->format('Y-m-d'),
            'jam_kembali' => '10:00',
            'lokasi_ambil' => 'kantor',
            'lokasi_kembali' => 'kantor',
            'sopir' => 'tanpa_sopir',
            'tujuan' => 'Test',
            'lama_sewa' => 2,
            'total_harga' => 600000,
            'status' => 'Disewa', // STATUS SUDAH DIBAYAR
        ]);

        // Kirim request PUT untuk membatalkan
        $response = $this->actingAs($user)->put("/order/{$transaksi->id}/batal");

        // Sistem harus menolak dengan session 'error'
        $response->assertSessionHas('error');
        
        // Status di database harus tetap 'Disewa' (Tidak berubah menjadi 'Dibatalkan')
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'status' => 'Disewa'
        ]);
    }
}
