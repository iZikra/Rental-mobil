<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsMitra
{
    public function handle(Request $request, Closure $next): Response
    {
        // LOGIKA SATPAM:
        // Jika user belum login ATAU role-nya bukan vendor -> TENDANG KELUAR
        if (!Auth::check() || Auth::user()->role !== 'vendor') {
            abort(403, 'AKSES DITOLAK: Halaman ini khusus Mitra Rental.');
        }

        return $next($request);
    }
}