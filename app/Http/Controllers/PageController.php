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
        // 1. Cek apakah ada kiriman ID mobil dari Chatbot/Link
        $selectedMobil = null;
        if ($request->has('mobil_id')) {
            $selectedMobil = Mobil::find($request->mobil_id);
        }

        // 2. Ambil SEMUA data mobil untuk isi Dropdown (PENTING!)
        // Kita ambil semua mobil yang statusnya TIDAK sedang disewa
        $semuaMobil = Mobil::where('status', '!=', 'Sewa')->get();

        // 3. Kirim data ke View 'pages.order'
        return view('pages.order', compact('selectedMobil', 'semuaMobil'));
    }

}