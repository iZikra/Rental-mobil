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
        html { overflow-y: scroll; }
    </style>

<!--  -->

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                
                
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-serif font-bold text-gray-900 mb-4">HUBUNGI KAMI</h2>
                    <div class="w-20 h-1 bg-red-600 mx-auto"></div>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    
                    
                    <div class="bg-gray-50 p-8 rounded-lg border border-gray-100">
                        <h3 class="text-xl font-bold mb-6 text-gray-800">Informasi Kontak</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div>
                                    <h4 class="font-bold text-gray-700">Alamat Kantor</h4>
                                    <p class="text-gray-600">Jl. Teropong, Riau, Pekanbaru</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div>
                                    <h4 class="font-bold text-gray-700">WhatsApp / Telepon</h4>
                                    <p class="text-gray-600">+62 838 9651 7385</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div>
                                    <h4 class="font-bold text-gray-700">Email</h4>
                                    <p class="text-gray-600">admin@rentcar.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <form action="#" class="space-y-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Anda</label>
                                <input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pesan</label>
                                <textarea rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                            </div>
                            <button type="button" class="bg-gray-800 text-white px-6 py-2 rounded-md hover:bg-gray-900 transition">Kirim Pesan</button>
                        </form>
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\pages\contact.blade.php ENDPATH**/ ?>