<?php

namespace App\Http\Controllers;
use App\Models\Mobil;
use App\Models\Rental;
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
        $daftarKota = \App\Models\Branch::select('kota')->distinct()->pluck('kota');

        $rentals = Rental::query()
            ->where('status', 'active')
            ->withCount(['mobils as mobil_tersedia_count' => fn($q) => $q->where('status', 'tersedia')])
            ->orderBy('nama_rental')
            ->get();

        $query = \App\Models\Mobil::with(['rental', 'branch'])
            ->where('status', 'tersedia')
            ->whereHas('rental', fn($q) => $q->where('status', 'active'));

        if ($request->filled('rental')) {
            $query->whereHas('rental', fn($q) => $q->where('slug', $request->rental));
        }

        // 1. Filter Lokasi (Kota)
        if ($request->filled('kota')) {
            $query->whereHas('branch', function($q) use ($request) {
                $q->where('kota', $request->kota);
            });
        }

        // 2. Filter Tipe Mobil (SEKARANG SUDAH AKTIF)
        if ($request->filled('tipe_mobil')) {
            $query->where('tipe_mobil', $request->tipe_mobil);
        }

        // 3. Filter Transmisi (Menggunakan LIKE agar lebih kebal typo)
        if ($request->filled('transmisi')) {
            $query->where('transmisi', 'LIKE', '%' . $request->transmisi . '%');
        }

        // 4. Filter Kapasitas
        if ($request->filled('jumlah_kursi')) {
            if ($request->jumlah_kursi == '4') {
                // Hanya cari yang pas 4 kursi
                $query->where('jumlah_kursi', 4);
            } elseif ($request->jumlah_kursi == '5-6') {
                // Cari yang kursinya di antara 5 sampai 6
                $query->whereBetween('jumlah_kursi', [5, 6]);
            } elseif ($request->jumlah_kursi == '>6') {
                // Cari yang kursinya lebih dari 6 (7, 8, dst)
                $query->where('jumlah_kursi', '>', 6);
            }
        }
        $mobils = $query->get();
        
        return view('pages.order', compact('mobils', 'daftarKota', 'rentals'));
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
