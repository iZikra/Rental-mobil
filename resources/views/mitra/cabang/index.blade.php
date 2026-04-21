<x-app-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap');
    
    body {
        font-family: 'Outfit', sans-serif;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .glass-dark {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(10px);
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }

    .modal-overlay.show {
        display: flex;
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to   { opacity: 1; transform: translateY(0)   scale(1); }
    }

    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-box {
        animation: slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-entry {
        animation: fade-in-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .branch-card {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .branch-card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
    }

    .input-glass {
        background: rgba(248, 250, 252, 0.8);
        border: 1.5px solid rgba(226, 232, 240, 0.8);
        transition: all 0.3s ease;
    }

    .input-glass-icon {
        padding-left: 4rem !important;
    }

    .input-glass:focus {
        background: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
</style>

<div class="min-h-screen bg-[#f8fafc] text-slate-900 pb-20">
    {{-- Decorative Background Elements --}}
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-100/40 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-100/40 rounded-full blur-[120px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">

        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-16 animate-entry">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="h-[2px] w-8 bg-blue-600 rounded-full"></span>
                    <span class="text-blue-600 font-black text-xs uppercase tracking-[0.3em]">Operational Hub</span>
                </div>
                <h1 class="text-5xl font-black text-slate-900 tracking-tight mb-3">
                    Cabang <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Saya</span>
                </h1>
                <p class="text-slate-500 font-medium text-lg max-w-xl leading-relaxed">
                    Pusat kendali lokasi strategis rental Anda. Kelola jangkauan wilayah operasional dengan mudah.
                </p>
            </div>
            <button onclick="openAddModal()"
                    class="group relative inline-flex items-center gap-4 px-8 py-4 bg-slate-900 hover:bg-blue-600 text-white font-bold text-sm rounded-2xl shadow-2xl shadow-slate-900/20 transition-all duration-500 hover:-translate-y-1">
                <div class="flex items-center justify-center w-6 h-6 bg-white/10 rounded-lg group-hover:rotate-180 transition-transform duration-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                </div>
                Tambah Cabang Baru
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 -z-10 blur-xl transition-opacity duration-500"></div>
            </button>
        </div>

        {{-- ===== STATS BAR ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-16 animate-entry" style="animation-delay: 0.1s">
            <div class="glass-card rounded-[2.5rem] p-8 group overflow-hidden relative">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center gap-5 mb-6">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-[11px] font-black uppercase tracking-[0.2em]">Total Cabang</p>
                        <h4 class="text-3xl font-black text-slate-900 leading-tight">{{ $branches->count() }} <span class="text-sm font-medium text-slate-400">Lokasi</span></h4>
                    </div>
                </div>
                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full w-[70%]"></div>
                </div>
            </div>
            
            <div class="glass-card rounded-[2.5rem] p-8 group overflow-hidden relative">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center gap-5 mb-6">
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-[11px] font-black uppercase tracking-[0.2em]">Cakupan Wilayah</p>
                        <h4 class="text-3xl font-black text-slate-900 leading-tight">{{ $branches->pluck('kota')->unique()->count() }} <span class="text-sm font-medium text-slate-400">Kota</span></h4>
                    </div>
                </div>
                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-600 rounded-full w-[45%]"></div>
                </div>
            </div>

            <div class="glass-dark rounded-[2.5rem] p-8 relative overflow-hidden group shadow-2xl shadow-slate-900/20">
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <p class="text-blue-400 text-[11px] font-black uppercase tracking-[0.2em] mb-4">Sistem Terintegrasi</p>
                        <p class="text-white font-bold text-lg leading-snug">Distribusi Unit Merata & Real-time</p>
                    </div>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full border-2 border-slate-800 bg-blue-500 flex items-center justify-center text-[10px] text-white font-bold">AI</div>
                            <div class="w-8 h-8 rounded-full border-2 border-slate-800 bg-emerald-500 flex items-center justify-center text-[10px] text-white font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        <span class="text-slate-400 text-xs font-medium italic">Connected to RAG Engine</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== CARDS GRID ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12 animate-entry" style="animation-delay: 0.2s">
            @forelse($branches as $index => $branch)
                <div class="branch-card glass-card rounded-[3rem] overflow-hidden flex flex-col group/card" style="animation-delay: {{ 0.1 * ($index + 3) }}s">
                    <div class="p-10 flex-1">
                        <div class="flex items-start justify-between mb-8">
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center text-blue-600 shadow-xl shadow-blue-900/5 border border-slate-100 group-hover/card:scale-110 transition-transform duration-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 tracking-tight group-hover/card:text-blue-600 transition-colors">{{ $branch->nama_cabang }}</h3>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="px-3 py-1.5 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-xl flex items-center gap-2 border border-blue-100/50">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                            {{ $branch->kota }}
                                        </span>
                                        <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-xl flex items-center gap-2 border border-emerald-100/50">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                            </span>
                                            Operational
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-3 relative z-20">
                                <button type="button" onclick='openEditModal(@json($branch))' 
                                        class="w-12 h-12 bg-white hover:bg-blue-600 text-slate-400 hover:text-white rounded-2xl flex items-center justify-center transition-all duration-300 shadow-lg shadow-slate-200/50 border border-slate-100 hover:-translate-y-1">
                                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('mitra.cabang.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Hapus cabang ini? Pastikan tidak ada armada yang terdaftar di sini.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-12 h-12 bg-white hover:bg-rose-600 text-slate-400 hover:text-white rounded-2xl flex items-center justify-center transition-all duration-300 shadow-lg shadow-slate-200/50 border border-slate-100 hover:-translate-y-1">
                                        <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="group/item">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Alamat Garasi & Unit</p>
                                <div class="p-5 bg-slate-50/50 rounded-[1.5rem] border border-slate-100 group-hover/card:bg-white transition-colors duration-500">
                                    <div class="flex gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-blue-100/50 flex items-center justify-center text-blue-600 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-700 leading-relaxed pt-1">{{ $branch->alamat_lengkap }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Kontak Operasional</p>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $branch->nomor_telepon_cabang) }}" 
                                       target="_blank"
                                       class="flex items-center gap-3 p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100/50 group/wa hover:bg-emerald-500 hover:border-emerald-500 transition-all duration-300">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white group-hover/wa:bg-white group-hover/wa:text-emerald-500 transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        </div>
                                        <span class="text-sm font-black text-slate-700 group-hover/wa:text-white transition-colors tracking-wide">{{ $branch->nomor_telepon_cabang }}</span>
                                    </a>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Layanan Terpadu</p>
                                    <div class="flex items-center gap-2 p-4 bg-blue-50/50 rounded-2xl border border-blue-100/50">
                                        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        </div>
                                        <span class="text-[11px] font-black text-blue-700 uppercase tracking-wider italic">Express Check-in</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-10 py-6 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                            <span class="text-[11px] text-slate-500 font-bold uppercase tracking-[0.15em]">Registered Since {{ $branch->created_at->format('M Y') }}</span>
                        </div>
                        <a href="https://maps.google.com/?q={{ urlencode($branch->alamat_lengkap . ', ' . $branch->kota) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-slate-900 text-[11px] font-black text-slate-900 hover:text-white rounded-xl transition-all duration-300 shadow-sm border border-slate-200 group/map">
                            LIHAT MAPS
                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-2 glass-card rounded-[4rem] border-2 border-dashed border-slate-200 p-24 text-center">
                    <div class="w-32 h-32 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 text-slate-200 rotate-12 group hover:rotate-0 transition-transform duration-500">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">Mulai Ekspansi Anda</h3>
                    <p class="text-slate-500 font-medium text-lg max-w-md mx-auto mb-10 leading-relaxed">Belum ada lokasi cabang yang terdaftar. Tambahkan titik operasional pertama untuk menjangkau lebih banyak pelanggan.</p>
                    <button onclick="openAddModal()" class="px-10 py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-2xl shadow-blue-600/30 transition-all hover:scale-105 active:scale-95">Daftarkan Cabang Pertama</button>
                </div>
            @endforelse
        </div>

    </div>
</div>

{{-- ===== MODAL FORM (ADD/EDIT) ===== --}}
<div id="modalCabang" class="modal-overlay" onclick="if(event.target===this) closeModal()">
    <div class="modal-box bg-white rounded-[3rem] shadow-2xl w-full max-w-xl border border-white/20">

        {{-- Header Modal --}}
        <div class="relative bg-slate-900 p-10 rounded-t-[3rem] overflow-hidden">
            <div class="absolute top-0 right-0 p-10 opacity-10 rotate-12">
                <svg class="w-32 h-32 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <button onclick="closeModal()" class="absolute top-8 right-8 w-12 h-12 flex items-center justify-center rounded-2xl bg-white/10 hover:bg-white/20 text-white transition-all hover:rotate-90 z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="relative">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-6 h-1 bg-blue-500 rounded-full"></span>
                    <span class="text-blue-400 text-[10px] font-black uppercase tracking-[0.3em]">Location Setup</span>
                </div>
                <h3 id="modalTitle" class="text-3xl font-black text-white tracking-tight">Tambah Cabang</h3>
                <p id="modalSubtitle" class="text-slate-400 text-sm font-medium mt-2 leading-relaxed">Silakan lengkapi informasi lokasi operasional baru untuk mendukung mobilitas armada Anda.</p>
            </div>
        </div>

        {{-- Form --}}
        <form id="formCabang" action="{{ route('mitra.cabang.store') }}" method="POST" class="p-12 space-y-8 rounded-b-[3rem]">
            @csrf
            <div id="methodField"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">Nama Cabang</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-300 group-focus-within:text-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <input type="text" name="nama_cabang" id="input_nama" placeholder="Pekanbaru Pusat"
                               class="w-full input-glass input-glass-icon rounded-[1.5rem] pr-6 py-4 text-slate-800 font-bold text-sm outline-none" required>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">Kota</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <input type="text" name="kota" id="input_kota" placeholder="Kota Pekanbaru"
                               class="w-full input-glass input-glass-icon rounded-[1.5rem] pr-6 py-4 text-slate-800 font-bold text-sm outline-none" required>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">Alamat Lengkap Garasi</label>
                <div class="relative group">
                    <textarea name="alamat_lengkap" id="input_alamat" rows="3"
                              placeholder="Jl. Sudirman No. 123, Pekanbaru (Tempat unit standby)"
                              class="w-full input-glass rounded-[1.5rem] p-6 text-slate-800 font-bold text-sm outline-none resize-none" required></textarea>
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">Nomor WhatsApp Cabang</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-300 group-focus-within:text-emerald-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <input type="text" name="nomor_telepon_cabang" id="input_phone" placeholder="081234567890"
                           class="w-full input-glass input-glass-icon rounded-[1.5rem] pr-6 py-4 text-slate-800 font-bold text-sm outline-none" required>
                </div>
            </div>

            <div class="pt-6 flex gap-4 pb-4">
                <button type="button" onclick="closeModal()"
                        class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs rounded-2xl transition-all uppercase tracking-[0.2em]">
                    Batal
                </button>
                <button type="submit" id="submitBtn"
                        class="flex-[2] py-4 bg-slate-900 hover:bg-blue-600 text-white font-black text-xs rounded-2xl shadow-2xl shadow-slate-900/10 transition-all hover:-translate-y-1 active:translate-y-0 uppercase tracking-[0.2em]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modalCabang');
    const form = document.getElementById('formCabang');
    const modalTitle = document.getElementById('modalTitle');
    const modalSubtitle = document.getElementById('modalSubtitle');
    const methodField = document.getElementById('methodField');
    const submitBtn = document.getElementById('submitBtn');
    
    const inputNama = document.getElementById('input_nama');
    const inputKota = document.getElementById('input_kota');
    const inputAlamat = document.getElementById('input_alamat');
    const inputPhone = document.getElementById('input_phone');

    function openAddModal() {
        modalTitle.innerText = "Tambah Cabang Baru";
        modalSubtitle.innerText = "Silakan lengkapi informasi lokasi operasional baru untuk mendukung mobilitas armada Anda.";
        form.action = "{{ route('mitra.cabang.store') }}";
        methodField.innerHTML = "";
        submitBtn.innerText = "Simpan Lokasi";
        
        inputNama.value = "";
        inputKota.value = "";
        inputAlamat.value = "";
        inputPhone.value = "";
        
        modal.classList.add('show');
    }

    function openEditModal(branch) {
        modalTitle.innerText = "Edit Cabang";
        modalSubtitle.innerText = "Perbarui informasi lokasi " + branch.nama_cabang + " untuk menjaga akurasi data operasional.";
        form.action = "/mitra/cabang/" + branch.id;
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        submitBtn.innerText = "Perbarui Data";
        
        inputNama.value = branch.nama_cabang;
        inputKota.value = branch.kota;
        inputAlamat.value = branch.alamat_lengkap;
        inputPhone.value = branch.nomor_telepon_cabang;
        
        modal.classList.add('show');
    }

    function closeModal() {
        modal.classList.remove('show');
    }
</script>

</x-app-layout>

