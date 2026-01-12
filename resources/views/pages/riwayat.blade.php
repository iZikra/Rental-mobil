<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Sukses --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mobil</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jadwal Sewa</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi Antar/Jemput</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total Biaya</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status & Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
    @forelse($transaksis as $t)
    <tr>
        {{-- 1. MOBIL --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                @if($t->mobil && $t->mobil->gambar)
                    <img class="h-12 w-16 rounded object-cover mr-3 border border-gray-200" src="{{ asset('storage/' . $t->mobil->gambar) }}">
                @else
                    <div class="h-12 w-16 bg-gray-100 rounded mr-3 flex items-center justify-center text-xs text-gray-400 border border-gray-200">No Pic</div>
                @endif
                <div>
                    <div class="text-sm font-bold text-gray-900">{{ $t->mobil->merk ?? 'Mobil' }} {{ $t->mobil->model ?? 'Dihapus' }}</div>
                    <div class="text-xs text-gray-500">{{ $t->mobil->nopol ?? '-' }}</div>
                    @if($t->sopir == 'dengan_sopir')
                        <span class="text-[10px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-bold">Pakai Sopir</span>
                    @else
                        <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">Lepas Kunci</span>
                    @endif
                </div>
            </div>
        </td>

        {{-- 2. JADWAL --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-xs text-gray-500 space-y-1">
                <div>
                    <span class="font-bold text-emerald-600">Ambil:</span> 
                    {{ \Carbon\Carbon::parse($t->tgl_ambil)->format('d M Y') }} 
                    <span class="text-gray-400">({{ \Carbon\Carbon::parse($t->jam_ambil)->format('H:i') }})</span>
                </div>
                <div>
                    <span class="font-bold text-rose-600">Kembali:</span> 
                    {{ \Carbon\Carbon::parse($t->tgl_kembali)->format('d M Y') }}
                    <span class="text-gray-400">({{ \Carbon\Carbon::parse($t->jam_kembali)->format('H:i') }})</span>
                </div>
                <div class="pt-1">
                    <span class="bg-gray-100 px-2 py-0.5 rounded text-[10px] font-bold">
                        {{ $t->lama_sewa }} Hari
                    </span>
                </div>
            </div>
        </td>

        {{-- 3. LOKASI --}}
        <td class="px-6 py-4">
            <div class="flex flex-col space-y-3 text-xs">
                <div>
                    <div class="flex items-center gap-1 mb-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="font-bold text-gray-700 uppercase text-[10px]">Titik Ambil</span>
                    </div>
                    @if(strtolower($t->lokasi_ambil) == 'kantor')
                        <span class="inline-flex items-center px-2 py-1 rounded border border-gray-200 bg-gray-50 text-gray-600 font-medium">🏢 Ambil di Kantor</span>
                    @else
                        <div class="bg-emerald-50 border border-emerald-100 p-2 rounded text-emerald-800 leading-snug">
                            📍 {{ $t->alamat_jemput ?? $t->alamat_lengkap }}
                        </div>
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-1 mb-1">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span class="font-bold text-gray-700 uppercase text-[10px]">Titik Kembali</span>
                    </div>
                    @if(strtolower($t->lokasi_kembali) == 'kantor')
                        <span class="inline-flex items-center px-2 py-1 rounded border border-gray-200 bg-gray-50 text-gray-600 font-medium">🏢 Kembali ke Kantor</span>
                    @else
                        <div class="bg-rose-50 border border-rose-100 p-2 rounded text-rose-800 leading-snug">
                            🏁 {{ $t->alamat_antar ?? $t->alamat_lengkap }}
                        </div>
                    @endif
                </div>
            </div>
        </td>

        {{-- 4. TOTAL BIAYA --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="text-sm font-bold text-gray-900">
                Rp {{ number_format($t->total_harga, 0, ',', '.') }}
            </span>
            <div class="text-[10px] text-gray-400 italic mt-1">
                @if($t->bukti_bayar)
                    <span class="text-green-600">✓ Sudah Upload Bukti</span>
                @else
                    <span class="text-red-500">Belum Bayar</span>
                @endif
            </div>
        </td>

        {{-- 5. STATUS & AKSI --}}
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex flex-col items-center gap-2">
                
                {{-- LOGIKA STATUS BARU --}}
                @php
                    $status = $t->status;
                    
                    // Grup Pending
                    $isPending = in_array($status, ['Pending', null, '']);

                    // Grup Sukses (Tiket Muncul Disini)
                    // SAYA MENAMBAHKAN 'Disewa' DI SINI
                    $showTicket = in_array($status, ['Approved', 'Disetujui', 'Disewa', 'Selesai']);
                    
                    // Grup Chat Admin
                    $showChat = in_array($status, ['Approved', 'Disetujui', 'Disewa']);
                @endphp

                {{-- BADGE STATUS --}}
                @if($isPending)
                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                @elseif($status == 'Selesai')
                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Selesai</span>
                @elseif($status == 'Disewa')
                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">Disewa</span>
                @else
                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">{{ $status }}</span>
                @endif

                {{-- TOMBOL AKSI --}}
                @if($isPending)
                    
                    {{-- Tombol Upload --}}
                    @if(!$t->bukti_bayar)
                        <button onclick="document.getElementById('upload-{{ $t->id }}').classList.toggle('hidden')" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs transition w-full shadow-sm">
                            ⬆ Upload Bayar
                        </button>
                    @else
                        <span class="text-[10px] text-gray-400">Menunggu Konfirmasi</span>
                    @endif

                    {{-- Tombol Batal --}}
                    <form action="{{ route('transaksi.batal', $t->id) }}" method="POST" onsubmit="return confirm('Yakin batalkan pesanan?');" class="w-full">
                        @csrf @method('PUT')
                        <button type="submit" class="mt-1 border border-red-200 text-red-600 hover:bg-red-50 px-3 py-1 rounded text-xs transition w-full">
                            Batal
                        </button>
                    </form>

                    {{-- Form Upload (Popup) --}}
                    <div id="upload-{{ $t->id }}" class="hidden mt-2 p-3 bg-white border border-gray-200 rounded-lg text-left w-56 absolute right-10 z-20 shadow-xl">
                        <form action="{{ route('riwayat.upload', $t->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf 
                            <label class="block text-[10px] font-bold text-gray-700 mb-2">Pilih Foto Bukti:</label>
                            <input type="file" name="bukti_bayar" class="text-[10px] w-full mb-3 border border-gray-300 rounded p-1 bg-gray-50" required>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-[10px] w-full font-bold shadow-sm">Kirim</button>
                                <button type="button" onclick="document.getElementById('upload-{{ $t->id }}').classList.add('hidden')" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded text-[10px] font-bold border border-gray-300">Tutup</button>
                            </div>
                        </form>
                    </div>

                @else
                    
                    {{-- 1. TOMBOL WHATSAPP --}}
                    @if($showChat)
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $t->no_hp) }}?text=Halo admin, saya mau tanya soal pesanan mobil {{ $t->mobil->model ?? '' }} (Status: {{ $status }})" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs flex items-center justify-center gap-1 w-full shadow-sm transition mb-1">
                            <span>📞</span> Hubungi Admin
                        </a>
                    @endif

                    {{-- 2. TOMBOL CETAK TIKET --}}
                    @if($showTicket)
                        <a href="{{ route('riwayat.cetak', $t->id) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs flex items-center justify-center gap-1 w-full shadow-sm transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Tiket
                        </a>
                    @endif

                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
            <div class="flex flex-col items-center">
                <span class="text-4xl mb-2">📭</span>
                <p>Belum ada riwayat pesanan.</p>
                <a href="{{ route('pages.order') }}" class="mt-4 text-indigo-600 hover:underline font-bold text-sm">Sewa Mobil Sekarang →</a>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</x-app-layout>