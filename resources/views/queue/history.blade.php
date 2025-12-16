<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Antrean
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- FILTER --}}
            <div class="bg-white p-4 rounded shadow mb-6">
                <form method="GET" action="{{ route('queue.history') }}" class="flex flex-wrap gap-4">

                    {{-- Filter per hari --}}
                    <div>
                        <label class="block text-sm font-medium">Tanggal</label>
                        <input
                            type="date"
                            name="date"
                            value="{{ request('date') }}"
                            class="border rounded px-3 py-2"
                        >
                    </div>

                    {{-- Filter per bulan --}}
                    <div>
                        <label class="block text-sm font-medium">Bulan</label>
                        <input
                            type="month"
                            name="month"
                            value="{{ request('month') }}"
                            class="border rounded px-3 py-2"
                        >
                    </div>

                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Filter
                        </button>

                        <a href="{{ route('queue.history') }}"
                           class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                            Reset
                        </a>
                    </div>

                </form>
            </div>

            {{-- TABLE --}}
            <div class="bg-white shadow rounded overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 border">No</th>
                            <th class="px-4 py-3 border">Nomor Antrean</th>
                            <th class="px-4 py-3 border">Waktu Diambil</th>
                            <th class="px-4 py-3 border">Waktu Dipanggil</th>
                            <th class="px-4 py-3 border">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($queues as $index => $queue)
                            <tr class="text-center">
                                <td class="px-4 py-2 border">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-4 py-2 border font-semibold">
                                    {{ $queue->queue_number }}
                                </td>
                                <td class="px-4 py-2 border">
                                    {{ $queue->taken_at
                                        ? $queue->taken_at->format('d-m-Y H:i')
                                        : '-' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    {{ $queue->called_at
                                        ? $queue->called_at->format('d-m-Y H:i')
                                        : '-' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    @if ($queue->status === 'done')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded">
                                            Selesai
                                        </span>
                                    @elseif ($queue->status === 'called')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded">
                                            Dipanggil
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Tidak ada data antrean
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
