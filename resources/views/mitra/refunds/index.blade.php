<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Manajemen Refund Mitra') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <p class="text-sm font-medium text-gray-500">Live Updates</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/20">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500 text-white font-bold rounded-2xl shadow-lg shadow-red-500/20">
                    {{ session('error') }}
                </div>
            @endif

            {{-- 3-Card Dashboard Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
                {{-- Card Total Pengajuan --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 rounded-3xl p-5 shadow-xl shadow-blue-100 text-white transform hover:scale-[1.02] transition duration-300">
                    <div class="absolute right-0 bottom-0 translate-y-3 translate-x-3 opacity-10">
                        <i class="fa-solid fa-list-check text-[100px]"></i>
                    </div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-blue-100">Total Pengajuan</p>
                    <h3 class="text-3xl font-black mt-1.5">{{ count($refunds) }} <span class="text-xs font-semibold text-blue-200">Pengajuan</span></h3>
                </div>

                {{-- Card Menunggu Proses --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-amber-700 rounded-3xl p-5 shadow-xl shadow-amber-100 text-white transform hover:scale-[1.02] transition duration-300">
                    <div class="absolute right-0 bottom-0 translate-y-3 translate-x-3 opacity-10">
                        <i class="fa-solid fa-hourglass-half text-[100px]"></i>
                    </div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-amber-100">Menunggu Proses</p>
                    <h3 class="text-3xl font-black mt-1.5">{{ $refunds->where('status', 'menunggu')->count() }} <span class="text-xs font-semibold text-amber-200">Baru</span></h3>
                </div>

                {{-- Card Disetujui --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-3xl p-5 shadow-xl shadow-emerald-100 text-white transform hover:scale-[1.02] transition duration-300">
                    <div class="absolute right-0 bottom-0 translate-y-3 translate-x-3 opacity-10">
                        <i class="fa-solid fa-circle-check text-[100px]"></i>
                    </div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-100">Selesai/Disetujui</p>
                    <h3 class="text-3xl font-black mt-1.5">{{ $refunds->where('status', 'disetujui')->count() }} <span class="text-xs font-semibold text-emerald-200 font-bold">Sukses</span></h3>
                </div>
            </div>

            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-900 text-white">
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest rounded-tl-xl">Detail Booking</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest">Detail Rekening User</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest">Alasan & Nominal</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest rounded-tr-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($refunds as $rf)
                                    <tr class="hover:bg-gray-50/50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-black text-gray-900">#{{ $rf->transaksi->id }}</div>
                                            <div class="text-xs text-gray-500 font-semibold">{{ $rf->transaksi->user->name }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase mt-1">{{ $rf->transaksi->mobil->merk }} {{ $rf->transaksi->mobil->model }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs font-black text-gray-800">{{ $rf->nama_bank }}</div>
                                            <div class="text-xs font-bold text-gray-600 mt-0.5">No. Rek: {{ $rf->nomor_rekening }}</div>
                                            <div class="text-[10px] text-gray-400 font-semibold uppercase">A/N: {{ $rf->nama_pemilik }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-black text-purple-600">Rp {{ number_format($rf->jumlah_refund, 0, ',', '.') }}</div>
                                            <div class="text-xs text-gray-600 mt-1 max-w-xs break-words italic">"{{ $rf->alasan_refund }}"</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($rf->status === 'menunggu')
                                                <span class="px-3 py-1 bg-amber-50 text-amber-600 border border-amber-100 rounded-full text-[10px] font-black uppercase tracking-wider">Menunggu</span>
                                            @elseif($rf->status === 'disetujui')
                                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-wider">Disetujui</span>
                                                @if($rf->bukti_transfer)
                                                    <div class="mt-2">
                                                        <a href="{{ asset('storage/' . $rf->bukti_transfer) }}" target="_blank" class="text-[10px] font-black text-blue-600 hover:underline">
                                                            <i class="fa-solid fa-file-image"></i> Bukti Transfer
                                                        </a>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="px-3 py-1 bg-red-50 text-red-600 border border-red-100 rounded-full text-[10px] font-black uppercase tracking-wider">Ditolak</span>
                                                <div class="text-[10px] text-gray-400 mt-1 max-w-xs break-words italic">"{{ $rf->alasan_penolakan }}"</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($rf->status === 'menunggu')
                                                <div class="flex flex-col gap-2 items-center justify-center">
                                                    <button onclick="openApproveModal({{ $rf->id }}, '{{ number_format($rf->jumlah_refund, 0, ',', '.') }}')" class="w-28 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition shadow-lg shadow-emerald-500/20">
                                                        Setujui
                                                    </button>
                                                    <button onclick="openRejectModal({{ $rf->id }})" class="w-28 py-2 bg-red-500 hover:bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition shadow-lg shadow-red-500/20">
                                                        Tolak
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-xs font-semibold text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-16 text-center text-sm font-medium text-gray-400">
                                            Tidak ada pengajuan refund saat ini.
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

    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center">
        <div class="relative bg-white rounded-3xl max-w-md w-full mx-4 p-8 shadow-2xl border border-slate-100">
            <button onclick="closeApproveModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <h3 class="text-xl font-black text-slate-900 mb-2">Setujui Refund</h3>
            <p class="text-slate-500 text-sm font-medium mb-6">Silakan unggah bukti transfer pengembalian dana kepada penyewa.</p>

            <form id="approveForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Total Pengembalian Dana</label>
                    <input type="text" id="approve_nominal" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-black text-slate-700" readonly>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Bukti Transfer (Gambar)</label>
                    <input type="file" name="bukti_transfer" required accept="image/*" class="w-full border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700">
                </div>

                <div class="pt-2 flex gap-4">
                    <button type="button" onclick="closeApproveModal()" class="flex-1 py-3 border border-slate-200 hover:bg-slate-50 text-slate-500 text-xs font-black rounded-xl uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl uppercase tracking-widest shadow-lg shadow-emerald-600/20">Setujui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center">
        <div class="relative bg-white rounded-3xl max-w-md w-full mx-4 p-8 shadow-2xl border border-slate-100">
            <button onclick="closeRejectModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <h3 class="text-xl font-black text-slate-900 mb-2">Tolak Refund</h3>
            <p class="text-slate-500 text-sm font-medium mb-6">Berikan alasan penolakan pengajuan refund ini.</p>

            <form id="rejectForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" required rows="4" placeholder="Tulis alasan penolakan refund di sini" class="w-full border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="pt-2 flex gap-4">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 py-3 border border-slate-200 hover:bg-slate-50 text-slate-500 text-xs font-black rounded-xl uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white text-xs font-black rounded-xl uppercase tracking-widest shadow-lg shadow-red-600/20">Tolak Refund</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openApproveModal(refundId, nominal) {
                const form = document.getElementById('approveForm');
                form.action = `/mitra/refunds/${refundId}/setujui`;
                document.getElementById('approve_nominal').value = 'Rp ' + nominal;
                document.getElementById('approveModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeApproveModal() {
                document.getElementById('approveModal').classList.add('hidden');
                document.body.style.overflow = '';
            }

            function openRejectModal(refundId) {
                const form = document.getElementById('rejectForm');
                form.action = `/mitra/refunds/${refundId}/tolak`;
                document.getElementById('rejectModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeRejectModal() {
                document.getElementById('rejectModal').classList.add('hidden');
                document.body.style.overflow = '';
            }
        </script>
    @endpush
</x-app-layout>
