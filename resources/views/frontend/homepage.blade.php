<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveNow - Layanan Rental Mobil Premium</title>

    {{-- PWA Meta Tags --}}
    <meta name="theme-color" content="#3b82f6">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/3202/3202926.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Menggunakan Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen font-sans selection:bg-indigo-500 selection:text-white">

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow">
        {{-- TOP NAVBAR --}}
        <nav class="flex flex-col sm:flex-row justify-between items-center px-6 py-5 max-w-7xl mx-auto gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fa-solid fa-car-side text-white text-lg"></i>
                </div>
                <span class="font-black text-2xl text-slate-900 tracking-tight">Drive<span class="text-indigo-600">Now.</span></span>
            </div>
            <div class="bg-white p-1 rounded-full flex items-center shadow-sm border border-slate-200">
                <a href="#" class="px-5 py-2 text-sm font-bold text-indigo-700 bg-indigo-50 rounded-full shadow-sm">Portal Pelanggan</a>
                <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium text-slate-500 hover:text-slate-900 rounded-full transition-colors">Panel Admin</a>
            </div>
        </nav>

        {{-- HERO SECTION --}}
        <div class="relative overflow-hidden bg-slate-900 rounded-[2.5rem] mx-4 sm:mx-6 lg:mx-8 mt-2 mb-12 shadow-2xl shadow-slate-900/20">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-40 mix-blend-overlay" alt="Hero background">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
            </div>
            <div class="relative px-6 py-20 sm:py-28 lg:px-12 flex flex-col items-center text-center">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-sm font-bold mb-6 backdrop-blur-md animate-fade-in-up">
                    <i class="fa-solid fa-star text-xs"></i> Layanan Rental Premium
                </span>
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight leading-tight" style="animation: fadeInUp 0.6s ease-out forwards;">Temukan Mobil Impian <br class="hidden sm:block"/>Untuk Perjalanan Anda</h1>
                <p class="text-slate-300 text-lg sm:text-xl max-w-2xl mb-12 font-medium" style="animation: fadeInUp 0.8s ease-out forwards;">Lebih dari sekadar rental. Nikmati pengalaman berkendara yang mewah, aman, dan tak terlupakan.</p>
                
                {{-- SMART SEARCH WIDGET --}}
                <div class="w-full max-w-4xl bg-white/10 backdrop-blur-xl p-2 rounded-3xl sm:rounded-full border border-white/20 shadow-2xl flex flex-col sm:flex-row gap-2 transition-all hover:bg-white/15" style="animation: fadeInUp 1s ease-out forwards;">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class="fa-solid fa-wand-magic-sparkles text-indigo-500 text-lg"></i>
                        </div>
                        <input type="text" id="smart-search" placeholder="Tanya AI: 'Mobil SUV irit di Jakarta'..." 
                               class="block w-full h-full pl-14 pr-4 py-4 sm:py-5 rounded-full bg-white border-0 focus:ring-4 focus:ring-indigo-500/30 text-slate-800 placeholder-slate-400 font-bold text-base sm:text-lg shadow-inner transition-all" autocomplete="off" />
                    </div>
                    <div class="relative w-full sm:w-64 flex-shrink-0 hidden sm:block">
                        <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                            <div class="h-8 w-px bg-slate-200"></div>
                            <i class="fa-solid fa-location-dot text-slate-400 pl-4"></i>
                        </div>
                        <input type="text" id="location-search" placeholder="Cari lokasi..." 
                               class="block w-full h-full pl-10 pr-4 py-4 sm:py-5 rounded-full bg-white border-0 focus:ring-4 focus:ring-indigo-500/30 text-slate-800 font-bold text-base transition-all" autocomplete="off" />
                        <ul id="location-suggestions" class="absolute z-10 w-full bg-white border border-gray-100 rounded-2xl mt-2 hidden max-h-48 overflow-y-auto shadow-xl"></ul>
                    </div>
                    <button id="btn-smart-search" class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-full px-8 py-4 sm:py-5 font-black text-lg transition-all shadow-lg hover:shadow-indigo-500/50 flex items-center justify-center gap-2 group">
                        <span class="group-hover:-translate-y-0.5 transition-transform"><i class="fa-solid fa-magnifying-glass"></i></span> Cari
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            
            {{-- TABS --}}
            <div class="flex justify-center mb-12">
                <div class="inline-flex bg-slate-200/50 p-1 rounded-full backdrop-blur-sm shadow-inner">
                    <a href="{{ route('homepage') }}" class="{{ request()->routeIs('homepage') ? 'bg-white text-slate-900 shadow-md' : 'text-slate-500 hover:text-slate-800' }} px-8 py-3 rounded-full font-bold text-sm transition-all duration-300">
                        <i class="fa-solid fa-car mr-2 text-indigo-500"></i>Daftar Kendaraan
                    </a>
                    <a href="{{ route('booking.lacak.form') }}" class="{{ request()->routeIs('booking.lacak.form') ? 'bg-white text-slate-900 shadow-md' : 'text-slate-500 hover:text-slate-800' }} px-8 py-3 rounded-full font-bold text-sm transition-all duration-300">
                        <i class="fa-solid fa-clock-rotate-left mr-2 text-emerald-500"></i>Riwayat Pesanan
                    </a>
                </div>
            </div>

            {{-- SMART SEARCH RESULTS --}}
            <div id="smart-search-results" class="hidden mb-12 p-6 bg-gradient-to-br from-indigo-50 to-white border border-indigo-100/50 rounded-3xl shadow-xl shadow-indigo-500/5 animate-fade-in-up">
                <div class="flex items-center gap-3 mb-5 border-b border-indigo-100 pb-4">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                        <i class="fa-solid fa-robot text-lg"></i>
                    </div>
                    <span class="font-black text-indigo-900 text-lg tracking-tight">Rekomendasi AI untuk Anda</span>
                    <button id="close-smart-search" class="ml-auto w-8 h-8 bg-white rounded-full flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                
                <div id="ai-summary" class="text-sm text-indigo-800 mb-6 font-medium leading-relaxed bg-white p-4 rounded-2xl shadow-sm border border-indigo-50 hidden"></div>

                <div id="ai-recommendations-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- AI cards injected here -->
                </div>
            </div>

            {{-- HEADER DAFTAR --}}
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                    Koleksi <span class="text-indigo-600">Eksklusif</span>
                </h3>
            </div>

            {{-- DAFTAR KENDARAAN (GRID) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($cars as $index => $car)
                    <div class="group bg-white rounded-[2rem] shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 border border-slate-100/80 overflow-hidden flex flex-col hover:-translate-y-2 transition-all duration-500"
                         style="animation: fadeInUp 0.6s ease-out {{ $index * 100 }}ms both">

                        {{-- GAMBAR --}}
                        <div class="relative h-60 bg-slate-100 overflow-hidden">
                            <img src="{{ $car->image_url }}"
                                 alt="{{ $car->nama_mobil }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out"
                                 onerror="this.src='https://placehold.co/800x500/f1f5f9/94a3b8?text=Mobil'">

                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                            {{-- BADGE TERSEDIA --}}
                            <div class="absolute top-4 left-4 flex items-center gap-2 bg-white/90 backdrop-blur-md text-emerald-600 text-xs font-black px-3 py-1.5 rounded-full shadow-lg">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                READY
                            </div>
                        </div>

                        {{-- KONTEN --}}
                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-xl font-black text-slate-900 leading-tight mb-1 truncate pr-2">
                                        {{ $car->nama_mobil }}
                                    </h4>
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $car->tipe_mobil ?? 'Premium' }}</div>
                                </div>
                            </div>

                            {{-- SPESIFIKASI BADGE --}}
                            @if(isset($car->transmisi))
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="flex items-center gap-1.5 bg-slate-50 text-slate-600 text-xs font-bold px-3 py-1.5 rounded-xl border border-slate-200/60">
                                    <i class="fa-solid fa-gears text-indigo-500"></i> {{ ucfirst($car->transmisi) }}
                                </span>
                                @if(isset($car->jumlah_kursi))
                                <span class="flex items-center gap-1.5 bg-slate-50 text-slate-600 text-xs font-bold px-3 py-1.5 rounded-xl border border-slate-200/60">
                                    <i class="fa-solid fa-users text-indigo-500"></i> {{ $car->jumlah_kursi }} Kursi
                                </span>
                                @endif
                                <span class="flex items-center gap-1.5 bg-slate-50 text-slate-600 text-xs font-bold px-3 py-1.5 rounded-xl border border-slate-200/60">
                                    <i class="fa-solid fa-gas-pump text-indigo-500"></i> {{ ucfirst($car->bahan_bakar ?? 'BBM') }}
                                </span>
                            </div>
                            @endif
                            
                            <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Sewa Per Hari</div>
                                    <div class="text-indigo-600 font-black text-2xl tracking-tight">Rp {{ number_format($car->harga_sewa) }}</div>
                                </div>
                                <a href="{{ route('car.show', $car->slug) }}"
                                   class="w-12 h-12 rounded-full bg-slate-900 text-white flex items-center justify-center hover:bg-indigo-600 hover:rotate-12 transition-all duration-300 shadow-md">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white border border-dashed border-slate-300 rounded-[2rem] text-center py-20 px-8">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="fa-solid fa-car-burst text-5xl text-slate-300"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-2">Belum Ada Armada Tersedia</h3>
                        <p class="text-slate-500 font-medium max-w-md mx-auto">Silakan cek kembali nanti atau gunakan asisten AI kami untuk mencari armada dari mitra lain.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} DriveNow Rental Mobil. Hak cipta dilindungi.
            </p>
        </div>
    </footer>
    
    {{-- SCRIPTS --}}
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

            // Ambil filter kota jika ada
            const selectedCity = document.getElementById('kota')?.value || '';

            btnSmartSearch.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            btnSmartSearch.disabled = true;

            fetch("{{ route('chatbot.smart_search') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ 
                    query_input: query,
                    selected_city: selectedCity
                })
            })
            .then(res => res.json())
            .then(res => {
                btnSmartSearch.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
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
                                    <div class="flex flex-wrap items-center gap-3 text-[10px] text-slate-500 mb-3">
                                        <span class="flex items-center gap-1 font-semibold text-indigo-600"><i class="fa-solid fa-store"></i> ${item.mitra}</span>
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot text-indigo-400"></i> ${item.kota}</span>
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-gear text-indigo-400"></i> ${item.transmisi}</span>
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-user-group text-indigo-400"></i> ${item.kursi}</span>
                                    </div>
                                    
                                    ${item.scores ? `
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="bg-emerald-50 text-emerald-700 text-[9px] px-2 py-0.5 rounded-full border border-emerald-100 flex items-center gap-1">
                                            <i class="fa-solid fa-gas-pump"></i> ${item.scores.bbm}
                                        </span>
                                        <span class="bg-amber-50 text-amber-700 text-[9px] px-2 py-0.5 rounded-full border border-amber-100 flex items-center gap-1">
                                            <i class="fa-solid fa-tag"></i> Harga: ${item.scores.harga}
                                        </span>
                                        <span class="bg-blue-50 text-blue-700 text-[9px] px-2 py-0.5 rounded-full border border-blue-100 flex items-center gap-1">
                                            <i class="fa-solid fa-users"></i> Kapasitas: ${item.scores.kapasitas}
                                        </span>
                                    </div>
                                    ` : ''}

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
                btnSmartSearch.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
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

        // === SERVICE WORKER (PWA) ===
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register("{{ asset('sw.js') }}").then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>
</html>