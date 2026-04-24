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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap');
        
        .font-outfit { font-family: 'Outfit', sans-serif; }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15);
        }

        .hero-bg {
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)), 
                        url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    </style>

    <div class="min-h-screen bg-[#f8fafc] font-outfit pb-20">
        
        <div class="relative hero-bg pt-32 pb-64 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] bg-blue-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute -bottom-[30%] -left-[10%] w-[70%] h-[70%] bg-indigo-600/10 rounded-full blur-[120px]"></div>
            </div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mb-6 animate-fade-in-up">
                    Customer Area
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6 animate-fade-in-up" style="animation-delay: 0.1s">
                    Riwayat <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Perjalanan</span>
                </h1>
                <p class="max-w-2xl mx-auto text-lg text-slate-400 font-medium leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s">
                    Pantau status pemesanan, lakukan pembayaran, dan unduh tiket elektronik Anda dalam satu tempat yang aman dan terorganisir.
                </p>
            </div>
        </div>

        <?php
            $normalizeStatus = function ($status) {
                $s = strtolower(trim((string) ($status ?? '')));
                if (in_array($s, ['pending', 'menunggu', 'menunggu_pembayaran', 'process', 'processing'], true)) return 'pending';
                if (in_array($s, ['disewa', 'approved', 'disetujui', 'sedang_jalan', 'sedang_disewa'], true)) return 'disewa';
                if (in_array($s, ['selesai', 'completed', 'complete'], true)) return 'selesai';
                if (in_array($s, ['expire', 'expired'], true)) return 'expired';
                if (in_array($s, ['dibatalkan', 'batal', 'cancel', 'canceled'], true)) return 'dibatalkan';
                if (in_array($s, ['ditolak', 'reject', 'rejected', 'deny', 'denied'], true)) return 'ditolak';
                return $s ?: 'unknown';
            };
            $totalCount = count($transaksis);
            $pendingCount = collect($transaksis)->filter(fn($t) => $normalizeStatus($t->status) === 'pending')->count();
            $activeCount = collect($transaksis)->filter(fn($t) => $normalizeStatus($t->status) === 'disewa')->count();
            $doneCount = collect($transaksis)->filter(fn($t) => $normalizeStatus($t->status) === 'selesai')->count();
        ?>

        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-20 relative z-20">
            
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 animate-fade-in-up" style="animation-delay: 0.3s">
                <div class="glass-card p-6 rounded-[2.5rem] shadow-xl border-white/40">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total</p>
                    <div class="flex items-center justify-between">
                        <h4 class="text-3xl font-black text-slate-900"><?php echo e($totalCount); ?></h4>
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500">
                            <i class="fa-solid fa-receipt text-sm"></i>
                        </div>
                    </div>
                </div>
                <div class="glass-card p-6 rounded-[2.5rem] shadow-xl border-amber-100 bg-amber-50/30">
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">Pending</p>
                    <div class="flex items-center justify-between">
                        <h4 class="text-3xl font-black text-amber-600"><?php echo e($pendingCount); ?></h4>
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                            <i class="fa-solid fa-hourglass-half text-sm"></i>
                        </div>
                    </div>
                </div>
                <div class="glass-card p-6 rounded-[2.5rem] shadow-xl border-blue-100 bg-blue-50/30">
                    <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Active</p>
                    <div class="flex items-center justify-between">
                        <h4 class="text-3xl font-black text-blue-600"><?php echo e($activeCount); ?></h4>
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-car text-sm"></i>
                        </div>
                    </div>
                </div>
                <div class="glass-card p-6 rounded-[2.5rem] shadow-xl border-emerald-100 bg-emerald-50/30">
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Done</p>
                    <div class="flex items-center justify-between">
                        <h4 class="text-3xl font-black text-emerald-600"><?php echo e($doneCount); ?></h4>
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-circle-check text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s">
                <div class="bg-slate-900 px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white border border-white/10">
                            <i class="fa-solid fa-list-ul"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white tracking-tight">Daftar Transaksi</h3>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">History Management</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 bg-blue-600/20 text-blue-400 rounded-xl text-xs font-black uppercase tracking-widest border border-blue-600/20">
                        <?php echo e($totalCount); ?> Pesanan Terdaftar
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Kendaraan & Detail</th>
                                <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Jadwal & Durasi</th>
                                <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Biaya Total</th>
                                <th class="px-8 py-5 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest">Status & Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $statusKey = $normalizeStatus($t->status);
                                $isPaid = in_array($statusKey, ['disewa', 'selesai'], true);
                                $statusClass = match($statusKey) {
                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'disewa' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'dibatalkan', 'ditolak', 'expired' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100'
                                };
                            ?>
                            <tr class="hover:bg-slate-50/50 transition-all duration-300 group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-6">
                                        <div class="relative w-28 h-20 rounded-2xl overflow-hidden shadow-lg border border-slate-100 shrink-0">
                                            <img src="<?php echo e($t->mobil?->image_url ?: 'https://placehold.co/600x400?text=Mobil'); ?>" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent"></div>
                                        </div>
                                        <div class="space-y-1">
                                            <h4 class="text-base font-black text-slate-900"><?php echo e($t->mobil->merk ?? 'Mobil'); ?> <?php echo e($t->mobil->model ?? ''); ?></h4>
                                            <div class="flex items-center gap-3">
                                                <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">#<?php echo e($t->id); ?></span>
                                                <span class="text-slate-400">•</span>
                                                <span class="text-[10px] font-bold text-slate-500 uppercase"><?php echo e(optional($t->created_at)->format('d M Y')); ?></span>
                                            </div>
                                            <p class="text-xs font-bold text-slate-400 flex items-center gap-1">
                                                <i class="fa-solid fa-building text-[10px]"></i> <?php echo e($t->rental->nama_rental ?? 'Mitra Rental'); ?>

                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-4">
                                            <div class="flex flex-col">
                                                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">Pickup</span>
                                                <span class="text-xs font-black text-slate-800"><?php echo e(\Carbon\Carbon::parse($t->tgl_ambil)->format('d M Y')); ?></span>
                                                <span class="text-[10px] font-bold text-slate-400"><?php echo e(\Carbon\Carbon::parse($t->jam_ambil)->format('H:i')); ?></span>
                                            </div>
                                            <div class="w-8 h-px bg-slate-200 relative">
                                                <i class="fa-solid fa-chevron-right absolute -right-1 -top-[5px] text-[8px] text-slate-300"></i>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest mb-1">Return</span>
                                                <span class="text-xs font-black text-slate-800"><?php echo e(\Carbon\Carbon::parse($t->tgl_kembali)->format('d M Y')); ?></span>
                                                <span class="text-[10px] font-bold text-slate-400"><?php echo e(\Carbon\Carbon::parse($t->jam_kembali)->format('H:i')); ?></span>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-blue-100">
                                            <i class="fa-solid fa-calendar-day"></i> <?php echo e($t->lama_sewa); ?> Hari Sewa
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Bill</span>
                                        <span class="text-base font-black text-slate-900">Rp <?php echo e(number_format($t->total_harga, 0, ',', '.')); ?></span>
                                        <span class="mt-2 inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest <?php echo e($isPaid ? 'text-emerald-500' : 'text-amber-500'); ?>">
                                            <i class="fa-solid <?php echo e($isPaid ? 'fa-circle-check' : 'fa-circle-exclamation'); ?>"></i>
                                            <?php echo e($isPaid ? 'Paid In Full' : 'Pending Payment'); ?>

                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center gap-4">
                                        <span class="px-4 py-1.5 rounded-full border text-[9px] font-black uppercase tracking-[0.2em] <?php echo e($statusClass); ?>">
                                            <?php if($statusKey == 'pending'): ?> Menunggu Pembayaran
                                            <?php elseif($statusKey == 'expired'): ?> Kadaluarsa
                                            <?php elseif($statusKey == 'disewa'): ?> Sedang Disewa
                                            <?php elseif($statusKey == 'selesai'): ?> Selesai
                                            <?php elseif($statusKey == 'dibatalkan'): ?> Dibatalkan
                                            <?php elseif($statusKey == 'ditolak'): ?> Ditolak
                                            <?php else: ?> <?php echo e($t->status); ?> <?php endif; ?>
                                        </span>

                                        <div class="flex items-center gap-2">
                                            
                                            <?php if($statusKey == 'pending' && !empty($t->snap_token)): ?>
                                                <button onclick="payNow('<?php echo e($t->snap_token); ?>')" 
                                                        class="w-32 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black rounded-xl shadow-lg shadow-blue-600/20 transition-all uppercase tracking-widest">
                                                    Bayar Sekarang
                                                </button>
                                            <?php endif; ?>

                                            <?php if($isPaid || $statusKey == 'selesai'): ?>
                                                <a href="<?php echo e(route('transaksi.cetak', $t->id)); ?>" target="_blank"
                                                   class="w-10 h-10 bg-slate-100 hover:bg-slate-900 text-slate-600 hover:text-white rounded-xl flex items-center justify-center transition-all shadow-sm border border-slate-200 group/btn" title="Download E-Tiket">
                                                    <i class="fa-solid fa-file-pdf group-hover:scale-110 transition-transform"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if(!in_array($statusKey, ['dibatalkan', 'ditolak'], true)): ?>
                                                <a href="https://wa.me/6285375285567?text=Halo Admin, Konfirmasi Order #<?php echo e($t->id); ?>" target="_blank"
                                                   class="w-10 h-10 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl flex items-center justify-center transition-all shadow-lg shadow-emerald-500/20 group/btn" title="Chat Admin">
                                                    <i class="fa-brands fa-whatsapp text-lg group-hover:scale-110 transition-transform"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if(!in_array($statusKey, ['selesai', 'dibatalkan', 'ditolak', 'expired'])): ?>
                                            <form action="<?php echo e(route('transaksi.batal', $t->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan?');" class="inline">
                                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                                <button type="submit" class="w-10 h-10 bg-rose-50 hover:bg-rose-600 text-rose-500 hover:text-white rounded-xl flex items-center justify-center transition-all shadow-sm border border-rose-100 group/btn" title="Batalkan Pesanan">
                                                    <i class="fa-solid fa-trash-can group-hover:scale-110 transition-transform text-sm"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="py-32 text-center">
                                    <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-slate-200 rotate-12 group hover:rotate-0 transition-transform duration-500">
                                        <i class="fa-solid fa-car-rear text-4xl"></i>
                                    </div>
                                    <h3 class="text-2xl font-black text-slate-900 mb-2">Belum Ada Perjalanan</h3>
                                    <p class="text-slate-500 font-medium mb-8">Anda belum memiliki riwayat transaksi saat ini.</p>
                                    <a href="<?php echo e(route('pages.order')); ?>" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-2xl shadow-blue-600/30 transition-all hover:scale-105">Mulai Booking Sekarang</a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\pages\riwayat.blade.php ENDPATH**/ ?>