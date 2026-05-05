<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use App\Models\Rental; // Pastikan Model Rental Anda sudah ada!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MitraRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.mitra-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'nama_rental' => 'required|string|max:255',
            'nama_cabang' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string|max:2000',
            'nomor_telepon_cabang' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'password' => Hash::make($request->password),
                'role' => 'mitra',
            ]);

            $baseSlug = Str::slug($request->nama_rental);
            $slug = $baseSlug ?: ('rental-' . Str::random(6));
            $suffix = 1;
            while (Rental::where('slug', $slug)->exists()) {
                $suffix++;
                $slug = $baseSlug ? "{$baseSlug}-{$suffix}" : ('rental-' . Str::random(6));
            }

            $rental = new Rental();
            $rental->user_id = $user->id;
            $rental->nama_rental = $request->nama_rental;
            $rental->slug = $slug;
            $rental->alamat = $request->alamat_lengkap;
            $rental->no_telp_bisnis = $request->nomor_telepon_cabang;
            $rental->status = 'inactive';
            $rental->save();

            Branch::create([
                'rental_id' => $rental->id,
                'nama_cabang' => $request->nama_cabang,
                'kota' => $request->kota,
                'alamat_lengkap' => $request->alamat_lengkap,
                'nomor_telepon_cabang' => $request->nomor_telepon_cabang,
            ]);

            $user->rental_id = $rental->id;
            $user->save();

            DB::commit();

            Auth::login($user);
            return redirect()->route('mitra.dashboard')->with('success', 'Registrasi mitra berhasil. Lengkapi pengaturan rental untuk mulai menerima pesanan.');

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Terjadi gangguan sistem. Coba lagi sebentar ya.')->withInput();
        }
    }
}
