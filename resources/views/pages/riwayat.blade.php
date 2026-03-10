<x-app-layout>
    {{-- 1. PARALLAX HERO SECTION --}}
    <div class="relative bg-fixed bg-center bg-cover h-[450px]" 
         style="background-image: url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop');">
        
        <div class="absolute inset-0 bg-slate-900/75"></div>
        
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

    {{-- 2. CONTENT CONTAINER --}}
    <div class="relative z-10 -mt-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-bounce-short">
                <i class="fa-solid fa-circle-check text-2xl"></i>
                <div>
                    <strong class="block font-bold">Berhasil!</strong>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-bounce-short">
                <i class="fa-solid fa-circle-exclamation text-2xl"></i>
                <div>
                    <strong class="block font-bold">Gagal!</strong>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="p-6 sm:p-8 border-b border-gray-50 flex justify-between items-center bg-white">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Daftar Transaksi
                </h3>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                    Total: {{ count($transaksis) }} Pesanan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Kendaraan</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Jadwal Sewa</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Tagihan</th>
                            <th class="px-6 py-4 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($transaksis as $t)
                        <tr class="hover:bg-blue-50/20 transition duration-150">
                            {{-- 1. MOBIL --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center">
                                    @if($t->mobil && $t->mobil->gambar)
                                        <img class="h-14 w-20 rounded-lg object-cover shadow-sm mr-4" src="{{ asset('storage/mobils/' . $t->mobil->gambar) }}">
                                    @else
                                        <div class="h-14 w-20 bg-gray-100 rounded-lg flex items-center justify-center text-[10px] text-gray-400 font-bold border border-gray-200 mr-4">NO PIC</div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-black text-slate-800">{{ $t->mobil->merek ?? 'Mobil' }} {{ $t->mobil->model ?? '' }}</div>
                                        <div class="text-[10px] text-gray-500 font-mono">{{ $t->mobil->no_plat ?? '-' }}</div>
                                        <span class="mt-1 inline-block px-2 py-0.5 rounded text-[9px] font-bold {{ $t->sopir == 'dengan_sopir' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                                            <i class="fa-solid {{ $t->sopir == 'dengan_sopir' ? 'fa-user-tie' : 'fa-key' }} mr-1"></i> 
                                            {{ $t->sopir == 'dengan_sopir' ? 'Dengan Sopir' : 'Lepas Kunci' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. JADWAL --}}
                            <td class="px-6 py-5 whitespace-nowrap text-xs">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 text-center text-[9px] bg-emerald-100 text-emerald-700 rounded font-bold">IN</span>
                                        <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($t->tgl_ambil)->format('d/m/y') }}</span>
                                        <span class="text-gray-400">{{ \Carbon\Carbon::parse($t->jam_ambil)->format('H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 text-center text-[9px] bg-rose-100 text-rose-700 rounded font-bold">OUT</span>
                                        <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($t->tgl_kembali)->format('d/m/y') }}</span>
                                        <span class="text-gray-400">{{ \Carbon\Carbon::parse($t->jam_kembali)->format('H:i') }}</span>
                                    </div>
                                    <div class="text-[10px] text-blue-600 font-bold">Durasi: {{ $t->lama_sewa }} Hari</div>
                                </div>
                            </td>

                            {{-- 3. LOKASI --}}
                            <td class="px-6 py-5 text-xs text-gray-600">
                                <div class="max-w-[150px] space-y-1">
                                    <div class="truncate"><i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i> {{ $t->lokasi_ambil == 'kantor' ? 'Ambil Kantor' : ($t->alamat_jemput ?? $t->alamat_lengkap) }}</div>
                                    <div class="truncate"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> {{ $t->lokasi_kembali == 'kantor' ? 'Balik Kantor' : ($t->alamat_antar ?? $t->alamat_lengkap) }}</div>
                                </div>
                            </td>

                            {{-- 4. TAGIHAN --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-black text-slate-800">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</div>
                                @if($t->bukti_bayar)
                                    <span class="text-[9px] font-bold text-emerald-600 flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-circle-check"></i> Paid/Uploaded
                                    </span>
                                @else
                                    <span class="text-[9px] font-bold text-amber-600 flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-circle-exclamation"></i> Unpaid
                                    </span>
                                @endif
                            </td>

                            {{-- 5. AKSI & STATUS --}}
                            <td class="px-6 py-5 text-center">
                                @php
                                    $statusLower = strtolower(trim($t->status));
                                    
                                    // LOGIKA TEGAS: Tombol muncul jika belum ada bukti bayar
                                    $showAction = empty($t->bukti_bayar) && !in_array($statusLower, ['selesai', 'dibatalkan', 'disewa']);
                                    $isProcess = !empty($t->bukti_bayar) && ($statusLower == 'pending' || $statusLower == 'menunggu konfirmasi');
                                    $isSuccess = in_array($statusLower, ['disewa', 'approved', 'disetujui', 'selesai']);

                                    // Mapping Warna Label
                                    $statusClass = 'bg-gray-100 text-gray-600';
                                    if($statusLower == 'pending' && empty($t->bukti_bayar)) $statusClass = 'bg-amber-100 text-amber-700';
                                    elseif($isProcess) $statusClass = 'bg-blue-100 text-blue-700';
                                    elseif($isSuccess) $statusClass = 'bg-emerald-100 text-emerald-700';
                                    elseif($statusLower == 'dibatalkan') $statusClass = 'bg-red-100 text-red-700';
                                @endphp

                                <div class="flex flex-col items-center gap-2">
                                    <span class="px-3 py-0.5 text-[9px] font-black uppercase rounded-full border border-current {{ $statusClass }}">
                                        {{ $statusLower == 'pending' && empty($t->bukti_bayar) ? 'Menunggu Pembayaran' : ($isProcess ? 'Verifikasi Admin' : $t->status) }}
                                    </span>

                                    <div class="flex flex-col gap-1.5 w-full max-w-[130px]">
                                        {{-- TOMBOL BAYAR & BATAL --}}
                                        @if($showAction)
                                            <button onclick="document.getElementById('modal-upload-{{ $t->id }}').classList.remove('hidden')" 
                                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold py-2 rounded-lg shadow transition">
                                                <i class="fa-solid fa-wallet mr-1"></i> Bayar Sekarang
                                            </button>
                                            
                                            <form action="{{ route('transaksi.batal', $t->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan?');">
                                                @csrf @method('PUT')
                                                <button type="submit" class="text-[10px] text-red-500 font-bold hover:underline">
                                                    Batalkan Pesanan
                                                </button>
                                            </form>
                                        @endif

                                        {{-- TOMBOL CETAK & KONTAK --}}
                                        @if($isSuccess)
                                            <a href="{{ route('transaksi.cetak', $t->id) }}" target="_blank" class="w-full bg-slate-800 text-white text-[10px] font-bold py-1.5 rounded-lg">
                                                <i class="fa-solid fa-print mr-1"></i> E-Tiket
                                            </a>
                                        @endif

                                        @if($statusLower != 'dibatalkan')
                                            <a href="https://wa.me/6285375285567?text=Halo Admin, Konfirmasi Order #{{ $t->id }}" target="_blank" 
                                               class="w-full bg-emerald-500 text-white text-[10px] font-bold py-1.5 rounded-lg">
                                                <i class="fa-brands fa-whatsapp mr-1"></i> Chat Admin
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                {{-- MODAL UPLOAD --}}
                                <div id="modal-upload-{{ $t->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto">
                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="fixed inset-0 bg-black/60 transition-opacity" onclick="document.getElementById('modal-upload-{{ $t->id }}').classList.add('hidden')"></div>
                                        
                                        <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden transform transition-all">
                                            <div class="bg-slate-900 p-4 flex justify-between items-center text-white">
                                                <h3 class="text-xs font-bold uppercase tracking-widest">Konfirmasi Bayar</h3>
                                                <button onclick="document.getElementById('modal-upload-{{ $t->id }}').classList.add('hidden')"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            
                                            <div class="p-6 text-center">
                                                <div class="bg-blue-50 rounded-xl p-4 mb-5 border border-blue-100">
                                                    <p class="text-[10px] text-blue-600 font-black uppercase">Tagihan Anda</p>
                                                    <p class="text-2xl font-black text-slate-800">Rp {{ number_format($t->total_harga) }}</p>
                                                    <div class="mt-3 pt-3 border-t border-blue-200 text-[10px] text-gray-600">
                                                        <p class="font-bold">Transfer Bank BRI</p>
                                                        <p class="text-blue-700 text-sm font-black">1234-5678-9012</p>
                                                        <p>a.n Zikrallah Al Hady</p>
                                                    </div>
                                                </div>

                                                <form action="{{ route('transaksi.upload', $t->id) }}" method="POST" enctype="multipart/form-data" class="text-left">
                                                    @csrf 
                                                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1">Upload Bukti Transfer</label>
                                                    <input type="file" name="bukti_bayar" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-5" required>
                                                    
                                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-xl shadow-lg transition transform active:scale-95">
                                                        KIRIM BUKTI PEMBAYARAN
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center text-gray-400 font-medium">
                                <i class="fa-solid fa-car-rear text-5xl mb-4 opacity-20"></i>
                                <p>Belum ada transaksi. Ayo mulai perjalananmu!</p>
                                <a href="{{ route('dashboard') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-bold">Cari Mobil</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
</x-app-layout>