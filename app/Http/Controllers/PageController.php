<?php

namespace App\Http\Controllers;
use App\Models\Mobil;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about() {
        return view('pages.about');
    }

    public function contact() {
        return view('pages.contact');
    }

    public function order(\Illuminate\Http\Request $request)
    {
        // 1. Ambil daftar kota untuk Dropdown
        $daftarKota = \App\Models\Branch::select('kota')->distinct()->pluck('kota');

        // 2. Siapkan query
        $query = \App\Models\Mobil::with(['rental', 'branch'])->where('status', 'tersedia');

        // 3. Terapkan Filter
        if ($request->filled('kota')) {
            $query->whereHas('branch', function($q) use ($request) {
                $q->where('kota', $request->kota);
            });
        }

        $mobils = $query->get();
        
        // Kirim $daftarKota dan $mobils ke view form order Anda
        return view('pages.order', compact('mobils', 'daftarKota'));
    }
// Contoh di PageController atau DashboardController
public function dashboard()
{
    // TEGAS: Hanya ambil mobil yang statusnya 'tersedia'
    $mobils = Mobil::where('status', 'tersedia')->latest()->get();

    return view('dashboard', compact('mobils'));
}
public function index()
{
    // Cek apakah Anda menggunakan where status tersedia
    // Pastikan huruf kecil/besarnya sama dengan yang Anda update di Controller
    $mobils = Mobil::where('status', 'tersedia')->latest()->get();

    return view('dashboard', compact('mobils'));
}
}