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

        // 2. Buat Akun Mitra (Pemilik FZ Rent Car)
        $ownerUser = User::create([
            'name' => 'Owner FZ Rent',
            'email' => 'owner@fzrent.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
        ]);

        // 3. Buat Profil Rental (Tanpa alamat, karena alamat milik cabang)
        $rental = Rental::create([
            'user_id' => $ownerUser->id,
            'nama_rental' => 'FZ Rent Car',
            'slug' => 'fz-rent-car',
            'no_telp_bisnis' => '081234567890',
            'status' => 'active'
        ]);

        // 4. Buat Cabang
        $cabangTeropong = Branch::create([
            'rental_id' => $rental->id,
            'nama_cabang' => 'Cabang Utama Teropong',
            'kota' => 'Pekanbaru',
            'alamat_lengkap' => 'Jl. Teropong No. 123, Sidomulyo Barat',
            'nomor_telepon_cabang' => '081234567890'
        ]);

        // 5. Masukkan 4 Mobil (Gunakan 'no_plat')
        
        // Mobil 1
        Mobil::create([
            'rental_id' => $rental->id,
            'branch_id' => $cabangTeropong->id,
            'merk' => 'Daihatsu All New Xenia',
            'model' => 'Xenia 1.3 R',
            'no_plat' => 'BM 1234 AA', // <--- SUDAH DIPERBAIKI
            'harga_sewa' => 350000,
            'status' => 'tersedia',
            'tahun_buat' => '2023',
            'transmisi' => 'Matic',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 7,
            'gambar' => 'xenia2023.jpg',
            'deskripsi' => 'Unit terbaru, bersih dan wangi.'
        ]);

        // Mobil 2
        Mobil::create([
            'rental_id' => $rental->id,
            'branch_id' => $cabangTeropong->id,
            'merk' => 'Daihatsu Xenia',
            'model' => 'Xenia 1.3 X',
            'no_plat' => 'BM 5678 BB', // <--- SUDAH DIPERBAIKI
            'harga_sewa' => 300000,
            'status' => 'tersedia',
            'tahun_buat' => '2022',
            'transmisi' => 'Manual',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 7,
            'gambar' => 'xenia2022.jpg',
            'deskripsi' => 'Irit bensin, cocok untuk keluarga.'
        ]);

        // Mobil 3
        Mobil::create([
            'rental_id' => $rental->id,
            'branch_id' => $cabangTeropong->id,
            'merk' => 'Toyota New Agya',
            'model' => 'Agya GR Sport',
            'no_plat' => 'BM 9012 CC', // <--- SUDAH DIPERBAIKI
            'harga_sewa' => 250000,
            'status' => 'tersedia',
            'tahun_buat' => '2023',
            'transmisi' => 'Matic',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 5,
            'gambar' => 'agya.jpg',
            'deskripsi' => 'Lincah dalam kota, hemat bahan bakar.'
        ]);

        // Mobil 4
        Mobil::create([
            'rental_id' => $rental->id,
            'branch_id' => $cabangTeropong->id,
            'merk' => 'Daihatsu Terios',
            'model' => 'Terios IDS',
            'no_plat' => 'BM 3456 DD', // <--- SUDAH DIPERBAIKI
            'harga_sewa' => 400000,
            'status' => 'tersedia',
            'tahun_buat' => '2022',
            'transmisi' => 'Manual',
            'bahan_bakar' => 'Bensin',
            'jumlah_kursi' => 7,
            'gambar' => 'terios.jpg',
            'deskripsi' => 'SUV Tangguh, cocok untuk perjalanan jauh.'
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