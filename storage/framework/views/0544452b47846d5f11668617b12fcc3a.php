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
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                <?php echo e(__('Kelola Transaksi')); ?>

            </h2>
            <div class="text-sm text-gray-500">
                Verifikasi pembayaran dan status sewa di sini.
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="mb-6 bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-wrap items-center gap-3">
                <span class="text-gray-400 font-bold text-xs uppercase tracking-wider mr-2">Status:</span>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold flex items-center gap-1">
                    <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span> Perlu Cek
                </span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">Disewa</span>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Selesai</span>
                
                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Dibatalkan/Ditolak</span>
            </div>

            <?php if(session('success')): ?>
                <div class="mb-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg font-bold flex items-center gap-2">
                    <i class="fa-solid fa-check-circle"></i> <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 align-middle">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Info Penyewa</th>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Armada</th>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Durasi & Biaya</th>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Logistik Unit</th>
                                <th class="px-6 py-5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Identitas</th>
                                <th class="px-6 py-5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Bukti Bayar</th>
                                <th class="px-6 py-5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Aksi Admin</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?> 
                            <tr class="hover:bg-slate-50 transition duration-200 group">
                                
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-bold text-sm shadow-md group-hover:scale-105 transition">
                                            <?php echo e(substr($t->user->name ?? '?', 0, 1)); ?>

                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-800"><?php echo e($t->user->name ?? 'User Dihapus'); ?></div>
                                            <div class="flex items-center gap-1 text-xs text-gray-500 mt-0.5">
                                                <?php echo e($t->no_hp ?? '-'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-800"><?php echo e($t->mobil->merk ?? 'Mobil'); ?> <?php echo e($t->mobil->model ?? 'Dihapus'); ?></span>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded border border-gray-200 font-mono">
                                                <?php echo e($t->mobil->no_plat ?? 'N/A'); ?>

                                            </span>
                                            <?php if($t->sopir == 'dengan_sopir'): ?>
                                                <span class="text-[10px] bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100 font-bold">👮 Pakai Sopir</span>
                                            <?php else: ?>
                                                <span class="text-[10px] bg-gray-50 text-gray-400 px-2 py-0.5 rounded border border-gray-200">🔑 Lepas Kunci</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <div class="flex items-center text-xs text-gray-500">
                                            <span class="w-12">Mulai:</span> 
                                            <span class="font-semibold text-gray-700"><?php echo e(\Carbon\Carbon::parse($t->tgl_ambil)->format('d/m/y')); ?></span>
                                        </div>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <span class="w-12">Selesai:</span>
                                            <span class="font-semibold text-gray-700"><?php echo e(\Carbon\Carbon::parse($t->tgl_kembali)->format('d/m/y')); ?></span>
                                        </div>
                                        <div class="pt-1">
                                            <span class="text-sm font-extrabold text-red-600 bg-red-50 px-2 py-0.5 rounded">
                                                Rp <?php echo e(number_format($t->total_harga, 0, ',', '.')); ?>

                                            </span>
                                        </div>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                    <div class="flex flex-col gap-3 text-xs">
                                        <div class="relative pl-4 border-l-2 border-indigo-400">
                                            <span class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-indigo-500"></span>
                                            <span class="font-bold text-gray-500 uppercase text-[10px]">Titik Ambil:</span>
                                            <p class="font-bold text-gray-800"><?php echo e($t->lokasi_jemput ?? 'Di Kantor FZ Rent'); ?></p>
                                        </div>
                                        <div class="relative pl-4 border-l-2 border-green-400">
                                            <span class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-green-500"></span>
                                            <span class="font-bold text-gray-500 uppercase text-[10px]">Titik Kembali:</span>
                                            <p class="font-bold text-gray-800"><?php echo e($t->lokasi_kembali ?? ($t->lokasi_jemput ?? 'Di Kantor FZ Rent')); ?></p>
                                        </div>
                                        <div class="mt-2 pt-2 border-t border-gray-100">
                                            <span class="font-bold text-gray-500 uppercase text-[9px]">Alamat Rumah User:</span>
                                            <p class="text-gray-700 text-[11px] font-medium leading-snug whitespace-normal max-w-[200px] bg-gray-50 p-2 rounded border border-gray-100">
                                                <?php echo e($t->user->alamat ?? 'User belum melengkapi data alamat.'); ?>

                                            </p>
                                        </div>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <?php $fotoIdentitas = $t->foto_identitas ?? $t->user->identitas_foto ?? null; ?>
                                    <?php if($fotoIdentitas): ?>
                                        <?php
                                            $urlIdentitas = str_contains($fotoIdentitas, '/') 
                                                ? route('storage.view', ['folder' => explode('/', $fotoIdentitas)[0], 'filename' => explode('/', $fotoIdentitas)[1]])
                                                : route('storage.view', ['folder' => 'identitas', 'filename' => $fotoIdentitas]);
                                        ?>
                                        <div class="flex flex-col items-center gap-2">
                                            <img src="<?php echo e($urlIdentitas); ?>" class="w-10 h-8 object-cover rounded cursor-pointer border border-gray-300 hover:scale-150 transition relative" onclick="window.open('<?php echo e($urlIdentitas); ?>', '_blank')">
                                            <a href="<?php echo e($urlIdentitas); ?>" target="_blank" class="text-[10px] text-blue-600 hover:underline">Lihat</a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-red-500 text-[10px] italic">Belum Upload</span>
                                    <?php endif; ?>
                                </td>

                                
                                <td class="px-6 py-4 text-center align-middle">
                                    <?php if($t->bukti_bayar): ?>
                                        <?php
                                            $urlBukti = str_contains($t->bukti_bayar, '/')
                                                ? route('storage.view', ['folder' => explode('/', $t->bukti_bayar)[0], 'filename' => explode('/', $t->bukti_bayar)[1]])
                                                : route('storage.view', ['folder' => 'bukti_bayar', 'filename' => $t->bukti_bayar]);
                                        ?>
                                        <a href="<?php echo e($urlBukti); ?>" target="_blank" class="relative group inline-block">
                                            <img src="<?php echo e($urlBukti); ?>" class="w-10 h-10 object-cover rounded-lg border border-gray-200 shadow-sm transition transform group-hover:scale-125" alt="Bukti">
                                            <div class="mt-1"><span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full border border-green-100">Ada File</span></div>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-[10px] text-gray-400 italic bg-gray-50 px-2 py-1 rounded border border-gray-100">Belum Ada</span>
                                    <?php endif; ?>
                                </td>

                                
                                <td class="px-6 py-4 text-center">
                                    <?php 
                                        $statusRaw = strtolower($t->status ?? ''); 
                                        
                                        $isVerifikasi = in_array($statusRaw, ['perlu cek', 'menunggu konfirmasi', 'verifikasi']) || ($statusRaw == 'pending' && $t->bukti_bayar);
                                        $isActive = in_array($statusRaw, ['disewa', 'approved', 'sedang disewa']);
                                        $isDone = in_array($statusRaw, ['selesai', 'finished']);
                                        // Deteksi Status Pembatalan/Penolakan
                                        $isCancelled = in_array($statusRaw, ['dibatalkan', 'ditolak', 'cancelled', 'rejected']);
                                    ?>

                                    <?php if($isCancelled): ?>
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-black uppercase border border-red-200 block shadow-sm">
                                                <?php echo e($statusRaw == 'dibatalkan' ? '🚫 Dibatalkan User' : '❌ Pesanan Ditolak'); ?>

                                            </span>
                                            <span class="text-[9px] text-gray-400 italic">Unit kembali tersedia</span>
                                        </div>

                                    <?php elseif($isVerifikasi): ?>
                                        <div class="flex flex-col gap-2">
                                            <form action="<?php echo e(route('admin.transaksi.approve', $t->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-1.5 px-3 rounded text-xs transition shadow-sm flex items-center justify-center gap-1" onclick="return confirm('Bukti bayar valid? Terima pesanan?')">
                                                    <span>✓</span> Terima
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('admin.transaksi.reject', $t->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="w-full bg-white border border-red-200 text-red-500 hover:bg-red-50 font-bold py-1.5 px-3 rounded text-xs transition shadow-sm flex items-center justify-center gap-1" onclick="return confirm('Tolak pesanan ini?')">
                                                    <span>✕</span> Tolak
                                                </button>
                                            </form>
                                            <div class="mt-1">
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[9px] font-black uppercase tracking-wide animate-pulse border border-yellow-200 block">⏳ Perlu Cek</span>
                                            </div>
                                        </div>

                                    <?php elseif($isActive): ?>
                                        <form action="<?php echo e(route('admin.transaksi.complete', $t->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md hover:shadow-lg transition w-full flex items-center justify-center gap-1" onclick="return confirm('Mobil sudah kembali?')">
                                                🏁 Selesai
                                            </button>
                                        </form>
                                        <span class="block mt-1 text-[10px] text-blue-500 font-medium italic">Unit sedang jalan</span>

                                    <?php elseif($isDone): ?>
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase border border-green-200 block">✅ Selesai</span>

                                    <?php else: ?>
                                        
                                        <div class="flex flex-col items-center">
                                            <span class="text-[10px] text-gray-400 italic">Menunggu user upload...</span>
                                            <span class="block mt-1 px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[9px] border border-gray-200">Pending</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-2"></i>
                                        <p>Belum ada transaksi masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <?php echo e($transaksis->links()); ?>

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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\admin\transaksi\index.blade.php ENDPATH**/ ?>