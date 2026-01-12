<div id="chatbot-container" class="fixed bottom-6 right-6 z-50 flex flex-col items-end font-sans no-print">

    {{-- 1. JENDELA CHAT --}}
    <div id="chat-window" class="hidden bg-white shadow-2xl rounded-2xl w-80 h-96 flex flex-col overflow-hidden border border-gray-200 mb-4 transform transition-all duration-300 origin-bottom-right">
        
        {{-- Header --}}
        <div class="bg-indigo-600 p-4 text-white flex justify-between items-center flex-shrink-0">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-indigo-600 font-bold animate-bounce">🤖</div>
                <div>
                    <h3 class="font-bold text-sm">Smart Assistant</h3>
                    <p class="text-xs text-indigo-200">Siap membantu 24/7</p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white hover:text-gray-200">✕</button>
        </div>

        {{-- Body Chat (Tempat Pesan Muncul) --}}
        <div id="chat-messages" class="flex-1 p-4 bg-gray-50 overflow-y-auto space-y-3">
            {{-- Pesan Awal --}}
            <div class="chat-bubble bot">
                Halo <strong>{{ Auth::user()->name ?? 'Kak' }}</strong>! 👋 <br>
                Saya bisa bantu cek ketersediaan mobil dan langsung bookingkan untukmu.
            </div>
        </div>

        {{-- Footer (Menu Pilihan) --}}
        <div class="p-2 bg-white border-t border-gray-100 grid grid-cols-1 gap-2 flex-shrink-0">
            <button onclick="checkCars()" class="text-left text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 p-2 rounded transition border border-indigo-200 font-bold flex items-center gap-2">
                🚗 Cek Mobil Ready
            </button>
            <button onclick="window.location.href='{{ route('riwayat') }}'" class="text-left text-xs bg-gray-50 hover:bg-gray-100 p-2 rounded transition border border-gray-200">
                📄 Cek Pesanan Saya
            </button>
        </div>
    </div>

    {{-- 2. TOMBOL PEMICU --}}
    <button onclick="toggleChat()" id="chat-trigger" class="bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-full shadow-lg transition transform hover:scale-110 flex items-center justify-center group relative">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
    </button>

</div>

<style>
    .chat-bubble { padding: 10px; border-radius: 10px; font-size: 13px; max-width: 90%; animation: fadeIn 0.3s ease; }
    .chat-bubble.bot { background: white; border: 1px solid #e5e7eb; border-bottom-left-radius: 0; color: #374151; }
    .chat-bubble.user { background: #4F46E5; color: white; border-bottom-right-radius: 0; align-self: flex-end; margin-left: auto; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    const chatContainer = document.getElementById('chat-messages');

    function toggleChat() {
        const win = document.getElementById('chat-window');
        win.classList.toggle('hidden');
    }

    // Fungsi Menambahkan Pesan ke Layar
    function addMessage(text, sender = 'bot') {
        const div = document.createElement('div');
        div.className = `chat-bubble ${sender} mb-2`;
        div.innerHTML = text;
        chatContainer.appendChild(div);
        chatContainer.scrollTop = chatContainer.scrollHeight; // Auto scroll ke bawah
    }
    // Fungsi Mengirim Pesan Teks (User Mengetik)
    function sendTextMessage() {
        const input = document.getElementById('chat-input'); // Pastikan ID input sesuai
        const message = input.value;
        if (!message) return;

        // Tampilkan pesan user di layar
        addMessage(message, 'user');
        input.value = ''; // Kosongkan input

        // Kirim ke Server (Route chatbot.send)
        fetch('{{ route('chatbot.send') }}', { // <--- INI KUNCINYA
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(res => res.json())
        .then(data => {
            addMessage(data.reply, 'bot');
        })
        .catch(err => {
            console.error(err);
            addMessage('Maaf, bot sedang gangguan.', 'bot');
        });
    }

    // 1. CEK MOBIL (AJAX)
    function checkCars() {
        addMessage('Tolong cek mobil yang kosong dong...', 'user');
        addMessage('Sebentar, saya cek garasi dulu... 🔍');

        fetch('/bot/check-cars')
            .then(res => res.json())
            .then(data => {
                if(data.status === 'found') {
                    let html = `<p class="mb-2 font-bold text-green-600">Ada ${data.data.length} mobil ready nih:</p>`;
                    
                    data.data.forEach(mobil => {
                        // Format Rupiah
                        let harga = new Intl.NumberFormat('id-ID').format(mobil.harga);
                        html += `
                            <div class="border p-2 rounded mb-2 bg-gray-50 hover:bg-indigo-50 transition cursor-pointer" onclick="confirmBook(${mobil.id}, '${mobil.model}', '${harga}')">
                                <div class="font-bold text-indigo-700">${mobil.merk} ${mobil.model}</div>
                                <div class="text-xs text-gray-500">Rp ${harga} / hari</div>
                                <button class="mt-1 w-full bg-indigo-600 text-white text-[10px] py-1 rounded">Pilih Ini</button>
                            </div>
                        `;
                    });
                    
                    html += `<p class="text-[10px] text-gray-400 italic mt-1">*Klik mobil untuk booking cepat (1 hari, besok).</p>`;
                    addMessage(html);
                } else {
                    addMessage(data.message);
                }
            })
            .catch(err => addMessage('Maaf, ada gangguan koneksi. 😥'));
    }

    // 2. KONFIRMASI BOOKING
    function confirmBook(id, model, harga) {
        addMessage(`Saya mau booking <strong>${model}</strong>.`, 'user');
        
        // Simpan ID mobil di memory sementara (closure)
        window.selectedCarId = id;

        let html = `
            <div class="border-l-4 border-yellow-400 pl-2 bg-yellow-50 p-2 text-xs mb-2">
                <strong>Syarat & Ketentuan:</strong><br>
                1. Booking ini otomatis untuk <strong>BESOK</strong> (1 Hari).<br>
                2. Anda wajib upload KTP & Bukti Transfer setelah ini.<br>
                3. Pembatalan sepihak akan dikenakan denda.
            </div>
            <p class="mb-2 font-bold">Lanjutkan booking ${model}?</p>
            <div class="flex gap-2">
                <button onclick="processBooking()" class="bg-green-600 text-white px-3 py-1 rounded text-xs w-full">✅ Ya, Booking</button>
                <button onclick="addMessage('Oke, dibatalkan.', 'bot')" class="bg-gray-400 text-white px-3 py-1 rounded text-xs w-full">❌ Batal</button>
            </div>
        `;
        addMessage(html);
    }

    // 3. PROSES BOOKING (AJAX POST)
    function processBooking() {
        addMessage('Sedang memproses pesanan...', 'bot');

        fetch('{{ route('bot.book') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ mobil_id: window.selectedCarId })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                addMessage(`
                    <div class="text-green-600 font-bold text-lg">🎉 BERHASIL!</div>
                    <p class="text-xs mb-2">${data.message}</p>
                    <a href="${data.redirect_url}" class="block bg-indigo-600 text-white text-center py-2 rounded text-xs font-bold animate-pulse">
                        📄 Lihat Tiket & Bayar
                    </a>
                `);
            } else {
                addMessage(`<span class="text-red-500 font-bold">Gagal:</span> ${data.message}`);
            }
        })
        .catch(err => addMessage('Terjadi kesalahan sistem.'));
    }
</script>