<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Pusat Pengetahuan AI (Global)') }}
                </h2>
                <p class="text-sm text-slate-500 font-medium mt-1">Latih Asisten AI dengan dokumen referensi berskala platform.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm animate-pulse">
                <div class="flex">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3"><p class="text-sm text-emerald-700 font-semibold">{{ session('success') }}</p></div>
                </div>
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3"><p class="text-sm text-red-700 font-semibold">{{ session('error') }}</p></div>
                </div>
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3">
                        <ul class="list-disc list-inside text-sm text-red-700 font-semibold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- KIRI: FORM UPLOAD --}}
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 overflow-hidden relative group">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-white opacity-50 pointer-events-none transition duration-500 group-hover:opacity-100"></div>
                        <div class="relative z-10">
                            <h3 class="text-lg font-extrabold text-slate-800 mb-2">Upload Dokumen Baru</h3>
                            <p class="text-xs text-slate-500 mb-6 leading-relaxed">Sistem RAG (Retrieval-Augmented Generation) hanya menerima format <code class="bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-bold">.txt</code>. Pastikan informasi di dalamnya ringkas dan jelas.</p>
                            
                            <form action="{{ route('admin.rag.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                @csrf
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih File Teks</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="document" class="flex flex-col items-center justify-center w-full h-32 border-2 border-indigo-200 border-dashed rounded-2xl cursor-pointer bg-white hover:bg-indigo-50 hover:border-indigo-400 transition-all duration-300">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                <p class="mb-2 text-sm text-slate-500"><span class="font-bold text-indigo-600">Klik untuk upload</span> atau drag and drop</p>
                                                <p class="text-xs text-slate-400">TXT (Maks 2MB)</p>
                                            </div>
                                            <input id="document" type="file" name="document" accept=".txt" class="hidden" required />
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 hover:bg-indigo-600 text-white font-bold py-3 px-4 rounded-xl transition duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Mulai Sinkronisasi AI
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="bg-indigo-50 rounded-3xl p-6 border border-indigo-100">
                        <div class="flex items-start gap-3">
                            <div class="text-indigo-500 mt-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                            <div>
                                <h4 class="text-sm font-bold text-indigo-900">Apa itu Dokumen Global?</h4>
                                <p class="text-xs text-indigo-700 mt-1 leading-relaxed">
                                    Dokumen ini dibaca oleh AI untuk menjawab pertanyaan umum pelanggan di seluruh platform (misal: "Apa bedanya SUV dan MPV?"). Mitra akan mengatur syarat sewa & harga mereka secara terpisah.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KANAN: LIST FILE --}}
                <div class="md:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden h-full">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-800">Database Pengetahuan (Vector DB)</h3>
                                <p class="text-xs text-slate-500 mt-1">Dokumen yang telah tertanam dalam memori AI.</p>
                            </div>
                            <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">{{ count($files) }} Dokumen Aktif</span>
                        </div>
                        <div class="p-0">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-white text-slate-400 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-4">Nama Referensi</th>
                                        <th class="px-6 py-4">Ukuran</th>
                                        <th class="px-6 py-4">Update Terakhir</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($files as $file)
                                    <tr class="hover:bg-slate-50 transition duration-150 group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-slate-100 rounded-lg text-slate-500 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                                <span class="font-semibold text-slate-700">{{ $file['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 font-medium">{{ $file['size'] }}</td>
                                        <td class="px-6 py-4 text-slate-500 text-xs">{{ \Carbon\Carbon::parse($file['modified'])->diffForHumans() }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('admin.rag.destroy', $file['name']) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini? AI akan melupakannya secara permanen.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition text-xs font-bold">HAPUS</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                <p class="font-medium text-sm">Tidak ada dokumen pengetahuan.</p>
                                                <p class="text-xs mt-1">Silakan upload file .txt pertama Anda.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
