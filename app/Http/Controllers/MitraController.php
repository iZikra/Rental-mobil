<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Mobil;
use App\Models\Branch;
use App\Models\Transaksi;
use App\Models\Rental;

class MitraController extends Controller
{

    /**
     * DASHBOARD MITRA
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Jika user adalah cabang
        if ($user->branch_id) {
            $branch = Branch::find($user->branch_id);

            if (!$branch) {
                abort(403, 'Cabang tidak ditemukan');
            }

            $rental = $branch->rental;
        } 
        // Jika user owner rental
        else {
            $rental = $user->rental;
        }

        if (!$rental) {
            abort(403, 'Data rental tidak ditemukan');
        }

        $user = Auth::user();

if ($user->branch_id) {
    // Jika user cabang
    $totalMobil = Mobil::where('branch_id', $user->branch_id)->count();
} else {
    // Jika owner rental
    $totalMobil = Mobil::where('rental_id', $rental->id)->count();
}

        $pesananAktif = Transaksi::where('rental_id', $rental->id)
            ->whereIn('status', ['pending', 'disetujui'])
            ->count();

        $pendapatan = Transaksi::where('rental_id', $rental->id)
            ->where('status', 'Selesai')
            ->sum('total_harga');

        $pesananTerbaru = Transaksi::where('rental_id', $rental->id)
            ->with(['user', 'mobil'])
            ->latest()
            ->take(5)
            ->get();

        return view('mitra.dashboard', compact(
            'rental',
            'totalMobil',
            'pesananAktif',
            'pendapatan',
            'pesananTerbaru'
        ));
    }

    /**
     * KONFIRMASI PESANAN
     */
    public function konfirmasiPesanan($id)
    {
        try {

            DB::transaction(function () use ($id) {

                $transaksi = DB::table('transaksis')
                    ->where('id', $id)
                    ->first();

                if (!$transaksi) {
                    throw new \Exception("Pesanan tidak ditemukan.");
                }

                DB::table('transaksis')
                    ->where('id', $id)
                    ->update([
                        'status' => 'disetujui'
                    ]);

                if ($transaksi->mobil_id) {

                    DB::table('mobils')
                        ->where('id', $transaksi->mobil_id)
                        ->update([
                            'status' => 'tidak tersedia'
                        ]);
                }

            });

            return back()->with('success', 'Pesanan berhasil dikonfirmasi.');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());

        }
    }


    /**
     * TOLAK PESANAN
     */
    public function tolakPesanan($id)
    {
        $rental = Auth::user()->rental;

        $transaksi = Transaksi::where('rental_id', $rental->id)
            ->findOrFail($id);

        $transaksi->update([
            'status' => 'pending',
            'bukti_bayar' => null
        ]);

        return back()->with('error', 'Pembayaran ditolak. User diminta upload ulang.');
    }


    /**
     * SELESAIKAN PESANAN
     */
    public function selesaikanPesanan($id)
    {
        $rental = Auth::user()->rental;

        $transaksi = Transaksi::where('rental_id', $rental->id)
            ->findOrFail($id);

        DB::transaction(function () use ($transaksi) {

            $transaksi->update([
                'status' => 'Selesai'
            ]);

            if ($transaksi->mobil) {

                $transaksi->mobil->update([
                    'status' => 'tersedia'
                ]);

            }

        });

        return back()->with('success', 'Transaksi selesai, mobil kembali tersedia.');
    }


    /**
     * LIST ARMADA
     */
    public function indexArmada()
{
    $user = Auth::user();

    if ($user->branch_id) {

        // cabang hanya melihat mobil cabangnya
        $mobils = Mobil::where('branch_id', $user->branch_id)
            ->with('branch')
            ->latest()
            ->get();

    } else {

        // owner melihat semua mobil rental
        $rental = $user->rental;

        $mobils = Mobil::where('rental_id', $rental->id)
            ->with('branch')
            ->latest()
            ->get();
    }

    return view('mitra.mobil.index', compact('mobils'));
}

    /**
     * FORM TAMBAH MOBIL
     */
    public function createArmada()
{
    $user = Auth::user();

    if ($user->branch_id) {

        // cabang hanya bisa pilih branch sendiri
        $branches = Branch::where('id', $user->branch_id)->get();

    } else {

        $rental = $user->rental;

        $branches = Branch::where('rental_id', $rental->id)->get();
    }

    return view('mitra.mobil.create', compact('branches'));
}
    /**
     * SIMPAN MOBIL
     */
    public function storeArmada(Request $request)
{
    $request->validate([
        'merk' => 'required|string',
        'model' => 'required|string',
        'no_plat' => 'required|unique:mobils,no_plat',
        'branch_id' => 'required|exists:branches,id',
        'harga_sewa' => 'required|numeric',
        'tahun_buat' => 'required|integer',
        'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user = Auth::user();

    // Jika user cabang
    if ($user->branch_id) {
        $branch = Branch::findOrFail($user->branch_id);
        $rental_id = $branch->rental_id;
        $branch_id = $branch->id;
    }
    // Jika owner rental
    else {
        $rental = $user->rental;
        $rental_id = $rental->id;
        $branch_id = $request->branch_id;
    }

    $imagePath = $request->file('gambar')->store('mobil_images', 'public');

    Mobil::create([
        'rental_id' => $rental_id,
        'branch_id' => $branch_id,
        'merk' => $request->merk,
        'model' => $request->model,
        'no_plat' => $request->no_plat,
        'harga_sewa' => $request->harga_sewa,
        'tahun_buat' => $request->tahun_buat,
        'transmisi' => $request->transmisi ?? 'Manual',
        'bahan_bakar' => $request->bahan_bakar ?? 'Bensin',
        'jumlah_kursi' => $request->jumlah_kursi ?? 4,
        'gambar' => $imagePath,
        'status' => 'tersedia',
    ]);

    return redirect()->route('mitra.mobil.index')
        ->with('success', 'Mobil berhasil ditambahkan!');
}
    /**
     * LIST PESANAN
     */
    public function indexPesanan()
{
    $user = Auth::user();

    // Jika user cabang
    if ($user->branch_id) {

        $branch = Branch::find($user->branch_id);

        $pesanan = Transaksi::where('branch_id', $branch->id)
            ->latest()
            ->get();

    } 
    // Jika owner rental
    else {

        $rental = $user->rental;

        $pesanan = Transaksi::where('rental_id', $rental->id)
            ->latest()
            ->get();
    }

    return view('mitra.pesanan.index', compact('pesanan'));
}
}