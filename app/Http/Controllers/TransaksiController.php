<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Midtrans\Config;
use Midtrans\Snap;

class TransaksiController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool) config('services.midtrans.is_3ds');
    }
    /**
     * Menampilkan riwayat transaksi milik user.
     */
    public function index()
    {
        // PERBAIKAN MUTLAK: Tambahkan 'rental' di dalam fungsi with()
        $transaksis = Transaksi::where('user_id', Auth::id())
            ->with(['mobil.branch', 'rental', 'user'])
            ->latest()
            ->get();

        foreach ($transaksis as $t) {
            if (strtolower(trim($t->status)) === 'pending' && !$t->snap_token) {
                $params = [
                    'transaction_details' => [
                        'order_id' => 'ORDER-' . $t->id . '-' . time(),
                        'gross_amount' => (int) $t->total_harga,
                    ],
                    'customer_details' => [
                        'first_name' => $t->user->name,
                        'email' => $t->user->email,
                        'phone' => $t->no_hp,
                    ],
                ];
                try {
                    $t->snap_token = Snap::getSnapToken($params);
                    $t->save();
                } catch (\Exception $e) {
                    Log::error("Midtrans Index Error for ID {$t->id}: " . $e->getMessage());
                }
            }
        }

        return view('pages.riwayat', compact('transaksis'));
    }

    /**
     * Form pembuatan pesanan baru.
     */
    public function create(Request $request)
    {
        $selectedMobil = null;
        if ($request->has('mobil_id')) {
            $selectedMobil = Mobil::find($request->mobil_id);
        }
        // Hanya tampilkan mobil yang statusnya 'tersedia'
        $semuaMobil = Mobil::where('status', 'tersedia')->get();
        
        return view('pages.order', compact('selectedMobil', 'semuaMobil'));
    }

    public function store(Request $request)
{
    // 1. Validasi Input KTP dan SIM
    $request->validate([
        'mobil_id'       => 'required|exists:mobils,id',
        'no_hp'          => 'required|string|max:20',
        'alamat'         => 'required|string|max:500',
        'tgl_ambil'      => 'required|date|after_or_equal:today',
        'jam_ambil'      => 'required',
        'tgl_kembali'    => 'required|date|after:tgl_ambil',
        'jam_kembali'    => 'required',
        'lokasi_ambil'   => 'required|in:kantor,bandara,lainnya',
        'lokasi_kembali' => 'required|in:kantor,bandara,lainnya',
        'alamat_jemput_lain' => 'required_if:lokasi_ambil,lainnya|string|max:500',
        'alamat_antar_lain'  => 'required_if:lokasi_kembali,lainnya|string|max:500',
        'sopir'          => 'required|in:tanpa_sopir,dengan_sopir',
        'tujuan'         => 'required|string|max:255',
        'setuju_sk'      => 'accepted',
        'foto_identitas' => 'required|image|max:2048',
        'foto_sim'       => 'required|image|mimes:jpeg,png,jpg|max:2048', 
    ]);

    // 2. Validasi Waktu
    $waktuAmbil   = \Carbon\Carbon::parse($request->tgl_ambil . ' ' . $request->jam_ambil);
    $waktuKembali = \Carbon\Carbon::parse($request->tgl_kembali . ' ' . $request->jam_kembali);

    if ($waktuAmbil->lessThan(now()->subHour())) {
        return redirect()->back()->withInput()->with('error', 'Tanggal pengambilan tidak valid (sudah lewat).');
    }

    if ($waktuKembali->lessThanOrEqualTo($waktuAmbil)) {
        return redirect()->back()->withInput()->with('error', 'Waktu pengembalian harus setelah waktu pengambilan.');
    }

    // 3. Cek Bentrok Jadwal
    $cekBentrok = \App\Models\Transaksi::where('mobil_id', $request->mobil_id)
        ->whereNotIn('status', ['Dibatalkan', 'Ditolak', 'Selesai']) 
        ->where(function ($query) use ($request) {
            $query->where('tgl_ambil', '<=', $request->tgl_kembali)
                  ->where('tgl_kembali', '>=', $request->tgl_ambil);
        })->exists();

    if ($cekBentrok) {
        return redirect()->back()->withInput()->with('error', 'Maaf, unit mobil ini sudah dibooking pada rentang waktu tersebut.');
    }

    \Illuminate\Support\Facades\DB::beginTransaction();
    try {
        // 4. Upload KTP & SIM (Dilakukan di dalam Try-Catch agar aman)
        $pathFotoKtp = $request->file('foto_identitas')->store('identitas', 'public');
        $pathFotoSim = $request->file('foto_sim')->store('sim_pelanggan', 'public');

        $mobil = \App\Models\Mobil::findOrFail($request->mobil_id);
        
        // 5. Kalkulasi Harga dan Durasi
        $selisihJam = $waktuAmbil->diffInHours($waktuKembali);
        $durasiHari = (int) ceil($selisihJam / 24);
        if ($durasiHari < 1) $durasiHari = 1;

        $rental = $mobil->rental;
        $biayaSopirPerHari = (int) ($rental->biaya_sopir_per_hari ?? 0);
        $biayaBandaraPerTrip = (int) ($rental->biaya_bandara_per_trip ?? 0);

        $biayaSewa = $mobil->harga_sewa * $durasiHari;
        $biayaSopir = ($request->sopir === 'dengan_sopir') ? ($biayaSopirPerHari * $durasiHari) : 0;
        $biayaJemputBandara = ($request->lokasi_ambil === 'bandara') ? $biayaBandaraPerTrip : 0;
        $biayaAntarBandara = ($request->lokasi_kembali === 'bandara') ? $biayaBandaraPerTrip : 0;
        $totalHarga = $biayaSewa + $biayaSopir + $biayaJemputBandara + $biayaAntarBandara;

        // 6. Eksekusi Simpan Data Utama (Ditambah atribut foto_sim)
        $transaksi = \App\Models\Transaksi::create([
            'user_id'         => \Illuminate\Support\Facades\Auth::id(),
            'mobil_id'        => $mobil->id,
            'rental_id'       => $mobil->rental_id, // WAJIB ADA
            'branch_id'       => $mobil->branch_id,
            'nama'            => \Illuminate\Support\Facades\Auth::user()->name,
            'no_hp'           => $request->no_hp,
            'alamat'          => $request->alamat,
            'foto_identitas'  => $pathFotoKtp,
            'foto_sim'        => $pathFotoSim, // INI PENYELAMATNYA
            'tgl_ambil'       => $request->tgl_ambil,
            'jam_ambil'       => $request->jam_ambil,
            'tgl_kembali'     => $request->tgl_kembali,
            'jam_kembali'     => $request->jam_kembali,
            'tujuan'          => $request->tujuan,
            'lokasi_ambil'    => $request->lokasi_ambil,
            'lokasi_kembali'  => $request->lokasi_kembali,
            'alamat_jemput'   => $request->lokasi_ambil === 'bandara'
                ? 'Bandara'
                : ($request->lokasi_ambil === 'lainnya' ? $request->alamat_jemput_lain : 'Ambil di Kantor'),
            'alamat_antar'    => $request->lokasi_kembali === 'bandara'
                ? 'Bandara'
                : ($request->lokasi_kembali === 'lainnya' ? $request->alamat_antar_lain : 'Kembalikan ke Kantor'),
            'sopir'           => $request->sopir ?? 'tanpa_sopir',
            'lama_sewa'       => $durasiHari,
            'total_harga'     => $totalHarga,
            'status'          => 'Pending',
        ]);

        // 7. Generate Midtrans Snap Token
        $itemDetails = [
            [
                'id' => (string) $mobil->id,
                'price' => (int) $mobil->harga_sewa,
                'quantity' => (int) $durasiHari,
                'name' => substr('Sewa ' . $mobil->merk . ' ' . $mobil->model, 0, 50),
            ]
        ];

        if ($request->sopir === 'dengan_sopir') {
            $itemDetails[] = [
                'id' => 'SOPIR',
                'price' => (int) $biayaSopirPerHari,
                'quantity' => (int) $durasiHari,
                'name' => 'Biaya Sopir',
            ];
        }

        if ($biayaJemputBandara > 0) {
            $itemDetails[] = [
                'id' => 'JEMPUT_BANDARA',
                'price' => (int) $biayaBandaraPerTrip,
                'quantity' => 1,
                'name' => 'Jemput di Bandara',
            ];
        }

        if ($biayaAntarBandara > 0) {
            $itemDetails[] = [
                'id' => 'ANTAR_BANDARA',
                'price' => (int) $biayaBandaraPerTrip,
                'quantity' => 1,
                'name' => 'Antar ke Bandara',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $transaksi->id . '-' . time(),
                'gross_amount' => (int) $totalHarga,
            ],
            'customer_details' => [
                'first_name' => \Illuminate\Support\Facades\Auth::user()->name,
                'email' => \Illuminate\Support\Facades\Auth::user()->email,
                'phone' => $request->no_hp,
            ],
            'item_details' => $itemDetails,
        ];

        try {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = (bool) config('services.midtrans.is_production');
            Config::$isSanitized = (bool) config('services.midtrans.is_sanitized');
            Config::$is3ds = (bool) config('services.midtrans.is_3ds');

            $snapToken = Snap::getSnapToken($params);
            $transaksi->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error("Midtrans Snap Error: " . $e->getMessage());
            throw new \RuntimeException('Gagal membuat pembayaran: ' . $e->getMessage(), previous: $e);
        }

        \Illuminate\Support\Facades\DB::commit(); 
        
        // --- KIRIM WA NOTIFIKASI BOOKING BARU (PENDING PAYMENT) ---
        try {
            $noHpPenyewa = $request->no_hp ?? \Illuminate\Support\Facades\Auth::user()->no_hp;
            if (!empty($noHpPenyewa)) {
                $namaPenyewa = \Illuminate\Support\Facades\Auth::user()->name;
                $teksPesan = "*TAGIHAN BARU - FZ RENT CAR*\n\n"
                           . "Halo {$namaPenyewa},\n"
                           . "Anda baru saja membuat pesanan sewa armada *{$mobil->merk} {$mobil->model}*.\n\n"
                           . "ID Pesanan: *ORDER-{$transaksi->id}*\n"
                           . "Total Tagihan: *Rp " . number_format($totalHarga, 0, ',', '.') . "*\n\n"
                           . "Mohon segera selesaikan pembayaran agar pesanan Anda dapat diproses.\n"
                           . "(Abaikan jika Anda sudah membayar via website).\n\n"
                           . "Terima kasih!";
                 
                $responseWa = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => env('WA_API_TOKEN'),
                ])->asForm()->post(env('WA_API_URL'), [
                    'target' => $noHpPenyewa, 
                    'message' => $teksPesan,
                    'countryCode' => '62',
                ]);
                $waResult = $responseWa->json();
                if (isset($waResult['status']) && $waResult['status'] === true) {
                    \Illuminate\Support\Facades\Log::info('WA Booking Baru Sukses: ' . $noHpPenyewa);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WA Booking Baru Error: ' . $e->getMessage());
        }
        // ----------------------------------------------------------

        return redirect()->route('riwayat', ['pay' => $transaksi->id])->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack(); 
        if (!empty($pathFotoKtp ?? null)) {
            Storage::disk('public')->delete($pathFotoKtp);
        }
        if (!empty($pathFotoSim ?? null)) {
            Storage::disk('public')->delete($pathFotoSim);
        }
        \Illuminate\Support\Facades\Log::error("Error Store Transaksi User " . \Illuminate\Support\Facades\Auth::id() . ": " . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
    }
}
    /**
     * User Membatalkan Pesanan.
     * Mengembalikan status mobil menjadi 'tersedia'.
     */
    public function batalkanPesanan($id)
    {
        // Pastikan transaksi milik user yang sedang login
        $transaksi = Transaksi::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan atau akses ditolak.');
        }

        // Jangan izinkan pembatalan jika sudah selesai/sedang jalan
        if (in_array($transaksi->status, ['Sedang Jalan', 'Selesai'])) {
            return redirect()->back()->with('error', 'Pesanan yang sedang berjalan tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            // Update status transaksi
            $transaksi->update(['status' => 'Dibatalkan']);

            // Kembalikan status mobil jadi tersedia
            DB::table('mobils')->where('id', $transaksi->mobil_id)->update(['status' => 'tersedia']);

            DB::commit();
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan. Dana akan dikembalikan sesuai kebijakan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal Batal Transaksi: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan.');
        }
    }

    /**
     * ADMIN ONLY: Menyelesaikan Transaksi (Pengembalian Mobil).
     * Pastikan route ini dilindungi middleware 'admin'.
     */
    public function selesaikanTransaksi($id)
    {
        $user = Auth::user();

        // 1. Ambil transaksi dengan relasi mobil agar tahu ini milik cabang mana
        $transaksi = Transaksi::with('mobil')->findOrFail($id);

        // 2. FILTER KEAMANAN (Authorization)
        // Jika bukan Super Admin, maka harus dicek apakah branch_id-nya cocok
        if ($user->role !== 'admin') { 
            // Cek apakah user memiliki branch_id yang sama dengan mobil di transaksi tersebut
            if ($user->branch_id !== $transaksi->branch_id) {
                Log::warning("User ID {$user->id} mencoba akses ilegal ke Transaksi ID {$id}");
                return redirect()->back()->with('error', 'Maaf, Anda tidak memiliki akses untuk menyelesaikan transaksi di cabang ini!');
            }
        }

        DB::beginTransaction();
        try {
            // 3. Update status transaksi menjadi 'Selesai'
            $transaksi->update(['status' => 'Selesai']);

            // 4. PENTING: Update status mobil di tabel mobils menjadi 'tersedia'
            // Menggunakan Eloquent agar observer (jika ada) bisa menangkap perubahannya
            $mobil = Mobil::find($transaksi->mobil_id);
            if ($mobil) {
                $mobil->update(['status' => 'tersedia']);
            }

            DB::commit();

            // Log untuk audit
            Log::info("Transaksi ID {$id} diselesaikan oleh {$user->name}");

            return redirect()->back()->with('success', 'Transaksi Selesai. Unit mobil kembali tersedia di stok Chatbot.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal Selesaikan Transaksi: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyelesaikan transaksi: ' . $e->getMessage());
        }
    }
    /**
     * Cetak Tiket/Invoice.
     */
public function cetak($id)
{
    $transaksi = Transaksi::with('mobil')->findOrFail($id);

    return view('pages.cetak_tiket', compact('transaksi'));
}
}
