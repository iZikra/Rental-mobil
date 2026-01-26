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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Riwayat Pesanan Saya')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <?php if(session('success')): ?>
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mobil</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jadwal Sewa</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi Antar/Jemput</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total Biaya</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status & Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
    <?php $__empty_1 = true; $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <?php if($t->mobil && $t->mobil->gambar): ?>
                    <img class="h-12 w-16 rounded object-cover mr-3 border border-gray-200" src="<?php echo e(asset('img/' . $t->mobil->gambar)); ?>">
                <?php else: ?>
                    <div class="h-12 w-16 bg-gray-100 rounded mr-3 flex items-center justify-center text-xs text-gray-400 border border-gray-200">No Pic</div>
                <?php endif; ?>
                <div>
                    <div class="text-sm font-bold text-gray-900"><?php echo e($t->mobil->merk ?? 'Mobil'); ?> <?php echo e($t->mobil->model ?? 'Dihapus'); ?></div>
                    <div class="text-xs text-gray-500"><?php echo e($t->mobil->nopol ?? '-'); ?></div>
                    <?php if($t->sopir == 'dengan_sopir'): ?>
                        <span class="text-[10px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-bold">Pakai Sopir</span>
                    <?php else: ?>
                        <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">Lepas Kunci</span>
                    <?php endif; ?>
                </div>
            </div>
        </td>

        
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-xs text-gray-500 space-y-1">
                <div>
                    <span class="font-bold text-emerald-600">Ambil:</span> 
                    <?php echo e(\Carbon\Carbon::parse($t->tgl_ambil)->format('d M Y')); ?> 
                    <span class="text-gray-400">(<?php echo e(\Carbon\Carbon::parse($t->jam_ambil)->format('H:i')); ?>)</span>
                </div>
                <div>
                    <span class="font-bold text-rose-600">Kembali:</span> 
                    <?php echo e(\Carbon\Carbon::parse($t->tgl_kembali)->format('d M Y')); ?>

                    <span class="text-gray-400">(<?php echo e(\Carbon\Carbon::parse($t->jam_kembali)->format('H:i')); ?>)</span>
                </div>
                <div class="pt-1">
                    <span class="bg-gray-100 px-2 py-0.5 rounded text-[10px] font-bold">
                        <?php echo e($t->lama_sewa); ?> Hari
                    </span>
                </div>
            </div>
        </td>

        
        <td class="px-6 py-4">
            <div class="flex flex-col space-y-3 text-xs">
                <div>
                    <div class="flex items-center gap-1 mb-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="font-bold text-gray-700 uppercase text-[10px]">Titik Ambil</span>
                    </div>
                    <?php if(strtolower($t->lokasi_ambil) == 'kantor'): ?>
                        <span class="inline-flex items-center px-2 py-1 rounded border border-gray-200 bg-gray-50 text-gray-600 font-medium">🏢 Ambil di Kantor</span>
                    <?php else: ?>
                        <div class="bg-emerald-50 border border-emerald-100 p-2 rounded text-emerald-800 leading-snug">
                            📍 <?php echo e($t->alamat_jemput ?? $t->alamat_lengkap); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="flex items-center gap-1 mb-1">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span class="font-bold text-gray-700 uppercase text-[10px]">Titik Kembali</span>
                    </div>
                    <?php if(strtolower($t->lokasi_kembali) == 'kantor'): ?>
                        <span class="inline-flex items-center px-2 py-1 rounded border border-gray-200 bg-gray-50 text-gray-600 font-medium">🏢 Kembali ke Kantor</span>
                    <?php else: ?>
                        <div class="bg-rose-50 border border-rose-100 p-2 rounded text-rose-800 leading-snug">
                            🏁 <?php echo e($t->alamat_antar ?? $t->alamat_lengkap); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </td>

        
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="text-sm font-bold text-gray-900">
                Rp <?php echo e(number_format($t->total_harga, 0, ',', '.')); ?>

            </span>
            <div class="text-[10px] text-gray-400 italic mt-1">
                <?php if($t->bukti_bayar): ?>
                    <span class="text-green-600">✓ Sudah Upload Bukti</span>
                <?php else: ?>
                    <span class="text-red-500">Belum Bayar</span>
                <?php endif; ?>
            </div>
        </td>

        
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex flex-col items-center gap-2">
                
                
                <?php
                    $status = $t->status;
                    
                    // Grup Pending
                    $isPending = in_array($status, ['Pending', null, '']);

                    // Grup Sukses (Tiket Muncul Disini)
                    // SAYA MENAMBAHKAN 'Disewa' DI SINI
                    $showTicket = in_array($status, ['Approved', 'Disetujui', 'Disewa', 'Selesai']);
                    
                    // Grup Chat Admin
                    $showChat = in_array($status, ['Approved', 'Disetujui', 'Disewa']);
                ?>

                
                <?php if($isPending): ?>
                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                <?php elseif($status == 'Selesai'): ?>
                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Selesai</span>
                <?php elseif($status == 'Disewa'): ?>
                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">Disewa</span>
                <?php else: ?>
                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800"><?php echo e($status); ?></span>
                <?php endif; ?>

                
                <?php if($isPending): ?>
                    
                    
                    <?php if(!$t->bukti_bayar): ?>
                        <button onclick="document.getElementById('upload-<?php echo e($t->id); ?>').classList.toggle('hidden')" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs transition w-full shadow-sm">
                            ⬆ Upload Bayar
                        </button>
                    <?php else: ?>
                        <span class="text-[10px] text-gray-400">Menunggu Konfirmasi</span>
                    <?php endif; ?>

                    
                    <form action="<?php echo e(route('transaksi.batal', $t->id)); ?>" method="POST" onsubmit="return confirm('Yakin batalkan pesanan?');" class="w-full">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <button type="submit" class="mt-1 border border-red-200 text-red-600 hover:bg-red-50 px-3 py-1 rounded text-xs transition w-full">
                            Batal
                        </button>
                    </form>

                    
                    <div id="upload-<?php echo e($t->id); ?>" class="hidden mt-2 p-3 bg-white border border-gray-200 rounded-lg text-left w-56 absolute right-10 z-20 shadow-xl">
                        <form action="<?php echo e(route('riwayat.upload', $t->id)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?> 
                            <label class="block text-[10px] font-bold text-gray-700 mb-2">Pilih Foto Bukti:</label>
                            <input type="file" name="bukti_bayar" class="text-[10px] w-full mb-3 border border-gray-300 rounded p-1 bg-gray-50" required>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-[10px] w-full font-bold shadow-sm">Kirim</button>
                                <button type="button" onclick="document.getElementById('upload-<?php echo e($t->id); ?>').classList.add('hidden')" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded text-[10px] font-bold border border-gray-300">Tutup</button>
                            </div>
                        </form>
                    </div>

                <?php else: ?>
                    
                    
                    <?php if($showChat): ?>
                        <a href="https://wa.me/<?php echo e(preg_replace('/^0/', '62', $t->no_hp)); ?>?text=Halo admin, saya mau tanya soal pesanan mobil <?php echo e($t->mobil->model ?? ''); ?> (Status: <?php echo e($status); ?>)" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs flex items-center justify-center gap-1 w-full shadow-sm transition mb-1">
                            <span>📞</span> Hubungi Admin
                        </a>
                    <?php endif; ?>

                    
                    <?php if($showTicket): ?>
                        <a href="<?php echo e(route('riwayat.cetak', $t->id)); ?>" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs flex items-center justify-center gap-1 w-full shadow-sm transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Tiket
                        </a>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
            <div class="flex flex-col items-center">
                <span class="text-4xl mb-2">📭</span>
                <p>Belum ada riwayat pesanan.</p>
                <a href="<?php echo e(route('pages.order')); ?>" class="mt-4 text-indigo-600 hover:underline font-bold text-sm">Sewa Mobil Sekarang →</a>
            </div>
        </td>
    </tr>
    <?php endif; ?>
</tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>
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