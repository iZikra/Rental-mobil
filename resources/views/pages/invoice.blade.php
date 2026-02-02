<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket Rental Mobil - {{ $transaksi->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-gray-100 p-6 md:p-10">

    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden p-8 border border-gray-200">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-center border-b pb-6 mb-6 text-center md:text-left gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">E-TICKET / INVOICE</h1>
                <p class="text-gray-500 text-sm">Terima kasih telah menyewa di Rental Kami</p>
            </div>
            <div class="text-center md:text-right">
                <h2 class="text-xl font-bold text-blue-600">RENT CAR APP</h2>
                <p class="text-sm text-gray-500">Jl. Teknologi No. 1, Indonesia</p>
            </div>
        </div>

        {{-- INFO PENYEWA & STATUS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 text-center md:text-left">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Penyewa</h3>
                <p class="text-lg font-bold text-gray-800">{{ $transaksi->user->name }}</p>
                <p class="text-sm text-gray-600">{{ $transaksi->no_hp }}</p>
            </div>
            <div class="text-center md:text-right">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status Pesanan</h3>
                @if(in_array($transaksi->status, ['process', 'finished', 'Approved', 'Disewa', 'Selesai']))
                    <span class="inline-block px-4 py-1.5 bg-green-100 text-green-800 rounded-full font-bold text-sm border border-green-200">
                        SUKSES / VALID
                    </span>
                @else
                    <span class="inline-block px-4 py-1.5 bg-yellow-100 text-yellow-800 rounded-full font-bold text-sm border border-yellow-200">
                        MENUNGGU KONFIRMASI
                    </span>
                @endif
            </div>
        </div>

        {{-- DETAIL KENDARAAN --}}
        <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-100">
            <h3 class="text-sm font-bold text-gray-500 uppercase mb-4 text-center md:text-left">Detail Kendaraan</h3>
            <div class="flex flex-col md:flex-row justify-between items-center text-center md:text-left gap-4">
                <div>
                    {{-- Sesuai dengan Model Mobil.php Anda: 'merk', 'model', 'no_plat' --}}
                    <p class="text-2xl font-bold text-gray-800">{{ $transaksi->mobil->merk }} {{ $transaksi->mobil->model }}</p>
                    <p class="text-gray-600 font-mono mt-1 text-sm">
                        Plat Nomor: {{ $transaksi->mobil->no_plat ?? '(Data Kosong)' }}
                    </p>
                </div>
                <div class="text-center md:text-right">
                    @if($transaksi->pakai_sopir)
                        <p class="text-blue-700 font-bold bg-blue-100 px-3 py-1 rounded text-xs">+ Dengan Sopir</p>
                    @else
                        <p class="text-gray-600 bg-gray-200 px-3 py-1 rounded text-xs">Lepas Kunci</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- =================================================== --}}
        {{-- KOTAK INFORMASI PEMBAYARAN (PASTIKAN INI TERSALIN) --}}
        {{-- =================================================== --}}
        <div class="mb-8 border-2 border-dashed border-blue-200 bg-blue-50 rounded-lg p-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                {{-- Kolom Kiri: Info Rekening --}}
                <div>
                    <h3 class="text-sm font-bold text-blue-800 uppercase tracking-wider mb-2">Metode Pembayaran</h3>
                    <p class="text-xs text-blue-600 mb-3">Silakan transfer ke rekening berikut:</p>
                    
                    <div class="flex items-center gap-4 bg-white p-4 rounded-lg border border-blue-100 shadow-sm">
                        <div class="w-12 h-10 bg-blue-900 rounded flex items-center justify-center text-white font-bold text-xs italic">
                            BCA
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Bank Central Asia</p>
                            <p class="text-xl font-mono font-bold text-gray-800 tracking-wide">123-456-7890</p>
                            <p class="text-xs text-gray-600 font-semibold">a.n. Zikrallah Al Hady</p>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Deadline --}}
                <div class="text-center md:text-right mt-4 md:mt-0">
                    <p class="text-xs text-gray-500 mb-1">Batas Waktu Pembayaran</p>
                    <p class="text-sm font-bold text-red-500">
                        {{ \Carbon\Carbon::parse($transaksi->created_at)->addHours(24)->format('d M Y H:i') }} WIB
                    </p>
                    <p class="text-[10px] text-gray-400 mt-2 italic max-w-xs ml-auto">
                        *Simpan bukti transfer dan upload di menu Riwayat Pesanan.
                    </p>
                </div>
            </div>
        </div>
        {{-- =================================================== --}}

        {{-- RINCIAN WAKTU & BIAYA --}}
        <div class="border-t border-gray-200 pt-6">
            <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <span class="text-gray-600 block mb-1">Waktu Pengambilan</span>
                    <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tgl_ambil)->format('d M Y') }}</span>
                    <span class="text-gray-500 text-xs">Pukul {{ $transaksi->jam_ambil }} WIB</span>
                </div>
                <div class="text-right">
                    <span class="text-gray-600 block mb-1">Waktu Pengembalian</span>
                    <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tgl_kembali)->format('d M Y') }}</span>
                    <span class="text-gray-500 text-xs">Pukul {{ $transaksi->jam_kembali }} WIB</span>
                </div>
            </div>

            <div class="flex justify-between mt-6 pt-4 border-t-2 border-gray-800 items-center">
                <span class="text-lg font-bold text-gray-800 uppercase">Total Biaya</span>
                <span class="text-3xl font-extrabold text-blue-600">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="mt-10 text-center text-xs text-gray-400">
            <p>Harap tunjukkan tiket ini kepada petugas saat pengambilan kendaraan.</p>
            <p>&copy; {{ date('Y') }} Sistem Informasi Rental Mobil.</p>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="mt-8 text-center no-print space-x-4">
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 shadow-lg transition transform hover:scale-105 inline-flex items-center gap-2">
                <span>🖨️</span> Cetak Tiket / Simpan PDF
            </button>
            <a href="{{ route('riwayat.index') }}" class="ml-4 text-gray-500 hover:text-gray-800 font-medium underline transition">
                Kembali ke Riwayat
            </a>
        </div>
    </div>

</body>
</html>