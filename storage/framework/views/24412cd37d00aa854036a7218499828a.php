<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <div class="relative bg-fixed bg-center bg-cover h-[500px]" 
         style="background-image: url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop');">
        
        <div class="absolute inset-0 bg-slate-900/70"></div>
        
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
            <span class="text-blue-400 font-bold tracking-widest uppercase text-sm mb-2 animate-fade-in-up">Area Pelanggan</span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-4 drop-shadow-lg animate-fade-in-up delay-100">
                Riwayat Perjalanan
            </h1>
            <p class="text-lg text-gray-300 max-w-2xl animate-fade-in-up delay-200">
                Pantau status pemesanan, lakukan pembayaran, dan unduh tiket elektronik Anda dalam satu tempat.
            </p>
        </div>
    </div>

    
    <div class="relative z-10 -mt-32 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        
        <?php if(session('success')): ?>
            <div class="mb-6 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-bounce-short">
                <i class="fa-solid fa-circle-check text-2xl"></i>
                <div>
                    <strong class="block font-bold">Berhasil!</strong>
                    <span class="text-sm"><?php echo e(session('success')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if(session('error')): ?>
            <div class="mb-6 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-bounce-short">
                <i class="fa-solid fa-circle-exclamation text-2xl"></i>
                <div>
                    <strong class="block font-bold">Gagal!</strong>
                    <span class="text-sm"><?php echo e(session('error')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden border border-white/20">
            
            <div class="p-6 sm:p-8 border-b border-gray-100 flex justify-between items-center bg-white">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Daftar Transaksi
                </h3>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                    Total: <?php echo e(count($transaksis)); ?> Pesanan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Kendaraan</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Jadwal Sewa</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Tagihan</th>
                            <th class="px-6 py-4 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Aksi & Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-blue-50/30 transition duration-200">
                            
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="relative group">
                                        <?php if($t->mobil && $t->mobil->gambar): ?>
                                            <img class="h-16 w-24 rounded-lg object-cover shadow-sm group-hover:scale-110 transition duration-300" src="<?php echo e(asset('img/' . $t->mobil->gambar)); ?>">
                                        <?php else: ?>
                                            <div class="h-16 w-24 bg-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-400 font-bold border border-gray-200">No Pic</div>
                                        <?php endif; ?>
                                        <div class="absolute inset-0 bg-black/10 rounded-lg group-hover:bg-transparent transition"></div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-black text-slate-800"><?php echo e($t->mobil->merek ?? 'Mobil'); ?> <?php echo e($t->mobil->model ?? 'Dihapus'); ?></div>
                                        <div class="text-xs text-gray-500 font-mono mt-0.5"><?php echo e($t->mobil->no_plat ?? '-'); ?></div>
                                        <?php if($t->sopir == 'dengan_sopir'): ?>
                                            <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">
                                                <i class="fa-solid fa-user-tie mr-1"></i> +Sopir
                                            </span>
                                        <?php else: ?>
                                            <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">
                                                <i class="fa-solid fa-key mr-1"></i> Lepas Kunci
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-xs space-y-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 text-center bg-emerald-100 text-emerald-700 rounded py-0.5 font-bold">IN</div>
                                        <div>
                                            <span class="block font-bold text-gray-700"><?php echo e(\Carbon\Carbon::parse($t->tgl_ambil)->format('d M Y')); ?></span>
                                            <span class="text-gray-400"><?php echo e(\Carbon\Carbon::parse($t->jam_ambil)->format('H:i')); ?> WIB</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 text-center bg-rose-100 text-rose-700 rounded py-0.5 font-bold">OUT</div>
                                        <div>
                                            <span class="block font-bold text-gray-700"><?php echo e(\Carbon\Carbon::parse($t->tgl_kembali)->format('d M Y')); ?></span>
                                            <span class="text-gray-400"><?php echo e(\Carbon\Carbon::parse($t->jam_kembali)->format('H:i')); ?> WIB</span>
                                        </div>
                                    </div>
                                    <div class="pl-10 text-gray-400 italic text-[10px]">
                                        Durasi: <strong class="text-slate-700"><?php echo e($t->lama_sewa); ?> Hari</strong>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-6 py-5">
                                <div class="flex flex-col space-y-2 text-xs">
                                    <?php if(strtolower($t->lokasi_ambil) == 'kantor'): ?>
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <i class="fa-solid fa-building text-emerald-500 mt-0.5"></i>
                                            <span class="font-medium">Ambil di Kantor</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <i class="fa-solid fa-location-dot text-emerald-500 mt-0.5"></i>
                                            <span class="font-medium truncate max-w-[150px]"><?php echo e($t->alamat_jemput ?? $t->alamat_lengkap); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if(strtolower($t->lokasi_kembali) == 'kantor'): ?>
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <i class="fa-solid fa-building text-rose-500 mt-0.5"></i>
                                            <span class="font-medium">Kembali ke Kantor</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <i class="fa-solid fa-location-dot text-rose-500 mt-0.5"></i>
                                            <span class="font-medium truncate max-w-[150px]"><?php echo e($t->alamat_antar ?? $t->alamat_lengkap); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-base font-black text-slate-800">
                                        Rp <?php echo e(number_format($t->total_harga, 0, ',', '.')); ?>

                                    </span>
                                    <div class="mt-1">
                                        <?php if($t->bukti_bayar): ?>
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                                <i class="fa-solid fa-check-double"></i> Bukti Terkirim
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-100">
                                                <i class="fa-regular fa-clock"></i> Belum Bayar
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                <?php
                                    $statusLower = strtolower(trim($t->status));
                                    $statusClass = 'bg-gray-100 text-gray-600 border-gray-200';
                                    $statusLabel = $t->status;
                                    
                                    if(in_array($statusLower, ['pending', 'perlu cek', 'menunggu'])) {
                                        $statusClass = 'bg-amber-100 text-amber-700 border-amber-200';
                                        $statusLabel = 'Menunggu Pembayaran';
                                    } elseif($statusLower == 'menunggu konfirmasi') {
                                        $statusClass = 'bg-blue-100 text-blue-700 border-blue-200';
                                        $statusLabel = 'Verifikasi Admin';
                                    } elseif(in_array($statusLower, ['disewa', 'approved', 'disetujui', 'sedang berjalan'])) {
                                        $statusClass = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                        $statusLabel = 'Sedang Berjalan';
                                    } elseif(in_array($statusLower, ['selesai', 'finished'])) {
                                        $statusClass = 'bg-slate-800 text-white border-slate-900';
                                        $statusLabel = 'Selesai';
                                    } elseif($statusLower == 'dibatalkan') {
                                        $statusClass = 'bg-red-100 text-red-700 border-red-200';
                                        $statusLabel = 'Dibatalkan';
                                    }

                                    $canCancel = in_array($statusLower, ['pending', 'perlu cek', 'menunggu pembayaran', 'menunggu']);
                                    $isRented = in_array($statusLower, ['disewa', 'sedang berjalan', 'approved', 'disetujui']);
                                ?>

                                <div class="flex flex-col items-center gap-3">
                                    <span class="px-3 py-1 text-[10px] uppercase font-black tracking-wide rounded-full border <?php echo e($statusClass); ?>">
                                        <?php echo e($statusLabel); ?>

                                    </span>

                                    <div class="flex flex-col gap-2 w-full max-w-[140px]">
                                        
                                        <?php if($canCancel): ?>
                                            <?php if(!$t->bukti_bayar): ?>
                                                <button onclick="document.getElementById('modal-upload-<?php echo e($t->id); ?>').classList.remove('hidden')" 
                                                        class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white text-xs font-bold py-2 px-3 rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                                    <i class="fa-solid fa-wallet"></i> Bayar
                                                </button>
                                                
                                                <form action="<?php echo e(route('transaksi.batal', $t->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                                    <button type="submit" class="w-full text-[10px] text-red-500 hover:text-red-700 font-bold hover:underline">
                                                        Batalkan Pesanan
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        
                                        <?php if($isRented): ?>
                                            <span class="text-[10px] text-gray-400 italic font-medium">
                                                <i class="fa-solid fa-lock mr-1"></i> Unit sedang jalan
                                            </span>
                                        <?php endif; ?>

                                        
                                        <?php if(!in_array($statusLower, ['selesai', 'finished', 'dibatalkan'])): ?>
                                            <a href="https://wa.me/6285375285567?text=Halo Admin FZ Rent, saya ingin konfirmasi pesanan #<?php echo e($t->id); ?>" 
                                               target="_blank" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg shadow-sm transition flex items-center justify-center gap-1">
                                                <i class="fa-brands fa-whatsapp text-sm"></i> Hubungi Admin
                                            </a>
                                        <?php endif; ?>

                                        
                                        <?php if(in_array($statusLower, ['approved', 'disetujui', 'disewa', 'sedang berjalan', 'selesai', 'finished'])): ?>
                                            <a href="<?php echo e(route('transaksi.cetak', $t->id)); ?>" target="_blank" 
                                               class="w-full bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold py-1.5 px-3 rounded-lg shadow-sm transition flex items-center justify-center gap-1">
                                                <i class="fa-solid fa-print"></i> E-Tiket
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <div id="modal-upload-<?php echo e($t->id); ?>" class="hidden fixed inset-0 z-50 overflow-y-auto">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('modal-upload-<?php echo e($t->id); ?>').classList.add('hidden')"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full">
                                            <div class="bg-slate-900 px-4 py-3 flex justify-between items-center text-white">
                                                <h3 class="text-xs font-bold uppercase tracking-wider"><i class="fa-solid fa-upload mr-2"></i> Konfirmasi Bayar</h3>
                                                <button onclick="document.getElementById('modal-upload-<?php echo e($t->id); ?>').classList.add('hidden')"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            <div class="p-6 text-center">
                                                <div class="bg-blue-50 rounded-xl p-4 mb-5">
                                                    <p class="text-xs text-blue-600 font-bold uppercase">Total Tagihan</p>
                                                    <p class="text-2xl font-black text-slate-800 mb-3">Rp <?php echo e(number_format($t->total_harga)); ?></p>
                                                    <p class="text-[10px] text-gray-500 italic">Transfer BRI: 1234567890 a.n Zikrallah</p>
                                                </div>
                                                <form action="<?php echo e(route('transaksi.upload', $t->id)); ?>" method="POST" enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?> 
                                                    <input type="file" name="bukti_bayar" class="w-full text-xs mb-4" required>
                                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition">Kirim Bukti Pembayaran</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-car-side text-4xl text-slate-200 mb-4 block"></i>
                                    <h3 class="text-xl font-bold text-slate-700">Belum Ada Riwayat</h3>
                                    <a href="<?php echo e(route('pages.order')); ?>" class="mt-4 inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg">Sewa Sekarang</a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-8 text-center">
            <p class="text-xs text-white/80 font-medium drop-shadow-md">&copy; <?php echo e(date('Y')); ?> FZ Rent. Perjalanan Anda, Prioritas Kami.</p>
        </div>
    </div>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/pages/riwayat.blade.php ENDPATH**/ ?>