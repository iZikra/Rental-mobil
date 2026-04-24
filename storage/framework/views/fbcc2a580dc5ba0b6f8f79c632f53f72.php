<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket Rental Mobil - <?php echo e($transaksi->id); ?></title>
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
                <p class="text-lg font-bold text-gray-800"><?php echo e($transaksi->user->name); ?></p>
                <p class="text-sm text-gray-600"><?php echo e($transaksi->no_hp); ?></p>
            </div>
            <div class="text-center md:text-right">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status Pesanan</h3>
                <?php if(in_array($transaksi->status, ['process', 'finished', 'Approved', 'Disewa', 'Selesai'])): ?>
                    <span class="inline-block px-4 py-1.5 bg-green-100 text-green-800 rounded-full font-bold text-sm border border-green-200">
                        SUKSES / VALID
                    </span>
                <?php else: ?>
                    <span class="inline-block px-4 py-1.5 bg-yellow-100 text-yellow-800 rounded-full font-bold text-sm border border-yellow-200">
                        MENUNGGU KONFIRMASI
                    </span>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-100">
            <h3 class="text-sm font-bold text-gray-500 uppercase mb-4 text-center md:text-left">Detail Kendaraan</h3>
            <div class="flex flex-col md:flex-row justify-between items-center text-center md:text-left gap-4">
                <div>
                    
                    <p class="text-2xl font-bold text-gray-800"><?php echo e($transaksi->mobil->merk); ?> <?php echo e($transaksi->mobil->model); ?></p>
                    <p class="text-gray-600 font-mono mt-1 text-sm">
                        Plat Nomor: <?php echo e($transaksi->mobil->no_plat ?? '(Data Kosong)'); ?>

                    </p>
                </div>
                <div class="text-center md:text-right">
                    <?php if($transaksi->pakai_sopir): ?>
                        <p class="text-blue-700 font-bold bg-blue-100 px-3 py-1 rounded text-xs">+ Dengan Sopir</p>
                    <?php else: ?>
                        <p class="text-gray-600 bg-gray-200 px-3 py-1 rounded text-xs">Lepas Kunci</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        
        
        <div class="mb-8 border-2 border-dashed border-emerald-200 bg-emerald-50 rounded-lg p-6 text-center">
            <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider mb-2">Status Pembayaran</h3>
            <p class="text-lg font-black text-emerald-600 uppercase flex items-center justify-center gap-2">
                <i class="fa-solid fa-circle-check"></i> LUNAS / PAID
            </p>
            <p class="text-[10px] text-gray-500 mt-2 italic">
                Pembayaran telah diverifikasi secara otomatis melalui Payment Gateway.
            </p>
        </div>
        

        
        <div class="border-t border-gray-200 pt-6">
            <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <span class="text-gray-600 block mb-1">Waktu Pengambilan</span>
                    <span class="font-bold text-gray-800"><?php echo e(\Carbon\Carbon::parse($transaksi->tgl_ambil)->format('d M Y')); ?></span>
                    <span class="text-gray-500 text-xs">Pukul <?php echo e($transaksi->jam_ambil); ?> WIB</span>
                </div>
                <div class="text-right">
                    <span class="text-gray-600 block mb-1">Waktu Pengembalian</span>
                    <span class="font-bold text-gray-800"><?php echo e(\Carbon\Carbon::parse($transaksi->tgl_kembali)->format('d M Y')); ?></span>
                    <span class="text-gray-500 text-xs">Pukul <?php echo e($transaksi->jam_kembali); ?> WIB</span>
                </div>
            </div>

            <div class="flex justify-between mt-6 pt-4 border-t-2 border-gray-800 items-center">
                <span class="text-lg font-bold text-gray-800 uppercase">Total Biaya</span>
                <span class="text-3xl font-extrabold text-blue-600">Rp <?php echo e(number_format($transaksi->total_harga, 0, ',', '.')); ?></span>
            </div>
        </div>

        
        <div class="mt-10 text-center text-xs text-gray-400">
            <p>Harap tunjukkan tiket ini kepada petugas saat pengambilan kendaraan.</p>
            <p>&copy; <?php echo e(date('Y')); ?> Sistem Informasi Rental Mobil.</p>
        </div>

        
        <div class="mt-8 text-center no-print space-x-4">
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 shadow-lg transition transform hover:scale-105 inline-flex items-center gap-2">
                <span>🖨️</span> Cetak Tiket / Simpan PDF
            </button>
            <a href="<?php echo e(route('riwayat')); ?>" class="ml-4 text-gray-500 hover:text-gray-800 font-medium underline transition">
                Kembali ke Riwayat
            </a>
        </div>
    </div>

</body>
</html>
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\pages\invoice.blade.php ENDPATH**/ ?>