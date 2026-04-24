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
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Tambah Kendaraan Baru</h2>
        <a href="<?php echo e(route('cars.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8">
        <?php if($errors->any()): ?>
            
        <?php endif; ?>

        <form action="<?php echo e(route('cars.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <h3 class="text-lg font-semibold border-b pb-2 mb-4">Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="nama_mobil" class="block text-sm font-medium text-gray-700">Nama Mobil</label>
                    <input type="text" name="nama_mobil" id="nama_mobil" value="<?php echo e(old('nama_mobil')); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="merek" class="block text-sm font-medium text-gray-700">Merek Mobil</label>
                    <input type="text" name="merek" id="merek" value="<?php echo e(old('merek')); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                 <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type (Contoh: Small MPV)</label>
                    <input type="text" name="type" id="type" value="<?php echo e(old('type')); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="jumlah_kursi" class="block text-sm font-medium text-gray-700">Jumlah Kursi</label>
                    <input type="number" name="jumlah_kursi" id="jumlah_kursi" value="<?php echo e(old('jumlah_kursi')); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="transmisi" class="block text-sm font-medium text-gray-700">Transmisi</label>
                    <select name="transmisi" id="transmisi" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="Manual">Manual</option>
                        <option value="Otomatis">Otomatis</option>
                    </select>
                </div>
                <div>
                    <label for="bahan_bakar" class="block text-sm font-medium text-gray-700">Bahan Bakar</label>
                    <input type="text" name="bahan_bakar" id="bahan_bakar" value="<?php echo e(old('bahan_bakar')); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                 <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="tersedia">Tersedia</option>
                        <option value="tidak tersedia">Tidak Tersedia</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><?php echo e(old('deskripsi')); ?></textarea>
                </div>
                 <div class="md:col-span-3">
                    <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar Mobil</label>
                    <input type="file" name="gambar" id="gambar" required class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                </div>
            </div>

            
            <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-8">Harga Mobil Saja (Rp)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <label for="harga_mobil_12h" class="block text-sm font-medium text-gray-700">12 Jam</label>
                    <input type="number" name="harga_mobil_12h" value="<?php echo e(old('harga_mobil_12h', 0)); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="harga_mobil_24h" class="block text-sm font-medium text-gray-700">24 Jam / Hari</label>
                    <input type="number" name="harga_mobil_24h" value="<?php echo e(old('harga_mobil_24h', 0)); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
            
            
            <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-8">Harga Mobil + Driver (Rp)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                 <div>
                    <label for="harga_driver_12h" class="block text-sm font-medium text-gray-700">12 Jam</label>
                    <input type="number" name="harga_driver_12h" value="<?php echo e(old('harga_driver_12h', 0)); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="harga_driver_24h" class="block text-sm font-medium text-gray-700">24 Jam / Hari</label>
                    <input type="number" name="harga_driver_24h" value="<?php echo e(old('harga_driver_24h', 0)); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            
            <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-8">(Mobil + Sopir + Bensin) (Rp)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                 <div>
                    <label for="harga_bbm_12h" class="block text-sm font-medium text-gray-700">12 Jam</label>
                    <input type="number" name="harga_bbm_12h" value="<?php echo e(old('harga_bbm_12h', 0)); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="harga_bbm_24h" class="block text-sm font-medium text-gray-700">24 Jam / Hari</label>
                    <input type="number" name="harga_bbm_24h" value="<?php echo e(old('harga_bbm_24h', 0)); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            
            <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-8">(Mobil + Sopir + Bensin + Parkir + Makan Sopir) (Rp)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                 <div>
                    <label for="harga_allin_12h" class="block text-sm font-medium text-gray-700">12 Jam</label>
                    <input type="number" name="harga_allin_12h" value="<?php echo e(old('harga_allin_12h', 0)); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="harga_allin_24h" class="block text-sm font-medium text-gray-700">24 Jam / Hari</label>
                    <input type="number" name="harga_allin_24h" value="<?php echo e(old('harga_allin_24h', 0)); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            
            <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-8">Fitur Tambahan</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                 <div>
                    <label for="p3k" class="block text-sm font-medium text-gray-700">P3K</label>
                    <select name="p3k" id="p3k" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="ya">Ya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>
                 <div>
                    <label for="ac" class="block text-sm font-medium text-gray-700">AC</label>
                    <select name="ac" id="ac" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="ya">Ya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>
                 <div>
                    <label for="audio" class="block text-sm font-medium text-gray-700">Audio</label>
                    <select name="audio" id="audio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="ya">Ya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>
                 <div>
                    <label for="charger" class="block text-sm font-medium text-gray-700">Charger</label>
                    <select name="charger" id="charger" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="ya">Ya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>
            </div>


            <div class="mt-8 text-right">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Simpan Kendaraan
                </button>
            </div>
        </form>
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\admin\cars\create.blade.php ENDPATH**/ ?>