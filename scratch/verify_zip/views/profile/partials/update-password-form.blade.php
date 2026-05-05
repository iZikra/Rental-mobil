<section>
    <header class="mb-8">
        <h2 class="text-2xl font-black text-slate-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-2 text-sm text-slate-500 font-medium">
            {{ __('Gunakan kata sandi yang panjang dan acak untuk menjaga keamanan akun Anda.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fa-solid fa-lock-open"></i>
                </div>
                <input id="update_password_current_password" name="current_password" type="password" 
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-bold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" 
                    autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <i class="fa-solid fa-key"></i>
                </div>
                <input id="update_password_password" name="password" type="password" 
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-bold outline-none transition-all duration-300 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10" 
                    autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-bold outline-none transition-all duration-300 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10" 
                    autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-8 py-3 bg-slate-900 hover:bg-blue-600 text-white font-black text-xs rounded-xl shadow-lg transition-all uppercase tracking-widest">
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600"
                ><i class="fa-solid fa-circle-check mr-1"></i> {{ __('Password updated.') }}</p>
            @endif
        </div>
    </form>
</section>
