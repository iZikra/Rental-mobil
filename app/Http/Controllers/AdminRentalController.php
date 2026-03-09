<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;

class AdminRentalController extends Controller
{
    /**
     * Menampilkan daftar semua mitra (tenant) untuk Super Admin.
     */
    public function index()
    {
        // Mengambil semua rental beserta data user pemiliknya
        $rentals = Rental::with('user')->latest()->get();
        return view('admin.rentals.index', compact('rentals'));
    }

    /**
     * Menyetujui pendaftaran mitra baru agar bisa mulai berjualan.
     */
    public function approve($id)
    {
        $rental = Rental::findOrFail($id);
        
        // Ubah status rental menjadi active
        $rental->update(['status' => 'active']);

        return back()->with('success', "Rental {$rental->nama_rental} sekarang sudah aktif!");
    }

    /**
     * Memblokir atau menonaktifkan mitra.
     */
    public function block($id)
    {
        $rental = Rental::findOrFail($id);
        
        // Ubah status rental menjadi inactive/blocked
        $rental->update(['status' => 'inactive']);

        return back()->with('warning', "Rental {$rental->nama_rental} telah dinonaktifkan!");
    }

    /**
     * Menghapus mitra dari sistem (Gunakan dengan hati-hati).
     */
    public function destroy($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->delete();

        return back()->with('danger', "Data mitra telah dihapus permanen dari sistem.");
    }
}