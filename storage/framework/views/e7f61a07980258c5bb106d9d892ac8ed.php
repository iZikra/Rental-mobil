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
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <?php if(session('success')): ?>
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="text-center mb-12 relative">
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl">
                    Kenalan dengan <span class="text-blue-600">Rental Kami</span>
                </h1>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Partner perjalanan terbaik Anda.
                </p>

                <?php if(Auth::check() && Auth::user()->role == 'admin'): ?>
                    <div class="mt-6">
                        <a href="<?php echo e(route('admin.tentang_kami.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg transform hover:scale-105">
                            + Tambah Seksi Baru
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="grid gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300 relative group">
                        
                        <div class="p-8 border-l-4 border-blue-500">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <?php echo e($item->judul); ?>

                            </h3>
                            <div class="prose max-w-none text-gray-600 leading-relaxed whitespace-pre-line">
                                <?php echo e($item->isi); ?>

                            </div>
                        </div>

                        <?php if(Auth::check() && Auth::user()->role == 'admin'): ?>
                            <div class="absolute top-4 right-4 flex space-x-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                <a href="<?php echo e(route('admin.tentang_kami.edit', $item->id)); ?>" class="bg-yellow-400 text-white p-2 rounded hover:bg-yellow-500 shadow-sm" title="Edit">
                                    ✏️
                                </a>
                                
                                <form action="<?php echo e(route('admin.tentang_kami.destroy', $item->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus seksi ini?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="bg-red-500 text-white p-2 rounded hover:bg-red-600 shadow-sm" title="Hapus">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <p class="text-gray-500">Belum ada konten.</p>
                        <?php if(Auth::check() && Auth::user()->role == 'admin'): ?>
                            <p class="text-sm text-blue-500 mt-2">Silakan klik tombol tambah di atas.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-20">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-10">Tim Di Balik Layar</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                   <div class="bg-white p-6 rounded-lg shadow-sm"><p>CEO & Founder</p></div>
                   <div class="bg-white p-6 rounded-lg shadow-sm"><p>Manager</p></div>
                   <div class="bg-white p-6 rounded-lg shadow-sm"><p>CS Support</p></div>
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\pages\tentang_kami.blade.php ENDPATH**/ ?>