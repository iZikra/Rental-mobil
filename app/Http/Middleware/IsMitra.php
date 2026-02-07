<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Transaksi;
use App\Models\Branch;
use Closure;
use Symfony\Component\HttpFoundation\Response;

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