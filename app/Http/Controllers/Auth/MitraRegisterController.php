<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rental; // Pastikan Model Rental Anda sudah ada!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MitraRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.mitra-register');
    }

    public function register(Request $request)
    {
        // 1. Validasi Super Ketat
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'nama_rental' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
        ]);

        // 2. Operasi Database Ganda (Transaksi)
        DB::beginTransaction();
        try {
            // A. Buat Akun User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'password' => Hash::make($request->password),
                // PERHATIAN MUTLAK: Sesuaikan value 'mitra' dengan role di database Anda (misal: 'mitra', 'rental', '2')
                'role' => 'mitra', 
            ]);

            // B. Buat Profil Rental yang terikat ke User
            Rental::create([
                'user_id' => $user->id,
                'nama_rental' => $request->nama_rental,
                'kota' => $request->kota,
                // Alamat lengkap, rekening, dll bisa disuruh isi nanti di Dashboard Mitra
            ]);

            DB::commit(); // Kunci data ke database

            // 3. Login Otomatis & Lempar ke Dashboard
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Registrasi Mitra Berhasil! Selamat datang di ekosistem kami.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }
}