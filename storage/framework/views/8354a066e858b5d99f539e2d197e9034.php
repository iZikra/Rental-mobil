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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="relative bg-slate-900 py-16 sm:py-24 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-30" alt="Booking Header">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/50 to-slate-900/90"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-4">Selesaikan Reservasi Anda</h1>
            <p class="text-blue-200 text-lg max-w-2xl mx-auto">Lengkapi formulir di bawah ini untuk mengamankan kendaraan pilihan Anda.</p>
        </div>
    </div>

    <div class="relative -mt-10 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            
            <?php if($errors->any()): ?>
                <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl shadow-lg flex items-start gap-4 animate-bounce">
                    <div class="flex-shrink-0 text-red-500">
                        <i class="fa-solid fa-circle-exclamation text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-800">Mohon Perbaiki Kesalahan Berikut:</h3>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl shadow-lg flex items-start gap-4 animate-bounce">
                    <div class="flex-shrink-0 text-red-500">
                        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-800">Gagal!</h3>
                        <p class="mt-1 text-sm text-red-700"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mb-8 rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden relative z-20">
                <div class="relative px-6 sm:px-8 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                    <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 20% 20%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 80% 30%, rgba(34,211,238,0.45), transparent 55%);"></div>
                    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                                <i class="fa-solid fa-sliders"></i>
                            </div>
                            <div>
                                <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Filter</div>
                                <div class="text-lg sm:text-xl font-extrabold text-white">Cari Armada yang Cocok</div>
                            </div>
                        </div>
                        <?php if(request()->anyFilled(['kota', 'rental', 'tipe_mobil', 'transmisi', 'jumlah_kursi'])): ?>
                            <a href="<?php echo e(url()->current()); ?>"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 hover:bg-white/15 border border-white/15 text-white font-bold text-sm transition">
                                <i class="fa-solid fa-rotate-left"></i> Reset Filter
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="px-6 sm:px-8 py-6">
                    <form action="<?php echo e(url()->current()); ?>" method="GET" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-blue-50 to-white p-5">
                                <label for="kota" class="flex items-center gap-2 text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-3">
                                    <i class="fa-solid fa-location-dot text-red-500"></i> Lokasi
                                </label>
                                <select name="kota" id="kota"
                                        class="w-full bg-white border border-slate-200 p-3 rounded-2xl text-sm font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer"
                                        onchange="this.form.submit()">
                                    <option value="">Semua Kota</option>
                                    <?php if(isset($daftarKota)): ?>
                                        <?php $__currentLoopData = $daftarKota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($kota); ?>" <?php echo e(request('kota') == $kota ? 'selected' : ''); ?>><?php echo e($kota); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                                <div class="mt-2 text-xs font-semibold text-slate-500">Pilih kota untuk melihat armada yang tersedia.</div>
                            </div>

                            <?php if(isset($rentals) && $rentals->count()): ?>
                            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-5">
                                <label for="rental" class="flex items-center gap-2 text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-3">
                                    <i class="fa-solid fa-building text-slate-700"></i> Rental Mitra
                                </label>
                                <select name="rental" id="rental"
                                        class="w-full bg-white border border-slate-200 p-3 rounded-2xl text-sm font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer"
                                        onchange="this.form.submit()">
                                    <option value="">Semua Rental</option>
                                    <?php $__currentLoopData = $rentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($r->slug); ?>" <?php echo e(request('rental') == $r->slug ? 'selected' : ''); ?>>
                                            <?php echo e($r->nama_rental); ?> (<?php echo e((int) ($r->mobil_tersedia_count ?? 0)); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
                                    <a href="<?php echo e(url()->current()); ?>?<?php echo e(http_build_query(array_filter(['kota' => request('kota'), 'tipe_mobil' => request('tipe_mobil'), 'transmisi' => request('transmisi'), 'jumlah_kursi' => request('jumlah_kursi')]))); ?>"
                                       class="shrink-0 px-3 py-1.5 rounded-full text-xs font-extrabold border transition <?php echo e(request('rental') ? 'bg-white border-slate-200 text-slate-700 hover:border-blue-300' : 'bg-blue-600 border-blue-600 text-white'); ?>">
                                        Semua
                                    </a>
                                    <?php $__currentLoopData = $rentals->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(url()->current()); ?>?<?php echo e(http_build_query(array_filter(['kota' => request('kota'), 'tipe_mobil' => request('tipe_mobil'), 'transmisi' => request('transmisi'), 'jumlah_kursi' => request('jumlah_kursi'), 'rental' => $r->slug]))); ?>"
                                           class="shrink-0 px-3 py-1.5 rounded-full text-xs font-extrabold border transition flex items-center gap-2 <?php echo e(request('rental') == $r->slug ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-slate-200 text-slate-700 hover:border-blue-300'); ?>">
                                            <span class="max-w-[140px] truncate"><?php echo e($r->nama_rental); ?></span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold <?php echo e(request('rental') == $r->slug ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'); ?>">
                                                <?php echo e((int) ($r->mobil_tersedia_count ?? 0)); ?>

                                            </span>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                                <label class="flex items-center gap-2 text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-3">
                                    <i class="fa-solid fa-car text-slate-500"></i> Tipe Mobil
                                </label>
                                <select name="tipe_mobil" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-2xl text-sm font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer">
                                    <option value="">Semua</option>
                                    <option value="City Car" <?php echo e(request('tipe_mobil') == 'City Car' ? 'selected' : ''); ?>>City Car</option>
                                    <option value="Compact MPV" <?php echo e(request('tipe_mobil') == 'Compact MPV' ? 'selected' : ''); ?>>Compact MPV</option>
                                    <option value="Luxury Sedan" <?php echo e(request('tipe_mobil') == 'Luxury Sedan' ? 'selected' : ''); ?>>Luxury Sedan</option>
                                    <option value="Mini MPV" <?php echo e(request('tipe_mobil') == 'Mini MPV' ? 'selected' : ''); ?>>Mini MPV</option>
                                    <option value="Minibus" <?php echo e(request('tipe_mobil') == 'Minibus' ? 'selected' : ''); ?>>Minibus</option>
                                    <option value="Minivan" <?php echo e(request('tipe_mobil') == 'Minivan' ? 'selected' : ''); ?>>Minivan</option>
                                    <option value="SUV" <?php echo e(request('tipe_mobil') == 'SUV' ? 'selected' : ''); ?>>SUV</option>
                                    <option value="Sedan" <?php echo e(request('tipe_mobil') == 'Sedan' ? 'selected' : ''); ?>>Sedan</option>
                                </select>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                                <label class="flex items-center gap-2 text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-3">
                                    <i class="fa-solid fa-gears text-slate-500"></i> Transmisi
                                </label>
                                <select name="transmisi" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-2xl text-sm font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer">
                                    <option value="">Semua</option>
                                    <option value="matic" <?php echo e(request('transmisi') == 'matic' ? 'selected' : ''); ?>>Automatic</option>
                                    <option value="manual" <?php echo e(request('transmisi') == 'manual' ? 'selected' : ''); ?>>Manual</option>
                                </select>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                                <label class="flex items-center gap-2 text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-3">
                                    <i class="fa-solid fa-chair text-slate-500"></i> Kapasitas Kursi
                                </label>
                                <select name="jumlah_kursi" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-2xl text-sm font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer">
                                    <option value="">Semua</option>
                                    <option value="4" <?php echo e(request('jumlah_kursi') == '4' ? 'selected' : ''); ?>>4 Penumpang</option>
                                    <option value="5-6" <?php echo e(request('jumlah_kursi') == '5-6' ? 'selected' : ''); ?>>5 - 6 Penumpang</option>
                                    <option value=">6" <?php echo e(request('jumlah_kursi') == '>6' ? 'selected' : ''); ?>>Lebih dari 6 Penumpang</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-1">
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 hover:bg-blue-600 text-white font-extrabold shadow-lg transition">
                                <i class="fa-solid fa-magnifying-glass"></i> Terapkan Filter
                            </button>
                            <?php if(request()->anyFilled(['kota', 'rental', 'tipe_mobil', 'transmisi', 'jumlah_kursi'])): ?>
                                <a href="<?php echo e(url()->current()); ?>"
                                   class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-red-50 border border-red-100 text-red-600 font-extrabold hover:bg-red-100 transition">
                                    <i class="fa-solid fa-xmark"></i> Hapus Semua Filter
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            <form action="<?php echo e(route('transaksi.store')); ?>" method="POST" enctype="multipart/form-data" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
                <?php echo csrf_field(); ?>
                
                
                <input type="hidden" name="total_harga" id="input_total_harga" value="<?php echo e(old('total_harga')); ?>">
                <input type="hidden" name="lama_sewa" id="input_lama_sewa" value="<?php echo e(old('lama_sewa')); ?>">
                <input type="hidden" name="status" value="Pending">

                
                <div class="lg:col-span-2 space-y-6">

                    <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                        <div class="relative px-6 sm:px-8 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                            <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 15% 20%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 85% 35%, rgba(34,211,238,0.45), transparent 55%);"></div>
                            <div class="relative flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                                        <i class="fa-solid fa-car"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Langkah 1</div>
                                        <div class="text-lg sm:text-xl font-extrabold text-white">Pilih Armada</div>
                                    </div>
                                </div>
                                <div class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white text-xs font-extrabold tracking-widest">1</div>
                            </div>
                        </div>
                        <div class="px-6 sm:px-8 py-6">
                            <label class="block text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-3">Mobil yang Ingin Disewa</label>
                            <select name="mobil_id" id="mobil_select" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 font-extrabold transition">
    <option value="" data-harga="0" data-img="" data-nama="" data-alamat="" data-kota="" data-map="">-- Pilih Mobil --</option>
    
    <?php if(isset($mobils) && $mobils->count() > 0): ?>
        <?php $__currentLoopData = $mobils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $branch = optional($m->branch);
        $rental = optional($m->rental);

        $imgUrl = $m->image_url;
        $namaKota = $branch->kota ?? 'Pusat';
        $desc = "{$m->tahun_buat} • {$m->transmisi} | 📍 " . $namaKota;

        $bankName = $rental->nama_bank ?? 'Belum Diatur';
        $bankRek = $rental->no_rekening ?? 'Silakan hubungi admin';
        $bankOwner = $rental->atas_nama_rekening ?? $rental->nama_rental ?? 'Mitra Pusat'; 
        $teksSnk = $rental->syarat_ketentuan ?? '';
        $biayaSopirPerHari = (int) ($rental->biaya_sopir_per_hari ?? 0);
        $biayaBandaraPerTrip = (int) ($rental->biaya_bandara_per_trip ?? 0);

        // Alamat sekarang selalu mengikuti input utama mitra di Profil Rental untuk menghindari kebingungan
        $alamatFinal = !empty($rental->alamat) ? $rental->alamat : ($branch->alamat_lengkap ?? "⚠️ ALAMAT KOSONG!");
        $mapFinal = '';
    ?>
            
            <option value="<?php echo e($m->id); ?>" 
                    data-harga="<?php echo e($m->harga_sewa); ?>" 
                    data-img="<?php echo e($imgUrl); ?>"
                    data-nama="<?php echo e($m->merk); ?> <?php echo e($m->model); ?>"
                    data-desc="<?php echo e($desc); ?>"
                    data-bank="<?php echo e($bankName); ?>"
                    data-rek="<?php echo e($bankRek); ?>"
                    data-owner="<?php echo e($bankOwner); ?>"
                    data-snk='<?php echo json_encode($teksSnk, 15, 512) ?>' 
                    data-biaya-sopir-per-hari="<?php echo e($biayaSopirPerHari); ?>"
                    data-biaya-bandara-per-trip="<?php echo e($biayaBandaraPerTrip); ?>"
                    data-alamat="<?php echo e($alamatFinal); ?>"
                    data-kota="<?php echo e($namaKota); ?>"
                    data-map="<?php echo e($mapFinal); ?>"
                    <?php echo e((isset($selectedMobil) && $selectedMobil->id == $m->id) || request('mobil_id') == $m->id || old('mobil_id') == $m->id ? 'selected' : ''); ?>>
                <?php echo e($m->merk); ?> <?php echo e($m->model); ?> (<?php echo e($namaKota); ?>) - Rp <?php echo e(number_format($m->harga_sewa, 0, ',', '.')); ?>/hari
            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <option value="" disabled>-- Tidak ada unit tersedia di lokasi ini --</option>
    <?php endif; ?>
</select>
                            <p class="mt-3 text-xs font-semibold text-slate-500">Pilih unit untuk menampilkan ringkasan pesanan dan lokasi pengambilan.</p>
                        </div>
                    </div>

                    
                    <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                        <div class="relative px-6 sm:px-8 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                            <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 15% 20%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 85% 35%, rgba(34,211,238,0.45), transparent 55%);"></div>
                            <div class="relative flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                                        <i class="fa-solid fa-id-card-clip"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Langkah 2</div>
                                        <div class="text-lg sm:text-xl font-extrabold text-white">Data Penyewa</div>
                                    </div>
                                </div>
                                <div class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white text-xs font-extrabold tracking-widest">2</div>
                            </div>
                        </div>

                        <div class="px-6 sm:px-8 py-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Lengkap</label>
                                <div class="flex items-center bg-gray-100 border border-gray-200 rounded-xl px-4 py-3">
                                    <i class="fa-regular fa-user text-gray-400 mr-3"></i>
                                    <input type="text" value="<?php echo e(Auth::check() ? Auth::user()->name : ''); ?>" <?php echo e(Auth::check() ? 'readonly' : ''); ?> class="bg-transparent border-none w-full text-gray-500 font-semibold focus:ring-0 <?php echo e(Auth::check() ? 'cursor-not-allowed' : ''); ?>" placeholder="<?php echo e(Auth::check() ? '' : 'Masukkan Nama'); ?>">
                                </div>
                            </div>
                            
      <div>
    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor HP (WhatsApp) <span class="text-red-500">*</span></label>
    
    <div class="flex items-center bg-white border border-gray-300 rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all shadow-sm">
        
        <i class="fa-brands fa-whatsapp text-green-500 text-lg mr-3"></i>
        
        <input type="text" id="no_hp" name="no_hp" required 
               value="<?php echo e(old('no_hp', Auth::check() ? Auth::user()->no_hp : '')); ?>" 
               class="bg-transparent outline-none border-0 w-full text-gray-900 font-semibold focus:ring-0 placeholder-gray-400" 
               placeholder="Contoh: 081234567890">
    </div>
    <p class="text-xs text-gray-500 mt-2 text-left">Nomor ditarik otomatis dari profil, namun Anda <b>bebas mengubahnya</b> khusus untuk pesanan ini.</p>
</div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Domisili <span class="text-red-500">*</span></label>
                                <div class="flex items-start bg-white border border-gray-300 rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition">
                                    <i class="fa-solid fa-map-pin text-red-500 mr-3 mt-1"></i>
                                    <textarea name="alamat" rows="2" class="bg-transparent border-none w-full text-gray-800 font-semibold focus:ring-0 placeholder-gray-400" placeholder="Alamat lengkap sesuai KTP..." required><?php echo e(old('alamat', Auth::check() ? Auth::user()->alamat : '')); ?></textarea>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                
                                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer" onclick="document.getElementById('file_ktp').click()">
                                    <i class="fa-solid fa-id-card text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500 font-medium">Klik untuk upload KTP</p>
                                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                    <input type="file" name="foto_identitas" id="file_ktp" class="hidden" onchange="previewFile('file_ktp', 'file_name_ktp')">
                                    <p id="file_name_ktp" class="text-center text-xs text-blue-600 font-bold mt-2 hidden"></p>
                                </div>

                                
                                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer" onclick="document.getElementById('file_sim').click()">
                                    <i class="fa-solid fa-address-card text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500 font-medium">Klik untuk upload SIM</p>
                                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                    <input type="file" name="foto_sim" id="file_sim" class="hidden" onchange="previewFile('file_sim', 'file_name_sim')">
                                    <p id="file_name_sim" class="text-center text-xs text-blue-600 font-bold mt-2 hidden"></p>
                                </div>
                                <p id="file_name" class="text-center text-sm text-blue-600 font-bold mt-2 hidden"></p>
                            </div>
                        </div>
                    </div>
                    </div>

                    
                    <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                        <div class="relative px-6 sm:px-8 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                            <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 15% 20%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 85% 35%, rgba(34,211,238,0.45), transparent 55%);"></div>
                            <div class="relative flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                                        <i class="fa-solid fa-route"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Langkah 3</div>
                                        <div class="text-lg sm:text-xl font-extrabold text-white">Detail Perjalanan</div>
                                    </div>
                                </div>
                                <div class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white text-xs font-extrabold tracking-widest">3</div>
                            </div>
                        </div>

                        <div class="px-6 sm:px-8 py-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase">Mulai Sewa</label>
                                    <div class="flex gap-2">
                                        <input type="date" name="tgl_ambil" id="tgl_ambil" min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e(old('tgl_ambil')); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-bold focus:ring-blue-500 text-gray-700" required>
                                        <input type="text" name="jam_ambil" id="jam_ambil" value="<?php echo e(old('jam_ambil')); ?>" readonly placeholder="--:--" class="w-1/3 bg-gray-50 border border-gray-200 rounded-xl px-2 py-3 font-bold focus:ring-blue-500 text-gray-700 cursor-pointer" required data-time-picker="jam_ambil">
                                    </div>
                                    <p class="text-[11px] text-gray-400">Pilih tanggal dulu, lalu tap jam untuk pilih menit (00/15/30/45).</p>
                                </div>
                                <div class="space-y-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase">Selesai Sewa</label>
                                    <div class="flex gap-2">
                                        <input type="date" name="tgl_kembali" id="tgl_kembali" min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e(old('tgl_kembali')); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-bold focus:ring-blue-500 text-gray-700" required>
                                        <input type="text" name="jam_kembali" id="jam_kembali" value="<?php echo e(old('jam_kembali')); ?>" readonly placeholder="--:--" class="w-1/3 bg-gray-50 border border-gray-200 rounded-xl px-2 py-3 font-bold focus:ring-blue-500 text-gray-700 cursor-pointer" required data-time-picker="jam_kembali">
                                    </div>
                                    <p class="text-[11px] text-gray-400">Kalau selesai sewa masih kosong, isi setelah pilih mulai sewa.</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Layanan Pengemudi</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="sopir" value="tanpa_sopir" class="peer sr-only" <?php echo e(old('sopir', 'tanpa_sopir') == 'tanpa_sopir' ? 'checked' : ''); ?>>
                                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition hover:bg-gray-50 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 peer-checked:text-blue-600 peer-checked:border-blue-500">
                                                    <i class="fa-solid fa-key"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-800">Lepas Kunci</h4>
                                                    <p class="text-xs text-gray-500">Setir sendiri</p>
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-circle-check text-blue-500 text-xl opacity-0 peer-checked:opacity-100 transition"></i>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="sopir" value="dengan_sopir" class="peer sr-only" <?php echo e(old('sopir') == 'dengan_sopir' ? 'checked' : ''); ?>>
                                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition hover:bg-gray-50 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 peer-checked:text-blue-600 peer-checked:border-blue-500">
                                                    <i class="fa-solid fa-user-tie"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-800">Dengan Sopir</h4>
                                                    <p class="text-xs text-gray-500">+Rp <span id="label_biaya_sopir_per_hari">0</span>/hari</p>
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-circle-check text-blue-500 text-xl opacity-0 peer-checked:opacity-100 transition"></i>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tujuan Penggunaan</label>
                                <input type="text" name="tujuan" value="<?php echo e(old('tujuan')); ?>" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 font-semibold text-gray-700" placeholder="Contoh: Liburan ke Berastagi" required>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-5 space-y-5">
    
    <div>
        <label class="flex items-center gap-2 text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-3">
            <i class="fa-solid fa-person-walking-luggage text-slate-500"></i> Lokasi Penjemputan
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="lokasi_ambil" value="kantor" 
                       class="text-blue-600 focus:ring-blue-500" 
                       <?php echo e(old('lokasi_ambil', 'kantor') == 'kantor' ? 'checked' : ''); ?>>
                <span class="ml-2 text-sm font-semibold text-gray-700">Jemput di Kantor (Gratis)</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="lokasi_ambil" value="bandara" 
                       class="text-blue-600 focus:ring-blue-500" 
                       <?php echo e(old('lokasi_ambil') == 'bandara' ? 'checked' : ''); ?>>
                <span class="ml-2 text-sm font-semibold text-gray-700">Jemput di Bandara (+Rp <span id="label_biaya_bandara_per_trip_jemput">0</span>)</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="lokasi_ambil" value="lainnya"
                       class="text-blue-600 focus:ring-blue-500"
                       <?php echo e(old('lokasi_ambil') == 'lainnya' ? 'checked' : ''); ?>>
                <span class="ml-2 text-sm font-semibold text-gray-700">Jemput di Lokasi Lain</span>
            </label>
        </div>
        <div id="jemput_lain_wrap" class="mt-3 <?php echo e(old('lokasi_ambil') == 'lainnya' ? '' : 'hidden'); ?>">
             <label class="block text-sm font-bold text-slate-800 mb-2">Tentukan Lokasi di Peta</label>
             <div class="relative group mb-3">
                 <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" id="search-input-ambil" placeholder="Cari alamat penjemputan..." 
                       class="w-full bg-white border-2 border-slate-900 rounded-full pl-11 pr-24 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all shadow-sm font-semibold">
                
                
                <button type="button" onclick="getLocation('ambil')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-blue-600 hover:text-blue-800 font-bold text-xs gap-1.5 transition">
                    <i class="fa-solid fa-location-crosshairs text-lg"></i>
                    <span>SAYA</span>
                </button>
                 
                 <div id="suggestions-ambil" class="absolute z-[1001] w-full bg-white border border-slate-200 rounded-2xl mt-2 hidden shadow-xl max-h-60 overflow-y-auto border-b-4 border-b-blue-600">
                 </div>
             </div>
             <div class="relative w-full h-48 bg-slate-200 rounded-xl overflow-hidden border border-gray-200 mb-2" id="map-ambil"></div>
             <p class="text-xs text-slate-500 mb-3 font-medium" id="map-ambil-text">Pilih lokasi di peta...</p>
 
             <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Detail Alamat Penjemputan</label>
             <textarea name="alamat_jemput_lain" id="alamat_jemput_val" rows="2" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 font-semibold text-gray-700" placeholder="Tulis alamat lengkap penjemputan"><?php echo e(old('alamat_jemput_lain')); ?></textarea>
             <?php $__errorArgs = ['alamat_jemput_lain'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                 <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
             <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    
    <div>
        <label class="flex items-center gap-2 text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-3">
            <i class="fa-solid fa-flag-checkered text-slate-500"></i> Lokasi Pengembalian
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="lokasi_kembali" value="kantor" 
                       class="text-blue-600 focus:ring-blue-500" 
                       <?php echo e(old('lokasi_kembali', 'kantor') == 'kantor' ? 'checked' : ''); ?>>
                <span class="ml-2 text-sm font-semibold text-gray-700">Antar ke Kantor (Gratis)</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="lokasi_kembali" value="bandara" 
                       class="text-blue-600 focus:ring-blue-500" 
                       <?php echo e(old('lokasi_kembali') == 'bandara' ? 'checked' : ''); ?>>
                <span class="ml-2 text-sm font-semibold text-gray-700">Antar ke Bandara (+Rp <span id="label_biaya_bandara_per_trip_antar">0</span>)</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="lokasi_kembali" value="lainnya"
                       class="text-blue-600 focus:ring-blue-500"
                       <?php echo e(old('lokasi_kembali') == 'lainnya' ? 'checked' : ''); ?>>
                <span class="ml-2 text-sm font-semibold text-gray-700">Antar ke Lokasi Lain</span>
            </label>
        </div>
        <div id="antar_lain_wrap" class="mt-3 <?php echo e(old('lokasi_kembali') == 'lainnya' ? '' : 'hidden'); ?>">
             <label class="block text-sm font-bold text-slate-800 mb-2">Tentukan Lokasi di Peta</label>
             <div class="relative group mb-3">
                 <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" id="search-input-kembali" placeholder="Cari alamat pengembalian..." 
                       class="w-full bg-white border-2 border-slate-900 rounded-full pl-11 pr-24 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all shadow-sm font-semibold">
                
                
                <button type="button" onclick="getLocation('kembali')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-blue-600 hover:text-blue-800 font-bold text-xs gap-1.5 transition">
                    <i class="fa-solid fa-location-crosshairs text-lg"></i>
                    <span>SAYA</span>
                </button>
                 
                 <div id="suggestions-kembali" class="absolute z-[1001] w-full bg-white border border-slate-200 rounded-2xl mt-2 hidden shadow-xl max-h-60 overflow-y-auto border-b-4 border-b-blue-600">
                 </div>
             </div>
             <div class="relative w-full h-48 bg-slate-200 rounded-xl overflow-hidden border border-gray-200 mb-2" id="map-kembali"></div>
             <p class="text-xs text-slate-500 mb-3 font-medium" id="map-kembali-text">Pilih lokasi di peta...</p>
 
             <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Detail Alamat Pengembalian</label>
             <textarea name="alamat_antar_lain" id="alamat_antar_val" rows="2" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 font-semibold text-gray-700" placeholder="Tulis alamat lengkap pengembalian"><?php echo e(old('alamat_antar_lain')); ?></textarea>
             <?php $__errorArgs = ['alamat_antar_lain'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                 <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
             <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                                        <i class="fa-solid fa-file-contract"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Langkah 4</div>
                                        <div class="text-lg sm:text-xl font-extrabold text-white">Syarat & Ketentuan Sewa</div>
                                    </div>
                                </div>
                                <div class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white text-xs font-extrabold tracking-widest">4</div>
                            </div>
                        </div>

                        <div class="px-6 sm:px-8 py-6">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 h-48 overflow-y-auto mb-5 text-sm text-slate-600 space-y-3">
                            <h4 class="font-bold text-gray-800">Harap Dibaca dengan Seksama:</h4>
                            <div id="tampil_snk" class="whitespace-pre-line leading-relaxed">
                                Pilih armada di langkah 1 terlebih dahulu untuk melihat Syarat & Ketentuan dari Mitra Rental terkait.
                            </div>
                        </div>

                        <label class="flex items-start cursor-pointer group p-3 border border-transparent hover:border-blue-200 hover:bg-blue-50 rounded-xl transition">
                            <div class="flex items-center h-5 mt-0.5">
                                <input type="checkbox" name="setuju_sk" required class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-bold text-gray-800 group-hover:text-blue-600 transition">Saya telah membaca, memahami, dan menyetujui seluruh Syarat & Ketentuan di atas. <span class="text-red-500">*</span></span>
                            </div>
                        </label>
                        </div>
                    </div>

                </div>

                
                <div class="lg:col-span-1">
                    <div class="sticky top-28 space-y-6">

                        
                        <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                            <div class="relative px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 18% 30%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 82% 30%, rgba(34,211,238,0.45), transparent 55%);"></div>
                                <div class="relative flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Lokasi</div>
                                        <div class="text-lg font-extrabold text-white">Lokasi Pengambilan</div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div id="lokasi_content" class="hidden">
                                    <p class="text-sm font-bold text-gray-800 mb-3 flex items-start gap-2">
                                        <i class="fa-solid fa-location-dot text-red-500 mt-1"></i>
                                        <span id="tampil_alamat_rental">-</span>
                                    </p>
                                    
                                    
                                    <div class="w-full h-48 rounded-xl overflow-hidden border border-gray-200 shadow-inner bg-gray-100">
                                        <iframe id="tampil_map" width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src=""></iframe>
                                    </div>
                                    
                                    <a id="link_gmaps" href="#" target="_blank" class="mt-4 flex items-center justify-center gap-2 w-full bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 text-xs font-extrabold py-2.5 rounded-xl transition">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Petunjuk Arah
                                    </a>
                                </div>
                                <div id="lokasi_placeholder" class="text-center py-8">
                                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-200">
                                        <i class="fa-solid fa-map text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-500 text-sm font-semibold">Pilih unit untuk melihat lokasi peta.</p>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                            <div class="relative px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 18% 30%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 82% 30%, rgba(34,211,238,0.45), transparent 55%);"></div>
                                <div class="relative flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Ringkasan</div>
                                        <div class="text-lg font-extrabold text-white">Ringkasan Pesanan</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                
                                <div id="summary_content" class="<?php echo e(isset($selectedMobil) ? '' : 'hidden'); ?>">
                                    <div class="text-center mb-6">
                                        <img id="summary_img" 
                                            src="<?php echo e(isset($selectedMobil) ? $selectedMobil->image_url : ''); ?>" 
                                            class="w-full h-48 object-contain mb-4 transform hover:scale-105 transition duration-500 rounded-2xl bg-slate-50 border border-slate-200 shadow-sm"
                                            onerror="this.src='https://placehold.co/600x400?text=Gambar+Tidak+Ditemukan'">
                                        <h4 id="summary_title" class="text-xl font-extrabold text-slate-800">
                                            <?php echo e(isset($selectedMobil) ? $selectedMobil->merk . ' ' . $selectedMobil->model : ''); ?>

                                        </h4>
                                        <p id="summary_desc" class="text-sm text-gray-500 font-medium">
                                            <?php echo e(isset($selectedMobil) ? $selectedMobil->tahun_buat . ' • ' . $selectedMobil->transmisi : ''); ?>

                                        </p>
                                    </div>
                                </div>

                                
                                <div id="mobil_placeholder" class="text-center mb-6 <?php echo e(isset($selectedMobil) ? 'hidden' : ''); ?>">
                                    <div class="w-full h-32 bg-slate-100 rounded-2xl flex items-center justify-center mb-4 border border-slate-200">
                                        <i class="fa-solid fa-car text-4xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-500 text-sm font-semibold">Silakan pilih mobil di form sebelah kiri.</p>
                                </div>

                                <div class="space-y-3 border-t border-dashed border-gray-200 pt-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Harga Unit</span>
                                        <span class="font-bold text-gray-800" id="harga_unit_display">
                                            Rp <?php echo e(isset($selectedMobil) ? number_format($selectedMobil->harga_sewa) : '0'); ?>

                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Durasi Sewa</span>
                                        <span class="font-bold text-blue-600" id="durasi_text">0 Hari</span>
                                    </div>
                                    <div class="flex justify-between text-sm hidden" id="row_sopir">
                                        <span class="text-gray-500">Biaya Sopir</span>
                                        <span class="font-bold text-gray-800" id="biaya_sopir_display">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-sm hidden" id="row_bandara_jemput">
                                        <span class="text-gray-500">Jemput Bandara</span>
                                        <span class="font-bold text-gray-800" id="biaya_bandara_jemput_display">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-sm hidden" id="row_bandara_antar">
                                        <span class="text-gray-500">Antar Bandara</span>
                                        <span class="font-bold text-gray-800" id="biaya_bandara_antar_display">Rp 0</span>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t-2 border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 font-bold">Total Estimasi</span>
                                        <span class="text-2xl font-extrabold text-blue-600" id="total_text">Rp 0</span>
                                    </div>
                                </div>

                                
                                <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                                    <h4 class="text-sm font-bold text-blue-800 uppercase mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-shield-check"></i> Pembayaran Aman
                                    </h4>
                                    <p class="text-xs text-gray-600 leading-relaxed">
                                        Pembayaran dilakukan melalui <strong>Payment Gateway Resmi</strong>. Anda dapat membayar menggunakan Transfer Bank (Virtual Account), E-Wallet (QRIS, OVO, Dana), atau Kartu Kredit setelah konfirmasi booking.
                                    </p>
                                    <div class="mt-3 flex gap-2 opacity-50 grayscale hover:grayscale-0 transition duration-300">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dan_Nama_Bank_BCA.svg" class="h-4" alt="BCA">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="h-4" alt="BCA">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo.svg" class="h-4" alt="OVO">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana.png" class="h-4" alt="DANA">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" class="h-4" alt="QRIS">
                                    </div>
                                </div>

                                <button type="submit" 
                                        class="w-full mt-6 bg-slate-900 hover:bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 flex justify-center items-center gap-2 group">
                                    Konfirmasi Booking
                                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>
                                
                                <p class="text-xs text-center text-gray-400 mt-4">
                                    <i class="fa-solid fa-shield-halved mr-1"></i> Data Anda diamankan dengan enkripsi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <script>
        function previewFile(inputId, textId) {
            const file = document.getElementById(inputId).files[0];
            const nameLabel = document.getElementById(textId);
            if(file) {
                nameLabel.innerText = "File: " + file.name;
                nameLabel.classList.remove('hidden');
            }
        }

        function formatTime(h, m) {
            return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
        }

        function roundUpToQuarterHour(date) {
            const d = new Date(date);
            d.setSeconds(0, 0);
            const m = d.getMinutes();
            const add = (15 - (m % 15)) % 15;
            d.setMinutes(m + add);
            return formatTime(d.getHours(), d.getMinutes());
        }

        function isValidQuarterMinute(minStr) {
            return minStr === '00' || minStr === '15' || minStr === '30' || minStr === '45';
        }

        function validateTimeRealtime() {
            const tglAmbil = document.getElementById('tgl_ambil');
            const jamAmbil = document.getElementById('jam_ambil');
            if(!tglAmbil || !jamAmbil) return;
            if (!tglAmbil.value) return;

            const now = new Date();
            const selectedDate = new Date(tglAmbil.value);
            const currentTime = roundUpToQuarterHour(now);

            if (selectedDate.toDateString() === now.toDateString()) {
                if (jamAmbil.value && jamAmbil.value < currentTime) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Waktu Tidak Valid',
                        text: 'Maaf, waktu jemput tidak boleh kurang dari jam sekarang!',
                        confirmButtonColor: '#0f172a'
                    });
                    jamAmbil.value = currentTime;
                }
            }
        }

        const jamAmbil = document.getElementById('jam_ambil');
        const jamKembali = document.getElementById('jam_kembali');

        const tglAmbil = document.getElementById('tgl_ambil');
        const tglKembali = document.getElementById('tgl_kembali');
        const mobilSelect = document.getElementById('mobil_select');
        
        if (tglAmbil) {
            tglAmbil.addEventListener('change', validateTimeRealtime);
        }

        function buildTimePickerModal() {
            if (document.getElementById('time_picker_modal')) return;
            const el = document.createElement('div');
            el.id = 'time_picker_modal';
            el.className = 'fixed inset-0 z-[9999] hidden';
            el.innerHTML = `
                <div class="absolute inset-0 bg-black/30"></div>
                <div class="absolute left-1/2 top-1/2 w-[92%] max-w-sm -translate-x-1/2 -translate-y-1/2 rounded-2xl bg-white shadow-2xl border border-gray-100">
                    <div class="px-5 pt-4 pb-3 border-b border-gray-100 flex items-center justify-between">
                        <div class="text-sm font-extrabold text-slate-900">Pilih Waktu</div>
                        <button type="button" id="time_picker_close" class="text-sm font-bold text-gray-500 hover:text-gray-700">Tutup</button>
                    </div>
                    <div class="px-5 py-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs font-bold text-gray-500 uppercase mb-2">Jam</div>
                                <div id="time_picker_hours" class="h-56 overflow-y-auto rounded-xl border border-gray-200 bg-gray-50"></div>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-500 uppercase mb-2">Menit</div>
                                <div id="time_picker_minutes" class="h-56 overflow-y-auto rounded-xl border border-gray-200 bg-gray-50"></div>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 pb-5 flex justify-end">
                        <button type="button" id="time_picker_done" class="text-blue-600 font-extrabold">Selesai</button>
                    </div>
                </div>
            `;
            document.body.appendChild(el);
        }

        function openTimePicker(targetId) {
            buildTimePickerModal();
            const modal = document.getElementById('time_picker_modal');
            const overlay = modal.querySelector('div');
            const btnClose = document.getElementById('time_picker_close');
            const btnDone = document.getElementById('time_picker_done');
            const listHours = document.getElementById('time_picker_hours');
            const listMinutes = document.getElementById('time_picker_minutes');
            const target = document.getElementById(targetId);
            if (!modal || !listHours || !listMinutes || !target) return;

            const parse = (v) => {
                if (!v || !v.includes(':')) return null;
                const [h, m] = v.split(':');
                if (h.length !== 2 || m.length !== 2) return null;
                return { h, m };
            };

            const initValue = parse(target.value) || parse(roundUpToQuarterHour(new Date())) || { h: '09', m: '00' };
            let selectedH = initValue.h;
            let selectedM = isValidQuarterMinute(initValue.m) ? initValue.m : '00';

            const renderItem = (label, active, onClick) => {
                const b = document.createElement('button');
                b.type = 'button';
                b.className = `w-full text-left px-4 py-3 font-bold ${active ? 'bg-blue-600 text-white' : 'text-slate-800 hover:bg-gray-100'}`;
                b.textContent = label;
                b.addEventListener('click', onClick);
                return b;
            };

            const render = () => {
                listHours.innerHTML = '';
                listMinutes.innerHTML = '';

                for (let h = 0; h <= 23; h++) {
                    const hh = String(h).padStart(2, '0');
                    listHours.appendChild(renderItem(hh, hh === selectedH, () => {
                        selectedH = hh;
                        render();
                    }));
                }
                for (const mm of ['00', '15', '30', '45']) {
                    listMinutes.appendChild(renderItem(mm, mm === selectedM, () => {
                        selectedM = mm;
                        render();
                    }));
                }
            };

            const close = () => {
                modal.classList.add('hidden');
                btnDone.onclick = null;
                btnClose.onclick = null;
                overlay.onclick = null;
            };

            const apply = () => {
                const picked = `${selectedH}:${selectedM}`;
                if (targetId === 'jam_ambil') {
                    const dAmbil = document.getElementById('tgl_ambil');
                    if (dAmbil && dAmbil.value) {
                        const now = new Date();
                        const selectedDate = new Date(dAmbil.value);
                        if (selectedDate.toDateString() === now.toDateString()) {
                            const minT = roundUpToQuarterHour(now);
                            if (picked < minT) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Waktu Tidak Valid',
                                    text: 'Maaf, waktu jemput tidak boleh kurang dari jam sekarang!',
                                    confirmButtonColor: '#0f172a'
                                });
                                target.value = minT;
                                if (jamKembali && !jamKembali.value) {
                                    jamKembali.value = target.value;
                                }
                                close();
                                return;
                            }
                        }
                    }
                }
                target.value = picked;
                if (targetId === 'jam_ambil' && jamKembali && !jamKembali.value) {
                    jamKembali.value = picked;
                }
                validateTimeRealtime();
                close();
            };

            render();
            modal.classList.remove('hidden');
            overlay.onclick = close;
            btnClose.onclick = close;
            btnDone.onclick = apply;
        }

        document.querySelectorAll('[data-time-picker]').forEach((el) => {
            el.addEventListener('click', () => openTimePicker(el.getAttribute('data-time-picker')));
        });
        
        const summaryContent = document.getElementById('summary_content');
        const summaryPlaceholder = document.getElementById('mobil_placeholder');
        const summaryImg = document.getElementById('summary_img');
        const summaryTitle = document.getElementById('summary_title');
        const summaryDesc = document.getElementById('summary_desc');
        
        // Element Lokasi Peta
        const lokasiContent = document.getElementById('lokasi_content');
        const lokasiPlaceholder = document.getElementById('lokasi_placeholder');
        const tampilAlamatRental = document.getElementById('tampil_alamat_rental');
        const tampilSnk = document.getElementById('tampil_snk');
        const tampilMap = document.getElementById('tampil_map');
        const linkGmaps = document.getElementById('link_gmaps');
        
        let hargaDasar = <?php echo e(isset($selectedMobil) ? $selectedMobil->harga_sewa : 0); ?>;
        let biayaSopirPerHari = 0;
        let biayaBandaraPerTrip = 0;

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        const jemputLainWrap = document.getElementById('jemput_lain_wrap');
        const antarLainWrap = document.getElementById('antar_lain_wrap');
        const jemputLainInput = document.querySelector('textarea[name="alamat_jemput_lain"]');
        const antarLainInput = document.querySelector('textarea[name="alamat_antar_lain"]');

        function toggleAlamatLokasiLain() {
            const lokasiAmbilVal = document.querySelector('input[name="lokasi_ambil"]:checked')?.value || 'kantor';
            const lokasiKembaliVal = document.querySelector('input[name="lokasi_kembali"]:checked')?.value || 'kantor';

            if (jemputLainWrap) {
                if (lokasiAmbilVal === 'lainnya') {
                    jemputLainWrap.classList.remove('hidden');
                    setTimeout(initMapAmbil, 300);
                    if (jemputLainInput) jemputLainInput.required = true;
                } else {
                    jemputLainWrap.classList.add('hidden');
                    if (jemputLainInput) jemputLainInput.required = false;
                }
            }

            if (antarLainWrap) {
                if (lokasiKembaliVal === 'lainnya') {
                    antarLainWrap.classList.remove('hidden');
                    setTimeout(initMapKembali, 300);
                    if (antarLainInput) antarLainInput.required = true;
                } else {
                    antarLainWrap.classList.add('hidden');
                    if (antarLainInput) antarLainInput.required = false;
                }
            }
        }

        // === OPENSTREETMAP (LEAFLET) INITIALIZATION ===
        let mapAmbilCreated = false;
        let mapKembaliCreated = false;
        let mapAmbil, mapKembali, markerAmbil, markerKembali;

        function getLocation(type) {
            if (navigator.geolocation) {
                const map = type === 'ambil' ? mapAmbil : mapKembali;
                const marker = type === 'ambil' ? markerAmbil : markerKembali;
                const textId = type === 'ambil' ? 'map-ambil-text' : 'map-kembali-text';
                const hiddenId = type === 'ambil' ? 'alamat_jemput_val' : 'alamat_antar_val';
                const inputId = type === 'ambil' ? 'search-input-ambil' : 'search-input-kembali';

                const btn = event.currentTarget;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
                btn.disabled = true;

                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const pos = [lat, lon];

                    if (map && marker) {
                        map.setView(pos, 16);
                        marker.setLatLng(pos);

                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                            .then(res => res.json())
                            .then(data => {
                                const address = data.display_name || `Kordinat: ${lat}, ${lon}`;
                                document.getElementById(textId).innerText = address;
                                document.getElementById(hiddenId).value = address;
                                document.getElementById(inputId).value = address;
                                btn.innerHTML = originalText;
                                btn.disabled = false;
                            });
                    }
                }, (error) => {
                    console.error(error);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    Swal.fire('Gagal', 'Tidak dapat mengambil lokasi Anda. Pastikan GPS aktif dan izin diberikan.', 'error');
                });
            } else {
                Swal.fire('Gagal', 'Browser Anda tidak mendukung fitur lokasi.', 'error');
            }
        }

        function setupAutocomplete(inputId, suggestionsId, map, marker, textId, hiddenId) {
            const input = document.getElementById(inputId);
            const suggestionsContainer = document.getElementById(suggestionsId);
            let debounceTimer;

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value;
                if (query.length < 3) {
                    suggestionsContainer.classList.add('hidden');
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id`)
                        .then(res => res.json())
                        .then(data => {
                            suggestionsContainer.innerHTML = '';
                            if (data.length > 0) {
                                suggestionsContainer.classList.remove('hidden');
                                data.forEach(item => {
                                    const div = document.createElement('div');
                                    div.className = 'px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm border-b border-gray-100 last:border-0';
                                    div.innerText = item.display_name;
                                    div.addEventListener('click', () => {
                                        const lat = parseFloat(item.lat);
                                        const lon = parseFloat(item.lon);
                                        const pos = [lat, lon];
                                        
                                        map.setView(pos, 16);
                                        marker.setLatLng(pos);
                                        
                                        document.getElementById(textId).innerText = item.display_name;
                                        document.getElementById(hiddenId).value = item.display_name;
                                        input.value = item.display_name;
                                        
                                        suggestionsContainer.classList.add('hidden');
                                    });
                                    suggestionsContainer.appendChild(div);
                                });
                            } else {
                                suggestionsContainer.classList.add('hidden');
                            }
                        });
                }, 500);
            });

            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                    suggestionsContainer.classList.add('hidden');
                }
            });
        }

        function initMapAmbil() {
            if (mapAmbilCreated) {
                mapAmbil.invalidateSize();
                return;
            }
            const container = document.getElementById('map-ambil');
            if (!container) return;
            
            mapAmbil = L.map('map-ambil').setView([-6.2000, 106.8166], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapAmbil);

            markerAmbil = L.marker([-6.2000, 106.8166], {draggable: true}).addTo(mapAmbil);
            
            function onDragEnd() {
                const latlng = markerAmbil.getLatLng();
                document.getElementById('map-ambil-text').innerText = `Mencari alamat...`;
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`)
                    .then(res => res.json())
                    .then(data => {
                        const address = data.display_name || `Kordinat: ${latlng.lat}, ${latlng.lng}`;
                        document.getElementById('map-ambil-text').innerText = address;
                        document.getElementById('alamat_jemput_val').value = address;
                        document.getElementById('search-input-ambil').value = address;
                    });
            }
            markerAmbil.on('dragend', onDragEnd);
            setupAutocomplete('search-input-ambil', 'suggestions-ambil', mapAmbil, markerAmbil, 'map-ambil-text', 'alamat_jemput_val');
            mapAmbilCreated = true;
        }

        function initMapKembali() {
            if (mapKembaliCreated) {
                mapKembali.invalidateSize();
                return;
            }
            const container = document.getElementById('map-kembali');
            if (!container) return;

            mapKembali = L.map('map-kembali').setView([-6.2000, 106.8166], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapKembali);

            markerKembali = L.marker([-6.2000, 106.8166], {draggable: true}).addTo(mapKembali);
            
            function onDragEnd() {
                const latlng = markerKembali.getLatLng();
                document.getElementById('map-kembali-text').innerText = `Mencari alamat...`;
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`)
                    .then(res => res.json())
                    .then(data => {
                        const address = data.display_name || `Kordinat: ${latlng.lat}, ${latlng.lng}`;
                        document.getElementById('map-kembali-text').innerText = address;
                        document.getElementById('alamat_antar_val').value = address;
                        document.getElementById('search-input-kembali').value = address;
                    });
            }
            markerKembali.on('dragend', onDragEnd);
            setupAutocomplete('search-input-kembali', 'suggestions-kembali', mapKembali, markerKembali, 'map-kembali-text', 'alamat_antar_val');
            mapKembaliCreated = true;
        }

        function hitung() {
            validateTimeRealtime();
            toggleAlamatLokasiLain();

            if(mobilSelect) {
                const selectedOption = mobilSelect.options[mobilSelect.selectedIndex];
                
                if(selectedOption.value) {
                    // 1. UPDATE DATA RINGKASAN
                    hargaDasar = parseInt(selectedOption.getAttribute('data-harga')) || 0;
                    summaryImg.src = selectedOption.getAttribute('data-img');
                    summaryTitle.innerText = selectedOption.getAttribute('data-nama');
                    summaryDesc.innerText = selectedOption.getAttribute('data-desc');
                    summaryContent.classList.remove('hidden');
                    summaryPlaceholder.classList.add('hidden');
                    document.getElementById('harga_unit_display').innerText = 'Rp ' + formatRupiah(hargaDasar);

                    biayaSopirPerHari = parseInt(selectedOption.getAttribute('data-biaya-sopir-per-hari')) || 0;
                    biayaBandaraPerTrip = parseInt(selectedOption.getAttribute('data-biaya-bandara-per-trip')) || 0;
                    const elSopir = document.getElementById('label_biaya_sopir_per_hari');
                    const elBandaraJemput = document.getElementById('label_biaya_bandara_per_trip_jemput');
                    const elBandaraAntar = document.getElementById('label_biaya_bandara_per_trip_antar');
                    if (elSopir) elSopir.innerText = formatRupiah(biayaSopirPerHari);
                    if (elBandaraJemput) elBandaraJemput.innerText = formatRupiah(biayaBandaraPerTrip);
                    if (elBandaraAntar) elBandaraAntar.innerText = formatRupiah(biayaBandaraPerTrip);
                    
                    // 2. UPDATE LOKASI DAN PETA
                    const alamatFinal = selectedOption.getAttribute('data-alamat') || '';
                    const kotaFinal = selectedOption.getAttribute('data-kota') || '';
                    const mapUrl = selectedOption.getAttribute('data-map') || '';
                    const snkRaw = selectedOption.getAttribute('data-snk') || '';
                    let snkFinal = '';
                    try {
                        snkFinal = JSON.parse(snkRaw);
                    } catch (e) {
                        snkFinal = snkRaw;
                    }
                    
                    tampilAlamatRental.innerText = alamatFinal;
                    tampilSnk.innerText = snkFinal || 'Tidak ada syarat dan ketentuan khusus dari mitra ini.';
                    
                    if (mapUrl && mapUrl.trim() !== '') {
                        tampilMap.src = mapUrl;
                        linkGmaps.href = mapUrl;
                    } else {
                        const queryPeta = encodeURIComponent(alamatFinal + " " + kotaFinal);
                        tampilMap.src = `https://maps.google.com/maps?q=${queryPeta}&hl=id&z=15&output=embed`;
                        linkGmaps.href = `https://maps.google.com/maps?q=${queryPeta}`;
                    }
                    
                    tampilMap.parentElement.classList.remove('hidden');
                    lokasiContent.classList.remove('hidden');
                    lokasiPlaceholder.classList.add('hidden');

                } else {
                    summaryContent.classList.add('hidden');
                    summaryPlaceholder.classList.remove('hidden');
                    hargaDasar = 0;
                    biayaSopirPerHari = 0;
                    biayaBandaraPerTrip = 0;
                    document.getElementById('harga_unit_display').innerText = 'Rp 0';
                    const elSopir = document.getElementById('label_biaya_sopir_per_hari');
                    const elBandaraJemput = document.getElementById('label_biaya_bandara_per_trip_jemput');
                    const elBandaraAntar = document.getElementById('label_biaya_bandara_per_trip_antar');
                    if (elSopir) elSopir.innerText = '0';
                    if (elBandaraJemput) elBandaraJemput.innerText = '0';
                    if (elBandaraAntar) elBandaraAntar.innerText = '0';
                    
                    tampilSnk.innerText = 'Pilih armada di langkah 1 terlebih dahulu untuk melihat Syarat & Ketentuan dari Mitra Rental terkait.';

                    lokasiContent.classList.add('hidden');
                    lokasiPlaceholder.classList.remove('hidden');
                }
            }

            // HITUNG TOTAL BIAYA (DURASI + SOPIR)
            let totalDays = 0;
            if(tglAmbil.value && tglKembali.value) {
                const start = new Date(tglAmbil.value);
                const end = new Date(tglKembali.value);
                
                if (end < start) {
                    totalDays = 0;
                } else {
                    const diffTime = end - start;
                    const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    totalDays = days > 0 ? days : 1; 
                }
            }

            const sopirElem = document.querySelector('input[name="sopir"]:checked');
            const pakaiSopir = sopirElem ? sopirElem.value === 'dengan_sopir' : false;
            const totalSopir = pakaiSopir ? (biayaSopirPerHari * totalDays) : 0;
            const lokasiAmbilVal = document.querySelector('input[name="lokasi_ambil"]:checked')?.value || 'kantor';
            const lokasiKembaliVal = document.querySelector('input[name="lokasi_kembali"]:checked')?.value || 'kantor';
            const totalBandaraJemput = (lokasiAmbilVal === 'bandara') ? biayaBandaraPerTrip : 0;
            const totalBandaraAntar = (lokasiKembaliVal === 'bandara') ? biayaBandaraPerTrip : 0;

            const grandTotal = (hargaDasar * totalDays) + totalSopir + totalBandaraJemput + totalBandaraAntar;

            document.getElementById('durasi_text').innerText = totalDays + ' Hari';
            
            if(pakaiSopir) {
                document.getElementById('row_sopir').classList.remove('hidden');
                document.getElementById('biaya_sopir_display').innerText = 'Rp ' + formatRupiah(totalSopir);
            } else {
                document.getElementById('row_sopir').classList.add('hidden');
            }

            if(totalBandaraJemput > 0) {
                document.getElementById('row_bandara_jemput').classList.remove('hidden');
                document.getElementById('biaya_bandara_jemput_display').innerText = 'Rp ' + formatRupiah(totalBandaraJemput);
            } else {
                document.getElementById('row_bandara_jemput').classList.add('hidden');
            }

            if(totalBandaraAntar > 0) {
                document.getElementById('row_bandara_antar').classList.remove('hidden');
                document.getElementById('biaya_bandara_antar_display').innerText = 'Rp ' + formatRupiah(totalBandaraAntar);
            } else {
                document.getElementById('row_bandara_antar').classList.add('hidden');
            }

            document.getElementById('total_text').innerText = 'Rp ' + formatRupiah(grandTotal);
            document.getElementById('input_total_harga').value = grandTotal;
            document.getElementById('input_lama_sewa').value = totalDays;
        }

        if(mobilSelect) mobilSelect.addEventListener('change', hitung);
        if(tglAmbil) tglAmbil.addEventListener('change', hitung);
        if(tglKembali) tglKembali.addEventListener('change', hitung);
        document.querySelectorAll('input[name="sopir"]').forEach(r => r.addEventListener('change', hitung));
        document.querySelectorAll('input[name="lokasi_ambil"]').forEach(r => r.addEventListener('change', hitung));
        document.querySelectorAll('input[name="lokasi_kembali"]').forEach(r => r.addEventListener('change', hitung));

        window.onload = hitung;
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\pages\order.blade.php ENDPATH**/ ?>