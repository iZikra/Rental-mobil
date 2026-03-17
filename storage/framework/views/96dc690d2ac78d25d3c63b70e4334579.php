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
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                <?php echo e(__('Manajemen Pesanan Mitra')); ?>

            </h2>
            <div class="flex items-center gap-2">
                <span class="flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <p class="text-sm font-medium text-gray-500">Live Updates</p>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-emerald-500 text-white font-bold rounded shadow-lg">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-4 p-4 bg-red-500 text-white font-bold rounded shadow-lg">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-900 text-white">
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest rounded-tl-xl">Pelanggan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest">Unit Mobil</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest">Dokumen & Bayar</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest">Total Harga</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest">Status Saat Ini</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest rounded-tr-xl">Aksi Konfirmasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php $__empty_1 = true; $__currentLoopData = $pesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-blue-50/50 transition duration-200">
                                    
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold uppercase">
                                                <?php echo e(substr($p->nama ?? ($p->user->name ?? 'U'), 0, 1)); ?>

                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-black text-gray-900"><?php echo e($p->nama ?? ($p->user->name ?? 'No Name')); ?></div>
                                                <div class="text-[11px] text-blue-600 font-medium"><?php echo e($p->no_hp ?? '-'); ?></div>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <?php if($p->mobil): ?>
                                            <div class="text-sm font-bold text-gray-900 uppercase tracking-tight">
                                                <?php echo e($p->mobil->merk ?? 'UNIT UNKNOWN'); ?>

                                            </div>
                                            <div class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase mt-1">
                                                <?php echo e($p->mobil->no_plat ?? 'NO PLAT'); ?>

                                            </div>
                                        <?php else: ?>
                                            <span class="text-sm font-bold text-gray-900 uppercase tracking-tight"><?php echo e($m->merk); ?> <?php echo e($m->model); ?></span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    
<td class="px-6 py-5 whitespace-nowrap text-center">
    <div class="flex flex-col items-center gap-3">
        
        
        <div class="flex items-center gap-3 justify-center">
            
            <?php if($p->foto_identitas): ?>
                <a href="<?php echo e(asset('storage/' . $p->foto_identitas)); ?>" target="_blank" class="group relative transform hover:scale-110 transition duration-200">
                    <img src="<?php echo e(asset('storage/' . $p->foto_identitas)); ?>" class="h-10 w-14 object-cover rounded border border-gray-300 group-hover:border-blue-500 shadow-sm" alt="KTP">
                    <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow">KTP</span>
                </a>
            <?php endif; ?>

            
            <?php if($p->foto_sim): ?>
                <a href="<?php echo e(asset('storage/' . $p->foto_sim)); ?>" target="_blank" class="group relative transform hover:scale-110 transition duration-200">
                    <img src="<?php echo e(asset('storage/' . $p->foto_sim)); ?>" class="h-10 w-14 object-cover rounded border border-gray-300 group-hover:border-emerald-500 shadow-sm" alt="SIM">
                    <span class="absolute -top-2 -right-2 bg-emerald-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow">SIM</span>
                </a>
            <?php endif; ?>
        </div>

        
        <?php if($p->bukti_bayar): ?>
            <a href="<?php echo e(asset('storage/' . $p->bukti_bayar)); ?>" target="_blank" class="text-[10px] font-black text-emerald-600 hover:text-emerald-800 hover:underline flex items-center gap-1 uppercase transition">
                <i class="fa-solid fa-receipt"></i> Lihat Bukti Bayar
            </a>
        <?php else: ?>
            <span class="text-[9px] text-gray-400 font-bold uppercase italic bg-gray-100 px-2 py-1 rounded">Belum Ada Bukti</span>
        <?php endif; ?>

    </div>
</td>

                                    
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        <div class="text-sm font-black text-gray-900">Rp <?php echo e(number_format($p->total_harga, 0, ',', '.')); ?></div>
                                        <div class="text-[9px] text-gray-400 font-bold uppercase"><?php echo e($p->lama_sewa ?? 0); ?> Hari</div>
                                    </td>

                                    
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        <?php
                                            $stRaw = strtolower(trim($p->status));
                                            $color = match($stRaw) {
                                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                'disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                'selesai' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                default => 'bg-gray-100 text-gray-700 border-gray-200'
                                            };
                                        ?>
                                        <span class="px-3 py-1 inline-flex text-[10px] font-black rounded-full border shadow-sm <?php echo e($color); ?> uppercase">
                                            <?php echo e($p->status); ?>

                                        </span>
                                    </td>

                                    
                                    
<td class="px-6 py-5 whitespace-nowrap text-center">
    <div class="flex flex-col items-center justify-center gap-2">
        
        <?php $stRaw = strtolower(trim($p->status)); ?>

        
        <?php if($stRaw == 'pending' || $stRaw == 'dibayar'): ?>
            <form action="<?php echo e(route('mitra.pesanan.konfirmasi', $p->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase shadow-lg transition-all active:scale-95">
                    Setujui
                </button>
            </form>

            <form action="<?php echo e(route('mitra.pesanan.tolak', $p->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" onclick="return confirm('Tolak pesanan ini?')" class="w-full bg-white border-2 border-rose-500 text-rose-500 px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all">
                    Tolak
                </button>
            </form>

        <?php elseif($stRaw == 'disetujui' || $stRaw == 'dikonfirmasi'): ?>
            <form action="<?php echo e(route('mitra.pesanan.selesai', $p->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase shadow-lg transition-all">
                    Selesai Sewa
                </button>
            </form>
        <?php else: ?>
            <span class="text-gray-300 text-[10px] font-bold italic uppercase">Status: <?php echo e($stRaw); ?></span>
        <?php endif; ?>
    </div>
</td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center text-gray-400 font-bold uppercase tracking-widest">
                                        Belum Ada Data Pesanan Masuk
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/mitra/pesanan/index.blade.php ENDPATH**/ ?>