<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsMitra
{
    public function handle(Request $request, Closure $next)
    {
        // Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Jika role mitra boleh lanjut
        if (Auth::user()->role === 'mitra') {
            return $next($request);
        }

        // Jika bukan mitra → hentikan akses
        abort(403, 'Akses hanya untuk mitra.');
    }
}