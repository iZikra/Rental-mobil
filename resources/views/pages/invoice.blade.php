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
<body class="bg-gray-100 p-10">

    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden p-8 border border-gray-200">
        
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 text-center md:text-left">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Penyewa</h3>
                <p class="text-lg font-bold text-gray-800">{{ $transaksi->user->name }}</p>
                <p class="text-sm text-gray-600">{{ $transaksi->no_hp }}</p>
            </div>
            <div class="text-center md:text-right">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Pesanan</h3>
                @if($transaksi->status == 'process' || $transaksi->status == 'finished')
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full font-bold text-sm">
                        SUKSES / VALID
                    </span>
                @else
                    <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full font-bold text-sm">
                        MENUNGGU KONFIRMASI
                    </span>
                @endif
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h3 class="text-sm font-bold text-gray-500 uppercase mb-4 text-center md:text-left">Detail Kendaraan</h3>
            
            <div class="flex flex-col md:flex-row justify-between items-center text-center md:text-left gap-4">
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $transaksi->mobil->merk }} {{ $transaksi->mobil->model }}</p>
                    <p class="text-gray-600 font-mono mt-1">
                        Plat Nomor: {{ $transaksi->mobil->no_plat ?? '(Data Kosong)' }}
                    </p>
                </div>
                <div class="text-center md:text-right">
                    @if($transaksi->pakai_sopir)
                        <p class="text-blue-600 font-bold bg-blue-50 px-3 py-1 rounded">+ Dengan Sopir</p>
                    @else
                        <p class="text-gray-500 bg-gray-200 px-3 py-1 rounded">Lepas Kunci</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <div class="flex justify-between mb-2 items-center">
                <span class="text-gray-600">Waktu Pengambilan</span>
                <div class="text-right">
                    <span class="font-bold block">{{ \Carbon\Carbon::parse($transaksi->tgl_ambil)->format('d M Y') }}</span>
                    <span class="text-sm text-gray-500">Pukul {{ $transaksi->jam_ambil }} WIB</span>
                </div>
            </div>

            <div class="flex justify-between mb-2 items-center">
                <span class="text-gray-600">Waktu Pengembalian</span>
                <div class="text-right">
                    <span class="font-bold block">{{ \Carbon\Carbon::parse($transaksi->tgl_kembali)->format('d M Y') }}</span>
                    <span class="text-sm text-gray-500">Pukul {{ $transaksi->jam_kembali }} WIB</span>
                </div>
            </div>

            <div class="flex justify-between mt-6 pt-4 border-t border-dashed border-gray-300 items-center">
                <span class="text-xl font-bold text-gray-800">Total Biaya</span>
                <span class="text-3xl font-bold text-blue-600">Rp {{ number_format($transaksi->total_harga) }}</span>
            </div>
        </div>

        <div class="mt-10 text-center text-xs text-gray-400">
            <p>Harap tunjukkan tiket ini kepada petugas saat pengambilan kendaraan.</p>
            <p>&copy; {{ date('Y') }} Sistem Informasi Rental Mobil.</p>
        </div>

        <div class="mt-8 text-center no-print">
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 shadow-lg transition transform hover:scale-105">
                🖨️ Cetak Tiket / Simpan PDF
            </button>
            <a href="{{ route('riwayat.index') }}" class="ml-4 text-gray-500 hover:text-gray-800 underline">Kembali</a>
        </div>
    </div>

</body>
</html>