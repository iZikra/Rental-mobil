<x-app-layout>
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
        {{-- Hero Section --}}
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
                    Our Journey
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6">
                    Kenalan dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Rental Kami</span>
                </h1>
                <p class="max-w-2xl mx-auto text-lg text-slate-400 font-medium leading-relaxed mb-10">
                    Lebih dari sekadar penyedia armada, kami adalah partner setia yang menemani setiap kilometer perjalanan Anda dengan kenyamanan dan keamanan terbaik.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('home') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-blue-600/20 hover:-translate-y-1">Mulai Perjalanan</a>
                    <a href="#stats" class="px-8 py-4 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl border border-white/10 transition-all backdrop-blur-sm">Lihat Pencapaian</a>
                </div>
            </div>
        </div>

        {{-- Stats Section --}}
        <div id="stats" class="max-w-7xl mx-auto px-6 lg:px-8 -mt-16 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="glass-card p-8 rounded-[2.5rem] text-center shadow-xl">
                    <p class="text-4xl font-black text-slate-900 mb-2">1.2k+</p>
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">Happy Clients</p>
                </div>
                <div class="glass-card p-8 rounded-[2.5rem] text-center shadow-xl">
                    <p class="text-4xl font-black text-blue-600 mb-2">500+</p>
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">Premium Fleet</p>
                </div>
                <div class="glass-card p-8 rounded-[2.5rem] text-center shadow-xl">
                    <p class="text-4xl font-black text-slate-900 mb-2">15+</p>
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">Cities Covered</p>
                </div>
                <div class="glass-card p-8 rounded-[2.5rem] text-center shadow-xl">
                    <p class="text-4xl font-black text-indigo-600 mb-2">24/7</p>
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">Expert Support</p>
                </div>
            </div>
        </div>

        {{-- Content from Database (Visi/Misi etc) --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-24">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-100 rounded-full blur-3xl opacity-60"></div>
                    <div class="relative glass-card rounded-[3rem] p-2 overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop" 
                             alt="Professional Car Rental" 
                             class="rounded-[2.5rem] w-full h-[500px] object-cover">
                    </div>
                </div>
                
                <div class="space-y-12">
                    @forelse($data as $item)
                        <div class="relative group">
                            <div class="flex items-start gap-6">
                                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20 shrink-0">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 mb-4">{{ $item->judul }}</h3>
                                    <p class="text-slate-500 font-medium leading-relaxed whitespace-pre-line">{{ $item->isi }}</p>
                                </div>
                            </div>

                            @if(Auth::check() && Auth::user()->role == 'admin')
                                <div class="mt-4 flex gap-2">
                                    <a href="{{ route('admin.tentang_kami.edit', $item->id) }}" class="text-xs font-bold text-blue-600 hover:underline">EDIT SECTION</a>
                                    <form action="{{ route('admin.tentang_kami.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-rose-600 hover:underline" onclick="return confirm('Hapus?')">HAPUS</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                            <p class="text-slate-400 font-bold uppercase tracking-widest">Belum ada konten tambahan.</p>
                        </div>
                    @endforelse

                    @if(Auth::check() && Auth::user()->role == 'admin')
                        <a href="{{ route('admin.tentang_kami.create') }}" class="inline-flex items-center gap-3 px-6 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                            TAMBAH SEKSI BARU
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Core Values Section --}}
        <div class="bg-slate-900 py-24 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:40px_40px]"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-6 lg:px-8 relative">
                <div class="text-center mb-16">
                    <h2 class="text-blue-400 text-xs font-black uppercase tracking-[0.3em] mb-4">Core Values</h2>
                    <h3 class="text-4xl font-black text-white">Mengapa Memilih Kami?</h3>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-10 bg-white/5 border border-white/10 rounded-[2.5rem] hover:bg-white/10 transition-all group">
                        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="text-xl font-black text-white mb-4">Keamanan Prioritas</h4>
                        <p class="text-slate-400 font-medium leading-relaxed">Setiap unit kami melewati inspeksi ketat dan pemeliharaan berkala untuk menjamin keselamatan Anda.</p>
                    </div>

                    <div class="p-10 bg-white/5 border border-white/10 rounded-[2.5rem] hover:bg-white/10 transition-all group">
                        <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center text-white mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="text-xl font-black text-white mb-4">Harga Kompetitif</h4>
                        <p class="text-slate-400 font-medium leading-relaxed">Kami menawarkan tarif rental terbaik tanpa biaya tersembunyi. Transparansi adalah kunci layanan kami.</p>
                    </div>

                    <div class="p-10 bg-white/5 border border-white/10 rounded-[2.5rem] hover:bg-white/10 transition-all group">
                        <div class="w-16 h-16 bg-amber-600 rounded-2xl flex items-center justify-center text-white mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h4 class="text-xl font-black text-white mb-4">Layanan Cepat</h4>
                        <p class="text-slate-400 font-medium leading-relaxed">Proses booking instan dan konfirmasi cepat. Armada siap kapanpun Anda membutuhkannya.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Team Section --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-24">
            <div class="text-center mb-16">
                <h2 class="text-blue-600 text-xs font-black uppercase tracking-[0.3em] mb-4">The Architects</h2>
                <h3 class="text-4xl font-black text-slate-900">Tim Di Balik Layar</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                {{-- CEO --}}
                <div class="group">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 bg-blue-600 rounded-[3rem] rotate-6 group-hover:rotate-0 transition-transform duration-500"></div>
                        <div class="relative bg-slate-100 rounded-[3rem] overflow-hidden aspect-square border-4 border-white shadow-2xl">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=1974&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="text-2xl font-black text-slate-900">Ahmad Fauzi</h4>
                        <p class="text-blue-600 font-bold uppercase tracking-widest text-xs mt-2">CEO & Founder</p>
                    </div>
                </div>

                {{-- Manager --}}
                <div class="group">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 bg-indigo-600 rounded-[3rem] -rotate-6 group-hover:rotate-0 transition-transform duration-500"></div>
                        <div class="relative bg-slate-100 rounded-[3rem] overflow-hidden aspect-square border-4 border-white shadow-2xl">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1976&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="text-2xl font-black text-slate-900">Sarah Wijaya</h4>
                        <p class="text-indigo-600 font-bold uppercase tracking-widest text-xs mt-2">Operations Manager</p>
                    </div>
                </div>

                {{-- CS --}}
                <div class="group">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 bg-emerald-600 rounded-[3rem] rotate-3 group-hover:rotate-0 transition-transform duration-500"></div>
                        <div class="relative bg-slate-100 rounded-[3rem] overflow-hidden aspect-square border-4 border-white shadow-2xl">
                            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=1961&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="text-2xl font-black text-slate-900">Budi Santoso</h4>
                        <p class="text-emerald-600 font-bold uppercase tracking-widest text-xs mt-2">CS Support Lead</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA Section --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-blue-600 rounded-[4rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl shadow-blue-600/30">
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <h3 class="text-3xl md:text-5xl font-black text-white mb-8">Siap Memulai Perjalanan Anda?</h3>
                    <p class="text-blue-100 text-lg font-medium max-w-2xl mx-auto mb-12">Bergabunglah dengan ribuan pelanggan puas lainnya dan nikmati pengalaman berkendara yang berbeda.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-4 px-10 py-5 bg-white text-blue-600 font-black rounded-2xl hover:bg-slate-900 hover:text-white transition-all transform hover:scale-105">
                        PESAN SEKARANG
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>