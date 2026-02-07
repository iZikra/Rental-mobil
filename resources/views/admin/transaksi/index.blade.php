<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Kelola Transaksi') }}
            </h2>
            <div class="text-sm text-gray-500">
                Verifikasi pembayaran dan status sewa di sini.
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Filter Status --}}
            <div class="mb-6 bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-wrap items-center gap-3">
                <span class="text-gray-400 font-bold text-xs uppercase tracking-wider mr-2">Status:</span>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold flex items-center gap-1">
                    <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span> Perlu Cek
                </span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">Disewa</span>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Selesai</span>
                {{-- Tambahan Visual untuk Admin --}}
                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Dibatalkan/Ditolak</span>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg font-bold flex items-center gap-2">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 align-middle">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Info Penyewa</th>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Armada</th>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Durasi & Biaya</th>
                                <th class="px-6 py-5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Logistik Unit</th>
                                <th class="px-6 py-5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Identitas</th>
                                <th class="px-6 py-5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Bukti Bayar</th>
                                <th class="px-6 py-5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Aksi Admin</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($transaksis as $t) 
                            <tr class="hover:bg-slate-50 transition duration-200 group">
                                
                                {{-- 1. INFO PENYEWA --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-bold text-sm shadow-md group-hover:scale-105 transition">
                                            {{ substr($t->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-800">{{ $t->user->name ?? 'User Dihapus' }}</div>
                                            <div class="flex items-center gap-1 text-xs text-gray-500 mt-0.5">
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
                                                <span class="text-[10px] bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100 font-bold">👮 Pakai Sopir</span>
                                            @else
                                                <span class="text-[10px] bg-gray-50 text-gray-400 px-2 py-0.5 rounded border border-gray-200">🔑 Lepas Kunci</span>
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

                                {{-- 4. LOGISTIK UNIT --}}
                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                    <div class="flex flex-col gap-3 text-xs">
                                        <div class="relative pl-4 border-l-2 border-indigo-400">
                                            <span class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-indigo-500"></span>
                                            <span class="font-bold text-gray-500 uppercase text-[10px]">Titik Ambil:</span>
                                            <p class="font-bold text-gray-800">{{ $t->lokasi_jemput ?? 'Di Kantor FZ Rent' }}</p>
                                        </div>
                                        <div class="relative pl-4 border-l-2 border-green-400">
                                            <span class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-green-500"></span>
                                            <span class="font-bold text-gray-500 uppercase text-[10px]">Titik Kembali:</span>
                                            <p class="font-bold text-gray-800">{{ $t->lokasi_kembali ?? ($t->lokasi_jemput ?? 'Di Kantor FZ Rent') }}</p>
                                        </div>
                                        <div class="mt-2 pt-2 border-t border-gray-100">
                                            <span class="font-bold text-gray-500 uppercase text-[9px]">Alamat Rumah User:</span>
                                            <p class="text-gray-700 text-[11px] font-medium leading-snug whitespace-normal max-w-[200px] bg-gray-50 p-2 rounded border border-gray-100">
                                                {{ $t->user->alamat ?? 'User belum melengkapi data alamat.' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- 5. IDENTITAS --}}
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @php $fotoIdentitas = $t->foto_identitas ?? $t->user->identitas_foto ?? null; @endphp
                                    @if($fotoIdentitas)
                                        @php
                                            $urlIdentitas = str_contains($fotoIdentitas, '/') 
                                                ? route('storage.view', ['folder' => explode('/', $fotoIdentitas)[0], 'filename' => explode('/', $fotoIdentitas)[1]])
                                                : route('storage.view', ['folder' => 'identitas', 'filename' => $fotoIdentitas]);
                                        @endphp
                                        <div class="flex flex-col items-center gap-2">
                                            <img src="{{ $urlIdentitas }}" class="w-10 h-8 object-cover rounded cursor-pointer border border-gray-300 hover:scale-150 transition relative" onclick="window.open('{{ $urlIdentitas }}', '_blank')">
                                            <a href="{{ $urlIdentitas }}" target="_blank" class="text-[10px] text-blue-600 hover:underline">Lihat</a>
                                        </div>
                                    @else
                                        <span class="text-red-500 text-[10px] italic">Belum Upload</span>
                                    @endif
                                </td>

                                {{-- 6. BUKTI BAYAR --}}
                                <td class="px-6 py-4 text-center align-middle">
                                    @if($t->bukti_bayar)
                                        @php
                                            $urlBukti = str_contains($t->bukti_bayar, '/')
                                                ? route('storage.view', ['folder' => explode('/', $t->bukti_bayar)[0], 'filename' => explode('/', $t->bukti_bayar)[1]])
                                                : route('storage.view', ['folder' => 'bukti_bayar', 'filename' => $t->bukti_bayar]);
                                        @endphp
                                        <a href="{{ $urlBukti }}" target="_blank" class="relative group inline-block">
                                            <img src="{{ $urlBukti }}" class="w-10 h-10 object-cover rounded-lg border border-gray-200 shadow-sm transition transform group-hover:scale-125" alt="Bukti">
                                            <div class="mt-1"><span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full border border-green-100">Ada File</span></div>
                                        </a>
                                    @else
                                        <span class="text-[10px] text-gray-400 italic bg-gray-50 px-2 py-1 rounded border border-gray-100">Belum Ada</span>
                                    @endif
                                </td>

                                {{-- 7. AKSI ADMIN (PERBAIKAN LOGIKA DISINI) --}}
                                <td class="px-6 py-4 text-center">
                                    @php 
                                        $statusRaw = strtolower($t->status ?? ''); 
                                        
                                        $isVerifikasi = in_array($statusRaw, ['perlu cek', 'menunggu konfirmasi', 'verifikasi']) || ($statusRaw == 'pending' && $t->bukti_bayar);
                                        $isActive = in_array($statusRaw, ['disewa', 'approved', 'sedang disewa']);
                                        $isDone = in_array($statusRaw, ['selesai', 'finished']);
                                        // Deteksi Status Pembatalan/Penolakan
                                        $isCancelled = in_array($statusRaw, ['dibatalkan', 'ditolak', 'cancelled', 'rejected']);
                                    @endphp

                                    @if($isCancelled)
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-black uppercase border border-red-200 block shadow-sm">
                                                {{ $statusRaw == 'dibatalkan' ? '🚫 Dibatalkan User' : '❌ Pesanan Ditolak' }}
                                            </span>
                                            <span class="text-[9px] text-gray-400 italic">Unit kembali tersedia</span>
                                        </div>

                                    @elseif($isVerifikasi)
                                        <div class="flex flex-col gap-2">
                                            <form action="{{ route('admin.transaksi.approve', $t->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-1.5 px-3 rounded text-xs transition shadow-sm flex items-center justify-center gap-1" onclick="return confirm('Bukti bayar valid? Terima pesanan?')">
                                                    <span>✓</span> Terima
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.transaksi.reject', $t->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="w-full bg-white border border-red-200 text-red-500 hover:bg-red-50 font-bold py-1.5 px-3 rounded text-xs transition shadow-sm flex items-center justify-center gap-1" onclick="return confirm('Tolak pesanan ini?')">
                                                    <span>✕</span> Tolak
                                                </button>
                                            </form>
                                            <div class="mt-1">
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[9px] font-black uppercase tracking-wide animate-pulse border border-yellow-200 block">⏳ Perlu Cek</span>
                                            </div>
                                        </div>

                                    @elseif($isActive)
                                        <form action="{{ route('admin.transaksi.complete', $t->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md hover:shadow-lg transition w-full flex items-center justify-center gap-1" onclick="return confirm('Mobil sudah kembali?')">
                                                🏁 Selesai
                                            </button>
                                        </form>
                                        <span class="block mt-1 text-[10px] text-blue-500 font-medium italic">Unit sedang jalan</span>

                                    @elseif($isDone)
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase border border-green-200 block">✅ Selesai</span>

                                    @else
                                        {{-- Khusus status Pending murni tanpa bukti bayar --}}
                                        <div class="flex flex-col items-center">
                                            <span class="text-[10px] text-gray-400 italic">Menunggu user upload...</span>
                                            <span class="block mt-1 px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[9px] border border-gray-200">Pending</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-2"></i>
                                        <p>Belum ada transaksi masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $transaksis->links() }}
            </div>
            
        </div>
    </div>
</x-app-layout>