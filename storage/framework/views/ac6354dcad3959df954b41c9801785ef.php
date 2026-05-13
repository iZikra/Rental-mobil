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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>

    <div class="min-h-screen bg-[#f8fafc] font-outfit pb-20">
        
        <div class="relative overflow-hidden bg-slate-900 py-24 sm:py-32">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] bg-blue-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute -bottom-[30%] -left-[10%] w-[70%] h-[70%] bg-indigo-600/10 rounded-full blur-[120px]"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-[0.2em] mb-8">
                    Contact Us
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6">
                    Mari <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Terhubung</span>
                </h1>
                <p class="max-w-2xl mx-auto text-lg text-slate-400 font-medium leading-relaxed">
                    Ada pertanyaan tentang armada kami? Tim kami siap membantu Anda kapan saja.
                </p>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-20 relative z-10">
            <div class="grid lg:grid-cols-3 gap-8">
                
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="glass-card p-8 rounded-[2.5rem] shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-blue-600/20">
                            <i class="fa-solid fa-location-dot text-xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-2">Lokasi Kami</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">Jl. Teropong, Riau, Pekanbaru</p>
                    </div>

                    <div class="glass-card p-8 rounded-[2.5rem] shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-emerald-600/20">
                            <i class="fa-brands fa-whatsapp text-xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-2">WhatsApp</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">+62 838 4596 6102</p>
                    </div>

                    <div class="glass-card p-8 rounded-[2.5rem] shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 bg-amber-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-amber-600/20">
                            <i class="fa-solid fa-envelope text-xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-2">Email</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">support@rentcar-project.com</p>
                    </div>
                </div>

                
                <div class="lg:col-span-2">
                    <div class="glass-card p-10 md:p-12 rounded-[3rem] shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-10 opacity-5">
                            <i class="fa-solid fa-paper-plane text-9xl"></i>
                        </div>
                        
                        <h2 class="text-3xl font-black text-slate-900 mb-8">Kirim Pesan</h2>
                        
                        <form action="#" class="space-y-6 relative z-10">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                    <input type="text" placeholder="Masukkan nama Anda" 
                                           class="w-full px-6 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all font-medium text-slate-900">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                                    <input type="email" placeholder="nama@email.com" 
                                           class="w-full px-6 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all font-medium text-slate-900">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Pesan</label>
                                <textarea rows="5" placeholder="Apa yang bisa kami bantu?" 
                                          class="w-full px-6 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all font-medium text-slate-900"></textarea>
                            </div>

                            <button type="button" class="w-full md:w-auto px-10 py-5 bg-slate-900 text-white font-black rounded-2xl hover:bg-blue-600 transition-all shadow-xl hover:-translate-y-1">
                                KIRIM PESAN SEKARANG
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="mt-20 p-8 rounded-[2.5rem] bg-blue-50 border border-blue-100 text-center">
                <div class="flex items-center justify-center gap-2 text-blue-600 font-black uppercase tracking-widest text-xs mb-4">
                    <i class="fa-solid fa-graduation-cap"></i>
                    Academic Project Notice
                </div>
                <p class="max-w-3xl mx-auto text-slate-500 font-medium leading-relaxed">
                    Website ini merupakan proyek pengembangan perangkat lunak untuk memenuhi **syarat kelulusan akademik**. Seluruh data, armada, dan transaksi yang ditampilkan adalah simulasi dan tidak digunakan untuk tujuan operasional bisnis nyata.
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/pages/contact.blade.php ENDPATH**/ ?>