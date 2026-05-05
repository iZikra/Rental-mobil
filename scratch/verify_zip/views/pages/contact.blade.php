<x-app-layout>
    {{-- ========================================================== --}}
    {{-- SOLUSI ANTI GESER (LAYOUT SHIFT FIX) --}}
    {{-- Memaksa track scrollbar selalu aktif agar lebar halaman konsisten --}}
    {{-- ========================================================== --}}
    <style>
        html { overflow-y: scroll; }
    </style>

<!--  -->

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                
                {{-- JUDUL --}}
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-serif font-bold text-gray-900 mb-4">HUBUNGI KAMI</h2>
                    <div class="w-20 h-1 bg-red-600 mx-auto"></div>
                </div>

                {{-- KONTEN GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    
                    {{-- KOLOM KIRI: INFO KONTAK --}}
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

                    {{-- KOLOM KANAN: FORM --}}
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
</x-app-layout>