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
            <?php echo e(__('Edit Data Mobil')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="<?php echo e(route('mobils.update', $mobil->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?> <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Merk Mobil</label>
                            <input type="text" name="merk" value="<?php echo e(old('merk', $mobil->merk)); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Model / Tipe</label>
                            <input type="text" name="model" value="<?php echo e(old('model', $mobil->model)); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Plat</label>
                            <input type="text" name="no_plat" value="<?php echo e(old('no_plat', $mobil->no_plat)); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Harga Sewa per Hari (Rp)</label>
                            <input type="number" name="harga_sewa" value="<?php echo e(old('harga_sewa', $mobil->harga_sewa)); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Foto Mobil (Opsional)</label>
                            
                            <?php if($mobil->gambar): ?>
                                <div class="mb-2">
                                    <span class="text-xs text-gray-500">Foto Saat Ini:</span>
                                    <img src="<?php echo e(asset('img/' . $mobil->gambar)); ?>" alt="Foto Lama" class="h-20 w-auto rounded mt-1 border border-gray-300">
                                </div>
                            <?php endif; ?>
                            
                            <input type="file" name="gambar" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-violet-50 file:text-violet-700
                                hover:file:bg-violet-100">
                            <p class="text-xs text-gray-500 mt-1">*Biarkan kosong jika tidak ingin mengganti foto.</p>
                        </div>

                        <div class="flex items-center justify-between mt-6">
        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300">
                            💾 Simpan Perubahan
                        </button>

                        <a href="<?php echo e(route('mobils.index')); ?>" class="text-gray-500 hover:text-red-500 font-bold text-sm transition">
                            ✕ Batal
                        </a>
                    </div>

                </form>
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\admin\mobil\edit.blade.php ENDPATH**/ ?>