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
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
                    <p class="text-gray-500 text-sm mt-1">Selamat datang kembali, <span class="text-red-600 font-bold"><?php echo e(Auth::user()->name); ?></span>! 👋</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="bg-red-50 text-red-700 text-xs font-bold px-3 py-1 rounded-full border border-red-100">
                        <?php echo e(now()->format('l, d F Y')); ?>

                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 border-l-4 border-l-blue-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Armada</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($totalMobil ?? 0); ?></p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 012-2v0a2 2 0 012 2m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V9a1 1 0 011-1h3m3 4a2 2 0 012-2v0a2 2 0 012 2m-6 0a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 border-l-4 border-l-green-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Unit Ready</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($mobilTersedia ?? 0); ?></p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-full text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 border-l-4 border-l-red-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Perlu Approval</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($transaksiBaru ?? 0); ?></p>
                        </div>
                        <div class="p-3 bg-red-50 rounded-full text-red-600 animate-pulse">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 border-l-4 border-l-purple-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pendapatan</p>
                            <p class="text-lg font-bold text-gray-800 mt-1">Rp <?php echo e(number_format($pendapatan ?? 0)); ?></p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-full text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Pesanan Masuk Terbaru</h3>
                        <a href="<?php echo e(route('admin.transaksi.index')); ?>" class="text-sm text-red-600 hover:underline font-medium">Lihat Semua →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3">Penyewa</th>
                                    <th class="px-6 py-3">Mobil</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $__empty_1 = true; $__currentLoopData = $recentOrders ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-800">
                                        <?php echo e($order->user->name ?? 'Guest'); ?>

                                        <div class="text-xs text-gray-400"><?php echo e($order->created_at->diffForHumans()); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo e($order->mobil->merk ?? '-'); ?> <?php echo e($order->mobil->model ?? ''); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if($order->status == 'Pending'): ?>
                                            <span class="px-2 py-1 text-xs font-bold text-yellow-600 bg-yellow-100 rounded-full">Pending</span>
                                        <?php elseif($order->status == 'Disewa'): ?>
                                            <span class="px-2 py-1 text-xs font-bold text-blue-600 bg-blue-100 rounded-full">Sedang Jalan</span>
                                        <?php elseif($order->status == 'Selesai'): ?>
                                            <span class="px-2 py-1 text-xs font-bold text-green-600 bg-green-100 rounded-full">Selesai</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs font-bold text-red-600 bg-red-100 rounded-full"><?php echo e($order->status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?php echo e(route('admin.transaksi.index')); ?>" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada pesanan masuk.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    
                    <div class="bg-slate-900 rounded-2xl p-6 text-white shadow-lg">
    <h3 class="text-lg font-bold mb-4">Kendali Ekosistem</h3>
    <div class="space-y-3">
        <a href="<?php echo e(route('admin.rentals.index')); ?>" class="flex items-center p-3 bg-white/10 hover:bg-white/20 rounded-xl transition group">
            <div class="p-2 bg-amber-500 rounded-lg mr-3">
                <i class="fas fa-users-cog"></i>
            </div>
            <span class="font-semibold text-sm">Verifikasi Mitra Baru</span>
        </a>

        <a href="<?php echo e(route('admin.branches.index')); ?>" class="flex items-center p-3 bg-white/10 hover:bg-white/20 rounded-xl transition group">
            <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <span class="font-semibold text-sm">Manajemen Wilayah</span>
        </a>

        <a href="<?php echo e(route('admin.transaksi.index')); ?>" class="flex items-center p-3 bg-white/10 hover:bg-white/20 rounded-xl transition group">
            <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <span class="font-semibold text-sm">Audit Transaksi Global</span>
        </a>
    </div>
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>