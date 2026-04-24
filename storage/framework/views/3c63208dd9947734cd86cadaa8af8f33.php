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
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin Sistem</h1>
                    <p class="text-gray-500 text-sm mt-1">Selamat datang, <span class="text-red-600 font-bold"><?php echo e(Auth::user()->name); ?></span> 👋 — Kelola ekosistem platform dari sini.</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center gap-3">
                    <span class="bg-red-50 text-red-700 text-xs font-bold px-3 py-1 rounded-full border border-red-100">
                        <?php echo e(now()->format('l, d F Y')); ?>

                    </span>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                
                <a href="<?php echo e(route('admin.rentals.index')); ?>" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 border-l-4 border-l-blue-500 hover:shadow-md transition block cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Mitra</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($totalMitra ?? 0); ?></p>
                            <p class="text-xs text-gray-400 mt-1">Vendor Aktif di Platform</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                </a>

                
                <a href="#" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 border-l-4 border-l-green-500 hover:shadow-md transition block cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Customer</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($totalCustomer ?? 0); ?></p>
                            <p class="text-xs text-gray-400 mt-1">Pengguna Terdaftar</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-full text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                </a>

                
                <a href="<?php echo e(route('admin.rentals.index')); ?>" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 border-l-4 border-l-amber-500 hover:shadow-md transition block cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Menunggu Verifikasi</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($mitraMenungguVerif ?? 0); ?></p>
                            <p class="text-xs text-gray-400 mt-1">Mitra Belum Diverifikasi</p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-full text-amber-500 <?php echo e(($mitraMenungguVerif ?? 0) > 0 ? 'animate-pulse' : ''); ?>">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </a>

                
                <a href="<?php echo e(route('admin.transaksi.index')); ?>" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 border-l-4 border-l-purple-500 hover:shadow-md transition block cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pendapatan</p>
                            <p class="text-lg font-bold text-gray-800 mt-1">Rp <?php echo e(number_format($pendapatan ?? 0, 0, ',', '.')); ?></p>
                            <p class="text-xs text-gray-400 mt-1">Dari Transaksi Selesai</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-full text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </a>

            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800">Riwayat Transaksi Terbaru</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Pantau aktivitas transaksi di seluruh mitra</p>
                        </div>
                        <a href="<?php echo e(route('admin.transaksi.index')); ?>" class="text-sm text-red-600 hover:underline font-medium">Audit Semua →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3">Penyewa</th>
                                    <th class="px-6 py-3">Mobil</th>
                                    <th class="px-6 py-3">Mitra</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $__empty_1 = true; $__currentLoopData = $recentOrders ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-800">
                                        <?php echo e($order->user->name ?? 'Guest'); ?>

                                        <div class="text-xs text-gray-400"><?php echo e($order->created_at->diffForHumans()); ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <?php echo e($order->mobil->merk ?? '-'); ?> <?php echo e($order->mobil->model ?? ''); ?>

                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-xs">
                                        <?php echo e($order->mobil->rental->nama_rental ?? '-'); ?>

                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php $st = strtolower($order->status ?? ''); ?>
                                        <?php if($st == 'pending'): ?>
                                            <span class="px-2 py-1 text-xs font-bold text-yellow-600 bg-yellow-100 rounded-full">Pending</span>
                                        <?php elseif($st == 'disewa'): ?>
                                            <span class="px-2 py-1 text-xs font-bold text-blue-600 bg-blue-100 rounded-full">Disewa</span>
                                        <?php elseif($st == 'selesai'): ?>
                                            <span class="px-2 py-1 text-xs font-bold text-green-600 bg-green-100 rounded-full">Selesai</span>
                                        <?php elseif($st == 'dikonfirmasi'): ?>
                                            <span class="px-2 py-1 text-xs font-bold text-indigo-600 bg-indigo-100 rounded-full">Dikonfirmasi</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs font-bold text-red-600 bg-red-100 rounded-full"><?php echo e($order->status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="space-y-4">
                    <div class="bg-slate-900 rounded-2xl p-6 text-white shadow-lg">
                        <h3 class="text-lg font-bold mb-1">Kendali Ekosistem</h3>
                        <p class="text-xs text-slate-400 mb-4">Aksi khusus Admin Sistem</p>
                        <div class="space-y-3">

                            <a href="<?php echo e(route('admin.rentals.index')); ?>" class="flex items-center p-3 bg-white/10 hover:bg-white/20 rounded-xl transition group relative z-10 cursor-pointer block w-full text-left">
                                <div class="flex items-center w-full">
                                    <div class="p-2 bg-amber-500 rounded-lg mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="flex-grow">
                                        <span class="font-semibold text-sm block">Verifikasi Mitra</span>
                                        <span class="text-xs text-slate-400">Approve / Block vendor baru</span>
                                    </div>
                                    <?php if(($mitraMenungguVerif ?? 0) > 0): ?>
                                        <span class="ml-auto bg-amber-500 text-white text-xs font-bold rounded-full px-2 py-0.5"><?php echo e($mitraMenungguVerif); ?></span>
                                    <?php endif; ?>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.branches.index')); ?>" class="flex items-center p-3 bg-white/10 hover:bg-white/20 rounded-xl transition group relative z-10 cursor-pointer block w-full text-left">
                                <div class="flex items-center w-full">
                                    <div class="p-2 bg-indigo-500 rounded-lg mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div class="flex-grow">
                                        <span class="font-semibold text-sm block">Manajemen Wilayah</span>
                                        <span class="text-xs text-slate-400">Kelola cabang & kota</span>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.transaksi.index')); ?>" class="flex items-center p-3 bg-white/10 hover:bg-white/20 rounded-xl transition group relative z-10 cursor-pointer block w-full text-left">
                                <div class="flex items-center w-full">
                                    <div class="p-2 bg-emerald-500 rounded-lg mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="flex-grow">
                                        <span class="font-semibold text-sm block">Audit Transaksi</span>
                                        <span class="text-xs text-slate-400">Pantau semua transaksi platform</span>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo e(route('admin.tentang_kami.index')); ?>" class="flex items-center p-3 bg-white/10 hover:bg-white/20 rounded-xl transition group relative z-10 cursor-pointer block w-full text-left">
                                <div class="flex items-center w-full">
                                    <div class="p-2 bg-rose-500 rounded-lg mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </div>
                                    <div class="flex-grow">
                                        <span class="font-semibold text-sm block">Konten Halaman</span>
                                        <span class="text-xs text-slate-400">Edit halaman Tentang Kami</span>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>

                    
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-xs text-blue-700">
                        <p class="font-bold mb-1">ℹ️ Peran Admin Sistem</p>
                        <ul class="space-y-1 text-blue-600 list-disc list-inside">
                            <li>Verifikasi & kelola Mitra (vendor)</li>
                            <li>Manajemen wilayah & cabang</li>
                            <li>Audit transaksi (read-only)</li>
                            <li>Kelola konten platform</li>
                        </ul>
                        <p class="mt-2 text-blue-400 italic">Approval/penolakan pesanan adalah wewenang Mitra.</p>
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>