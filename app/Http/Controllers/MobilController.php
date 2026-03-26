<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // WAJIB ADA
use Illuminate\Support\Facades\Storage;

class MobilController extends Controller
{
    /**
     * Menampilkan daftar mobil dengan filter status untuk User.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Logika untuk ADMIN (Melihat semua tanpa filter)
        if ($user->role === 'admin') {
            $mobils = Mobil::with('rental')->latest()->get();
        } 
        
        // 2. Logika untuk VENDOR/MITRA (Melihat armada miliknya sendiri)
        elseif ($user->role === 'vendor') {
            if (!$user->rental) {
                return redirect()->route('dashboard')->with('error', 'Profil Rental Anda belum terdaftar.');
            }
            $mobils = Mobil::where('rental_id', $user->rental->id)->latest()->get();
        }
        
        // 3. Logika untuk USER / PENYEWA
        else {
            // HANYA ambil yang statusnya 'tersedia'
            $mobils = Mobil::with('rental')
                ->where('status', 'tersedia') 
                ->latest()
                ->get();
            
            // Jika katalog user menggunakan view yang berbeda, ganti di sini:
            // return view('user.katalog', compact('mobils'));
        }

        return view('dashboard.mobil.index', compact('mobils'));
    }

    /**
     * PERBAIKAN STORE: Menangani Relasi Rental & Upload
     */
    public function store(\Illuminate\Http\Request $request)
    {
        // 1. VALIDASI KETAT (PAGAR KEAMANAN)
        $validatedData = $request->validate([
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'no_plat' => 'required|string|max:20|unique:mobils',
            'harga_sewa' => 'required|numeric|min:0',
            
            // ATURAN BARU YANG MUTLAK:
            'tipe_mobil' => 'required|in:SUV,MPV,Mini MPV', // Hanya izinkan 3 kata ini
            'transmisi' => 'required|in:matic,manual',      // Hanya izinkan 2 kata ini
            'jumlah_kursi' => 'required|integer|min:2',     // Wajib berupa angka!
            
            'tahun_buat' => 'required|integer|min:2000',
            'bahan_bakar' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. PROSES UPLOAD GAMBAR (Biarkan sesuai kode asli Anda)
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $nama_gambar = time() . "_" . $gambar->getClientOriginalName();
            $gambar->move(public_path('img/mobil'), $nama_gambar);
            $validatedData['gambar'] = $nama_gambar;
        }

        // 3. TAMBAHKAN ID RENTAL & CABANG SECARA OTOMATIS
        $validatedData['rental_id'] = \Illuminate\Support\Facades\Auth::user()->rental->id;
        $validatedData['status'] = 'tersedia'; // Status default
        
        // Cabang ID disesuaikan dengan logika aplikasi Anda (misal dari form atau relasi mitra)
        // $validatedData['branch_id'] = $request->branch_id; 

        // 4. SIMPAN KE DATABASE
        \App\Models\Mobil::create($validatedData);

        return redirect()->route('mitra.mobil.index')->with('success', 'Mobil baru berhasil ditambahkan dengan spesifikasi yang presisi!');
    }

    /**
     * PERBAIKAN UPDATE: Sinkronisasi Storage & Path
     */
    public function update(Request $request, $id)
    {
        $mobil = Mobil::findOrFail($id);

        $request->validate([
            'merk'  => 'required',
            'model' => 'required',
            'no_plat' => 'required|unique:mobils,no_plat,' . $id,
            'harga_sewa' => 'required|numeric',
            'status' => 'required|in:tersedia,tidak tersedia',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($mobil->gambar) {
                Storage::disk('public')->delete($mobil->gambar);
            }
            // Simpan gambar baru menggunakan storage (konsisten dengan store)
            $data['gambar'] = $request->file('gambar')->store('mobils', 'public');
        }

        $mobil->update($data);

        return redirect()->route('mobils.index')->with('success', 'Data mobil berhasil diperbarui!');
    }

    public function destroy(Mobil $mobil)
    {
        if ($mobil->gambar) {
            Storage::disk('public')->delete($mobil->gambar);
        }
        $mobil->delete();
        return redirect()->route('mobils.index')->with('success', 'Data mobil berhasil dihapus');
    }

    public function create()
    {
        return view('admin.mobil.create');
    }

    public function edit($id)
    {
        $mobil = Mobil::findOrFail($id);
        return view('admin.mobil.edit', compact('mobil'));
    }
}