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
        // 1. Ambil data mobil berdasarkan ID jika ada (dari tombol "Sewa" di dashboard)
        $selectedMobil = null;
        if ($request->has('mobil_id')) {
            // Cek apakah mobil tersebut statusnya TERSEDIA
            $cekMobil = Mobil::where('id', $request->mobil_id)
                             ->where('status', 'tersedia') // Pastikan huruf kecil sesuai migration
                             ->first();
            
            if ($cekMobil) {
                $selectedMobil = $cekMobil;
            } else {
                $selectedMobil = null; 
            }
        }
        $semuaMobil = Mobil::where('status', 'tersedia')->get();

        // 3. Kirim data ke View 'pages.order'
        return view('pages.order', compact('selectedMobil', 'semuaMobil'));
    }
}