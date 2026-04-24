<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail - <?php echo e($car->nama_mobil); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <header class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <a href="<?php echo e(route('homepage')); ?>" class="flex items-center space-x-4">
                        <div class="bg-gray-900 p-3 rounded-lg">
                            <i class="fas fa-car-side text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">DriveNow Rental Mobil</h1>
                            <p class="text-gray-500">Layanan rental mobil premium</p>
                        </div>
                    </a>
                </div>
            </header>
            <?php if(session('success')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                    <p class="font-bold">Booking Berhasil!</p>
                    <p><?php echo e(session('success')); ?></p>
                    
                    <?php if(Str::contains(session('success'), 'kode booking')): ?>
                        <p class="mt-2 text-sm">Harap catat kode ini untuk melacak pesanan Anda.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
            <?php endif; ?>
            
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="p-4">
                        <img src="<?php echo e(asset('img/' . $car->gambar)); ?>" alt="<?php echo e($car->nama_mobil); ?>" class="w-full h-auto rounded-lg object-cover">
                    </div>

                    <div class="p-8">
                        <h1 class="text-4xl font-bold text-gray-900 mb-2"><?php echo e($car->nama_mobil); ?></h1>
                        <p class="text-2xl font-semibold text-indigo-600 mb-6">
                            Rp <?php echo e(number_format($car->harga_sewa)); ?> <span class="text-lg font-normal text-gray-500">/ hari</span>
                        </p>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Deskripsi</h3>
                        <p class="text-gray-600 mb-6">
                            <?php echo e($car->deskripsi); ?>

                        </p>

                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Spesifikasi Utama</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center space-x-2 text-gray-700">
                                <i class="fas fa-user-friends w-5 text-center"></i>
                                <span><?php echo e($car->jumlah_kursi); ?> Kursi</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-700">
                                <i class="fas fa-cogs w-5 text-center"></i>
                                <span><?php echo e($car->transmisi); ?></span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-700">
                                <i class="fas fa-gas-pump w-5 text-center"></i>
                                <span><?php echo e($car->bahan_bakar); ?></span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-700">
                                <i class="fas fa-fan w-5 text-center"></i>
                                <span><?php echo e($car->ac == 'ya' ? 'AC Tersedia' : 'Tanpa AC'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-lg shadow-lg p-8 mt-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Formulir Reservasi Rental Mobil</h2>

                
                <?php if($errors->any()): ?>
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <p class="font-bold">Oops! Ada beberapa kesalahan:</p>
                        <ul class="list-disc pl-5 mt-2">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo e(route('booking.store')); ?>" method="POST" 
                x-data="{ tipe_pengambilan: 'kantor', tipe_pengembalian: 'kantor' }">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="car_id" value="<?php echo e($car->id); ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        
                        <div>
                            <label for="nama_customer" class="block text-sm font-medium text-gray-700">Nama Lengkap Anda</label>
                            <input type="text" name="nama_customer" id="nama_customer" value="<?php echo e(old('nama_customer')); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="telp_customer" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" name="telp_customer" id="telp_customer" value="<?php echo e(old('telp_customer')); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="alamat_customer" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                            <input type="text" name="alamat_customer" id="alamat_customer" value="<?php echo e(old('alamat_customer')); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                       
                        <div class="md:col-span-1">
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai Sewa</label>
                            <div class="flex space-x-2 mt-1">
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo e(old('tanggal_mulai')); ?>" required class="block w-2/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <input type="time" name="jam_mulai" id="jam_mulai" value="<?php echo e(old('jam_mulai', '09:00')); ?>" required class="block w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        
                        <div class="md:col-span-1">
                            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai Sewa</label>
                            <div class="flex space-x-2 mt-1">
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="<?php echo e(old('tanggal_selesai')); ?>" required class="block w-2/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <input type="time" name="jam_selesai" id="jam_selesai" value="<?php echo e(old('jam_selesai', '09:00')); ?>" required class="block w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        
                        <div class="md:col-span-2 mb-4">
                            <label class="block text-lg font-semibold text-gray-800 mb-3">
                                <i class="fas fa-car-side mr-2"></i>Lokasi Pengambilan<span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer" :class="{ 'border-indigo-500 ring-2 ring-indigo-300': tipe_pengambilan === 'kantor' }">
                                    <input type="radio" name="tipe_pengambilan" value="kantor" x-model="tipe_pengambilan" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-3 block text-sm font-medium text-gray-900">Kantor Rental</span>
                                    <span class="ml-auto text-sm font-semibold text-green-600">Gratis</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer" :class="{ 'border-indigo-500 ring-2 ring-indigo-300': tipe_pengambilan === 'lainnya' }">
                                    <input type="radio" name="tipe_pengambilan" value="lainnya" x-model="tipe_pengambilan" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-3 block text-sm font-medium text-gray-900">Lokasi Lainnya</span>
                                    <span class="ml-auto text-sm text-gray-500">Dikenakan biaya tambahan</span>
                                </label>
                                <div x-show="tipe_pengambilan === 'lainnya'" x-transition>
                                    <label for="alamat_pengambilan" class="block text-sm font-medium text-gray-700">Alamat Pengambilan</label>
                                    <textarea name="alamat_pengambilan" id="alamat_pengambilan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis nama jalan, gedung, atau area. Contoh: Bandara SSK II, Pekanbaru"></textarea>
                                </div>
                            </div>
                        </div>

                        
                        <div class="md:col-span-2 mb-4">
                            <label class="block text-lg font-semibold text-gray-800 mb-3">
                                <i class="fas fa-key mr-2"></i>Lokasi Pengembalian<span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer" :class="{ 'border-indigo-500 ring-2 ring-indigo-300': tipe_pengembalian === 'kantor' }">
                                    <input type="radio" name="tipe_pengembalian" value="kantor" x-model="tipe_pengembalian" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-3 block text-sm font-medium text-gray-900">Kantor Rental</span>
                                    <span class="ml-auto text-sm font-semibold text-green-600">Gratis</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer" :class="{ 'border-indigo-500 ring-2 ring-indigo-300': tipe_pengembalian === 'lainnya' }">
                                    <input type="radio" name="tipe_pengembalian" value="lainnya" x-model="tipe_pengembalian" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-3 block text-sm font-medium text-gray-900">Lokasi Lainnya</span>
                                    <span class="ml-auto text-sm text-gray-500">Dikenakan biaya tambahan</span>
                                </label>
                                <div x-show="tipe_pengembalian === 'lainnya'" x-transition>
                                    <label for="alamat_pengembalian" class="block text-sm font-medium text-gray-700">Alamat Pengembalian</label>
                                    <textarea name="alamat_pengembalian" id="alamat_pengembalian" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis nama jalan, gedung, atau area. Contoh: Hotel Pangeran, Pekanbaru"></textarea>
                                </div>
                            </div>
                        </div>

                    
                    <div class="mt-8 text-right">
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                            Booking Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">
                &copy; <?php echo e(date('Y')); ?> DriveNow Rental Mobil. Hak cipta dilindungi.
            </J>
        </div>
    </footer>

</body>
</html><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\frontend\detail.blade.php ENDPATH**/ ?>