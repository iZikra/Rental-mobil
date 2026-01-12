<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket #<?php echo e($transaksi->id); ?> - <?php echo e(config('app.name', 'Rental Mobil')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Warna Hijau Khas Tokopedia */
        .bg-tokopedia { background-color: #00AA5B; }
        .text-tokopedia { color: #00AA5B; }
        .border-tokopedia { border-color: #00AA5B; }
        
        body { font-family: 'Open Sans', sans-serif; background-color: #f0f3f7; }
        
        /* CSS Khusus Print */
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; -webkit-print-color-adjust: exact; }
            .shadow-lg { box-shadow: none !important; }
            .border { border: 1px solid #ddd !important; }
        }
    </style>
</head>
<body class="py-10">

    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden relative">
        
        
        <?php
            // Daftar Status yang dianggap SUDAH BAYAR
            $statusLunas = ['Approved', 'Disetujui', 'Selesai', 'Disewa'];
            
            // Cek apakah status transaksi saat ini ada dalam daftar di atas?
            $isLunas = in_array($transaksi->status, $statusLunas);

            if ($isLunas) {
                $textWatermark = 'LUNAS';
                $classWatermark = 'text-green-600 border-green-600'; // Hijau
            } else {
                $textWatermark = 'BELUM LUNAS';
                $classWatermark = 'text-red-600 border-red-600'; // Merah
            }
        ?>

        
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10 overflow-hidden">
            <span class="transform -rotate-45 border-4 <?php echo e($classWatermark); ?> opacity-20 text-6xl font-black px-10 py-4 uppercase tracking-widest rounded-xl whitespace-nowrap">
                <?php echo e($textWatermark); ?>

            </span>
        </div>
        

        
        <div class="bg-tokopedia p-4 text-white flex justify-between items-center no-print-bg relative z-20">
            <div class="font-bold text-lg flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Transaksi Berhasil
            </div>
            <div class="text-sm opacity-90"><?php echo e(date('d F Y, H:i')); ?></div>
        </div>

        <div class="p-6 relative z-20">
            
            
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="font-bold text-xl text-gray-800">Tiket Sewa Mobil</h1>
                    <p class="text-sm text-gray-500 mt-1">Kode Booking: <span class="text-tokopedia font-bold font-mono">INV/<?php echo e(date('Ymd')); ?>/RENT/<?php echo e($transaksi->id); ?></span></p>
                </div>
                <div class="text-right">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Tokopedia_2019_logo.svg/1200px-Tokopedia_2019_logo.svg.png" alt="Logo" class="h-8 opacity-0 hidden"> 
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wide">Penyewa</span>
                    <span class="block font-bold text-gray-800"><?php echo e($transaksi->nama); ?></span>
                </div>
            </div>

            
            <div class="border-t-2 border-dashed border-gray-200 my-4"></div>

            
            <div class="flex gap-4 mb-4">
                
                <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden border border-gray-200">
                    <?php if($transaksi->mobil && $transaksi->mobil->gambar): ?>
                        <img src="<?php echo e(asset('storage/'.$transaksi->mobil->gambar)); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">No Image</div>
                    <?php endif; ?>
                </div>

                
                <div class="flex-1">
                    <h2 class="font-bold text-lg text-gray-800"><?php echo e($transaksi->mobil->merk); ?> <?php echo e($transaksi->mobil->model); ?></h2>
                    <p class="text-sm text-gray-500 mb-2"><?php echo e($transaksi->mobil->nopol); ?> • <?php echo e($transaksi->mobil->tahun); ?></p>
                    
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-green-50 text-tokopedia text-xs px-2 py-1 rounded font-bold border border-green-100">
                            <?php echo e($transaksi->sopir == 'dengan_sopir' ? 'Dengan Sopir' : 'Lepas Kunci'); ?>

                        </span>
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">
                            <?php echo e($transaksi->lama_sewa); ?> Hari
                        </span>
                    </div>
                </div>
            </div>

            
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 mb-4">
                <div class="grid grid-cols-2 gap-4 relative">
                    
                    <div class="absolute left-1/2 top-0 bottom-0 w-px bg-gray-200 transform -translate-x-1/2"></div>

                    
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Jadwal Ambil</p>
                        <p class="font-bold text-gray-800 text-sm"><?php echo e(\Carbon\Carbon::parse($transaksi->tgl_ambil)->format('d M Y')); ?></p>
                        <p class="text-gray-500 text-xs"><?php echo e(\Carbon\Carbon::parse($transaksi->jam_ambil)->format('H:i')); ?> WIB</p>
                        <p class="text-tokopedia text-xs mt-1 font-medium">📍 <?php echo e($transaksi->lokasi_ambil == 'kantor' ? 'Kantor Rental' : 'Diantar ke Lokasi'); ?></p>
                    </div>

                    
                    <div class="pl-4">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Jadwal Kembali</p>
                        <p class="font-bold text-gray-800 text-sm"><?php echo e(\Carbon\Carbon::parse($transaksi->tgl_kembali)->format('d M Y')); ?></p>
                        <p class="text-gray-500 text-xs"><?php echo e(\Carbon\Carbon::parse($transaksi->jam_kembali)->format('H:i')); ?> WIB</p>
                        <p class="text-tokopedia text-xs mt-1 font-medium">📍 <?php echo e($transaksi->lokasi_kembali == 'kantor' ? 'Kantor Rental' : 'Dijemput di Lokasi'); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Harga Sewa (<?php echo e($transaksi->lama_sewa); ?> hari)</span>
                    <span>Rp <?php echo e(number_format($transaksi->total_harga - ($transaksi->sopir == 'dengan_sopir' ? 150000 * $transaksi->lama_sewa : 0), 0, ',', '.')); ?></span>
                </div>
                
                <?php if($transaksi->sopir == 'dengan_sopir'): ?>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Jasa Sopir</span>
                    <span>Rp <?php echo e(number_format(150000 * $transaksi->lama_sewa, 0, ',', '.')); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Biaya Layanan</span>
                    <span>Rp 0</span>
                </div>

                
                <div class="border-t-2 border-dashed border-gray-200 my-2 pt-2 flex justify-between items-center">
                    <span class="font-bold text-gray-800">Total Bayar</span>
                    <span class="font-bold text-xl text-orange-500">Rp <?php echo e(number_format($transaksi->total_harga, 0, ',', '.')); ?></span>
                </div>
            </div>

        </div>

        
        <div class="bg-gray-50 p-4 border-t border-gray-100 text-xs text-gray-500 leading-relaxed relative z-20">
            <p class="font-bold text-gray-700 mb-1">Catatan Penting:</p>
            <ul class="list-disc pl-4 space-y-1">
                <li>Tunjukkan E-Tiket ini kepada petugas saat pengambilan kendaraan.</li>
                <li>Pastikan membawa KTP asli dan SIM A yang masih berlaku.</li>
                <li>Keterlambatan pengembalian akan dikenakan denda sesuai ketentuan.</li>
            </ul>
        </div>

        
        <div class="p-4 border-t border-gray-100 flex gap-3 no-print bg-white relative z-20">
            <button onclick="window.print()" class="flex-1 bg-tokopedia hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow transition flex justify-center items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Tiket
            </button>
            <button onclick="window.close()" class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-3 rounded-lg hover:bg-gray-50 transition">
                Tutup
            </button>
        </div>

    </div>

    <script>
        // window.onload = function() { window.print(); } // Uncomment jika ingin auto print
    </script>
</body>
</html><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/pages/tiket.blade.php ENDPATH**/ ?>