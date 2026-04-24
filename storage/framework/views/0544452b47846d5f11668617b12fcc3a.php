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
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Audit Transaksi Global</h2>
                <p class="text-sm text-gray-400 mt-0.5">Pantau seluruh aktivitas transaksi di semua mitra — hanya bisa dilihat.</p>
            </div>
            <a href="<?php echo e(route('dashboard')); ?>" class="text-sm text-red-600 hover:underline font-medium flex items-center gap-1">
                ← Kembali ke Dashboard
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            
            <div class="mb-5 bg-blue-50 border border-blue-200 rounded-xl px-5 py-3 flex items-center gap-3 text-sm text-blue-700">
                <svg class="w-5 h-5 flex-shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Halaman ini bersifat <strong>read-only</strong>. Approval, konfirmasi, dan penolakan pesanan adalah wewenang <strong>Mitra</strong> masing-masing.</span>
            </div>

            
            <div class="mb-6 bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-wrap items-center gap-3">
                <span class="text-gray-400 font-bold text-xs uppercase tracking-wider mr-2">Status:</span>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold flex items-center gap-1">
                    <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span> Pending
                </span>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-bold">Dikonfirmasi</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">Disewa</span>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Selesai</span>
                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Dibatalkan / Ditolak</span>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 align-middle">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Info Penyewa</th>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Armada</th>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Mitra</th>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Durasi & Biaya</th>
                                <th class="px-6 py-5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50 transition duration-200 group">

                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                            <?php echo e(substr($t->user->name ?? '?', 0, 1)); ?>

                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-800"><?php echo e($t->user->name ?? 'User Dihapus'); ?></div>
                                            <div class="text-xs text-gray-400 mt-0.5"><?php echo e($t->no_hp ?? $t->user->no_hp ?? '-'); ?></div>
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
                                    <span class="text-sm font-semibold text-gray-700"><?php echo e($t->mobil->rental->nama_rental ?? '-'); ?></span>
                                </td>

                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <div class="text-xs text-gray-500">
                                            <span class="font-semibold text-gray-700"><?php echo e(\Carbon\Carbon::parse($t->tgl_ambil)->format('d/m/y')); ?></span>
                                            <span class="mx-1 text-gray-300">→</span>
                                            <span class="font-semibold text-gray-700"><?php echo e(\Carbon\Carbon::parse($t->tgl_kembali)->format('d/m/y')); ?></span>
                                        </div>
                                        <span class="text-sm font-extrabold text-red-600 bg-red-50 px-2 py-0.5 rounded inline-block w-fit">
                                            Rp <?php echo e(number_format($t->total_harga, 0, ',', '.')); ?>

                                        </span>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <?php $statusRaw = strtolower($t->status ?? ''); ?>
                                    <?php if($statusRaw == 'pending'): ?>
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200">⏳ Pending</span>
                                    <?php elseif($statusRaw == 'dikonfirmasi'): ?>
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold border border-indigo-200">✅ Dikonfirmasi</span>
                                    <?php elseif($statusRaw == 'disewa'): ?>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold border border-blue-200">🚗 Disewa</span>
                                    <?php elseif($statusRaw == 'selesai'): ?>
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">🏁 Selesai</span>
                                    <?php elseif($statusRaw == 'dibatalkan'): ?>
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200">🚫 Dibatalkan</span>
                                    <?php elseif($statusRaw == 'ditolak'): ?>
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200">❌ Ditolak</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold border border-gray-200"><?php echo e($t->status); ?></span>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <p>Belum ada transaksi.</p>
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
<?php endif; ?>
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\admin\transaksi\index.blade.php ENDPATH**/ ?>