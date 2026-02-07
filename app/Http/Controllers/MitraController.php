<?php

namespace App\Http\Controllers;

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    // === DASHBOARD UTAMA ===
    public function index()
    {
        $user = Auth::user();
        
        // Cek apakah User sudah punya profil Rental
        if (!$user->rental) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Rental Anda terlebih dahulu!');
        }

        $rental = $user->rental;

        // Statistik untuk Dashboard
        $totalMobil = $rental->mobils()->count();
        $pesananAktif = $rental->transaksis()->whereIn('status', ['pending', 'dibayar', 'dikonfirmasi'])->count();
        $pendapatan = $rental->transaksis()->where('status', 'selesai')->sum('total_harga');

        return view('mitra.dashboard', compact('rental', 'totalMobil', 'pesananAktif', 'pendapatan'));
    }

    // === MANAJEMEN MOBIL (Hanya Mobil Milik Sendiri) ===
    public function indexMobil()
    {
        $rentalId = Auth::user()->rental->id;
        
        // Query: Ambil mobil DIMANA rental_id = rental saya
        $mobils = Mobil::where('rental_id', $rentalId)->with('branch')->latest()->get();

        return view('mitra.mobil.index', compact('mobils'));
    }

    public function createMobil()
    {
        // Kita butuh data cabang untuk dropdown (Pilih Lokasi Mobil)
        $branches = Branch::where('rental_id', Auth::user()->rental->id)->get();
        return view('mitra.mobil.create', compact('branches'));
    }

    public function storeMobil(Request $request)
    {
        $request->validate([
            'merk' => 'required|string',
            'model' => 'required|string',
            'no_plat' => 'required|unique:mobils,no_plat',
            'branch_id' => 'required|exists:branches,id', // Pastikan cabang valid
            'harga_sewa' => 'required|numeric',
            'tahun_buat' => 'required|integer',
            'transmisi' => 'required',
            'bahan_bakar' => 'required',
            'jumlah_kursi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload Gambar
        $imagePath = null;
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('mobil_images', 'public');
        }

        // Simpan ke Database
        Mobil::create([
            'rental_id' => Auth::user()->rental->id, // OTOMATIS (Jangan dari input user)
            'branch_id' => $request->branch_id,
            'merk' => $request->merk,
            'model' => $request->model,
            'no_plat' => $request->no_plat,
            'harga_sewa' => $request->harga_sewa,
            'tahun_buat' => $request->tahun_buat,
            'transmisi' => $request->transmisi,
            'bahan_bakar' => $request->bahan_bakar,
            'jumlah_kursi' => $request->jumlah_kursi,
            'gambar' => $imagePath,
            'status' => 'tersedia',
        ]);

        return redirect()->route('mitra.mobil.index')->with('success', 'Mobil berhasil ditambahkan!');
    }

    // === MANAJEMEN PESANAN ===
    public function indexPesanan()
    {
        $rentalId = Auth::user()->rental->id;
        
        // Ambil transaksi yang masuk ke rental ini saja
        $transaksis = Transaksi::where('rental_id', $rentalId)
                        ->with(['user', 'mobil']) // Load data penyewa & mobil
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('mitra.pesanan.index', compact('transaksis'));
    }

    public function konfirmasiPesanan(Request $request, Transaksi $transaksi)
    {
        // Validasi Keamanan: Pastikan transaksi ini milik rental saya
        if ($transaksi->rental_id !== Auth::user()->rental->id) {
            abort(403, 'Akses Ilegal');
        }

        $transaksi->update(['status' => 'dikonfirmasi']);
        
        return back()->with('success', 'Pesanan dikonfirmasi!');
    }

}