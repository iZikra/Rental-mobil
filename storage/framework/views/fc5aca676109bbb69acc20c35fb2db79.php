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
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap');
        
        .font-outfit { font-family: 'Outfit', sans-serif; }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.1), transparent),
                        radial-gradient(circle at bottom left, rgba(99, 102, 241, 0.1), transparent);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>

    <div class="min-h-screen bg-[#f8fafc] font-outfit pb-20">
        
        <div class="relative overflow-hidden bg-slate-900 py-24 sm:py-32">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] bg-blue-600/20 rounded-full blur-[120px] animate-pulse"></div>
                <div class="absolute -bottom-[30%] -left-[10%] w-[70%] h-[70%] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-[0.2em] mb-8">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    Project Showcase
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6">
                    Tentang <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Proyek Ini</span>
                </h1>
                <p class="max-w-2xl mx-auto text-lg text-slate-400 font-medium leading-relaxed mb-10">
                    Aplikasi persewaan mobil berbasis web yang dirancang untuk mengintegrasikan teknologi AI dalam pencarian armada dan manajemen mitra yang efisien.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="<?php echo e(route('home')); ?>" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-blue-600/20 hover:-translate-y-1">Eksplorasi Aplikasi</a>
                    <a href="#features" class="px-8 py-4 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl border border-white/10 transition-all backdrop-blur-sm">Fitur Utama</a>
                </div>
            </div>
        </div>

        
        <div id="features" class="max-w-7xl mx-auto px-6 lg:px-8 -mt-16 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="glass-card p-8 rounded-[2.5rem] text-center shadow-xl">
                    <div class="w-10 h-10 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-600 mx-auto mb-4">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <p class="text-slate-900 text-sm font-black uppercase tracking-widest">RAG AI Search</p>
                </div>
                <div class="glass-card p-8 rounded-[2.5rem] text-center shadow-xl">
                    <div class="w-10 h-10 bg-indigo-600/10 rounded-xl flex items-center justify-center text-indigo-600 mx-auto mb-4">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <p class="text-slate-900 text-sm font-black uppercase tracking-widest">Multi-Tenant</p>
                </div>
                <div class="glass-card p-8 rounded-[2.5rem] text-center shadow-xl">
                    <div class="w-10 h-10 bg-amber-600/10 rounded-xl flex items-center justify-center text-amber-600 mx-auto mb-4">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <p class="text-slate-900 text-sm font-black uppercase tracking-widest">Secure Auth</p>
                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-24">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-100 rounded-full blur-3xl opacity-60"></div>
                    <div class="relative glass-card rounded-[3rem] p-2 overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=2070&auto=format&fit=crop" 
                             alt="Academic Project" 
                             class="rounded-[2.5rem] w-full h-[500px] object-cover">
                    </div>
                </div>
                
                <div class="space-y-12">
                    <div class="relative group">
                        <div class="flex items-start gap-6">
                            <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg shrink-0">
                                <i class="fa-solid fa-bullseye text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 mb-4">Tujuan Proyek</h3>
                                <p class="text-slate-500 font-medium leading-relaxed">
                                    Proyek ini dikembangkan untuk mendemonstrasikan implementasi sistem informasi manajemen persewaan mobil yang modern, aman, dan cerdas dengan pemanfaatan Large Language Models (LLM) melalui metode RAG (Retrieval-Augmented Generation).
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="relative group">
                            <div class="flex items-start gap-6">
                                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20 shrink-0">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 mb-4"><?php echo e($item->judul); ?></h3>
                                    <p class="text-slate-500 font-medium leading-relaxed whitespace-pre-line"><?php echo e($item->isi); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="bg-slate-900 py-24 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:40px_40px]"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-6 lg:px-8 relative text-center">
                <h2 class="text-blue-400 text-xs font-black uppercase tracking-[0.3em] mb-4">Technology Stack</h2>
                <h3 class="text-4xl font-black text-white mb-16">Dibangun Dengan Teknologi Modern</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="p-8 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition-all">
                        <i class="fa-brands fa-laravel text-4xl text-rose-500 mb-4"></i>
                        <p class="text-white font-bold">Laravel 11</p>
                    </div>
                    <div class="p-8 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition-all">
                        <i class="fa-brands fa-python text-4xl text-blue-400 mb-4"></i>
                        <p class="text-white font-bold">Python RAG</p>
                    </div>
                    <div class="p-8 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition-all">
                        <i class="fa-solid fa-database text-4xl text-cyan-400 mb-4"></i>
                        <p class="text-white font-bold">MySQL Database</p>
                    </div>
                    <div class="p-8 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition-all">
                        <i class="fa-solid fa-wind text-4xl text-sky-400 mb-4"></i>
                        <p class="text-white font-bold">Tailwind CSS</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mt-24">
            <div class="bg-blue-50 border border-blue-100 rounded-[3rem] p-10 text-center">
                <div class="flex items-center justify-center gap-2 text-blue-600 font-black uppercase tracking-widest text-xs mb-4">
                    <i class="fa-solid fa-graduation-cap"></i>
                    Academic Project 
                </div>
                <p class="max-w-3xl mx-auto text-slate-500 font-medium leading-relaxed">
                    <!-- Website ini merupakan proyek pengembangan perangkat lunak untuk memenuhi **syarat kelulusan akademik**. Seluruh data, armada, dan transaksi yang ditampilkan adalah simulasi dan tidak digunakan untuk tujuan operasional bisnis nyata. -->
                </p>
            </div>
        </div>
    </div>
    
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/pages/tentang_kami.blade.php ENDPATH**/ ?>