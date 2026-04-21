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
            <h2 class="font-bold text-2xl text-gray-800 leading-tight uppercase tracking-tight">
                Armada Saya
            </h2>
            <a href="<?php echo e(route('mitra.mobil.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-xs font-black uppercase shadow-lg transition-all">
                + Tambah Mobil
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 p-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex gap-4">
                        <a href="<?php echo e(route('mitra.mobil.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            + Tambah Mobil Baru
                    </a>
                </div>
            </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase">Foto</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase">Nama Mobil</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-300 uppercase">Harga/Hari</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-300 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $mobils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-blue-50/50 transition">
                            
                            
            <td class="px-6 py-4 w-32 h-24">
                <img src="<?php echo e($m->image_url); ?>"
                     alt="<?php echo e($m->merk); ?> <?php echo e($m->model); ?>"
                     class="w-full h-full object-contain relative z-10 group-hover:scale-110 transition-transform duration-500 drop-shadow-lg <?php echo e($m->status != 'tersedia' ? 'grayscale opacity-70' : ''); ?>"
                     onerror="this.src='https://placehold.co/150x100?text=Tanpa+Foto'">
            </td>

                            
                            <td class="px-6 py-4">
                                <div class="text-xl font-black text-gray-900 uppercase tracking-tight leading-none">
                                    <?php echo e($m->model); ?>

                                </div>
                                <div class="text-xs font-bold text-blue-600 uppercase mt-1 tracking-widest">
                                    <?php echo e($m->merk ?? $m->merek); ?> 
                                </div>
                            </td>
                            
                            
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm font-black text-blue-700">Rp <?php echo e(number_format($m->harga_sewa, 0, ',', '.')); ?></div>
                            </td>
                            
                            
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-[10px] font-black rounded-full <?php echo e($m->status == 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?> uppercase">
                                    <?php echo e($m->status); ?>

                                </span>
                            </td>
                            
                            
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="<?php echo e(route('mitra.mobil.edit', $m->id)); ?>" class="bg-amber-400 hover:bg-amber-500 text-white p-2 rounded-lg shadow-sm transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="<?php echo e(route('mitra.mobil.destroy', $m->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" onclick="return confirm('Hapus mobil ini?')" class="bg-rose-500 hover:bg-rose-600 text-white p-2 rounded-lg shadow-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 font-bold uppercase italic">Belum ada armada mobil.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/mitra/mobil/index.blade.php ENDPATH**/ ?>