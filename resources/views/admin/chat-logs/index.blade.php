<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Chat Logs AI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Daftar Interaksi Chatbot RAG</h3>
                    <p class="text-sm text-gray-600 mb-6">Halaman ini digunakan untuk mengaudit dan memantau respons AI, kecepatan respons (latency), dan referensi dokumen pengetahuan (context sources) yang digunakan.</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 uppercase">
                                <tr>
                                    <th class="px-6 py-3">Waktu</th>
                                    <th class="px-6 py-3">User/Sesi</th>
                                    <th class="px-6 py-3">Pertanyaan</th>
                                    <th class="px-6 py-3">Respons AI</th>
                                    <th class="px-6 py-3 text-center">Latency (ms)</th>
                                    <th class="px-6 py-3">Sumber Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $log->user ? $log->user->name : 'Guest' }}<br>
                                        <span class="text-xs text-gray-400" title="{{ $log->session_id }}">ID: {{ substr($log->session_id, 0, 8) }}...</span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs truncate" title="{{ $log->message }}">{{ $log->message }}</td>
                                    <td class="px-6 py-4 max-w-xs truncate" title="{{ strip_tags($log->response) }}">{!! Str::limit(strip_tags($log->response), 50) !!}</td>
                                    <td class="px-6 py-4 text-center font-bold {{ $log->latency < 2000 ? 'text-green-600' : ($log->latency < 5000 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $log->latency ? number_format($log->latency, 0) . ' ms' : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $sources = json_decode($log->context_sources, true) ?? [];
                                        @endphp
                                        @if(count($sources) > 0)
                                            <ul class="list-disc list-inside text-xs text-blue-600">
                                                @foreach($sources as $source)
                                                    <li>{{ $source }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-xs text-gray-400">Tidak ada/MySQL murni</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data interaksi chat.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
