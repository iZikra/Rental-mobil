<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 text-slate-800">Daftar Mitra (Tenant)</h2>
            
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b">
                        <tr>
                            <th class="px-6 py-4 font-bold text-sm">Nama Rental</th>
                            <th class="px-6 py-4 font-bold text-sm">Pemilik</th>
                            <th class="px-6 py-4 font-bold text-sm">Status</th>
                            <th class="px-6 py-4 font-bold text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rentals as $rental)
                        <tr class="border-b">
                            <td class="px-6 py-4 font-semibold">{{ $rental->nama_rental }}</td>
                            <td class="px-6 py-4">{{ $rental->user->name }}</td>
                            <td class="px-6 py-4">
                                @if($rental->status == 'active')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($rental->status == 'pending')
                                <form action="{{ route('admin.rentals.approve', $rental->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="bg-indigo-600 text-white px-4 py-1 rounded-lg text-xs font-bold hover:bg-indigo-700">Approve</button>
                                </form>
                                @endif
                                <form action="{{ route('admin.rentals.block', $rental->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="text-rose-600 ml-3 font-bold text-xs">Block</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>