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
public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'no_hp' => ['required', 'string', 'max:15'], // Validasi No HP
            'alamat' => ['required', 'string'],          // Validasi Alamat
        ]);

        // 2. Simpan ke Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
            // --- SIMPAN DATA TAMBAHAN ---
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'no_sim' => $request->no_sim ?? null,
            'role' => 'user', // Default jadi user biasa
        ]);

        event(new Registered($user));

        // 3. Redirect ke Login (Bukan Auto Login)
        // Auth::login($user); // Matikan auto login

        return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan login.');
    }
}
