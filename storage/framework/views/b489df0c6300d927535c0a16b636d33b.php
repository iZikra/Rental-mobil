<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Booking - <?php echo e($car->nama_mobil ?? $car->merk . ' ' . $car->model); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-blue-500 selection:text-white pb-10">

    
    <div class="relative bg-slate-900 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-30" alt="Booking Header">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/50 to-slate-900/90"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <header class="flex items-center justify-between mb-8">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center space-x-4 bg-white/10 hover:bg-white/20 transition px-4 py-2 rounded-2xl backdrop-blur-sm border border-white/10">
                    <div class="bg-blue-600 p-2.5 rounded-xl shadow-lg shadow-blue-600/30">
                        <i class="fas fa-car-side text-white text-xl"></i>
                    </div>
                </a>
            </header>
            
            <div class="text-center pb-12 pt-4">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold tracking-widest uppercase mb-4 shadow-sm">
                    <i class="fa-solid fa-bolt text-yellow-400"></i> Reservasi Cepat / Guest
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-4">Selesaikan Reservasi Anda</h1>
                <p class="text-blue-100 text-lg max-w-2xl mx-auto opacity-90">Lengkapi data perjalanan dan identitas sesuai KTP untuk memproses pesanan <?php echo e($car->nama_mobil ?? $car->merk); ?> ini ke Mitra terkait.</p>
            </div>
        </div>
    </div>

    <main class="relative -mt-10 px-4 sm:px-6 lg:px-8 z-10 max-w-7xl mx-auto">
        <?php if($errors->any()): ?>
            <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl shadow-lg flex items-start gap-4 animate-bounce">
                <div class="flex-shrink-0 text-red-500">
                    <i class="fa-solid fa-circle-exclamation text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-red-800">Oops, Ada Kesalahan Formulir:</h3>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('guest.booking.submit', ['token' => $transaksi->booking_token])); ?>" method="POST" enctype="multipart/form-data" 
              class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ tipe_pengambilan: '<?php echo e(old('tipe_pengambilan', 'kantor')); ?>', tipe_pengembalian: '<?php echo e(old('tipe_pengembalian', 'kantor')); ?>' }">
            <?php echo csrf_field(); ?>
            
            <div class="lg:col-span-2 space-y-6 flex flex-col h-full gap-2">
                
                
                <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                    <div class="relative px-6 sm:px-8 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                        <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 15% 20%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 85% 35%, rgba(34,211,238,0.45), transparent 55%);"></div>
                        <div class="relative flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white shadow-inner">
                                    <i class="fa-solid fa-id-card-clip"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Langkah 1</div>
                                    <div class="text-lg sm:text-xl font-extrabold text-white">Data Penanggung Jawab</div>
                                </div>
                            </div>
                            <div class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 shadow-inner text-white text-xs font-extrabold tracking-widest">1</div>
                        </div>
                    </div>

                    <div class="px-6 sm:px-8 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <div class="flex items-center bg-white border border-gray-300 rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition shadow-sm hover:border-blue-300">
                                    <i class="fa-regular fa-user text-gray-400 mr-3"></i>
                                    <input type="text" name="nama_customer" id="nama_customer" value="<?php echo e(old('nama_customer')); ?>" required class="bg-transparent border-none w-full text-slate-800 font-semibold focus:ring-0 placeholder-gray-400" placeholder="Sesuai KTP Anda">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                <div class="flex items-center bg-white border border-gray-300 rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition shadow-sm hover:border-blue-300">
                                    <i class="fa-brands fa-whatsapp text-green-500 text-lg mr-3"></i>
                                    <input type="text" name="telp_customer" id="telp_customer" value="<?php echo e(old('telp_customer')); ?>" required inputmode="numeric" class="bg-transparent border-none w-full text-slate-800 font-semibold focus:ring-0 placeholder-gray-400" placeholder="08123xxxx">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Domisili <span class="text-red-500">*</span></label>
                                <div class="flex items-start bg-white border border-gray-300 rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition shadow-sm hover:border-blue-300">
                                    <i class="fa-solid fa-map-pin text-red-500 mr-3 mt-1 cursor-pointer"></i>
                                    <textarea name="alamat_customer" id="alamat_customer" rows="2" required class="bg-transparent border-none w-full text-slate-800 font-semibold focus:ring-0 placeholder-gray-400" placeholder="Alamat tinggal Anda saat ini..."></textarea>
                                </div>
                            </div>
                            
                            
                            <div class="md:col-span-2 mt-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Dokumen Identitas Wajib</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    
                                    <div class="relative border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:bg-blue-50 hover:border-blue-400 hover:shadow-inner transition duration-300 cursor-pointer group" onclick="document.getElementById('foto_identitas').click()">
                                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-id-card text-2xl text-blue-500"></i>
                                        </div>
                                        <p class="text-sm text-gray-700 font-bold mb-1">Upload KTP Asli</p>
                                        <p class="text-xs text-gray-400">Scan / Foto jelas (Max 2MB)</p>
                                        <input type="file" name="foto_identitas" id="foto_identitas" accept="image/*" required class="hidden" onchange="previewFile('foto_identitas', 'name_ktp')">
                                        <div id="name_ktp" class="bg-green-100 text-green-700 border border-green-200 py-1.5 px-3 rounded-lg text-xs font-bold mt-4 hidden overflow-hidden text-ellipsis whitespace-nowrap"></div>
                                    </div>
                                    
                                    <div class="relative border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:bg-blue-50 hover:border-blue-400 hover:shadow-inner transition duration-300 cursor-pointer group" onclick="document.getElementById('foto_sim').click()">
                                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-address-card text-2xl text-blue-500"></i>
                                        </div>
                                        <p class="text-sm text-gray-700 font-bold mb-1">Upload SIM A / SIM B</p>
                                        <p class="text-xs text-gray-400">Sebagai Syarat Sewa (Max 2MB)</p>
                                        <input type="file" name="foto_sim" id="foto_sim" accept="image/*" required class="hidden" onchange="previewFile('foto_sim', 'name_sim')">
                                        <div id="name_sim" class="bg-green-100 text-green-700 border border-green-200 py-1.5 px-3 rounded-lg text-xs font-bold mt-4 hidden overflow-hidden text-ellipsis whitespace-nowrap"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                
                <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                    <div class="relative px-6 sm:px-8 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                        <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 15% 20%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 85% 35%, rgba(34,211,238,0.45), transparent 55%);"></div>
                        <div class="relative flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white shadow-inner">
                                    <i class="fa-solid fa-calendar-check"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Langkah 2</div>
                                    <div class="text-lg sm:text-xl font-extrabold text-white">Waktu & Titik Serah Terima</div>
                                </div>
                            </div>
                            <div class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white shadow-inner text-xs font-extrabold tracking-widest">2</div>
                        </div>
                    </div>
                    
                    <div class="px-6 sm:px-8 py-6 space-y-8">
                        
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-2">
                            <div class="space-y-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase">Mulai Waktu Sewa <span class="text-red-500">*</span></label>
                                <div class="flex gap-2">
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo e(old('tanggal_mulai', $transaksi->tgl_ambil ?? date('Y-m-d'))); ?>" min="<?php echo e(date('Y-m-d')); ?>" class="w-3/5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl px-4 py-3 font-semibold text-slate-800 transition" required>
                                    <input type="time" name="jam_mulai" id="jam_mulai" value="<?php echo e(old('jam_mulai', '09:00')); ?>" class="w-2/5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl px-2 py-3 font-semibold text-slate-800 text-center transition" required>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase">Perkiraan Selesai <span class="text-red-500">*</span></label>
                                <div class="flex gap-2">
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="<?php echo e(old('tanggal_selesai', $transaksi->tgl_kembali ?? date('Y-m-d', strtotime('+1 day')))); ?>" class="w-3/5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl px-4 py-3 font-semibold text-slate-800 transition" required>
                                    <input type="time" name="jam_selesai" id="jam_selesai" value="<?php echo e(old('jam_selesai', '09:00')); ?>" class="w-2/5 bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl px-2 py-3 font-semibold text-slate-800 text-center transition" required>
                                </div>
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-3xl border border-slate-200">
                            
                            
                            <div>
                                <label class="flex items-center gap-2 text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-4">
                                    <i class="fa-solid fa-map-location-dot text-slate-500"></i> Ambil Kendaraan
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center p-4 border border-gray-200 bg-white shadow-sm rounded-2xl cursor-pointer transition hover:border-blue-300" :class="{ 'border-blue-500 ring-2 ring-blue-100 bg-blue-50/30': tipe_pengambilan === 'kantor' }">
                                        <input type="radio" name="tipe_pengambilan" value="kantor" x-model="tipe_pengambilan" class="text-blue-600 focus:ring-blue-500 w-5 h-5 rounded-full border-gray-300 shadow-sm">
                                        <div class="ml-3">
                                            <p class="font-bold text-slate-800 text-sm">Ambil di Kantor</p>
                                            <p class="text-xs text-green-600 font-semibold mt-0.5">Gratis Biaya</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 border border-gray-200 bg-white shadow-sm rounded-2xl cursor-pointer transition hover:border-blue-300" :class="{ 'border-blue-500 ring-2 ring-blue-100 bg-blue-50/30': tipe_pengambilan === 'lainnya' }">
                                        <input type="radio" name="tipe_pengambilan" value="lainnya" x-model="tipe_pengambilan" class="text-blue-600 focus:ring-blue-500 w-5 h-5 rounded-full border-gray-300 shadow-sm">
                                        <div class="ml-3">
                                            <p class="font-bold text-slate-800 text-sm">Lokasi Lain / Antar</p>
                                            <p class="text-xs text-slate-500 mt-0.5">Mungkin berbiaya ekstra</p>
                                        </div>
                                    </label>
                                </div>
                                <div x-show="tipe_pengambilan === 'lainnya'" x-transition class="mt-4 animate-fade-in-up">
                                    <textarea name="alamat_pengambilan" rows="2" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 font-semibold text-sm placeholder-gray-400 shadow-inner" placeholder="Contoh: Bandara SSK II atau Stasiun terdekat"></textarea>
                                </div>
                            </div>

                            
                            <div>
                                <label class="flex items-center gap-2 text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-4">
                                    <i class="fa-solid fa-flag-checkered text-slate-500"></i> Pulangkan Kendaraan
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center p-4 border border-gray-200 bg-white shadow-sm rounded-2xl cursor-pointer transition hover:border-blue-300" :class="{ 'border-blue-500 ring-2 ring-blue-100 bg-blue-50/30': tipe_pengembalian === 'kantor' }">
                                        <input type="radio" name="tipe_pengembalian" value="kantor" x-model="tipe_pengembalian" class="text-blue-600 focus:ring-blue-500 w-5 h-5 rounded-full border-gray-300 shadow-sm">
                                        <div class="ml-3">
                                            <p class="font-bold text-slate-800 text-sm">Kembali di Kantor</p>
                                            <p class="text-xs text-green-600 font-semibold mt-0.5">Termudah</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 border border-gray-200 bg-white shadow-sm rounded-2xl cursor-pointer transition hover:border-blue-300" :class="{ 'border-blue-500 ring-2 ring-blue-100 bg-blue-50/30': tipe_pengembalian === 'lainnya' }">
                                        <input type="radio" name="tipe_pengembalian" value="lainnya" x-model="tipe_pengembalian" class="text-blue-600 focus:ring-blue-500 w-5 h-5 rounded-full border-gray-300 shadow-sm">
                                        <div class="ml-3">
                                            <p class="font-bold text-slate-800 text-sm">Lokasi Bebas / Jemput</p>
                                            <p class="text-xs text-slate-500 mt-0.5">Biaya menyesuaikan jarak</p>
                                        </div>
                                    </label>
                                </div>
                                <div x-show="tipe_pengembalian === 'lainnya'" x-transition class="mt-4 animate-fade-in-up">
                                    <textarea name="alamat_pengembalian" rows="2" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 font-semibold text-sm placeholder-gray-400 shadow-inner" placeholder="Dimana mitra kami mengambil mobilnya?"></textarea>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                
                <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                    <div class="relative px-6 sm:px-8 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                        <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 15% 20%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 85% 35%, rgba(34,211,238,0.45), transparent 55%);"></div>
                        <div class="relative flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white shadow-inner">
                                    <i class="fa-solid fa-file-contract"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Langkah 3</div>
                                    <div class="text-lg sm:text-xl font-extrabold text-white">Syarat & Ketentuan Sewa</div>
                                </div>
                            </div>
                            <div class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 shadow-inner text-white text-xs font-extrabold tracking-widest">3</div>
                        </div>
                    </div>

                    <div class="px-6 sm:px-8 py-6">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 h-48 overflow-y-auto mb-5 text-sm text-slate-600 space-y-3 shadow-inner">
                            <h4 class="font-bold text-gray-800">Kebijakan Mitra Rental (<?php echo e($car->rental->nama_rental ?? 'Mitra DriveNow'); ?>):</h4>
                            <div class="whitespace-pre-line leading-relaxed font-medium">
                                <?php echo e($car->rental->syarat_ketentuan ?? "1. Penyewa wajib memiliki e-KTP dan SIM asli yang masih berlaku.\n2. Pembayaran sewa dilakukan di awal atau menggunakan fitur Secure Payment.\n3. Kendaraan dikembalikan dalam kondisi bersih dan volume BBM sama dengan saat pengambilan.\n4. Keterlambatan akan dikenakan denda sesuai dengan kebijakan mitra."); ?>

                            </div>
                        </div>

                        <label class="flex items-start cursor-pointer group p-3 border border-transparent hover:border-blue-200 hover:bg-blue-50 rounded-xl transition">
                            <div class="flex items-center h-5 mt-0.5">
                                <input type="checkbox" name="setuju_sk" required class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 cursor-pointer shadow-sm">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-bold text-gray-800 group-hover:text-blue-600 transition">Saya telah membaca, memahami, dan menyetujui seluruh Syarat & Ketentuan di atas. <span class="text-red-500">*</span></span>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            
            <div class="lg:col-span-1">
                <div class="sticky top-10 space-y-6">
                    
                    
                    <?php
                        $alamatPusat = $car->rental->alamat ?? null;
                        $alamatCabang = $car->branch->alamat_lengkap ?? null;
                        $alamatRental = $alamatCabang ?: $alamatPusat ?: 'Jakarta Raya';
                        $kotaRental = $car->branch->kota ?? 'Jakarta';
                        $queryMap = urlencode($alamatRental . ' ' . $kotaRental);
                    ?>
                    <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                        <div class="relative px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                            <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 18% 30%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 82% 30%, rgba(34,211,238,0.45), transparent 55%);"></div>
                            <div class="relative flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white shadow-inner">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Lokasi</div>
                                    <div class="text-lg font-extrabold text-white">Titik Pengambilan</div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-sm font-bold text-gray-800 mb-3 flex items-start gap-2">
                                <i class="fa-solid fa-location-dot text-red-500 mt-1"></i>
                                <span><?php echo e($alamatRental); ?></span>
                            </p>
                            
                            
                            <div class="w-full h-48 rounded-xl overflow-hidden border border-gray-200 shadow-inner bg-gray-100 mb-4">
                                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
                                        src="https://maps.google.com/maps?q=<?php echo e($queryMap); ?>&hl=id&z=15&output=embed">
                                </iframe>
                            </div>
                            
                            <a href="https://maps.google.com/maps?q=<?php echo e($queryMap); ?>" target="_blank" class="flex items-center justify-center gap-2 w-full bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 text-xs font-extrabold py-2.5 rounded-xl transition">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Petunjuk Arah
                            </a>
                        </div>
                    </div>

                    
                    <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                        
                        <div class="relative px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                            <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 18% 30%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 82% 30%, rgba(34,211,238,0.45), transparent 55%);"></div>
                            <div class="relative flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white shadow-inner">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Total Biaya</div>
                                    <div class="text-lg font-extrabold text-white">Ringkasan Pesanan</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            
                            
                            <div class="mb-5 text-center">
                                <div class="rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 mb-4 p-4 shadow-inner min-h-[160px] flex items-center justify-center">
                                    <img src="<?php echo e($car->image_url); ?>" class="w-full h-36 object-contain drop-shadow-md transition hover:scale-105 duration-300" alt="<?php echo e($car->nama_mobil ?? $car->merk); ?>" onerror="this.src='https://placehold.co/600x400?text=Gambar+Tidak+Ada'">
                                </div>
                                <h4 class="text-xl font-extrabold text-slate-800"><?php echo e($car->nama_mobil ?? $car->merk . ' ' . $car->model); ?></h4>
                                <p class="text-sm text-gray-500 font-semibold mt-1">
                                    <?php echo e($car->branch->nama_cabang ?? $car->rental->nama_rental ?? 'Mitra DriveNow'); ?> • <?php echo e($car->branch->kota ?? 'Pusat'); ?>

                                </p>
                                
                                <div class="mt-3 flex flex-wrap gap-2 justify-center">
                                    <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-[11px] font-bold tracking-wide"><i class="fa-solid fa-chair mr-1.5 opacity-70"></i><?php echo e($car->jumlah_kursi ?? 4); ?> Seat</span>
                                    <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-[11px] font-bold tracking-wide uppercase"><i class="fa-solid fa-gears mr-1.5 opacity-70"></i><?php echo e($car->transmisi ?? 'Manual'); ?></span>
                                </div>
                            </div>

                            
                            <div class="space-y-4 border-t border-dashed border-gray-200 pt-5">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 font-medium">Harga per Hari</span>
                                    <span class="font-bold text-gray-800">Rp <?php echo e(number_format($car->harga_sewa, 0, ',', '.')); ?></span>
                                </div>
                                <div class="flex justify-between items-center text-sm bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                                    <span class="text-gray-600 font-bold"><i class="fa-solid fa-clock opacity-50 mr-2"></i>Durasi Sewa</span>
                                    <span class="font-extrabold text-blue-600 text-base" id="display_durasi">1 Hari</span>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-t-2 border-gray-100">
                                <div class="flex flex-col gap-1">
                                    <span class="text-gray-400 font-semibold text-xs uppercase tracking-widest text-right">Estimasi Tagihan</span>
                                    <div class="flex justify-between items-end">
                                        <span class="text-sm text-gray-500 font-medium mb-1">Total Biaya</span>
                                        <span class="text-3xl font-extrabold text-blue-600" id="display_total">Rp <?php echo e(number_format($car->harga_sewa, 0, ',', '.')); ?></span>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mt-6 p-4 bg-sky-50 border border-sky-100 rounded-xl relative overflow-hidden">
                                <div class="absolute -right-4 -bottom-4 text-sky-200 opacity-50">
                                    <i class="fa-solid fa-shield-halved text-6xl"></i>
                                </div>
                                <h4 class="text-sm font-bold text-sky-900 uppercase mb-2 flex items-center gap-2 relative z-10">
                                    <i class="fa-solid fa-shield-check text-sky-500"></i> Keamanan Data
                                </h4>
                                <p class="text-xs text-sky-700 leading-relaxed text-justify relative z-10">
                                    Data privasi Anda berupa foto dokumen KTP dan SIM dilindungi dengan enkripsi tinggi dan hanya diteruskan eksklusif kepada Mitra yang memegang mobil bersangkutan guna menghindari kecurangan/fraud.
                                </p>
                            </div>

                            
                            <div class="mt-6 relative group">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-500"></div>
                                <button type="submit" class="relative w-full bg-slate-900 hover:bg-slate-800 hover:-translate-y-0.5 text-white font-extrabold py-4 px-4 rounded-xl shadow-xl transition-all duration-300 flex justify-center items-center gap-2">
                                    <i class="fa-solid fa-lock text-slate-400"></i>
                                    Sewa Mobil Sekarang
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </form>
    </main>

    
    <footer class="mt-16 py-8 border-t border-gray-200 text-center">
        <div class="mx-auto flex justify-center items-center gap-1.5 opacity-40 text-sm font-bold text-slate-900">
            <i class="fa-solid fa-car-side"></i> <span>DriveNow <?php echo e(date('Y')); ?> ©️ Hak Cipta Dilindungi</span>
        </div>
    </footer>

    
    <script>
        // CSS untuk animasi input
        document.head.insertAdjacentHTML('beforeend', '<style>@keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } } .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }</style>');

        // Preview Nama File Upload
        function previewFile(inputId, textId) {
            const file = document.getElementById(inputId).files[0];
            const label = document.getElementById(textId);
            const parent = document.getElementById(inputId).parentElement;
            
            if(file) {
                // Check filesize validation frontend
                if(file.size > 2 * 1024 * 1024) {
                    Swal.fire('File Terlalu Besar!', 'Maksimal ukuran file adalah 2MB', 'error');
                    document.getElementById(inputId).value = '';
                    label.classList.add('hidden');
                    parent.classList.remove('border-green-500', 'bg-green-50');
                    parent.classList.add('border-gray-300');
                    return;
                }
                
                label.innerText = 'File: ' + file.name;
                label.classList.remove('hidden');
                
                // Style when filled
                parent.classList.remove('border-gray-300', 'hover:bg-blue-50');
                parent.classList.add('border-green-500', 'bg-green-50/50');
                parent.querySelector('i').classList.replace('text-blue-500', 'text-green-500');
                parent.querySelector('.w-12').classList.replace('bg-blue-100', 'bg-green-200');
            }
        }

        const tglMulai = document.getElementById('tanggal_mulai');
        const tglSelesai = document.getElementById('tanggal_selesai');
        const dDurasi = document.getElementById('display_durasi');
        const dTotal = document.getElementById('display_total');
        const hargaUnit = <?php echo e($car->harga_sewa ?? 0); ?>;

        function hitungTotal() {
            if(tglMulai && tglSelesai && tglMulai.value && tglSelesai.value) {
                const s = new Date(tglMulai.value);
                const e = new Date(tglSelesai.value);
                let days = Math.ceil((e - s) / (1000 * 60 * 60 * 24));
                
                if (days < 1) days = 1; // Minimal sewa dihitung 1 hari

                const total = days * hargaUnit;
                dDurasi.innerText = days + " Hari";
                dTotal.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);

                // Tambahkan pop animasi di total
                dTotal.classList.add('scale-110', 'text-green-600');
                dTotal.classList.remove('text-blue-600');
                setTimeout(() => {
                    dTotal.classList.remove('scale-110', 'text-green-600');
                    dTotal.classList.add('text-blue-600', 'transition-all', 'duration-300');
                }, 300);
            }
        }

        tglMulai.addEventListener('change', hitungTotal);
        tglSelesai.addEventListener('change', hitungTotal);
        window.addEventListener('load', hitungTotal);
        
        // Form Submit Loading State
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Mengamankan Pesanan...';
                
                // Tambahkan sedikit delay agar visual efek terlihat oleh user
                setTimeout(() => { 
                    btn.disabled = true;
                    btn.classList.add('opacity-80', 'cursor-not-allowed');
                }, 10);
            }
        });
    </script>
</body>
</html><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/frontend/guest_booking.blade.php ENDPATH**/ ?>