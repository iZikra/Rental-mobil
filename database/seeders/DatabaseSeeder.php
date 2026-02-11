<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rental;
use App\Models\Branch;
use App\Models\Mobil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin Utama
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@fzrent.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun Mitra (Pemilik Rental / Vendor)
        $ownerFz = User::create([
            'name' => 'Owner FZ Rent',
            'email' => 'owner@fzrent.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Teropong No. 123, Sidomulyo Barat, Pekanbaru'
        ]);

        $ownerBerkah = User::create([
            'name' => 'Owner Berkah Rent',
            'email' => 'owner@berkahrent.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'no_hp' => '081266665555',
            'alamat' => 'Jl. Ubur-ubur No. 123, Tangkerang Barat, Pekanbaru'
        ]);

        // 3. Buat Profil Rental
        $rentalFz = Rental::create([
            'user_id' => $ownerFz->id,
            'nama_rental' => 'FZ Rent Car',
            'slug' => 'fz-rent-car',
            'no_telp_bisnis' => '081234567890',
            'alamat' => 'Jl. Teropong No. 123, Sidomulyo Barat, Pekanbaru',
            'status' => 'active'
        ]);

        $rentalBerkah = Rental::create([
            'user_id' => $ownerBerkah->id,
            'nama_rental' => 'Berkah Rent',
            'slug' => 'berkah-rent-car',
            'no_telp_bisnis' => '081266665555',
            'alamat' => 'Jl. Ubur-ubur No. 123, Tangkerang Barat, Pekanbaru',
            'status' => 'active'
        ]);

        // 4. BUAT CABANG (PERMANEN)
        
        // Cabang 1: Pekanbaru (Utama)
        $cabangFzPekanbaru = Branch::create([
            'rental_id' => $rentalFz->id,
            'nama_cabang' => 'Cabang Fz Pekanbaru',
            'kota' => 'Pekanbaru',
            'alamat_lengkap' => 'Jl. Teropong No. 123, Sidomulyo Barat, Pekanbaru',
            'nomor_telepon_cabang' => '081234567890'
        ]);

        // Cabang 2: Jakarta (Tambahan Baru)
        $cabangFzJakarta = Branch::create([
            'rental_id' => $rentalFz->id,
            'nama_cabang' => 'Cabang Fz Jakarta',
            'kota' => 'Jakarta',
            'alamat_lengkap' => 'Jl. Sudirman No. 88, Jakarta Pusat',
            'nomor_telepon_cabang' => '021-98765432'
        ]);

        $cabangBerkahPekanbaru = Branch::create([
            'rental_id' => $rentalBerkah->id,
            'nama_cabang' => 'Cabang Berkah Rent Pekanbaru',
            'kota' => 'Pekanbaru',
            'alamat_lengkap' => 'Jl. Ubur-ubur No. 123, Tangkerang Barat, Pekanbaru',
            'nomor_telepon_cabang' => '081266665555'
        ]);

        // 5. Masukkan Mobil (Dibagi ke 2 Cabang)
        
        // Mobil 1 - Pekanbaru
        Mobil::create([
            'rental_id' => $rentalFz->id,
            'branch_id' => $cabangFzPekanbaru->id, // Masuk Pekanbaru
            'merk' => 'Daihatsu All New Xenia',
            'model' => 'Xenia 1.3 R',
            'no_plat' => 'BM 1234 AA',
            'harga_sewa' => 350000,
            'status' => 'tersedia',
            'tahun_buat' => '2023',
            'transmisi' => 'Matic',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 7,
            'gambar' => 'xenia2023.jpg',
            'deskripsi' => 'Unit terbaru, bersih dan wangi.'
        ]);

        // Mobil 2 - Pekanbaru
        Mobil::create([
            'rental_id' => $rentalFz->id,
            'branch_id' => $cabangFzPekanbaru->id, // Masuk Pekanbaru
            'merk' => 'Daihatsu Xenia',
            'model' => 'Xenia 1.3 X',
            'no_plat' => 'BM 5678 BB',
            'harga_sewa' => 300000,
            'status' => 'tersedia',
            'tahun_buat' => '2022',
            'transmisi' => 'Manual',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 7,
            'gambar' => 'xenia2022.jpg',
            'deskripsi' => 'Irit bensin, cocok untuk keluarga.'
        ]);

        // Mobil 3 - JAKARTA
        Mobil::create([
            'rental_id' => $rentalFz->id,
            'branch_id' => $cabangFzJakarta->id, // <--- Masuk JAKARTA
            'merk' => 'Toyota New Agya',
            'model' => 'Agya GR Sport',
            'no_plat' => 'B 9012 JK', // Plat B (Jakarta)
            'harga_sewa' => 250000,
            'status' => 'tersedia',
            'tahun_buat' => '2023',
            'transmisi' => 'Matic',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 5,
            'gambar' => 'agya.jpg',
            'deskripsi' => 'Lincah dalam kota, hemat bahan bakar.'
        ]);

        // Mobil 4 - JAKARTA
        Mobil::create([
            'rental_id' => $rentalFz->id,
            'branch_id' => $cabangFzJakarta->id, // <--- Masuk JAKARTA
            'merk' => 'Daihatsu Terios',
            'model' => 'Terios IDS',
            'no_plat' => 'B 3456 JK', // Plat B (Jakarta)
            'harga_sewa' => 400000,
            'status' => 'tersedia',
            'tahun_buat' => '2022',
            'transmisi' => 'Manual',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 7,
            'gambar' => 'terios.jpg',
            'deskripsi' => 'SUV Tangguh, cocok untuk perjalanan jauh.'
        ]);

        // Mobil 5 - Pekanbaru (Berkah Rent)
        Mobil::create([
            'rental_id' => $rentalBerkah->id,
            'branch_id' => $cabangBerkahPekanbaru->id, // Masuk Pekanbaru
            'merk' => 'Daihatsu All New Xenia',
            'model' => 'Xenia 1.3 R',
            'no_plat' => 'BM 1666 AB',
            'harga_sewa' => 300000,
            'status' => 'tersedia',
            'tahun_buat' => '2020',
            'transmisi' => 'Matic',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 7,
            'gambar' => 'xenia2020.jpg',
            'deskripsi' => 'Unit terbaru, bersih dan wangi.'
        ]);
        
        // Mobil 6 - Pekanbaru (Berkah Rent)
        Mobil::create([
            'rental_id' => $rentalBerkah->id,
            'branch_id' => $cabangBerkahPekanbaru->id, // Masuk Pekanbaru
            'merk' => 'Toyota Alphard',
            'model' => 'Alphard 2.5 G',
            'no_plat' => 'BM 9898 GG',
            'harga_sewa' => 600000,
            'status' => 'tersedia',
            'tahun_buat' => '2019',
            'transmisi' => 'Matic',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 6,
            'gambar' => 'alphard2019.jpg',
            'deskripsi' => 'Unit terawat, ban baru, bersih dan wangi.'
        ]);

        // Mobil 7 - Pekanbaru (Berkah Rent)
        Mobil::create([
            'rental_id' => $rentalBerkah->id,
            'branch_id' => $cabangBerkahPekanbaru->id, // Masuk Pekanbaru
            'merk' => 'Toyota Fortuner',
            'model' => 'Fortuner 2.8 Vrz',
            'no_plat' => 'BM 6588 OP',
            'harga_sewa' => 500000,
            'status' => 'tersedia',
            'tahun_buat' => '2023',
            'transmisi' => 'Matic',
            'bahan_bakar' => 'Diesel',
            'jumlah_kursi' => 7,
            'gambar' => 'fortuner2023.jpg',
            'deskripsi' => 'Unit terawat, ban baru, bersih dan wangi.'
        ]);

        // 6. Buat Akun Customer
        User::create([
            'name' => 'Pelanggan Setia',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}