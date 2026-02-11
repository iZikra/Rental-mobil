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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <h2 class="text-2xl font-bold mb-6">Tambah Armada Baru</h2>

                    
                    <form action="<?php echo e(route('mitra.mobil.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Cabang</label>
                                <select name="branch_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->nama_cabang); ?> - <?php echo e($branch->kota); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Merk Mobil</label>
                                <input type="text" name="merk" placeholder="Contoh: Toyota" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Model / Tipe</label>
                                <input type="text" name="model" placeholder="Contoh: Avanza Veloz" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Plat</label>
                                <input type="text" name="no_plat" placeholder="BM 1234 XX" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tahun Pembuatan</label>
                                <input type="number" name="tahun_buat" value="2023" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Transmisi</label>
                                <select name="transmisi" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="Manual">Manual</option>
                                    <option value="Matic">Matic</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Bahan Bakar</label>
                                <select name="bahan_bakar" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="Bensin">Bensin</option>
                                    <option value="Solar">Solar</option>
                                    <option value="Listrik">Listrik</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Kursi</label>
                                <input type="number" name="jumlah_kursi" value="4" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga Sewa (Per Hari)</label>
                                <input type="number" name="harga_sewa" placeholder="350000" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Foto Mobil</label>
                                <input type="file" name="gambar" class="w-full border border-gray-300 rounded-md p-2" required>
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700 shadow-lg">
                                Simpan Mobil
                            </button>
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\mitra\mobil\create.blade.php ENDPATH**/ ?>