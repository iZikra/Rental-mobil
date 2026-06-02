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
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                <?php echo e(__('Manajemen Pesanan Mitra')); ?>

            </h2>
            <div class="flex items-center gap-2">
                <span class="flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <p class="text-sm font-medium text-gray-500">Live Updates</p>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-emerald-500 text-white font-bold rounded shadow-lg">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-4 p-4 bg-red-500 text-white font-bold rounded shadow-lg">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 rounded-3xl p-5 shadow-xl shadow-blue-100 text-white transform hover:scale-[1.02] transition duration-300">
                    <div class="absolute right-0 bottom-0 translate-y-3 translate-x-3 opacity-10">
                        <i class="fa-solid fa-list-check text-[100px]"></i>
                    </div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-blue-100">Total Pesanan</p>
                    <h3 class="text-3xl font-black mt-1.5"><?php echo e(count($pesanan)); ?> <span class="text-xs font-semibold text-blue-200">Pesanan</span></h3>
                    <div class="mt-3 flex items-center gap-1.5 text-[10px] text-blue-100/80">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Seluruh riwayat transaksi masuk</span>
                    </div>
                </div>

                
                <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-amber-700 rounded-3xl p-5 shadow-xl shadow-amber-100 text-white transform hover:scale-[1.02] transition duration-300">
                    <div class="absolute right-0 bottom-0 translate-y-3 translate-x-3 opacity-10">
                        <i class="fa-solid fa-hourglass-half text-[100px]"></i>
                    </div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-amber-100">Butuh Tindakan</p>
                    <h3 class="text-3xl font-black mt-1.5"><?php echo e($pesanan->where('status', 'Pending')->count()); ?> <span class="text-xs font-semibold text-amber-200">Baru</span></h3>
                    <div class="mt-3 flex items-center gap-1.5 text-[10px] text-amber-100/80">
                        <?php if($pesanan->where('status', 'Pending')->count() > 0): ?>
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                            </span>
                            <span class="font-bold">Segera konfirmasi pesanan baru!</span>
                        <?php else: ?>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Semua pesanan telah diproses</span>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="relative overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-3xl p-5 shadow-xl shadow-indigo-100 text-white transform hover:scale-[1.02] transition duration-300">
                    <div class="absolute right-0 bottom-0 translate-y-3 translate-x-3 opacity-10">
                        <i class="fa-solid fa-car-side text-[100px]"></i>
                    </div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-100">Sedang Disewa</p>
                    <h3 class="text-3xl font-black mt-1.5"><?php echo e($pesanan->where('status', 'Disewa')->count()); ?> <span class="text-xs font-semibold text-indigo-200">Aktif</span></h3>
                    <div class="mt-3 flex items-center gap-1.5 text-[10px] text-indigo-100/80">
                        <i class="fa-solid fa-spinner animate-spin"></i>
                        <span>Armada sedang dibawa penyewa</span>
                    </div>
                </div>

                
                <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-3xl p-5 shadow-xl shadow-emerald-100 text-white transform hover:scale-[1.02] transition duration-300">
                    <div class="absolute right-0 bottom-0 translate-y-3 translate-x-3 opacity-10">
                        <i class="fa-solid fa-flag-checkered text-[100px]"></i>
                    </div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-100">Selesai Sewa</p>
                    <h3 class="text-3xl font-black mt-1.5"><?php echo e($pesanan->where('status', 'Selesai')->count()); ?> <span class="text-xs font-semibold text-emerald-200">Transaksi</span></h3>
                    <div class="mt-3 flex items-center gap-1.5 text-[10px] text-emerald-100/80">
                        <i class="fa-solid fa-face-smile"></i>
                        <span>Transaksi sukses diselesaikan</span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-900 text-white">
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest rounded-tl-xl">Pelanggan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest">Unit Mobil</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest">Dokumen</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest">Total Harga</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest">Status Saat Ini</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest rounded-tr-xl">Aksi Konfirmasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php $__empty_1 = true; $__currentLoopData = $pesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-blue-50/50 transition duration-200">
                                    
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold uppercase">
                                                <?php echo e(substr($p->nama ?? ($p->user->name ?? 'U'), 0, 1)); ?>

                                            </div>
                                            <div class="ml-4 flex flex-col justify-center">
                                                <div class="text-sm font-black text-gray-900"><?php echo e($p->nama ?? ($p->user->name ?? 'No Name')); ?></div>
                                                <div class="text-[10px] text-emerald-600 font-bold mb-1"><i class="fa-regular fa-clock mr-1"></i><?php echo e($p->created_at->format('H:i')); ?> WIB</div>
                                                <div class="text-[11px] text-blue-600 font-medium whitespace-nowrap"><i class="fa-brands fa-whatsapp mr-1"></i><?php echo e($p->no_hp ?? '-'); ?></div>
                                                <div class="text-[10px] text-gray-500 max-w-[150px] truncate" title="<?php echo e($p->alamat ?? $p->alamat_lengkap ?? 'Tidak ada alamat'); ?>"><i class="fa-solid fa-home mr-1"></i><?php echo e($p->alamat ?? $p->alamat_lengkap ?? 'Tidak ada alamat'); ?></div>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <?php if($p->mobil): ?>
                                            <div class="text-sm font-bold text-gray-900 uppercase tracking-tight flex items-center gap-2">
                                                <span><?php echo e($p->mobil->merk ?? 'UNIT'); ?> <?php echo e($p->mobil->model ?? 'UNKNOWN'); ?></span>
                                                <?php if($p->mobil->no_plat): ?>
                                                    <span class="text-[9px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded border border-slate-200 font-mono tracking-tight font-extrabold uppercase shadow-sm" title="Nomor Plat Mobil">
                                                        <?php echo e($p->mobil->no_plat); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex flex-col gap-1.5 mt-2">
                                                <div class="flex items-center gap-1.5 text-[10px] text-gray-700 font-bold bg-blue-50 px-2 py-1 rounded w-max border border-blue-100 shadow-sm">
                                                    <i class="fa-regular fa-calendar-alt text-blue-500"></i>
                                                    <?php echo e(\Carbon\Carbon::parse($p->tgl_ambil)->format('d M y')); ?> <?php echo e(substr($p->jam_ambil ?? '09:00', 0, 5)); ?> 
                                                    <i class="fa-solid fa-arrow-right text-gray-400 mx-0.5"></i> 
                                                    <?php echo e(\Carbon\Carbon::parse($p->tgl_kembali)->format('d M y')); ?> <?php echo e(substr($p->jam_kembali ?? '09:00', 0, 5)); ?>

                                                </div>
                                                <div class="flex flex-col gap-0.5 text-[10px] text-gray-500 font-medium">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="fa-solid fa-location-dot text-red-500"></i>
                                                        <span class="truncate max-w-[150px]" title="<?php echo e($p->alamat_jemput ?? 'Kantor Rental'); ?>">
                                                            Ambil: <?php echo e($p->alamat_jemput ?? 'Kantor Rental'); ?>

                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 ml-0.5 border-l border-dashed border-gray-300 pl-1.5 py-0.5">
                                                        <i class="fa-solid fa-flag-checkered text-gray-400"></i>
                                                        <span class="truncate max-w-[150px]" title="<?php echo e($p->alamat_antar ?? 'Kantor Rental'); ?>">
                                                            Kembali: <?php echo e($p->alamat_antar ?? 'Kantor Rental'); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="flex flex-wrap gap-1.5 mt-1">
                                                    <?php if($p->sopir == 'dengan_sopir'): ?>
                                                        <span class="text-[9px] bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100 font-extrabold uppercase flex items-center gap-1 shadow-sm">
                                                            👮 Pakai Sopir
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-[9px] bg-slate-50 text-slate-500 px-2 py-0.5 rounded border border-slate-200 font-bold uppercase flex items-center gap-1 shadow-sm">
                                                            🔑 Lepas Kunci
                                                        </span>
                                                    <?php endif; ?>

                                                    <?php if($p->tujuan): ?>
                                                        <span class="text-[9px] bg-amber-50 text-amber-700 px-2 py-0.5 rounded border border-amber-100 font-extrabold uppercase flex items-center gap-1 shadow-sm" title="Tujuan: <?php echo e($p->tujuan); ?>">
                                                            📍 Ke: <?php echo e(\Illuminate\Support\Str::limit($p->tujuan, 15)); ?>

                                                        </span>
                                                    <?php endif; ?>

                                                    <?php if($p->catatan): ?>
                                                        <span class="text-[9px] bg-purple-50 text-purple-700 px-2 py-0.5 rounded border border-purple-100 font-extrabold uppercase flex items-center gap-1 shadow-sm" title="Catatan: <?php echo e($p->catatan); ?>">
                                                            📝 <?php echo e(\Illuminate\Support\Str::limit($p->catatan, 15)); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-sm font-bold text-gray-900 uppercase tracking-tight">-</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            
                                            
                                            <div class="flex items-center gap-3 justify-center">
                                                
                                                <?php if($p->foto_identitas): ?>
                                                    <a href="<?php echo e(url('img-proxy/' . $p->foto_identitas)); ?>" target="_blank" class="group relative transform hover:scale-110 transition duration-200">
                                                        <img src="<?php echo e(url('img-proxy/' . $p->foto_identitas)); ?>" class="h-10 w-14 object-cover rounded border border-gray-300 group-hover:border-blue-500 shadow-sm" alt="KTP">
                                                        <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow shadow-blue-200">KTP</span>
                                                    </a>
                                                <?php endif; ?>

                                                
                                                <?php if($p->foto_sim): ?>
                                                    <a href="<?php echo e(url('img-proxy/' . $p->foto_sim)); ?>" target="_blank" class="group relative transform hover:scale-110 transition duration-200">
                                                        <img src="<?php echo e(url('img-proxy/' . $p->foto_sim)); ?>" class="h-10 w-14 object-cover rounded border border-gray-300 group-hover:border-emerald-500 shadow-sm" alt="SIM">
                                                        <span class="absolute -top-2 -right-2 bg-emerald-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow shadow-emerald-200">SIM</span>
                                                    </a>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        <div class="text-sm font-black text-gray-900">Rp <?php echo e(number_format($p->total_harga, 0, ',', '.')); ?></div>
                                        <div class="text-[9px] text-gray-400 font-bold uppercase"><?php echo e($p->lama_sewa ?? 0); ?> Hari</div>
                                        <?php if($p->biaya_tambahan > 0): ?>
                                            <div class="text-[8px] text-rose-500 font-extrabold uppercase mt-1" title="Termasuk biaya tambahan layanan antar-jemput luar kantor">
                                                + Rp <?php echo e(number_format($p->biaya_tambahan, 0, ',', '.')); ?> Layanan
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        <?php
                                            $stRaw = strtolower(trim($p->status));
                                            $color = match($stRaw) {
                                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                'disewa' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                'selesai' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                'dibatalkan' => 'bg-red-100 text-red-700 border-red-200',
                                                default => 'bg-gray-100 text-gray-700 border-gray-200'
                                            };
                                        ?>
                                        <span class="px-3 py-1 inline-flex text-[10px] font-black rounded-full border shadow-sm <?php echo e($color); ?> uppercase">
                                            <?php echo e($p->status); ?>

                                        </span>
                                    </td>

                                    
                                    
<td class="px-6 py-5 whitespace-nowrap text-center">
    <div class="flex flex-col items-center justify-center gap-2">
        
        <?php $stRaw = strtolower(trim($p->status)); ?>

        <?php if($stRaw == 'pending'): ?>
            <span class="text-gray-400 text-[10px] font-bold italic uppercase">Menunggu konfirmasi</span>
            
            <form action="<?php echo e(route('mitra.pesanan.konfirmasi', $p->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase shadow-lg transition-all mb-2">
                    Terima & Tagih
                </button>
            </form>

            <form action="<?php echo e(route('mitra.pesanan.tolak', $p->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" onclick="return confirm('Tolak pesanan ini?')" class="w-full bg-white border-2 border-rose-500 text-rose-500 px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all">
                    Tolak
                </button>
            </form>

        <?php elseif($stRaw == 'disewa'): ?>
            <form action="<?php echo e(route('mitra.pesanan.selesai', $p->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase shadow-lg transition-all">
                    Selesai Sewa
                </button>
            </form>
        <?php else: ?>
            <span class="text-gray-300 text-[10px] font-bold italic uppercase">Status: <?php echo e($stRaw); ?></span>
        <?php endif; ?>
    </div>
</td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center text-gray-400 font-bold uppercase tracking-widest">
                                        Belum Ada Data Pesanan Masuk
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/mitra/pesanan/index.blade.php ENDPATH**/ ?>