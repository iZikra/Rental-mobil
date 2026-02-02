<x-app-layout>
    {{-- 1. PARALLAX HERO SECTION --}}
    {{-- Menggunakan bg-fixed untuk efek parallax saat scroll --}}
    <div class="relative bg-fixed bg-center bg-cover h-[500px]" 
         style="background-image: url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop');">
        
        {{-- Overlay Gelap --}}
        <div class="absolute inset-0 bg-slate-900/70"></div>
        
        {{-- Teks Header --}}
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
            <span class="text-blue-400 font-bold tracking-widest uppercase text-sm mb-2 animate-fade-in-up">Area Pelanggan</span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-4 drop-shadow-lg animate-fade-in-up delay-100">
                Riwayat Perjalanan
            </h1>
            <p class="text-lg text-gray-300 max-w-2xl animate-fade-in-up delay-200">
                Pantau status pemesanan, lakukan pembayaran, dan unduh tiket elektronik Anda dalam satu tempat.
            </p>
        </div>
    </div>

    {{-- 2. CONTENT CONTAINER (Floating Card) --}}
    {{-- Class -mt-32 membuat elemen ini naik menimpa header (efek tumpuk) --}}
    <div class="relative z-10 -mt-32 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        {{-- Alert Sukses --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-bounce-short">
                <i class="fa-solid fa-circle-check text-2xl"></i>
                <div>
                    <strong class="block font-bold">Berhasil!</strong>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Alert Error --}}
        @if(session('error'))
            <div class="mb-6 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-bounce-short">
                <i class="fa-solid fa-circle-exclamation text-2xl"></i>
                <div>
                    <strong class="block font-bold">Gagal!</strong>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Kartu Utama dengan Efek Glassmorphism (sedikit transparan) --}}
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden border border-white/20">
            
            <div class="p-6 sm:p-8 border-b border-gray-100 flex justify-between items-center bg-white">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Daftar Transaksi
                </h3>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                    Total: {{ count($transaksis) }} Pesanan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Kendaraan</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Jadwal Sewa</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Tagihan</th>
                            <th class="px-6 py-4 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Aksi & Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($transaksis as $t)
                        <tr class="hover:bg-blue-50/30 transition duration-200">
                            {{-- 1. MOBIL --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="relative group">
                                        @if($t->mobil && $t->mobil->gambar)
                                            <img class="h-16 w-24 rounded-lg object-cover shadow-sm group-hover:scale-110 transition duration-300" src="{{ asset('img/' . $t->mobil->gambar) }}">
                                        @else
                                            <div class="h-16 w-24 bg-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-400 font-bold border border-gray-200">No Pic</div>
                                        @endif
                                        <div class="absolute inset-0 bg-black/10 rounded-lg group-hover:bg-transparent transition"></div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-black text-slate-800">{{ $t->mobil->merek ?? 'Mobil' }} {{ $t->mobil->model ?? 'Dihapus' }}</div>
                                        <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $t->mobil->no_plat ?? '-' }}</div>
                                        @if($t->sopir == 'dengan_sopir')
                                            <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">
                                                <i class="fa-solid fa-user-tie mr-1"></i> +Sopir
                                            </span>
                                        @else
                                            <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">
                                                <i class="fa-solid fa-key mr-1"></i> Lepas Kunci
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- 2. JADWAL --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-xs space-y-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 text-center bg-emerald-100 text-emerald-700 rounded py-0.5 font-bold">IN</div>
                                        <div>
                                            <span class="block font-bold text-gray-700">{{ \Carbon\Carbon::parse($t->tgl_ambil)->format('d M Y') }}</span>
                                            <span class="text-gray-400">{{ \Carbon\Carbon::parse($t->jam_ambil)->format('H:i') }} WIB</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 text-center bg-rose-100 text-rose-700 rounded py-0.5 font-bold">OUT</div>
                                        <div>
                                            <span class="block font-bold text-gray-700">{{ \Carbon\Carbon::parse($t->tgl_kembali)->format('d M Y') }}</span>
                                            <span class="text-gray-400">{{ \Carbon\Carbon::parse($t->jam_kembali)->format('H:i') }} WIB</span>
                                        </div>
                                    </div>
                                    <div class="pl-10 text-gray-400 italic text-[10px]">
                                        Durasi: <strong class="text-slate-700">{{ $t->lama_sewa }} Hari</strong>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. LOKASI --}}
                            <td class="px-6 py-5">
                                <div class="flex flex-col space-y-2 text-xs">
                                    {{-- Ambil --}}
                                    @if(strtolower($t->lokasi_ambil) == 'kantor')
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <i class="fa-solid fa-building text-emerald-500 mt-0.5"></i>
                                            <span class="font-medium">Ambil di Kantor</span>
                                        </div>
                                    @else
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <i class="fa-solid fa-location-dot text-emerald-500 mt-0.5"></i>
                                            <span class="font-medium truncate max-w-[150px]" title="{{ $t->alamat_jemput ?? $t->alamat_lengkap }}">{{ $t->alamat_jemput ?? $t->alamat_lengkap }}</span>
                                        </div>
                                    @endif

                                    {{-- Kembali --}}
                                    @if(strtolower($t->lokasi_kembali) == 'kantor')
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <i class="fa-solid fa-building text-rose-500 mt-0.5"></i>
                                            <span class="font-medium">Kembali ke Kantor</span>
                                        </div>
                                    @else
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <i class="fa-solid fa-location-dot text-rose-500 mt-0.5"></i>
                                            <span class="font-medium truncate max-w-[150px]" title="{{ $t->alamat_antar ?? $t->alamat_lengkap }}">{{ $t->alamat_antar ?? $t->alamat_lengkap }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- 4. BIAYA --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-base font-black text-slate-800">
                                        Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                                    </span>
                                    <div class="mt-1">
                                        @if($t->bukti_bayar)
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                                <i class="fa-solid fa-check-double"></i> Bukti Terkirim
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-100">
                                                <i class="fa-regular fa-clock"></i> Belum Bayar
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- 5. AKSI & STATUS --}}
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                @php
                                    $statusRaw = trim($t->status);
                                    $statusLower = strtolower($statusRaw);
                                    
                                    // Mapping Status untuk Tampilan
                                    $statusClass = 'bg-gray-100 text-gray-600 border-gray-200';
                                    $statusLabel = $statusRaw;
                                    
                                    if(in_array($statusLower, ['pending', 'menunggu'])) {
                                        $statusClass = 'bg-amber-100 text-amber-700 border-amber-200';
                                        $statusLabel = 'Menunggu Pembayaran';
                                    } elseif($statusLower == 'menunggu konfirmasi') {
                                        $statusClass = 'bg-blue-100 text-blue-700 border-blue-200';
                                        $statusLabel = 'Verifikasi Admin';
                                    } elseif(in_array($statusLower, ['approved', 'disetujui', 'disewa', 'process'])) {
                                        $statusClass = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                        $statusLabel = 'Sedang Berjalan';
                                    } elseif(in_array($statusLower, ['selesai', 'finished'])) {
                                        $statusClass = 'bg-slate-800 text-white border-slate-900';
                                        $statusLabel = 'Selesai';
                                    } elseif($statusLower == 'dibatalkan') {
                                        $statusClass = 'bg-red-100 text-red-700 border-red-200';
                                        $statusLabel = 'Dibatalkan';
                                    }
                                    
                                    // Logic Tombol
                                    $canPay = in_array($statusLower, ['pending', 'menunggu', '']);
                                    $canChat = !in_array($statusLower, ['selesai', 'finished', 'dibatalkan']);
                                    $canPrint = in_array($statusLower, ['approved', 'disetujui', 'disewa', 'selesai', 'finished']);
                                @endphp

                                <div class="flex flex-col items-center gap-3">
                                    {{-- Status Badge --}}
                                    <span class="px-3 py-1 text-[10px] uppercase font-black tracking-wide rounded-full border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                    {{-- Action Buttons --}}
                                    <div class="flex flex-col gap-2 w-full max-w-[140px]">
                                        
                                        @if($canPay)
                                            {{-- Tombol Bayar --}}
                                            @if(!$t->bukti_bayar)
                                                <button onclick="document.getElementById('modal-upload-{{ $t->id }}').classList.remove('hidden')" 
                                                        class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white text-xs font-bold py-2 px-3 rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                                    <i class="fa-solid fa-wallet"></i> Bayar
                                                </button>
                                                
                                                {{-- Tombol Batal --}}
                                                <form action="{{ route('transaksi.batal', $t->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                                                    @csrf @method('PUT')
                                                    <button type="submit" class="w-full text-[10px] text-red-500 hover:text-red-700 font-bold hover:underline mt-1">
                                                        Batalkan Pesanan
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        @if($canChat)
                                            <a href="https://wa.me/6285375285567?text=Halo Admin, saya ingin konfirmasi pesanan ID #{{ $t->id }} - {{ $t->mobil->merek }} {{ $t->mobil->model }}" 
                                               target="_blank" 
                                               class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg shadow-sm transition flex items-center justify-center gap-1">
                                                <i class="fa-brands fa-whatsapp text-sm"></i> Hubungi Admin
                                            </a>
                                        @endif

                                        @if($canPrint)
                                            <a href="{{ route('transaksi.cetak', $t->id) }}" target="_blank" 
                                               class="w-full bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold py-1.5 px-3 rounded-lg shadow-sm transition flex items-center justify-center gap-1">
                                                <i class="fa-solid fa-print"></i> E-Tiket
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                {{-- MODAL UPLOAD (Hidden by Default) --}}
                                @if($canPay && !$t->bukti_bayar)
                                <div id="modal-upload-{{ $t->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        
                                        {{-- Backdrop --}}
                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('modal-upload-{{ $t->id }}').classList.add('hidden')"></div>

                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                        {{-- Modal Content --}}
                                        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full">
                                            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-4 py-3 flex justify-between items-center">
                                                <h3 class="text-sm leading-6 font-bold text-white uppercase tracking-wider">
                                                    <i class="fa-solid fa-upload mr-2"></i> Konfirmasi Pembayaran
                                                </h3>
                                                <button onclick="document.getElementById('modal-upload-{{ $t->id }}').classList.add('hidden')" class="text-gray-400 hover:text-white">
                                                    <i class="fa-solid fa-xmark text-lg"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="p-6">
                                                {{-- Info Rekening --}}
                                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5 text-center">
                                                    <p class="text-xs text-blue-600 font-bold uppercase mb-1">Total yang harus dibayar</p>
                                                    <p class="text-2xl font-black text-slate-800 mb-3">Rp {{ number_format($t->total_harga) }}</p>
                                                    
                                                    <div class="border-t border-blue-200 pt-3 space-y-1">
                                                        <p class="text-xs text-gray-500">Bank BCA</p>
                                                        <p class="text-lg font-mono font-bold text-blue-700 tracking-wider">1234567890</p>
                                                        <p class="text-xs text-gray-500">a.n. Zikrallah Al Hady</p>
                                                    </div>
                                                </div>

                                                {{-- Form --}}
                                                <form action="{{ route('transaksi.upload', $t->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf 
                                                    <div class="mb-4">
                                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Unggah Bukti Transfer</label>
                                                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition">
                                                            <input type="file" name="bukti_bayar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                                                            <i class="fa-solid fa-image text-gray-400 text-2xl mb-2"></i>
                                                            <p class="text-xs text-gray-500">Klik untuk memilih file (JPG/PNG)</p>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition">
                                                        Kirim Bukti Pembayaran
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
                                        <i class="fa-solid fa-car-side text-4xl text-slate-300"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-700">Belum Ada Riwayat</h3>
                                    <p class="text-gray-500 max-w-sm mt-2 mb-6">Anda belum pernah melakukan pemesanan mobil. Yuk, mulai perjalanan Anda sekarang!</p>
                                    <a href="{{ route('pages.order') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-blue-500/30 transition transform hover:-translate-y-1">
                                        Sewa Mobil Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Footer Note --}}
        <div class="mt-8 text-center">
            <p class="text-xs text-white/80 font-medium drop-shadow-md">
                &copy; {{ date('Y') }} Rental Mobil Zikrallah. Sistem pembayaran aman dan terpercaya.
            </p>
        </div>
    </div>

    {{-- Script Animasi Sederhana --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
</x-app-layout>