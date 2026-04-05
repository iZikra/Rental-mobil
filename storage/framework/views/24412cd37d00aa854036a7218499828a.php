<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
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

    
    <div class="relative z-10 -mt-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        
        <?php if(session('success')): ?>
            <div class="mb-6 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-bounce-short">
                <i class="fa-solid fa-circle-check text-2xl"></i>
                <div>
                    <strong class="block font-bold">Berhasil!</strong>
                    <span class="text-sm"><?php echo e(session('success')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-6 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-bounce-short">
                <i class="fa-solid fa-circle-exclamation text-2xl"></i>
                <div>
                    <strong class="block font-bold">Gagal!</strong>
                    <span class="text-sm"><?php echo e(session('error')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php
            $normalizeStatus = function ($status) {
                $s = strtolower(trim((string) ($status ?? '')));
                if (in_array($s, ['pending', 'menunggu', 'menunggu_pembayaran', 'process', 'processing'], true)) return 'pending';
                if (in_array($s, ['disewa', 'approved', 'disetujui', 'sedang_jalan', 'sedang_disewa'], true)) return 'disewa';
                if (in_array($s, ['selesai', 'completed', 'complete'], true)) return 'selesai';
                if (in_array($s, ['dibatalkan', 'batal', 'cancel', 'canceled', 'expire', 'expired'], true)) return 'dibatalkan';
                if (in_array($s, ['ditolak', 'reject', 'rejected', 'deny', 'denied'], true)) return 'ditolak';
                return $s ?: 'unknown';
            };
            $totalCount = count($transaksis);
            $pendingCount = collect($transaksis)->filter(fn($t) => $normalizeStatus($t->status) === 'pending')->count();
            $activeCount = collect($transaksis)->filter(fn($t) => $normalizeStatus($t->status) === 'disewa')->count();
            $doneCount = collect($transaksis)->filter(fn($t) => $normalizeStatus($t->status) === 'selesai')->count();
        ?>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                <div class="text-xs font-extrabold tracking-widest uppercase text-slate-500">Total Pesanan</div>
                <div class="mt-2 flex items-end justify-between">
                    <div class="text-3xl font-extrabold text-slate-900"><?php echo e($totalCount); ?></div>
                    <div class="w-10 h-10 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 shadow-sm p-5">
                <div class="text-xs font-extrabold tracking-widest uppercase text-amber-700">Menunggu Bayar</div>
                <div class="mt-2 flex items-end justify-between">
                    <div class="text-3xl font-extrabold text-amber-800"><?php echo e($pendingCount); ?></div>
                    <div class="w-10 h-10 rounded-2xl bg-white/70 border border-amber-200 flex items-center justify-center text-amber-700">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-blue-200 bg-blue-50 shadow-sm p-5">
                <div class="text-xs font-extrabold tracking-widest uppercase text-blue-700">Sedang Disewa</div>
                <div class="mt-2 flex items-end justify-between">
                    <div class="text-3xl font-extrabold text-blue-800"><?php echo e($activeCount); ?></div>
                    <div class="w-10 h-10 rounded-2xl bg-white/70 border border-blue-200 flex items-center justify-center text-blue-700">
                        <i class="fa-solid fa-car"></i>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm p-5">
                <div class="text-xs font-extrabold tracking-widest uppercase text-emerald-700">Selesai</div>
                <div class="mt-2 flex items-end justify-between">
                    <div class="text-3xl font-extrabold text-emerald-800"><?php echo e($doneCount); ?></div>
                    <div class="w-10 h-10 rounded-2xl bg-white/70 border border-emerald-200 flex items-center justify-center text-emerald-700">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white shadow-[0_18px_60px_-30px_rgba(15,23,42,0.35)] overflow-hidden">
            <div class="relative px-6 sm:px-8 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 20% 20%, rgba(59,130,246,0.55), transparent 60%), radial-gradient(circle at 80% 30%, rgba(34,211,238,0.45), transparent 55%);"></div>
                <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <div class="text-xs font-extrabold tracking-widest uppercase text-blue-200">Riwayat</div>
                            <div class="text-lg sm:text-xl font-extrabold text-white">Daftar Transaksi</div>
                        </div>
                    </div>
                    <span class="text-xs font-extrabold text-white/70 uppercase tracking-wider">
                        Total: <?php echo e($totalCount); ?> Pesanan
                    </span>
                </div>
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
                        <?php $__empty_1 = true; $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-blue-50/20 transition duration-150">
                            
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">

                                    
                                    <img src="<?php echo e($t->mobil?->image_url ?: 'https://placehold.co/600x400?text=Mobil'); ?>"
                                         class="w-24 h-16 object-cover rounded-lg shadow"
                                         alt="<?php echo e($t->mobil->model ?? 'Mobil'); ?>"
                                         onerror="this.src='https://placehold.co/600x400?text=Gambar+Kosong'">

                                    
                                    <div class="flex flex-col">
                                        
                                        <div class="text-sm font-bold text-slate-800">
                                            <?php echo e($t->mobil->merk ?? 'Mobil'); ?> <?php echo e($t->mobil->model ?? ''); ?>

                                        </div>

                                        <div class="mt-1 text-[10px] font-extrabold tracking-widest uppercase text-slate-400">
                                            Booking #<?php echo e($t->id); ?> • <?php echo e(optional($t->created_at)->format('d M Y H:i')); ?>

                                        </div>

                                        
                                        <div class="text-xs text-gray-400 flex items-center gap-1 mt-1">
                                            <i class="fa-solid fa-location-dot text-red-400"></i>
                                            <?php echo e($t->mobil->branch->kota ?? 'Lokasi tidak diketahui'); ?>

                                        </div>

                                        <div class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                                            <i class="fa-solid fa-building text-slate-400"></i>
                                            <?php echo e($t->rental->nama_rental ?? 'Mitra tidak diketahui'); ?>

                                        </div>

                                        
                                        <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold
                                            <?php echo e($t->sopir == 'dengan_sopir' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600'); ?>">
                                            <i class="fa-solid <?php echo e($t->sopir == 'dengan_sopir' ? 'fa-user-tie' : 'fa-key'); ?> mr-1"></i>
                                            <?php echo e($t->sopir == 'dengan_sopir' ? 'Dengan Sopir' : 'Lepas Kunci'); ?>

                                        </span>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-6 py-5 whitespace-nowrap text-xs">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 text-center text-[9px] bg-emerald-100 text-emerald-700 rounded font-bold">IN</span>
                                        <span class="font-bold text-gray-700"><?php echo e(\Carbon\Carbon::parse($t->tgl_ambil)->format('d/m/y')); ?></span>
                                        <span class="text-gray-400"><?php echo e(\Carbon\Carbon::parse($t->jam_ambil)->format('H:i')); ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 text-center text-[9px] bg-rose-100 text-rose-700 rounded font-bold">OUT</span>
                                        <span class="font-bold text-gray-700"><?php echo e(\Carbon\Carbon::parse($t->tgl_kembali)->format('d/m/y')); ?></span>
                                        <span class="text-gray-400"><?php echo e(\Carbon\Carbon::parse($t->jam_kembali)->format('H:i')); ?></span>
                                    </div>
                                    <div class="text-[10px] text-blue-600 font-bold">Durasi: <?php echo e($t->lama_sewa); ?> Hari</div>
                                </div>
                            </td>

                            
                            <td class="px-6 py-5 text-xs text-gray-600">
                                <div class="max-w-[150px] space-y-1">
                                    <div class="truncate"><i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i>
                                        <?php echo e($t->lokasi_ambil == 'kantor' ? 'Jemput di Kantor' : ($t->lokasi_ambil == 'bandara' ? 'Jemput di Bandara' : ($t->alamat_jemput ?? $t->alamat_lengkap))); ?>

                                    </div>
                                    <div class="truncate"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>
                                        <?php echo e($t->lokasi_kembali == 'kantor' ? 'Antar ke Kantor' : ($t->lokasi_kembali == 'bandara' ? 'Antar ke Bandara' : ($t->alamat_antar ?? $t->alamat_lengkap))); ?>

                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-6 py-5 whitespace-nowrap">
                                <?php
                                    $statusKey = $normalizeStatus($t->status);
                                    $isPaid = in_array($statusKey, ['disewa', 'selesai'], true);
                                    $days = (int) ($t->lama_sewa ?? 0);
                                    if ($days < 1) $days = 1;
                                    $hargaUnit = (int) ($t->mobil->harga_sewa ?? 0);
                                    $baseSewa = $hargaUnit * $days;
                                    $totalHarga = (int) ($t->total_harga ?? 0);
                                    $addon = max(0, $totalHarga - $baseSewa);
                                ?>
                                <div class="text-sm font-black text-slate-800">Rp <?php echo e(number_format($totalHarga, 0, ',', '.')); ?></div>
                                <div class="mt-1 text-[11px] text-slate-500 space-y-0.5">
                                    <div>Unit: Rp <?php echo e(number_format($baseSewa, 0, ',', '.')); ?></div>
                                    <?php if($addon > 0): ?>
                                        <div>Add-on: Rp <?php echo e(number_format($addon, 0, ',', '.')); ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php if($isPaid): ?>
                                    <span class="text-[9px] font-bold text-emerald-600 flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-circle-check"></i> Paid
                                    </span>
                                <?php else: ?>
                                    <span class="text-[9px] font-bold text-amber-600 flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-circle-exclamation"></i> Unpaid
                                  </span>
                                <?php endif; ?>
                            </td>

                            
<td class="px-6 py-5 text-center">
    <?php
        $showAction = !$isPaid && !in_array($statusKey, ['selesai', 'dibatalkan', 'ditolak'], true);
        $isSuccess = in_array($statusKey, ['disewa', 'selesai'], true);

        $statusClass = 'bg-gray-100 text-gray-600';
        if($statusKey == 'pending') $statusClass = 'bg-amber-100 text-amber-700';
        elseif($statusKey == 'disewa') $statusClass = 'bg-blue-100 text-blue-700';
        elseif($statusKey == 'selesai') $statusClass = 'bg-emerald-100 text-emerald-700';
        elseif($statusKey == 'dibatalkan') $statusClass = 'bg-red-100 text-red-700';
        elseif($statusKey == 'ditolak') $statusClass = 'bg-rose-100 text-rose-800 border-rose-500 bg-rose-50';
    ?>

    <div class="flex flex-col items-center gap-2">
        <span class="px-3 py-0.5 text-[9px] font-black uppercase rounded-full border <?php echo e($statusClass); ?>">
            <?php if($statusKey == 'pending'): ?>
                Menunggu Pembayaran
            <?php elseif($statusKey == 'disewa'): ?>
                Disewa
            <?php elseif($statusKey == 'selesai'): ?>
                Selesai
            <?php elseif($statusKey == 'dibatalkan'): ?>
                Dibatalkan
            <?php elseif($statusKey == 'ditolak'): ?>
                Ditolak
            <?php else: ?>
                <?php echo e($t->status); ?> 
            <?php endif; ?>
        </span>

        <div class="flex flex-col gap-1.5 w-full max-w-[130px]">
            
            <?php if($showAction): ?>
                <?php if(!empty($t->snap_token)): ?>
                    <button onclick="payNow('<?php echo e($t->snap_token); ?>')"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold py-2 rounded-lg shadow transition">
                        <i class="fa-solid fa-wallet mr-1"></i> Bayar Sekarang
                    </button>
                <?php else: ?>
                    <button disabled
                            class="w-full bg-gray-300 text-gray-600 text-[11px] font-bold py-2 rounded-lg shadow cursor-not-allowed">
                        Menyiapkan Pembayaran...
                    </button>
                <?php endif; ?>
                
                <form action="<?php echo e(route('transaksi.batal', $t->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan?');">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <button type="submit" class="text-[10px] text-red-500 font-bold hover:underline">
                        Batalkan Pesanan
                    </button>
                </form>
            <?php endif; ?>

            
            <?php if($isSuccess): ?>
                <a href="<?php echo e(route('transaksi.cetak', $t->id)); ?>" target="_blank" class="w-full bg-slate-800 text-white text-[10px] font-bold py-1.5 rounded-lg">
                    <i class="fa-solid fa-print mr-1"></i> E-Tiket
                </a>
            <?php endif; ?>

            
            <?php if(!in_array($statusKey, ['dibatalkan', 'ditolak'], true)): ?>
                <a href="https://wa.me/6285375285567?text=Halo Admin, Konfirmasi Order #<?php echo e($t->id); ?>" target="_blank"
                   class="w-full bg-emerald-500 text-white text-[10px] font-bold py-1.5 rounded-lg">
                    <i class="fa-brands fa-whatsapp mr-1"></i> Chat Admin
                </a>
            <?php endif; ?>
        </div>
    </div>
</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="py-20 text-center text-gray-400 font-medium">
                                <i class="fa-solid fa-car-rear text-5xl mb-4 opacity-20"></i>
                                <p>Belum ada transaksi. Ayo mulai perjalananmu!</p>
                                <a href="<?php echo e(route('dashboard')); ?>" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-bold">Cari Mobil</a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <?php
        $snapBase = config('services.midtrans.is_production') ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';
    ?>
    <script src="<?php echo e($snapBase); ?>/snap/snap.js" data-client-key="<?php echo e(config('services.midtrans.client_key')); ?>"></script>
    <script>
        async function syncMidtransResult(result) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                await fetch("<?php echo e(route('midtrans.finish')); ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ order_id: result?.order_id })
                });
            } catch (e) {
            }
        }

        function payNow(snapToken) {
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    syncMidtransResult(result).finally(() => {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('pay');
                        window.location.href = url.toString();
                    });
                },
                onPending: function(result) {
                    syncMidtransResult(result).finally(() => {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('pay');
                        window.location.href = url.toString();
                    });
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    const url = new URL(window.location.href);
                    url.searchParams.delete('pay');
                    window.location.href = url.toString();
                },
                onClose: function() {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('pay');
                    window.history.replaceState({}, document.title, url.toString());
                }
            });
        }

        <?php
            $payId = request('pay');
            $autoPayToken = null;
            if (!empty($payId)) {
                $target = $transaksis->firstWhere('id', (int) $payId);
                $autoPayToken = $target?->snap_token;
            }
        ?>

        <?php if(!empty($autoPayToken)): ?>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    payNow(<?php echo json_encode($autoPayToken, 15, 512) ?>);
                }, 300);
            });
        <?php endif; ?>
    </script>
    <?php $__env->stopPush(); ?>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/pages/riwayat.blade.php ENDPATH**/ ?>