<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed'],
        'nama_rental' => ['required', 'string'], // Nama Cabang (Contoh: FZRENT Jakarta)
        'kota' => ['required', 'string'],        // Kota Cabang
    ]);

    // 1. Simpan ke Tabel Users
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'mitra', // Set otomatis sebagai mitra
    ]);

    // 2. Simpan ke Tabel Rentals (Inilah yang memisahkan akun)
    Rental::create([
        'user_id' => $user->id,
        'nama_rental' => $request->nama_rental,
        'kota' => $request->kota,
        'status' => 'pending', // Menunggu persetujuan Admin
    ]);

    event(new Registered($user));
    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
}
}
