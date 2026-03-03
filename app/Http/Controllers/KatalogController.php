<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Branch;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil nama-nama kota untuk dropdown
        $daftarKota = Branch::select('kota')->distinct()->pluck('kota');

        // 2. Query dasar (hanya mobil yang tersedia)
        $query = Mobil::with(['rental', 'branch'])->where('status', 'tersedia');

        // 3. Filter Kota Mutlak (Jika User memilih di dropdown)
        if ($request->filled('kota')) {
            $kotaPilihan = $request->kota;
            $query->whereHas('branch', function($q) use ($kotaPilihan) {
                $q->where('kota', $kotaPilihan);
            });
        }

        $mobils = $query->get();

        // 4. Lempar data ke file Blade
        return view('katalog.index', compact('mobils', 'daftarKota'));
    }
}