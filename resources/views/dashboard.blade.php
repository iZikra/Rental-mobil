<x-app-layout>
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

    {{-- HERO SECTION --}}
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
                    <a href="#list-mobil" class="group bg-blue-600 hover:bg-blue-500 text-white px-8 py-4 rounded-full font-bold transition-all duration-300 shadow-[0_0_20px_rgba(37,99,235,0.5)] hover:shadow-[0_0_30px_rgba(37,99,235,0.7)] flex items-center gap-3">
                        Mulai Booking
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('pages.about') }}" class="group bg-white/5 hover:bg-white/10 backdrop-blur-sm border border-white/20 text-white px-8 py-4 rounded-full font-bold transition flex items-center gap-3">
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

    {{-- KEUNGGULAN --}}
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

    {{-- LIST MOBIL DENGAN FILTER --}}
    <div id="list-mobil" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <span class="text-blue-600 font-bold tracking-wider uppercase text-sm">Koleksi Terbaru</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2">Pilihan Armada Terbaik</h2>
                </div>
                <a href="{{ route('pages.order') }}" class="group flex items-center gap-2 text-slate-600 hover:text-blue-600 font-bold transition">
                    Lihat Semua Mobil 
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="mb-10 rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
                <div class="relative px-6 sm:px-8 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                    <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 20% 20%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 80% 30%, rgba(34,211,238,0.45), transparent 55%);"></div>
                    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            </div>
                            <div>
                                <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">AI Power</div>
                                <div class="text-lg sm:text-xl font-extrabold text-white">Smart Search AI</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 sm:px-8 py-6">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                        </div>
                        <input type="text" id="smart-search" placeholder="Tanya AI: 'Cari mobil keluarga yang irit untuk 7 orang'..." 
                               class="block w-full pl-12 pr-32 rounded-2xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm py-4 font-semibold" autocomplete="off" />
                        <button id="btn-smart-search" class="absolute inset-y-0 right-2 my-2 px-6 flex items-center bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition gap-2 shadow-lg shadow-blue-600/20">
                            <i class="fa-solid fa-paper-plane"></i>
                            CARI
                        </button>
                    </div>

                    <!-- Smart Search Results (AI Recommendations) -->
                    <div id="smart-search-results" class="hidden mt-6 p-6 bg-blue-50 border border-blue-100 rounded-2xl animate-fade-in relative">
                        <div class="flex items-center gap-2 mb-4">
                            <i class="fa-solid fa-robot text-blue-600"></i>
                            <span class="font-bold text-blue-900 text-sm">Rekomendasi AI untuk Anda:</span>
                            <button id="close-smart-search" class="ml-auto text-blue-400 hover:text-blue-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        
                        <!-- AI Summary Text -->
                        <div id="ai-summary" class="text-sm text-slate-700 mb-5 font-medium leading-relaxed"></div>

                        <div id="ai-recommendations-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- AI cards will be injected here -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-10 rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
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
                        @if(request('kota'))
                            <a href="{{ url()->current() }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 hover:bg-white/15 border border-white/15 text-white font-bold text-sm transition">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
                <div class="px-6 sm:px-8 py-6">
                    <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-end">
                        <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-gradient-to-br from-blue-50 to-white p-5">
                            <label for="kota" class="flex items-center gap-2 text-xs font-extrabold tracking-widest uppercase text-slate-700 mb-3">
                                <i class="fa-solid fa-location-dot text-red-500"></i> Lokasi
                            </label>
                            <select name="kota" id="kota"
                                    class="w-full bg-white border border-slate-200 p-3 rounded-2xl text-sm font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer"
                                    onchange="this.form.submit()">
                                <option value="">Semua Kota</option>
                                @if(isset($daftarKota))
                                    @foreach($daftarKota as $kota)
                                        <option value="{{ $kota }}" {{ request('kota') == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="mt-2 text-xs font-semibold text-slate-500">Pilih kota untuk melihat armada yang tersedia.</div>
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 hover:bg-blue-600 text-white font-extrabold shadow-lg transition">
                            <i class="fa-solid fa-magnifying-glass"></i> Terapkan
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($mobils as $mobil)

<div class="group bg-white rounded-3xl border border-slate-200/70 shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] hover:shadow-[0_22px_70px_-28px_rgba(37,99,235,0.35)] transition-all duration-300 relative overflow-hidden flex flex-col">

    {{-- STATUS --}}
    <div class="absolute top-5 right-5 z-10">
        @if($mobil->status == 'tersedia')
            <span class="px-4 py-2 bg-green-100 text-green-800 text-xs font-bold rounded-full shadow-sm flex items-center gap-1">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Tersedia
            </span>
        @else
            <span class="px-4 py-2 bg-gray-100 text-gray-500 text-xs font-bold rounded-full shadow-sm flex items-center gap-1 border border-gray-200">
                <i class="fa-solid fa-lock"></i> Disewa
            </span>
        @endif
    </div>

    {{-- GAMBAR MOBIL --}}
    <div class="h-64 flex items-center justify-center p-6 relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-blue-50">
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-blue-500/10 rounded-full blur-2xl"></div>
        <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-cyan-400/10 rounded-full blur-2xl"></div>
        <div class="absolute inset-0 opacity-[0.35]" style="background-image: radial-gradient(circle at 20% 20%, rgba(59,130,246,0.35), transparent 55%), radial-gradient(circle at 80% 30%, rgba(34,211,238,0.25), transparent 55%);"></div>
        <div class="absolute inset-0 bg-[linear-gradient(110deg,rgba(255,255,255,0.0),rgba(255,255,255,0.55),rgba(255,255,255,0.0))] -translate-x-[120%] group-hover:translate-x-[120%] transition-transform duration-1000"></div>
        <div class="absolute inset-6 rounded-3xl border border-slate-200 bg-white/60 backdrop-blur-[1px]"></div>
        <img src="{{ $mobil->image_url ?: asset(gambarMobil($mobil->model)) }}"
             alt="{{ $mobil->model }}"
             loading="lazy"
             decoding="async"
             onerror="this.src='https://placehold.co/800x500?text=Mobil'"
             class="w-full h-full object-contain relative z-10 group-hover:scale-110 transition-transform duration-500 drop-shadow-[0_18px_30px_rgba(15,23,42,0.18)] {{ $mobil->status != 'tersedia' ? 'grayscale opacity-70' : '' }}">
    </div>

    {{-- DETAIL MOBIL --}}
    <div class="p-8 flex-1 flex flex-col">

        <div class="mb-2">
            <p class="text-xs text-blue-600 font-extrabold uppercase tracking-widest mb-1">
                {{ $mobil->merk }}
            </p>

            <h3 class="text-2xl font-bold text-slate-900 group-hover:text-blue-600 transition">
                {{ $mobil->merk }} {{ $mobil->model }}
            </h3>
        </div>

        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
            <p class="text-sm font-bold text-red-600 mb-1">
                <i class="fa-solid fa-location-dot mr-1"></i>
                Lokasi: {{ $mobil->branch->kota ?? 'Tidak Diketahui' }}
            </p>

            <p class="text-sm font-bold text-slate-700">
                <i class="fa-solid fa-building mr-1"></i>
                Mitra: {{ $mobil->rental->nama_rental ?? 'Tidak Diketahui' }}
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm text-gray-500 mb-6 border-y border-gray-100 py-4">

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-chair text-blue-400"></i>
                <span class="font-medium">{{ $mobil->jumlah_kursi }} Kursi</span>
            </div>

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-gears text-blue-400"></i>
                <span class="font-medium">{{ $mobil->transmisi }}</span>
            </div>

        </div>

        <div class="mt-auto flex items-center justify-between">

            <div>
                <span class="text-gray-400 text-xs font-bold uppercase">
                    Harga Sewa
                </span>

                <div class="flex items-end gap-1">
                    <span class="text-2xl font-bold text-slate-900">
                        Rp {{ number_format($mobil->harga_sewa,0,',','.') }}
                    </span>
                </div>
            </div>

            @if($mobil->status == 'tersedia')

            <a href="{{ route('pages.order', ['mobil_id' => $mobil->id]) }}"
               class="w-12 h-12 bg-slate-900 hover:bg-blue-600 text-white rounded-full flex items-center justify-center transition-colors shadow-lg group-hover:rotate-45 duration-300"
               title="Sewa Sekarang">

                <i class="fa-solid fa-arrow-up"></i>

            </a>

            @else

            <button disabled
                    class="w-12 h-12 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center cursor-not-allowed shadow-none"
                    title="Unit Sedang Disewa">

                <i class="fa-solid fa-lock"></i>

            </button>

            @endif

        </div>

    </div>

</div>

@empty
                <div class="col-span-full bg-red-50 text-red-600 text-center p-8 rounded-2xl border border-red-200 font-bold text-lg">
                    ⚠️ Maaf, tidak ada unit mobil yang tersedia untuk area "{{ request('kota') }}" saat ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="relative py-32 bg-slate-900 overflow-hidden isolate">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.blue.900),theme(colors.slate.900))] opacity-50"></div>
        <div class="absolute inset-y-0 right-1/2 -z-10 mr-16 w-[200%] origin-bottom-left skew-x-[-30deg] bg-slate-900 shadow-xl shadow-blue-600/10 ring-1 ring-blue-50 sm:mr-28 lg:mr-0 xl:mr-16 xl:origin-center"></div>
        
        <div class="relative max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 tracking-tight">Siap untuk Perjalanan Impian?</h2>
            <p class="text-blue-100 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">Jangan biarkan transportasi menghambat mobilitas Anda. Dapatkan penawaran eksklusif via WhatsApp kami sekarang juga.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="https://wa.me/6283845966102" class="bg-green-500 hover:bg-green-400 text-white px-8 py-4 rounded-full font-bold shadow-lg shadow-green-500/30 flex items-center justify-center gap-2 transition transform hover:scale-105">
                    <i class="fa-brands fa-whatsapp text-xl"></i> 
                    Chat WhatsApp
                </a>
                <a href="{{ route('pages.contact') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-8 py-4 rounded-full font-bold backdrop-blur-sm transition flex items-center justify-center gap-2">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>

    <x-chatbot />
    
    <script>
        // === SMART SEARCH AI ===
        const smartSearchInput = document.getElementById('smart-search');
        const btnSmartSearch = document.getElementById('btn-smart-search');
        const smartSearchResults = document.getElementById('smart-search-results');
        const aiRecommendationsList = document.getElementById('ai-recommendations-list');
        const aiSummaryText = document.getElementById('ai-summary');
        const closeSmartSearch = document.getElementById('close-smart-search');

        function doSmartSearch() {
            const query = smartSearchInput.value.trim();
            if (query.length < 3) return;

            btnSmartSearch.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            btnSmartSearch.disabled = true;

            fetch("{{ route('chatbot.smart_search') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ query_input: query })
            })
            .then(res => res.json())
            .then(res => {
                btnSmartSearch.innerHTML = '<i class="fa-solid fa-paper-plane"></i> CARI';
                btnSmartSearch.disabled = false;

                if (res.status === 'success' && res.data.length > 0) {
                    smartSearchResults.classList.remove('hidden');
                    aiRecommendationsList.innerHTML = '';
                    
                    // Set Summary
                    if (res.summary) {
                        aiSummaryText.innerHTML = `<strong>Ringkasan:</strong> ${res.summary}`;
                        aiSummaryText.classList.remove('hidden');
                    } else {
                        aiSummaryText.classList.add('hidden');
                    }
                    
                    res.data.forEach(item => {
                        const card = `
                            <div class="group bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col animate-fade-in-up">
                                <div class="relative h-40 overflow-hidden">
                                    <img src="${item.gambar}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="${item.nama}">
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-white/90 backdrop-blur-sm text-indigo-600 text-[10px] font-bold px-2 py-1 rounded-lg shadow-sm border border-indigo-50">
                                            ${item.tipe}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4 flex flex-col flex-1">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-bold text-slate-900 text-sm truncate pr-2">${item.nama}</h5>
                                        <span class="text-indigo-600 font-bold text-xs whitespace-nowrap">Rp ${item.harga}</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-[10px] text-slate-500 mb-3">
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot text-indigo-400"></i> ${item.kota}</span>
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-gear text-indigo-400"></i> ${item.transmisi}</span>
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-user-group text-indigo-400"></i> ${item.kursi}</span>
                                    </div>
                                    <div class="bg-indigo-50/50 rounded-xl p-3 mb-4 flex-1">
                                        <p class="text-[11px] text-indigo-700 font-medium leading-relaxed italic line-clamp-3">
                                            <i class="fa-solid fa-quote-left text-[8px] opacity-50 mr-1"></i>
                                            ${item.reason}
                                        </p>
                                    </div>
                                    <a href="${item.booking_url}" target="_blank" class="w-full bg-indigo-600 text-white text-center py-2.5 rounded-xl text-xs font-bold hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                                        Detail & Pesan Sekarang
                                    </a>
                                </div>
                            </div>
                        `;
                        aiRecommendationsList.innerHTML += card;
                    });
                    
                    // Scroll to results
                    smartSearchResults.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Ditemukan',
                        text: 'Maaf, AI tidak menemukan mobil yang sesuai dengan kriteria tersebut di stok saat ini.',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            })
            .catch(err => {
                console.error(err);
                btnSmartSearch.innerHTML = '<i class="fa-solid fa-paper-plane"></i> CARI';
                btnSmartSearch.disabled = false;
            });
        }

        btnSmartSearch.addEventListener('click', doSmartSearch);
        smartSearchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') doSmartSearch();
        });
        closeSmartSearch.addEventListener('click', () => {
            smartSearchResults.classList.add('hidden');
        });
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</x-app-layout>
