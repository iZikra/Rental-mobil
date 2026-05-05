<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap');
        
        .font-outfit { font-family: 'Outfit', sans-serif; }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .profile-hero-gradient {
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.15), transparent),
                        radial-gradient(circle at bottom left, rgba(99, 102, 241, 0.15), transparent);
        }

        .form-input-focus {
            transition: all 0.3s ease;
        }
        .form-input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.1);
        }
    </style>

    <div class="min-h-screen bg-[#f8fafc] font-outfit pb-20">
        {{-- Hero Header --}}
        <div class="relative bg-slate-900 pt-32 pb-48 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] bg-blue-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute -bottom-[30%] -left-[10%] w-[70%] h-[70%] bg-indigo-600/10 rounded-full blur-[120px]"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                        <div class="relative w-32 h-32 md:w-40 md:h-40 bg-white rounded-full flex items-center justify-center text-4xl md:text-5xl font-black text-blue-600 shadow-2xl border-4 border-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                            Account Settings
                        </div>
                        <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-2">
                            {{ Auth::user()->name }}
                        </h1>
                        <p class="text-slate-400 font-medium text-lg">
                            {{ Auth::user()->email }} • <span class="text-blue-400 uppercase tracking-widest text-sm">{{ Auth::user()->role }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-24 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Navigation / Info Sidebar --}}
                <div class="lg:col-span-4 space-y-6">
                    <div class="glass-card p-8 rounded-[2.5rem] shadow-xl">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Quick Navigation</h3>
                        <nav class="space-y-2">
                            <a href="#personal-info" class="flex items-center gap-4 p-4 bg-blue-50 text-blue-600 rounded-2xl font-bold transition-all border border-blue-100">
                                <i class="fa-solid fa-user-gear"></i> Personal Info
                            </a>
                            <a href="#security" class="flex items-center gap-4 p-4 text-slate-600 hover:bg-slate-50 rounded-2xl font-bold transition-all">
                                <i class="fa-solid fa-shield-halved"></i> Password & Security
                            </a>
                            <a href="#danger-zone" class="flex items-center gap-4 p-4 text-rose-600 hover:bg-rose-50 rounded-2xl font-bold transition-all">
                                <i class="fa-solid fa-triangle-exclamation"></i> Danger Zone
                            </a>
                        </nav>
                    </div>

                    <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/20 rounded-full blur-2xl"></div>
                        <h4 class="font-black text-lg mb-4 relative z-10">Need Help?</h4>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6 relative z-10">
                            Jika Anda mengalami kesulitan saat memperbarui data profil, tim dukungan kami siap membantu Anda 24/7.
                        </p>
                        <a href="{{ route('pages.contact') }}" class="inline-flex items-center gap-2 text-blue-400 font-bold text-sm hover:text-white transition-colors">
                            Contact Support <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Forms Container --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- Personal Information --}}
                    <div id="personal-info" class="glass-card p-8 md:p-12 rounded-[3rem] shadow-xl">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    {{-- Security --}}
                    <div id="security" class="glass-card p-8 md:p-12 rounded-[3rem] shadow-xl">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    {{-- Danger Zone --}}
                    <div id="danger-zone" class="bg-white border-2 border-rose-100 p-8 md:p-12 rounded-[3rem] shadow-xl">
                        <div class="max-w-2xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
