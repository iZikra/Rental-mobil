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
    public function store(Request $request)
    {
        $data = $request->validate([
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'no_plat' => 'required|string|max:20|unique:mobils,no_plat',
            'harga_sewa' => 'required|numeric',
            'status' => 'required|in:tersedia,tidak tersedia',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // Otomatis pasang rental_id berdasarkan siapa yang login
        $data['rental_id'] = Auth::user()->rental->id;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('mobils', 'public');
        }

        Mobil::create($data);

        return redirect()->route('mobils.index')->with('success', 'Mobil berhasil ditambahkan!');
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