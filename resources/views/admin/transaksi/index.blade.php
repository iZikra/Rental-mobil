<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Daftar Pesanan Masuk') }}
            </h2>
            <div class="text-sm text-gray-500">
                Pantau transaksi sewa secara real-time.
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Filter Bar --}}
            <div class="mb-6 bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-wrap items-center gap-3">
                <span class="text-gray-400 font-medium text-sm uppercase tracking-wider mr-2">
                    <span class="text-red-600 text-lg">🔍</span> Filter Status:
                </span>
                <span class="px-4 py-1.5 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-full text-xs font-bold shadow-sm flex items-center gap-2 cursor-default">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span> Menunggu
                </span>
                <span class="px-4 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-bold shadow-sm flex items-center gap-2 cursor-default">
                    <span class="w-2 h-2 bg-blue-400 rounded-full"></span> Sedang Jalan
                </span>
                <span class="px-4 py-1.5 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-bold shadow-sm flex items-center gap-2 cursor-default">
                    <span class="w-2 h-2 bg-green-400 rounded-full"></span> Selesai
                </span>
            </div>

            {{-- Pesan Sukses / Eror --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                <div class="overflow-x-auto">
                    
                    <table class="min-w-full divide-y divide-gray-100 align-middle">
                        
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Info Penyewa</th>
                                <th class="px-6 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Armada</th>
                                <th class="px-6 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Durasi & Biaya</th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Identitas</th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Bukti Bayar</th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
    @forelse($transaksis as $t) 
    
    <tr class="hover:bg-gray-50 transition duration-200 group">
        
        {{-- 1. INFO PENYEWA --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-bold text-sm shadow-md group-hover:scale-105 transition">
                    {{ substr($t->user->name ?? '?', 0, 1) }}
                </div>
                <div class="ml-4">
                    <div class="text-sm font-bold text-gray-800">{{ $t->user->name ?? 'User Dihapus' }}</div>
                    <div class="flex items-center gap-1 text-xs text-gray-500 mt-0.5">
                        <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $t->no_hp ?? '-' }}
                    </div>
                </div>
            </div>
        </td>

        {{-- 2. ARMADA --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex flex-col">
                <span class="text-sm font-bold text-gray-800">{{ $t->mobil->merk ?? 'Mobil' }} {{ $t->mobil->model ?? 'Dihapus' }}</span>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded border border-gray-200 font-mono">
                        {{ $t->mobil->no_plat ?? 'N/A' }}
                    </span>
                    @if($t->sopir == 'dengan_sopir')
                        <span class="text-[10px] bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100 font-bold flex items-center gap-1">
                            👮 Pakai Sopir
                        </span>
                    @else
                        <span class="text-[10px] bg-gray-50 text-gray-400 px-2 py-0.5 rounded border border-gray-200">
                            🔑 Lepas Kunci
                        </span>
                    @endif
                </div>
            </div>
        </td>

        {{-- 3. DURASI & BIAYA --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex flex-col space-y-1">
                <div class="flex items-center text-xs text-gray-500">
                    <span class="w-12">Mulai:</span> 
                    <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($t->tgl_ambil)->format('d/m/y') }}</span>
                </div>
                <div class="flex items-center text-xs text-gray-500">
                    <span class="w-12">Selesai:</span>
                    <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($t->tgl_kembali)->format('d/m/y') }}</span>
                </div>
                <div class="pt-1">
                    <span class="text-sm font-extrabold text-red-600 bg-red-50 px-2 py-0.5 rounded">
                        Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </td>

       {{-- 4. LOKASI (LOGIKA KETAT: HANYA ALAMAT JEMPUT) --}}
<td class="px-6 py-4 text-center whitespace-nowrap">
    @php 
        // AMBIL HANYA DARI INPUTAN SAAT BOOKING (alamat_jemput)
        // Kita hapus fallback ke alamat profil/KTP agar tidak tercampur.
        $alamatJemput = $t->alamat_jemput ?? null;

        // Cek Validitas:
        // 1. Tidak boleh kosong / null
        // 2. Tidak boleh tanda strip '-'
        // 3. Tidak boleh mengandung kata 'kantor' (karena itu berarti ambil di kantor)
        $isDiantar = !empty($alamatJemput) && $alamatJemput != '-' && !str_contains(strtolower($alamatJemput), 'kantor');
    @endphp

    @if($isDiantar)
        {{-- Jika User mengisi Alamat Penjemputan Khusus --}}
        <button onclick='lihatAlamat(@json($alamatJemput))' 
                class="inline-flex items-center gap-1 bg-white text-indigo-600 px-3 py-1 rounded-full text-[10px] font-bold border border-indigo-200 hover:bg-indigo-50 hover:border-indigo-300 transition shadow-sm"
                title="Klik untuk lihat detail alamat">
            📍 Cek Lokasi
        </button>
    @else
        {{-- Jika Kosong atau memilih 'Ambil di Kantor' --}}
        <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-[10px] font-bold border border-gray-200">
            🏢 Di Kantor
        </span>
    @endif
</td>

        {{-- 5. IDENTITAS (KTP) - PERBAIKAN LOGIKA GAMBAR --}}
        <td class="px-6 py-4 text-center whitespace-nowrap">
            @php
                // Cek nama kolom, sesuaikan prioritas jika ada di user atau transaksi
                $fotoIdentitas = $t->foto_identitas ?? $t->user->identitas_foto ?? null;
            @endphp

            @if($fotoIdentitas)
                @php
                    // Logika Path Khusus
                    if (str_contains($fotoIdentitas, '/')) {
                        $parts = explode('/', $fotoIdentitas);
                        $folderId = $parts[0];
                        $fileId = $parts[1];
                    } else {
                        $folderId = 'identitas'; // Folder default
                        $fileId = $fotoIdentitas;
                    }
                    // Buat URL Route Khusus
                    $urlIdentitas = route('storage.view', ['folder' => $folderId, 'filename' => $fileId]);
                @endphp

                <div class="flex flex-col items-center gap-2">
                    <img src="{{ $urlIdentitas }}" 
                            class="w-10 h-8 object-cover rounded cursor-pointer border border-gray-300 hover:scale-150 transition z-0 hover:z-50 relative"
                            onclick="window.open('{{ $urlIdentitas }}', '_blank')">
                    
                    <a href="{{ $urlIdentitas }}" target="_blank" class="text-[10px] text-blue-600 hover:underline">
                        Lihat
                    </a>
                </div>
            @else
                <span class="text-red-500 text-[10px] italic">Belum Upload</span>
            @endif
        </td>

        {{-- 6. BUKTI BAYAR - PERBAIKAN LOGIKA GAMBAR --}}
        <td class="px-6 py-4 text-center align-middle">
            @if($t->bukti_bayar)
                @php
                    // Logika Path Khusus Bukti Bayar
                    if (str_contains($t->bukti_bayar, '/')) {
                        $parts = explode('/', $t->bukti_bayar);
                        $folderBayar = $parts[0];
                        $fileBayar = $parts[1];
                    } else {
                        $folderBayar = 'bukti_bayar'; // Folder default
                        $fileBayar = $t->bukti_bayar;
                    }
                    $urlBukti = route('storage.view', ['folder' => $folderBayar, 'filename' => $fileBayar]);
                @endphp

                <a href="{{ $urlBukti }}" target="_blank" class="relative group inline-block">
                    <img src="{{ $urlBukti }}" 
                            class="w-10 h-10 object-cover rounded-lg border border-gray-200 shadow-sm transition transform group-hover:scale-125 z-0 group-hover:z-10" 
                            alt="Bukti">
                </a>
            @else
                <span class="text-[10px] text-gray-400 italic bg-gray-50 px-2 py-1 rounded border border-gray-100">Belum Ada</span>
            @endif
        </td>

        {{-- 7. AKSI --}}
        <td class="px-6 py-4 text-center">
            @php $status = strtolower($t->status ?? ''); @endphp

            {{-- KASUS 1: MENUNGGU --}}
            @if(in_array($status, ['pending', 'menunggu_pembayaran', 'menunggu']))
                <div class="flex justify-center items-center gap-2">
                    
                    {{-- TOMBOL TERIMA (APPROVE) --}}
                    <form action="{{ route('admin.transaksi.approve', $t->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition shadow-sm" title="Terima" onclick="return confirm('Terima pesanan ini?')">
                            ✓
                        </button>
                    </form>
                    
                    {{-- TOMBOL TOLAK (REJECT) --}}
                    <form action="{{ route('admin.transaksi.reject', $t->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition shadow-sm" title="Tolak" onclick="return confirm('Tolak pesanan ini?')">
                            ✕
                        </button>
                    </form>
                </div>
                <div class="mt-1">
                    <span class="text-[9px] uppercase font-bold text-yellow-600 tracking-wide">Menunggu</span>
                </div>

            {{-- KASUS 2: DI SEWA --}}
            @elseif(in_array($status, ['approved', 'disetujui', 'process', 'disewa', 'sedang_disewa']))
                
                {{-- TOMBOL SELESAI (COMPLETE) --}}
                <form action="{{ route('admin.transaksi.complete', $t->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md hover:shadow-lg transition w-full flex items-center justify-center gap-1" onclick="return confirm('Mobil sudah kembali?')">
                        Selesaikan
                    </button>
                </form>
                <span class="block mt-1 text-[10px] text-blue-500 font-medium">Di Sewa</span>

            {{-- KASUS 3: SELESAI --}}
            @elseif(in_array($status, ['finished', 'selesai']))
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-full border border-green-200">
                    ✓ Selesai
                </span>

            {{-- KASUS 4: DIBATALKAN / DITOLAK --}}
            @else
                <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">
                    {{ $t->status }}
                </span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="px-6 py-16 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-gray-900 font-medium text-base">Tidak ada pesanan</h3>
                <p class="text-gray-500 text-sm mt-1">Belum ada data transaksi yang masuk saat ini.</p>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function lihatAlamat(alamat) {
            if(!alamat || alamat === 'null' || alamat === '' || alamat === '-') {
                alamat = 'User tidak menyertakan detail alamat.';
            }

            Swal.fire({
                title: '📍 Detail Lokasi',
                html: `
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-left mt-2">
                        <p class="text-gray-800 text-sm font-medium leading-relaxed font-sans">
                            "${alamat}"
                        </p>
                    </div>
                    <p class="mt-4 text-[10px] text-gray-400 italic">
                        *Infokan ke driver untuk penjemputan/pengantaran.
                    </p>
                `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#DC2626',
                width: '400px'
            });
        }
    </script>
</x-app-layout>