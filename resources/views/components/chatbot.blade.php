<div x-data="chatbotComponent()" x-init="initBot()" x-cloak class="fixed bottom-6 right-6 z-50 flex flex-col items-end font-sans print:hidden">

    {{-- 1. JENDELA CHAT --}}
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90 translate-y-10"
         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 transform scale-90 translate-y-10"
         class="bg-white/90 backdrop-blur-2xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.25)] rounded-[2rem] w-[380px] h-[600px] flex flex-col overflow-hidden border border-white/40 ring-1 ring-black/5 mb-6 origin-bottom-right font-sans">
        
        {{-- HEADER --}}
        <div class="bg-white/50 backdrop-blur-xl border-b border-white/50 p-5 flex justify-between items-center z-10 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-500/30">🤖</div>
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 border-[3px] border-white rounded-full animate-pulse shadow-sm"></span>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 text-lg tracking-tight">Chatbot Cerdas</h3>
                    <p class="text-[11px] text-indigo-600 font-bold bg-indigo-50 px-2.5 py-0.5 rounded-full inline-flex items-center gap-1 mt-0.5 border border-indigo-100">
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span> Online
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <button @click="resetChat" class="text-slate-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50" title="Hapus Chat">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                <button @click="toggleChat" class="text-slate-400 hover:text-slate-800 transition-colors p-2 rounded-full hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        {{-- BODY --}}
        <div id="chat-messages" class="flex-1 p-5 overflow-y-auto space-y-5 scroll-smooth bg-slate-50/50 relative">
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#4F46E5 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>

            <template x-for="msg in messages" :key="msg.id">
                <div class="flex flex-col animate-slide-up relative z-10">
                    {{-- Pesan Bot --}}
                    <div x-show="msg.sender === 'bot'" class="flex gap-3 max-w-[90%] mb-4">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 flex-shrink-0 flex items-center justify-center text-white text-xs shadow-md shadow-indigo-500/20">🤖</div>
                        <div class="flex flex-col gap-1.5">
                            <div class="bg-white p-4 rounded-3xl rounded-tl-sm shadow-sm text-slate-700 text-sm border border-white/60 leading-relaxed font-medium" x-html="msg.text"></div>
                            <span style="font-size: 10px; color: #94a3b8; margin-left: 6px; font-weight: 600;" x-text="msg.time || 'Baru saja'"></span>
                        </div>
                    </div>
                    {{-- Pesan User --}}
                    <div x-show="msg.sender === 'user'" class="flex flex-col items-end mb-4">
                        <div class="bg-indigo-600 text-white p-4 px-5 rounded-3xl rounded-tr-sm shadow-md shadow-indigo-500/20 text-sm max-w-[85%] leading-relaxed font-medium" x-text="msg.text"></div>
                        <span style="font-size: 10px; color: #94a3b8; margin-right: 6px; margin-top: 4px; font-weight: 600;" x-text="msg.time || 'Baru saja'"></span>
                    </div>
                </div>
            </template>
            
            {{-- Loading --}}
            <div x-show="isLoading" class="flex gap-3 relative z-10">
                <div class="w-8 h-8 rounded-full bg-slate-200 animate-pulse"></div>
                <div class="bg-white p-4 rounded-3xl rounded-tl-sm shadow-sm border border-white/60 flex gap-1.5 items-center">
                    <div class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-indigo-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>

        {{-- INPUT --}}
        <div class="p-4 bg-white/80 backdrop-blur-xl border-t border-white/50 z-10">
            <form @submit.prevent="sendMessage" class="relative flex items-center gap-2">
                <input x-model="userInput" 
                       type="text" 
                       class="w-full bg-slate-100/80 text-sm border-0 focus:ring-2 focus:ring-indigo-500 focus:bg-white rounded-full pl-5 pr-14 py-4 shadow-inner placeholder-slate-400 font-medium transition-all" 
                       placeholder="Ketik pesan Anda di sini..." 
                       autocomplete="off">
                <button type="submit" 
                        class="absolute right-2 p-3 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 hover:scale-105 active:scale-95 transition-all shadow-md shadow-indigo-500/30 disabled:opacity-50 disabled:hover:scale-100" 
                        :disabled="isLoading || !userInput.trim()">
                    <svg class="w-4 h-4 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- FLOATING BUTTON --}}
    <button @click="toggleChat" 
            class="group relative bg-indigo-600 hover:bg-indigo-500 text-white w-16 h-16 rounded-[2rem] rounded-br-xl shadow-[0_0_30px_rgba(79,70,229,0.5)] transition-all duration-300 hover:-translate-y-1 hover:scale-105 flex items-center justify-center focus:outline-none ring-4 ring-indigo-500/20">
        <span class="absolute -top-1 -right-1 flex h-4 w-4 z-10">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-white"></span>
        </span>
        <svg x-show="!isOpen" class="w-8 h-8 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        <svg x-show="isOpen" class="w-8 h-8 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
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
            storageKey: 'fz_chat_history_v10_redirect_{{ Auth::check() ? Auth::id() : "guest" }}', 

            initBot() {
                const saved = localStorage.getItem(this.storageKey);
                if (saved) { 
                    this.messages = JSON.parse(saved); 
                } 
                else { 
                    this.addMessage("Halo 👋<br>Ada yang bisa saya bantu?", 'bot'); 
                }
                this.$nextTick(() => this.scrollToBottom());
            },

            toggleChat() {
                this.isOpen = !this.isOpen;
                if(this.isOpen) this.$nextTick(() => this.scrollToBottom());
            },

            addMessage(text, sender) {
                const now = new Date();
                const d = now.getDate().toString().padStart(2, '0');
                const m = (now.getMonth() + 1).toString().padStart(2, '0');
                const y = now.getFullYear();
                const dateStr = `${d}/${m}/${y}`;
                const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                
                const timeStamp = `${dateStr}, ${timeStr}`;
                
                this.messages.push({ 
                    id: Date.now() + Math.random(), 
                    text: text, 
                    sender: sender,
                    time: timeStamp 
                });
                localStorage.setItem(this.storageKey, JSON.stringify(this.messages));
                this.$nextTick(() => this.scrollToBottom());
            },

            resetChat() {
                if(confirm('Hapus chat?')) {
                    this.messages = [];
                    localStorage.removeItem(this.storageKey);
                    
                    // Clear backend session history
                    fetch("{{ route('chatbot.clear_history') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
                    });

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
                fetch("{{ route('chatbot.send') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: JSON.stringify({ message: text })
                })
                .then(async res => {
                    if (!res.ok) {
                        const errText = await res.text();
                        console.error("HTTP Error:", res.status, errText);
                        throw new Error("HTTP " + res.status);
                    }
                    return res.json();
                })
                .then(data => {
                    let reply = data.reply;
                    
                    // --- LOGIKA OTOMATIS: JIKA ADA #SHOW_CARS ---
                    if (reply && reply.includes('#SHOW_CARS')) {
                        reply = reply.replace('#SHOW_CARS', '');
                        this.addMessage(reply, 'bot');
                        setTimeout(() => { this.checkCars(); }, 600);
                    } else {
                        this.addMessage(reply, 'bot');
                    }
                })
                .catch(err => {
                    console.error("Fetch Catch:", err);
                    this.addMessage('⚠️ Koneksi gangguan. Cek Console.', 'bot');
                })
                .finally(() => this.isLoading = false);
            },

            checkCars() {
                this.addMessage('Sebentar, saya cek garasi... 🔍', 'bot');
                this.isLoading = true;

                fetch("{{ url('/bot/check-cars') }}")
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'found') {
                        let html = `<p class="mb-3 font-bold text-gray-700 text-xs uppercase tracking-wider border-b pb-1">🚙 Armada Ready Hari Ini</p>`;
                        
                        data.data.forEach(m => {
                            let harga = new Intl.NumberFormat('id-ID').format(m.harga_sewa || m.harga || 0);
                            
                            // === KUNCI SOLUSI: LANGSUNG GENERATE URL DISINI ===
                            // Ganti 'user.transaksi.create' sesuai nama route Anda di web.php
                            // Jika route Anda adalah /booking, maka gunakan url('/booking')
                            let bookingLink = "{{ route('user.transaksi.create') }}" + "?mobil_id=" + m.id;

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
                                        
                                        {{-- TOMBOL LANGSUNG REDIRECT (ANTI GAGAL) --}}
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
</script>