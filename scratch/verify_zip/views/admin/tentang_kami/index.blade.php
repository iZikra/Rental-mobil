<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Halaman Tentang Kami') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 text-right">
                <a href="{{ route('admin.tentang_kami.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Tambah Seksi Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Judul Seksi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Isi Konten</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($data as $item)
                            <tr>
                                <td class="px-6 py-4">{{ $item->judul }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($item->isi, 50) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.tentang_kami.edit', $item->id) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                                    <form action="{{ route('admin.tentang_kami.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>