<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveNow - Layanan Rental Mobil Premium</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <header class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-900 p-3 rounded-lg">
                            <i class="fas fa-car-side text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">DriveNow Rental Mobil</h1>
                            <p class="text-gray-500">Layanan rental mobil premium</p>
                        </div>
                    </div>
                </header>

            
            <div class="flex justify-center mb-8">
                <div class="bg-gray-200 p-1 rounded-full flex items-center space-x-1">
                    <a href="#" class="px-6 py-2 text-sm font-semibold text-gray-800 bg-white rounded-full shadow-sm">
                        <i class="fas fa-user mr-2"></i>Portal Pelanggan
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="px-6 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-300 rounded-full">
                        <i class="fas fa-shield-alt mr-2"></i>Panel Admin
                    </a>
                </div>
            </div>

            
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8 text-center shadow-sm">
                <h2 class="text-2xl font-semibold mb-2">Selamat Datang</h2>
                <p class="text-gray-600">Jelajahi armada kendaraan premium kami dan pesan mobil impian Anda</p>
            </div>

            
            <div>
                <div class="border-b border-gray-200">
                    
                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                        
                        
                        <a href="<?php echo e(route('homepage')); ?>" 
                        class="<?php echo e(request()->routeIs('homepage') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> py-4 px-1 border-b-2 font-medium">
                            Daftar Kendaraan
                        </a>
                        
                        
                        <a href="<?php echo e(route('booking.lacak.form')); ?>" 
                        class="<?php echo e(request()->routeIs('booking.lacak.form') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> py-4 px-1 border-b-2 font-medium">
                            Riwayat Pesanan
                        </a>
                    </nav>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center mt-6 mb-4">
    <h3 class="text-xl font-bold text-gray-900 mb-4 sm:mb-0">
        Kendaraan Tersedia
    </h3>
</div>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden group">
                        <div class="relative">
                            <img src="<?php echo e(asset('img/' . $car->gambar)); ?>" alt="<?php echo e($car->nama_mobil); ?>" class="h-56 w-full object-cover group-hover:opacity-80 transition-opacity duration-300">
                        </div>
                        <div class="p-5">
                            <h4 class="text-xl font-bold text-gray-900 truncate"><?php echo e($car->nama_mobil); ?></h4>
                            <p class="text-gray-600 mt-1">Harga mulai dari <span class="font-bold text-indigo-600">Rp <?php echo e(number_format($car->harga_sewa)); ?></span> / hari</p>
                            <a href="<?php echo e(route('car.show', $car->slug)); ?>" class="mt-4 inline-block w-full text-center bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-300">
                                Lihat Detail & Pesan
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center py-16 bg-white rounded-lg shadow-lg">
                        <i class="fas fa-car-crash text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-700">Oops! Belum Ada Mobil yang Tersedia</h3>
                        <p class="text-gray-500 mt-2">Silakan cek kembali nanti atau hubungi kami untuk informasi lebih lanjut.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">
                &copy; <?php echo e(date('Y')); ?> DriveNow Rental Mobil. Hak cipta dilindungi.
            </p>
        </div>
    </footer>
</body>
</html><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\frontend\homepage.blade.php ENDPATH**/ ?>