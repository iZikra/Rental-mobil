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
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    <?php echo e(__('Pusat Pengetahuan AI (Mitra)')); ?>

                </h2>
                <p class="text-sm text-slate-500 font-medium mt-1">Kelola dokumen SOP, harga khusus, dan kebijakan toko Anda.</p>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <?php if(session('success')): ?>
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm animate-pulse">
                <div class="flex">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3"><p class="text-sm text-emerald-700 font-semibold"><?php echo e(session('success')); ?></p></div>
                </div>
            </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3"><p class="text-sm text-red-700 font-semibold"><?php echo e(session('error')); ?></p></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3">
                        <ul class="list-disc list-inside text-sm text-red-700 font-semibold">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 overflow-hidden relative group">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-white opacity-50 pointer-events-none transition duration-500 group-hover:opacity-100"></div>
                        <div class="relative z-10">
                            <h3 class="text-lg font-extrabold text-slate-800 mb-2">Upload Referensi AI</h3>
                            <p class="text-xs text-slate-500 mb-6 leading-relaxed">Format yang diterima: <code class="bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded font-bold">.txt</code> (maks 2MB). AI Anda secara otomatis akan membaca dokumen ini ketika ada yang bertanya.</p>
                            
                            <?php if($folderName): ?>
                            <form action="<?php echo e(route('mitra.rag.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih File Referensi</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="document" class="flex flex-col items-center justify-center w-full h-32 border-2 border-blue-200 border-dashed rounded-2xl cursor-pointer bg-white hover:bg-blue-50 hover:border-blue-400 transition-all duration-300">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                <p class="mb-2 text-sm text-slate-500"><span class="font-bold text-blue-600">Klik untuk upload</span></p>
                                                <p class="text-xs text-slate-400">TXT Files Only</p>
                                            </div>
                                            <input id="document" type="file" name="document" accept=".txt" class="hidden" required />
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold py-3 px-4 rounded-xl transition duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Latih Ulang Bot
                                </button>
                            </form>
                            <?php else: ?>
                            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-sm font-medium flex items-start gap-2">
                                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Akun ini tidak tertaut ke Rental/Mitra manapun. Upload dimatikan.
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="bg-sky-50 rounded-3xl p-6 border border-sky-100">
                        <div class="flex items-start gap-3">
                            <div class="text-sky-500 mt-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                            <div>
                                <h4 class="text-sm font-bold text-sky-900">Folder Data Anda: <span class="bg-sky-200 text-sky-800 px-1.5 py-0.5 rounded ml-1"><?php echo e($folderName ?? 'Kosong'); ?></span></h4>
                                <p class="text-xs text-sky-700 mt-1 leading-relaxed">
                                    File yang Anda upload di sini bersifat privat dan HANYA akan digunakan oleh Chatbot AI ketika ada pelanggan yang menanyakan ketersediaan mobil atau persyaratan di toko Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="md:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden h-full">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-800">Dokumen Kebijakan Aktif</h3>
                                <p class="text-xs text-slate-500 mt-1">File-file ini telah diserap oleh Chatbot AI.</p>
                            </div>
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full"><?php echo e(count($files)); ?> Dokumen</span>
                        </div>
                        <div class="p-0">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-white text-slate-400 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-4">Nama Dokumen</th>
                                        <th class="px-6 py-4">Ukuran</th>
                                        <th class="px-6 py-4">Terakhir Diperbarui</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php $__empty_1 = true; $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50 transition duration-150 group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-slate-100 rounded-lg text-slate-500 group-hover:bg-blue-100 group-hover:text-blue-600 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                </div>
                                                <span class="font-semibold text-slate-700"><?php echo e($file['name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 font-medium"><?php echo e($file['size']); ?></td>
                                        <td class="px-6 py-4 text-slate-500 text-xs"><?php echo e(\Carbon\Carbon::parse($file['modified'])->diffForHumans()); ?></td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="<?php echo e(route('mitra.rag.destroy', $file['name'])); ?>" method="POST" onsubmit="return confirm('Anda yakin ingin mencabut file kebijakan ini dari Chatbot AI?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition text-xs font-bold">HAPUS</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                                <p class="font-medium text-sm">Belum ada dokumen khusus Mitra.</p>
                                                <p class="text-xs mt-1">Chatbot hanya akan mengandalkan informasi dasar platform.</p>
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
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/mitra/rag/index.blade.php ENDPATH**/ ?>