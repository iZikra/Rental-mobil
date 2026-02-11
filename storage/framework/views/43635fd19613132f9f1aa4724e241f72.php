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
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.3s; }
        .delay-300 { animation-delay: 0.5s; }
    </style>

    
    <div class="relative bg-fixed bg-center bg-cover h-[85vh]" 
         style="background-image: url('https://images.unsplash.com/photo-1485291571150-772bcfc10da5?q=80&w=2000&auto=format&fit=crop');">
        
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-slate-900/90"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center">
            <div class="md:w-3/4 lg:w-2/3">
                <div class="animate-fade-up">
                    <span class="inline-flex items-center gap-2 py-1 px-4 rounded-full bg-blue-600/30 border border-blue-400 backdrop-blur-md text-blue-100 text-sm font-semibold mb-6">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        Solusi Transportasi Premium
                    </span>
                </div>

                <h1 class="animate-fade-up delay-100 text-5xl md:text-7xl font-extrabold text-white tracking-tight leading-tight mb-6">
                    Bebaskan Langkah, <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">Nikmati Perjalanan.</span>
                </h1>

                <p class="animate-fade-up delay-200 text-lg md:text-xl text-gray-300 mb-10 max-w-2xl leading-relaxed font-light">
                    Sewa mobil lepas kunci atau dengan sopir profesional. Armada terbaru, bersih, dan siap mengantar Anda ke tujuan dengan gaya.
                </p>

                <div class="animate-fade-up delay-300 flex flex-wrap gap-4">
                    <a href="#booking-widget" class="group bg-blue-600 hover:bg-blue-500 text-white px-8 py-4 rounded-full font-bold transition-all duration-300 shadow-[0_0_20px_rgba(37,99,235,0.5)] hover:shadow-[0_0_30px_rgba(37,99,235,0.7)] flex items-center gap-3">
                        Mulai Booking
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="<?php echo e(route('pages.about')); ?>" class="group bg-white/5 hover:bg-white/10 backdrop-blur-sm border border-white/20 text-white px-8 py-4 rounded-full font-bold transition flex items-center gap-3">
                        <i class="fa-regular fa-circle-play text-xl"></i>
                        Tentang Kami
                    </a>
                </div>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 text-white animate-bounce text-center opacity-70">
            <span class="text-xs uppercase tracking-widest mb-2 block">Scroll Down</span>
            <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </div>

    
    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-blue-600 font-bold tracking-wider uppercase text-sm">Kenapa Kami?</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2">Standar Baru Rental Mobil</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="bg-white p-8 rounded-3xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:-translate-y-2 transition duration-500 border border-gray-100">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 text-2xl">
                        <i class="fa-solid fa-car-on"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Armada Premium</h3>
                    <p class="text-gray-500 leading-relaxed">Unit mobil selalu di bawah 3 tahun pemakaian, bersih, wangi, dan diservis secara berkala di bengkel resmi.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:-translate-y-2 transition duration-500 border border-gray-100">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 mb-6 text-2xl">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Harga Jujur</h3>
                    <p class="text-gray-500 leading-relaxed">Apa yang Anda lihat adalah yang Anda bayar. Tidak ada biaya tersembunyi saat pengambilan kunci.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:-translate-y-2 transition duration-500 border border-gray-100">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mb-6 text-2xl">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Support 24 Jam</h3>
                    <p class="text-gray-500 leading-relaxed">Mengalami kendala di jalan? Tim darurat kami siap membantu Anda kapanpun dibutuhkan.</p>
                </div>
            </div>
        </div>
    </div>

    
    <div id="list-mobil" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                <div>
                    <span class="text-blue-600 font-bold tracking-wider uppercase text-sm">Koleksi Terbaru</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2">Pilihan Armada Terbaik</h2>
                </div>
                <a href="<?php echo e(route('pages.order')); ?>" class="group flex items-center gap-2 text-slate-600 hover:text-blue-600 font-bold transition">
                    Lihat Semua Mobil 
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__currentLoopData = $mobils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mobil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group bg-white rounded-3xl border border-gray-100 shadow-lg hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                    
                    
                    <div class="absolute top-5 right-5 z-10">
                        <?php if($mobil->status == 'tersedia'): ?>
                            <span class="px-4 py-2 bg-green-100 text-green-800 text-xs font-bold rounded-full shadow-sm flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Tersedia
                            </span>
                        <?php else: ?>
                            <span class="px-4 py-2 bg-gray-100 text-gray-500 text-xs font-bold rounded-full shadow-sm flex items-center gap-1 border border-gray-200">
                                <i class="fa-solid fa-lock"></i> Disewa
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="h-64 bg-gray-50 flex items-center justify-center p-8 relative overflow-hidden">
                        <div class="absolute w-64 h-64 bg-blue-500/10 rounded-full scale-0 group-hover:scale-150 transition-transform duration-700 ease-out"></div>
                        
                        <img src="<?php echo e(asset('img/' . $mobil->gambar)); ?>" 
                             alt="<?php echo e($mobil->merek); ?>" 
                             class="w-full h-full object-contain relative z-10 group-hover:scale-110 transition-transform duration-500 drop-shadow-lg <?php echo e($mobil->status != 'tersedia' ? 'grayscale opacity-70' : ''); ?>">
                    </div>

                    <div class="p-8">
                        <div class="mb-4">
                            <p class="text-xs text-blue-600 font-extrabold uppercase tracking-widest mb-1"><?php echo e($mobil->merek); ?></p>
                            <h3 class="text-2xl font-bold text-slate-900 group-hover:text-blue-600 transition"><?php echo e($mobil->merek); ?> <?php echo e($mobil->model); ?></h3>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-500 mb-6 border-y border-gray-100 py-4">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-chair text-blue-400"></i>
                                <span class="font-medium"><?php echo e($mobil->jumlah_kursi); ?> Kursi</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-gears text-blue-400"></i>
                                <span class="font-medium"><?php echo e($mobil->transmisi); ?></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-gray-400 text-xs font-bold uppercase">Harga Sewa</span>
                                <div class="flex items-end gap-1">
                                    <span class="text-2xl font-bold text-slate-900">Rp <?php echo e(number_format($mobil->harga_sewa, 0, ',', '.')); ?></span>
                                </div>
                            </div>
                            
                            
                            <?php if($mobil->status == 'tersedia'): ?>
                                
                                <a href="<?php echo e(route('pages.order', ['mobil_id' => $mobil->id])); ?>" 
                                   class="w-12 h-12 bg-slate-900 hover:bg-blue-600 text-white rounded-full flex items-center justify-center transition-colors shadow-lg group-hover:rotate-45 duration-300"
                                   title="Sewa Sekarang">
                                    <i class="fa-solid fa-arrow-up"></i>
                                </a>
                            <?php else: ?>
                                
                                <button disabled 
                                        class="w-12 h-12 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center cursor-not-allowed shadow-none"
                                        title="Unit Sedang Disewa">
                                    <i class="fa-solid fa-lock"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

             <div class="mt-12 text-center md:hidden">
                <a href="<?php echo e(route('pages.order')); ?>" class="w-full inline-block bg-white border border-gray-300 text-slate-900 px-6 py-4 rounded-xl font-bold hover:bg-gray-50 transition">
                    Lihat Semua Armada
                </a>
            </div>
        </div>
    </div>

    
    <div class="relative py-32 bg-slate-900 overflow-hidden isolate">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.blue.900),theme(colors.slate.900))] opacity-50"></div>
        <div class="absolute inset-y-0 right-1/2 -z-10 mr-16 w-[200%] origin-bottom-left skew-x-[-30deg] bg-slate-900 shadow-xl shadow-blue-600/10 ring-1 ring-blue-50 sm:mr-28 lg:mr-0 xl:mr-16 xl:origin-center"></div>
        
        <div class="relative max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 tracking-tight">Siap untuk Perjalanan Impian?</h2>
            <p class="text-blue-100 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">Jangan biarkan transportasi menghambat mobilitas Anda. Dapatkan penawaran eksklusif via WhatsApp kami sekarang juga.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="https://wa.me/6285375285567" class="bg-green-500 hover:bg-green-400 text-white px-8 py-4 rounded-full font-bold shadow-lg shadow-green-500/30 flex items-center justify-center gap-2 transition transform hover:scale-105">
                    <i class="fa-brands fa-whatsapp text-xl"></i> 
                    Chat WhatsApp
                </a>
                <a href="<?php echo e(route('pages.contact')); ?>" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-8 py-4 rounded-full font-bold backdrop-blur-sm transition flex items-center justify-center gap-2">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginal662fac80dd7ea9f5f1f2fae88b808dd2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal662fac80dd7ea9f5f1f2fae88b808dd2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.chatbot','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('chatbot'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal662fac80dd7ea9f5f1f2fae88b808dd2)): ?>
<?php $attributes = $__attributesOriginal662fac80dd7ea9f5f1f2fae88b808dd2; ?>
<?php unset($__attributesOriginal662fac80dd7ea9f5f1f2fae88b808dd2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal662fac80dd7ea9f5f1f2fae88b808dd2)): ?>
<?php $component = $__componentOriginal662fac80dd7ea9f5f1f2fae88b808dd2; ?>
<?php unset($__componentOriginal662fac80dd7ea9f5f1f2fae88b808dd2); ?>
<?php endif; ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\dashboard.blade.php ENDPATH**/ ?>