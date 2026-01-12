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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <img src="<?php echo e(asset('img/baner.jpg')); ?>" 
                 alt="Banner FZ Rent Car" 
                 class="w-full h-auto object-cover rounded-xl shadow-2xl border-4 border-white/10">
        </div>
    </div>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <?php if(session('success')): ?>
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                    </span>
                </div>
            <?php endif; ?>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="py-16 bg-gray-100">
        </div>

    <div class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-serif font-bold text-gray-900">ARMADA KAMI</h2>
                <div class="w-24 h-1 bg-red-600 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <?php $__empty_1 = true; $__currentLoopData = $mobils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mobil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-2 group">
                    <div class="relative overflow-hidden h-56 bg-gray-200">
                        <?php if($mobil->gambar): ?>
                            <img src="<?php echo e(asset('img/' . $mobil->gambar)); ?>" 
                            alt="<?php echo e($mobil->merk); ?>" 
                            class="w-full h-48 object-cover">
                        <?php else: ?>
                        
                        <?php endif; ?>
                        <div class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">Ready</div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-1"><?php echo e($mobil->merk); ?> <?php echo e($mobil->model); ?></h3>
                        <p class="text-gray-500 text-sm mb-4 truncate"><?php echo e($mobil->deskripsi ?? 'Fasilitas Lengkap'); ?></p>
                        
                        <div class="flex justify-between items-end border-t border-gray-100 pt-4">
                            <div>
                                <span class="text-xs text-gray-400 block">Harga Sewa</span>
                                <span class="text-red-600 font-bold text-lg">Rp <?php echo e(number_format($mobil->harga_sewa, 0, ',', '.')); ?></span>
                            </div>
                            
                            <a href="<?php echo e(route('pages.order', ['mobil_id' => $mobil->id])); ?>" class="w-full block text-center bg-red-600 text-white font-bold py-2 rounded-lg hover:bg-red-700 transition">
                                Sewa Mobil
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-3 text-center py-10 bg-white rounded-lg border border-dashed border-gray-300">
                    <p class="text-gray-500 text-lg font-serif">Belum ada armada yang tersedia.</p>
                    <p class="text-sm text-gray-400">Admin belum menambahkan mobil dengan status 'tersedia'.</p>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
<style>
    #chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 99999 !important; /* Memaksa muncul paling atas */
        font-family: sans-serif;
    }
    #chat-window {
        display: none; /* Sembunyi dulu */
        width: 350px;
        height: 450px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        flex-direction: column;
        border: 1px solid #ddd;
        margin-bottom: 15px;
        overflow: hidden;
    }
    .chat-header { background: #2563EB; color: white; padding: 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
    .chat-body { flex: 1; padding: 15px; overflow-y: auto; background: #f9fafb; display: flex; flex-direction: column; gap: 10px; }
    .chat-footer { padding: 10px; border-top: 1px solid #eee; display: flex; gap: 5px; background: white; }
    .btn-toggle {
        width: 60px; height: 60px; background: #2563EB; border-radius: 50%; color: white; border: none; cursor: pointer;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.4); display: flex; align-items: center; justify-content: center; font-size: 24px;
        transition: transform 0.2s;
    }
    .btn-toggle:hover { transform: scale(1.1); }
    .msg-user { align-self: flex-end; background: #2563EB; color: white; padding: 8px 12px; border-radius: 15px 15px 0 15px; font-size: 13px; max-width: 80%; }
    .msg-bot { align-self: flex-start; background: #e5e7eb; color: #1f2937; padding: 8px 12px; border-radius: 15px 15px 15px 0; font-size: 13px; max-width: 80%; }
</style>

<div id="chatbot-container">
    <div id="chat-window">
        <div class="chat-header">
            <span>🤖 CS Otomatis</span>
            <button onclick="toggleChat()" style="background:none; border:none; color:white; cursor:pointer;">✕</button>
        </div>
        <div class="chat-body" id="chat-messages">
            <div class="msg-bot">Halo! Ada yang bisa saya bantu? Silakan tanya stok mobil atau harga.</div>
        </div>
        <div class="chat-footer">
            <input type="text" id="chat-input" placeholder="Tulis pesan..." style="flex:1; padding:8px; border:1px solid #ccc; border-radius:20px; outline:none;">
            <button onclick="kirimPesan()" style="background:#2563EB; color:white; border:none; padding:8px 15px; border-radius:20px; cursor:pointer;">➤</button>
        </div>
    </div>

    <button class="btn-toggle" onclick="toggleChat()">💬</button>
</div>

<script>
    // 1. SAAT HALAMAN DIBUKA: Cek apakah ada riwayat chat?
    document.addEventListener("DOMContentLoaded", function() {
        var savedChat = localStorage.getItem("chat_history");
        if (savedChat) {
            document.getElementById('chat-messages').innerHTML = savedChat;
            // Scroll ke bawah
            var chatBox = document.getElementById('chat-messages');
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });

    // Fungsi Buka/Tutup
    function toggleChat() {
        var box = document.getElementById('chat-window');
        if (box.style.display === 'none' || box.style.display === '') {
            box.style.display = 'flex';
        } else {
            box.style.display = 'none';
        }
    }

    // Fungsi Simpan Chat ke Browser
    function simpanRiwayat() {
        var isiChat = document.getElementById('chat-messages').innerHTML;
        localStorage.setItem("chat_history", isiChat);
    }

    // Fungsi Kirim Pesan
    async function kirimPesan() {
        var input = document.getElementById('chat-input');
        var message = input.value;
        var chatBox = document.getElementById('chat-messages');

        if(message.trim() === '') return;

        // Tampilkan Pesan User
        chatBox.innerHTML += `<div class="msg-user">${message}</div>`;
        input.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;
        simpanRiwayat(); // <--- Simpan otomatis

        // Tampilkan Loading
        var loadingId = 'loading-' + Date.now();
        chatBox.innerHTML += `<div id="${loadingId}" class="msg-bot">Sedang mengetik...</div>`;
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            let response = await fetch("<?php echo e(route('chatbot.send')); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                },
                body: JSON.stringify({ message: message })
            });

            let data = await response.json();

            // Ganti Loading dengan Jawaban Bot
            document.getElementById(loadingId).remove();
            chatBox.innerHTML += `<div class="msg-bot">${data.reply}</div>`;
            simpanRiwayat(); // <--- Simpan otomatis setelah bot menjawab

        } catch (error) {
            document.getElementById(loadingId).remove();
            chatBox.innerHTML += `<div class="msg-bot" style="color:red;">Error: Gagal terhubung.</div>`;
        }
        
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Tombol Enter
    document.getElementById('chat-input').addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            kirimPesan();
        }
    });
</script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/dashboard.blade.php ENDPATH**/ ?>