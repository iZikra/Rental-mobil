<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-black text-rose-600">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-2 text-sm text-slate-500 font-medium leading-relaxed">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    <button type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-8 py-3 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs rounded-xl shadow-lg shadow-rose-600/20 transition-all uppercase tracking-widest"
    >{{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                {{ __('Apakah Anda yakin ingin menghapus akun?') }}
            </h2>

            <p class="mt-4 text-sm text-slate-500 font-medium leading-relaxed">
                {{ __('Tindakan ini tidak dapat dibatalkan. Harap masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
            </p>

            <div class="mt-8 space-y-2">
                <x-input-label for="password" value="{{ __('Password') }}" class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1" />

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-rose-600 transition-colors">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-bold outline-none transition-all duration-300 focus:bg-white focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10"
                        placeholder="{{ __('Masukkan Kata Sandi Anda') }}"
                    />
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs rounded-xl transition-all uppercase tracking-widest">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="px-8 py-3 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs rounded-xl shadow-lg shadow-rose-600/20 transition-all uppercase tracking-widest">
                    {{ __('Confirm Delete') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
