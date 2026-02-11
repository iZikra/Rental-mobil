<div x-data="chatbotComponent()" x-init="initBot()" x-cloak class="fixed bottom-6 right-6 z-50 flex flex-col items-end font-sans print:hidden">

    
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95 translate-y-10"
         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 transform scale-95 translate-y-10"
         class="bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl w-[350px] h-[550px] flex flex-col overflow-hidden border border-white/20 ring-1 ring-black/5 mb-4 origin-bottom-right font-sans">
        
        
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-4 flex justify-between items-center shadow-md z-10">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-indigo-600 text-xl shadow-inner">🤖</div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-indigo-600 rounded-full animate-pulse"></span>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base tracking-wide">FZ Assistant</h3>
                    <p class="text-[10px] text-blue-100 font-medium bg-blue-500/30 px-2 py-0.5 rounded-full inline-block">Online 24 Jam</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button @click="resetChat" class="text-blue-200 hover:text-white transition p-1 rounded-full hover:bg-white/10" title="Hapus Chat">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                <button @click="toggleChat" class="text-white hover:text-red-200 transition p-1 rounded-full hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-4 scroll-smooth bg-slate-50 relative">
            <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(#4F46E5 1px, transparent 1px); background-size: 20px 20px;"></div>

            <template x-for="msg in messages" :key="msg.id">
                <div class="flex flex-col animate-slide-up">
                    
                    <div x-show="msg.sender === 'bot'" class="flex gap-3 max-w-[90%] mb-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-white flex-shrink-0 flex items-center justify-center text-sm border border-indigo-50 shadow-sm">🤖</div>
                        <div class="bg-white p-3.5 rounded-2xl rounded-tl-none shadow-sm text-gray-700 text-sm border border-gray-100 leading-relaxed" x-html="msg.text"></div>
                    </div>
                    
                    <div x-show="msg.sender === 'user'" class="flex justify-end mb-3">
                        <div class="bg-gradient-to-br from-indigo-600 to-blue-600 text-white p-3 px-4 rounded-2xl rounded-tr-none shadow-md text-sm max-w-[85%] leading-relaxed" x-text="msg.text"></div>
                    </div>
                </div>
            </template>
            
            
            <div x-show="isLoading" class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-gray-100"></div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 flex gap-1 items-center">
                    <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce delay-100"></div>
                    <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce delay-200"></div>
                </div>
            </div>
        </div>

        
        <div class="p-4 bg-white border-t border-gray-100 z-10">
            <form @submit.prevent="sendMessage" class="relative flex items-center gap-2">
                <input x-model="userInput" 
                       type="text" 
                       class="w-full bg-gray-50 text-sm border-0 ring-1 ring-gray-200 rounded-full pl-4 pr-12 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition shadow-inner" 
                       placeholder="Ketik pesan (e.g. Avanza ada?)..." 
                       autocomplete="off">
                <button type="submit" 
                        class="absolute right-2 p-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition shadow-md disabled:opacity-50" 
                        :disabled="isLoading || !userInput.trim()">
                    <svg class="w-4 h-4 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
            
            <div class="mt-3 flex gap-2 justify-center">
                <button @click="checkCars" class="text-[11px] bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-full hover:bg-indigo-100 transition border border-indigo-100 font-medium">🚗 Cek Stok</button>
                <button @click="userInput = 'Apa saja syarat sewanya?'; sendMessage()" class="text-[11px] bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-full hover:bg-indigo-100 transition border border-indigo-100 font-medium">📋 Syarat Sewa</button>
            </div>
        </div>
    </div>

    
    <button @click="toggleChat" 
            class="group relative bg-gradient-to-br from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white w-14 h-14 rounded-full shadow-2xl transition-all duration-300 hover:scale-110 flex items-center justify-center focus:outline-none ring-4 ring-white/50">
        <span class="absolute -top-1 -right-1 flex h-4 w-4 z-10">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-white"></span>
        </span>
        <svg x-show="!isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        <svg x-show="isOpen" class="w-7 h-7 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>

<style>
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slideUp 0.3s ease-out forwards; }
    #chat-messages::-webkit-scrollbar { width: 5px; }
    #chat-messages::-webkit-scrollbar-track { background: transparent; }
    #chat-messages::-webkit-scrollbar-thumb { background: #e0e7ff; border-radius: 10px; }
    [x-cloak] { display: none !important; }
</style>

<script>
    function chatbotComponent() {
        return {
            isOpen: false,
            isLoading: false,
            userInput: '',
            messages: [],
            storageKey: 'fz_chat_history_v10_redirect', 

            initBot() {
                const saved = localStorage.getItem(this.storageKey);
                if (saved) { this.messages = JSON.parse(saved); } 
                else { this.addMessage("Halo <strong><?php echo e(Auth::user()->name ?? 'Kak'); ?></strong>! 👋<br>Saya Asisten FZ Rent. Ada yang bisa saya bantu?", 'bot'); }
                this.$nextTick(() => this.scrollToBottom());
            },

            toggleChat() {
                this.isOpen = !this.isOpen;
                if(this.isOpen) this.$nextTick(() => this.scrollToBottom());
            },

            addMessage(text, sender) {
                this.messages.push({ id: Date.now() + Math.random(), text: text, sender: sender });
                localStorage.setItem(this.storageKey, JSON.stringify(this.messages));
                this.scrollToBottom();
            },

            resetChat() {
                if(confirm('Hapus chat?')) {
                    this.messages = [];
                    localStorage.removeItem(this.storageKey);
                    this.addMessage("✨ Chat bersih.", 'bot');
                }
            },

            scrollToBottom() {
                setTimeout(() => {
                    const c = document.getElementById('chat-messages');
                    if (c) c.scrollTop = c.scrollHeight;
                }, 100);
            },

            sendMessage() {
                if (!this.userInput.trim()) return;
                const text = this.userInput;
                this.addMessage(text, 'user');
                this.userInput = '';

                this.isLoading = true;
                fetch("<?php echo e(route('chatbot.send')); ?>", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>" },
                    body: JSON.stringify({ message: text })
                })
                .then(res => res.json())
                .then(data => {
                    let reply = data.reply;
                    
                    // --- LOGIKA OTOMATIS: JIKA ADA #SHOW_CARS ---
                    if (reply.includes('#SHOW_CARS')) {
                        reply = reply.replace('#SHOW_CARS', '');
                        this.addMessage(reply, 'bot');
                        setTimeout(() => { this.checkCars(); }, 600);
                    } else {
                        this.addMessage(reply, 'bot');
                    }
                })
                .catch(() => this.addMessage('⚠️ Koneksi gangguan.', 'bot'))
                .finally(() => this.isLoading = false);
            },

            checkCars() {
                this.addMessage('Sebentar, saya cek garasi... 🔍', 'bot');
                this.isLoading = true;

                fetch("<?php echo e(url('/bot/check-cars')); ?>")
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'found') {
                        let html = `<p class="mb-3 font-bold text-gray-700 text-xs uppercase tracking-wider border-b pb-1">🚙 Armada Ready Hari Ini</p>`;
                        
                        data.data.forEach(m => {
                            let harga = new Intl.NumberFormat('id-ID').format(m.harga_sewa || m.harga || 0);
                            
                            // === KUNCI SOLUSI: LANGSUNG GENERATE URL DISINI ===
                            // Ganti 'user.transaksi.create' sesuai nama route Anda di web.php
                            // Jika route Anda adalah /booking, maka gunakan url('/booking')
                            let bookingLink = "<?php echo e(route('user.transaksi.create')); ?>" + "?mobil_id=" + m.id;

                            html += `
                                <div class="relative bg-white border border-gray-100 rounded-xl p-3 mb-3 shadow-sm hover:shadow-md transition-all group overflow-hidden">
                                    <div class="absolute -right-3 -top-3 w-12 h-12 bg-green-100 rounded-full opacity-50 z-0"></div>
                                    <div class="relative z-10">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">${m.merk}</div>
                                                <div class="font-extrabold text-indigo-800 text-sm leading-tight">${m.model}</div>
                                            </div>
                                            <span class="bg-green-100 text-green-700 text-[9px] font-bold px-2 py-0.5 rounded-full border border-green-200">READY</span>
                                        </div>
                                        <div class="mt-2 text-xs font-medium text-gray-500">
                                            Rp <span class="text-indigo-600 font-bold text-sm">${harga}</span> / hari
                                        </div>
                                        
                                        
                                        <button onclick="window.location.href='${bookingLink}'" 
                                                class="mt-3 w-full bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white text-xs font-bold py-2 rounded-lg transition shadow-md flex items-center justify-center gap-1">
                                            <span>📅</span> Booking Sekarang
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                        this.addMessage(html, 'bot');
                    } else {
                        this.addMessage(`
                            <div class="bg-red-50 border border-red-100 rounded-xl p-3 text-red-700 text-xs flex gap-2 items-center">
                                <span class="text-lg">😢</span>
                                <span>${data.message}</span>
                            </div>
                        `, 'bot');
                    }
                })
                .catch(err => {
                    this.addMessage('Gagal mengambil data mobil.', 'bot');
                })
                .finally(() => {
                    this.isLoading = false;
                });
            }
        }
    }
</script><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\components\chatbot.blade.php ENDPATH**/ ?>