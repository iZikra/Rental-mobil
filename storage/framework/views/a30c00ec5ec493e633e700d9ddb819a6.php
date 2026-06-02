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
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-800 leading-tight uppercase tracking-tight">
                    <?php echo e(__('Armada Saya')); ?>

                </h2>
                <p class="text-xs font-semibold text-slate-400 mt-1">Kelola ketersediaan, spesifikasi, dan tarif sewa mobil rental Anda.</p>
            </div>
            <a href="<?php echo e(route('mitra.mobil.create')); ?>" class="group inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase shadow-lg shadow-blue-200 hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                <i class="fa-solid fa-car-plus text-sm group-hover:rotate-12 transition-transform"></i>
                Tambah Mobil Baru
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-emerald-500 text-white font-bold rounded-2xl shadow-lg shadow-emerald-100 flex items-center gap-3 animate-fade-in">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <span><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-6 p-4 bg-rose-500 text-white font-bold rounded-2xl shadow-lg shadow-rose-100 flex items-center gap-3 animate-fade-in">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    <span><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <div class="relative overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-3xl p-6 shadow-xl shadow-indigo-100 text-white transform hover:scale-[1.02] transition duration-300">
                    <div class="absolute right-0 bottom-0 translate-y-4 translate-x-4 opacity-10">
                        <i class="fa-solid fa-car text-[120px]"></i>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-100">Total Armada</p>
                    <h3 class="text-4xl font-black mt-2"><?php echo e(count($mobils)); ?> <span class="text-sm font-medium text-indigo-200">Unit</span></h3>
                    <div class="mt-4 flex items-center gap-2 text-xs text-indigo-100/80">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Armada terdaftar di seluruh cabang Anda</span>
                    </div>
                </div>

                
                <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-3xl p-6 shadow-xl shadow-emerald-100 text-white transform hover:scale-[1.02] transition duration-300">
                    <div class="absolute right-0 bottom-0 translate-y-4 translate-x-4 opacity-10">
                        <i class="fa-solid fa-circle-check text-[120px]"></i>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-100">Armada Ready</p>
                    <h3 class="text-4xl font-black mt-2"><?php echo e($mobils->where('status', 'tersedia')->count()); ?> <span class="text-sm font-medium text-emerald-200">Unit</span></h3>
                    <div class="mt-4 flex items-center gap-2 text-xs text-emerald-100/80">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                        </span>
                        <span>Siap dipesan langsung oleh pelanggan</span>
                    </div>
                </div>

                
                <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-amber-700 rounded-3xl p-6 shadow-xl shadow-amber-100 text-white transform hover:scale-[1.02] transition duration-300">
                    <div class="absolute right-0 bottom-0 translate-y-4 translate-x-4 opacity-10">
                        <i class="fa-solid fa-key text-[120px]"></i>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-100">Sedang Disewa / Sibuk</p>
                    <h3 class="text-4xl font-black mt-2"><?php echo e($mobils->where('status', '!=', 'tersedia')->count()); ?> <span class="text-sm font-medium text-amber-200">Unit</span></h3>
                    <div class="mt-4 flex items-center gap-2 text-xs text-amber-100/80">
                        <i class="fa-solid fa-spinner animate-spin"></i>
                        <span>Armada sedang aktif di jalan atau diservis</span>
                    </div>
                </div>
            </div>

            
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-slate-100">
                <div class="p-6 md:p-8">
                    <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-sm">
                        <table class="min-w-full divide-y divide-slate-100 align-middle">
                            <thead>
                                <tr class="bg-slate-900 text-slate-100 text-left">
                                    <th class="px-6 py-5 text-xs font-extrabold uppercase tracking-widest rounded-tl-2xl">Visual Unit</th>
                                    <th class="px-6 py-5 text-xs font-extrabold uppercase tracking-widest">Detail & Spesifikasi</th>
                                    <th class="px-6 py-5 text-center text-xs font-extrabold uppercase tracking-widest">Cabang Lokasi</th>
                                    <th class="px-6 py-5 text-center text-xs font-extrabold uppercase tracking-widest">Harga / Hari</th>
                                    <th class="px-6 py-5 text-center text-xs font-extrabold uppercase tracking-widest">Status</th>
                                    <th class="px-6 py-5 text-center text-xs font-extrabold uppercase tracking-widest rounded-tr-2xl">Tindakan</th>
                                </tr>
                            </thead>
                            
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <?php $__empty_1 = true; $__currentLoopData = $mobils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/70 transition duration-200 group">
                                    
                                    
                                    <td class="px-6 py-5 w-40">
                                        <div class="relative overflow-hidden rounded-2xl bg-slate-50 border border-slate-150 p-2 h-24 flex items-center justify-center shadow-inner group-hover:border-blue-300 group-hover:bg-blue-50/20 transition-all duration-300">
                                            <img src="<?php echo e($m->image_url); ?>"
                                                 alt="<?php echo e($m->merk); ?> <?php echo e($m->model); ?>"
                                                 class="max-h-full max-w-full object-contain relative z-10 group-hover:scale-110 transition-transform duration-500 filter drop-shadow-md <?php echo e($m->status != 'tersedia' ? 'grayscale opacity-60' : ''); ?>"
                                                 onerror="this.src='https://placehold.co/200x120?text=Tanpa+Foto'">
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg font-black text-slate-800 uppercase tracking-tight"><?php echo e($m->model); ?></span>
                                                <?php if($m->no_plat): ?>
                                                    <span class="text-[9px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-mono tracking-tight font-extrabold uppercase shadow-sm">
                                                        <?php echo e($m->no_plat); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-xs font-black text-blue-600 uppercase tracking-widest mt-0.5 leading-none">
                                                <?php echo e($m->merk ?? $m->merek); ?>

                                            </span>
                                            
                                            
                                            <div class="flex flex-wrap gap-1 mt-2.5">
                                                <?php if($m->tipe_mobil): ?>
                                                    <span class="text-[9px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-bold uppercase tracking-tight">
                                                        🚙 <?php echo e($m->tipe_mobil); ?>

                                                    </span>
                                                <?php endif; ?>
                                                <span class="text-[9px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-bold uppercase tracking-tight">
                                                    ⚙️ <?php echo e($m->transmisi); ?>

                                                </span>
                                                <span class="text-[9px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-bold uppercase tracking-tight">
                                                    ⛽ <?php echo e($m->bahan_bakar); ?>

                                                </span>
                                                <span class="text-[9px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-bold uppercase tracking-tight">
                                                    👥 <?php echo e($m->jumlah_kursi); ?> Kursi
                                                </span>
                                                <span class="text-[9px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-bold tracking-tight">
                                                    📅 Th <?php echo e($m->tahun_buat); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center gap-1 bg-indigo-50 border border-indigo-150 text-indigo-700 text-[10px] font-black uppercase px-2.5 py-1 rounded-full shadow-inner">
                                            <i class="fa-solid fa-location-dot"></i>
                                            <?php echo e($m->branch->nama_cabang ?? 'Pusat'); ?> (<?php echo e($m->branch->kota ?? 'Medan'); ?>)
                                        </span>
                                    </td>
                                    
                                    
                                    <td class="px-6 py-5 text-center whitespace-nowrap">
                                        <div class="text-base font-black text-slate-900">Rp <?php echo e(number_format($m->harga_sewa, 0, ',', '.')); ?></div>
                                        <div class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Per Hari</div>
                                    </td>
                                    
                                    
                                    <td class="px-6 py-5 text-center whitespace-nowrap">
                                        <?php if($m->status == 'tersedia'): ?>
                                            <span class="px-3 py-1 inline-flex items-center gap-1.5 text-[10px] font-black rounded-full bg-emerald-100 border border-emerald-200 text-emerald-700 uppercase shadow-sm">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Tersedia
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 inline-flex items-center gap-1.5 text-[10px] font-black rounded-full bg-rose-100 border border-rose-200 text-rose-700 uppercase shadow-sm">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                <?php echo e($m->status); ?>

                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    
                                    <td class="px-6 py-5 text-center whitespace-nowrap">
                                        <div class="flex justify-center gap-2">
                                            <a href="<?php echo e(route('mitra.mobil.edit', $m->id)); ?>" class="group/btn bg-amber-50 hover:bg-amber-500 text-amber-600 hover:text-white p-2.5 rounded-xl border border-amber-200 hover:border-amber-500 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5" title="Edit Armada">
                                                <svg class="w-4 h-4 group-hover/btn:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="<?php echo e(route('mitra.mobil.destroy', $m->id)); ?>" method="POST" class="inline-block">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus mobil <?php echo e($m->model); ?> secara permanen dari sistem?')" class="group/btn bg-rose-50 hover:bg-rose-500 text-rose-600 hover:text-white p-2.5 rounded-xl border border-rose-200 hover:border-rose-500 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5" title="Hapus Armada">
                                                    <svg class="w-4 h-4 group-hover/btn:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center text-slate-400 font-extrabold uppercase tracking-widest bg-slate-50/30">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <i class="fa-solid fa-car-rear text-4xl text-slate-300"></i>
                                            <span>Belum Ada Armada Terdaftar</span>
                                        </div>
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
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/mitra/mobil/index.blade.php ENDPATH**/ ?>