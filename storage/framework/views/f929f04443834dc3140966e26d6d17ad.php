<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Sewa Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-center mb-8 text-blue-700">Katalog Sewa Mobil Multi-Tenant</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8 border border-gray-200">
        <form action="<?php echo e(route('katalog.index')); ?>" method="GET" class="flex flex-col md:flex-row items-center gap-4 justify-center">
            <label for="kota" class="font-bold text-lg">📍 Filter berdasarkan Kota:</label>
            
            <select name="kota" id="kota" class="border-2 border-blue-400 p-2 rounded-md w-full md:w-1/3 text-lg cursor-pointer" onchange="this.form.submit()">
                <option value="">-- Tampilkan Semua Kota --</option>
                
                <?php $__currentLoopData = $daftarKota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($kota); ?>" <?php echo e(request('kota') == $kota ? 'selected' : ''); ?>>
                        <?php echo e($kota); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            
            </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $mobils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mobil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white border rounded-lg p-5 shadow-lg relative overflow-hidden">
                <span class="absolute top-0 right-0 bg-green-500 text-white px-3 py-1 text-sm font-bold rounded-bl-lg">Tersedia</span>
                
                <h3 class="text-2xl font-black mt-4"><?php echo e($mobil->merk); ?> <?php echo e($mobil->model); ?></h3>
                <p class="text-gray-500 mb-2">Tahun: <?php echo e($mobil->tahun_buat); ?></p>
                <p class="text-blue-600 font-extrabold text-xl mb-4">
                    Rp <?php echo e(number_format($mobil->harga_sewa, 0, ',', '.')); ?> <span class="text-sm text-gray-500 font-normal">/ hari</span>
                </p>
                
                <hr class="my-4 border-gray-300">
                
                <p class="text-md mb-1">📍 Lokasi Cabang: <strong class="text-red-600"><?php echo e($mobil->branch->kota); ?></strong></p>
                <p class="text-md mb-6">🏢 Mitra Rental: <strong><?php echo e($mobil->rental->nama_rental); ?></strong></p>
                
                <button onclick="alert('Fitur Booking menuju mobil ID: <?php echo e($mobil->id); ?>')" class="block w-full text-center bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-800 transition">
                    Pilih Mobil Ini
                </button>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 bg-red-100 text-red-700 text-center p-6 rounded-lg font-bold border border-red-300">
                ⚠️ Maaf, saat ini tidak ada unit mobil yang tersedia di lokasi "<?php echo e(request('kota')); ?>".
            </div>
        <?php endif; ?>
    </div>

</div>
</body>
</html><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\katalog\index.blade.php ENDPATH**/ ?>