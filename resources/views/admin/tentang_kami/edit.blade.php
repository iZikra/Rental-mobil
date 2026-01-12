<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-lg font-bold mb-4">Edit Konten</h2>
                <form action="{{ route('admin.tentang_kami.update', $item->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700">Judul Seksi</label>
                        <input type="text" name="judul" value="{{ $item->judul }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Isi Konten</label>
                        <textarea name="isi" rows="5" class="w-full border-gray-300 rounded-md shadow-sm" required>{{ $item->isi }}</textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>