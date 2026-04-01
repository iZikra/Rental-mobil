<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rental; // PENTING: Harus di-import
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (Pastikan no_hp dan alamat wajib diisi)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'no_hp' => ['required', 'string', 'max:20'], // WAJIB ADA
            'alamat' => ['required', 'string'],          // WAJIB ADA
        ]);

        // 2. Simpan ke Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'no_hp' => $request->no_hp,   // KODE MUTLAK: Masukkan data ke kolom
            'alamat' => $request->alamat, // KODE MUTLAK: Masukkan data ke kolom
            'role' => 'customer',         // (Sesuaikan jika Anda punya default role)
        ]);

        event(new \Illuminate\Auth\Events\Registered($user));

        \Illuminate\Support\Facades\Auth::login($user);

        // Arahkan ke dashboard setelah berhasil
        return redirect('/dashboard'); 
    }
}