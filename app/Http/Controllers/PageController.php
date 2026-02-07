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

    public function order(Request $request)
{
    $selectedMobil = null;
    if ($request->has('mobil_id')) {
        // Cek apakah mobil tersebut statusnya TERSEDIA
        $cekMobil = Mobil::where('id', $request->mobil_id)
                         ->where('status', 'tersedia') 
                         ->first();
        
        if ($cekMobil) {
            $selectedMobil = $cekMobil;
        } else {
            // Jika user mencoba akses mobil yang 'disewa' via URL, paksa jadi null
            $selectedMobil = null; 
        }
    }

    // Hanya ambil mobil yang statusnya 'tersedia' untuk dropdown/pilihan
    $semuaMobil = Mobil::where('status', 'tersedia')->get();

    return view('pages.order', compact('selectedMobil', 'semuaMobil'));
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