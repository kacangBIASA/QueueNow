<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl">Kelola Antrean</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $today }}</p>
            </div>
            @if($activeBranch)
                <div class="text-right">
                    {!! QrCode::size(96)->margin(1)->generate(route('public.queue.show', $activeBranch->public_code)) !!}
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">QR Halaman Publik</p>
                </div>
            @endif
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 text-green-700 p-3 dark:bg-green-500/10 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(!$activeBranch)
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-gray-600 dark:text-gray-300">
                Belum ada cabang. Minimal kamu butuh 1 cabang untuk menjalankan antrean.
            </p>
        </div>
    @else
        <div class="grid md:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Cabang Aktif</p>
                <p class="mt-1 font-semibold">{{ $activeBranch->name }}</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Sedang dipanggil</p>
                <p class="mt-1 text-3xl font-bold text-indigo-600 dark:text-indigo-300">
                    {{ $data['called']?->number ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Hari Ini</p>
                <p class="mt-1 text-3xl font-bold">{{ $data['totalToday'] }}</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Selesai</p>
                <p class="mt-1 text-3xl font-bold">{{ $data['finishedCount'] }}</p>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <form method="POST" action="{{ route('owner.queues.callNext', $activeBranch->id) }}">
                @csrf
                <button class="rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                    Panggil Berikutnya
                </button>
            </form>

            <form method="POST" action="{{ route('owner.queues.resetDaily', $activeBranch->id) }}"
                  onsubmit="return confirm('Reset antrean hari ini? Ini akan menghapus antrean hari ini.')">
                @csrf
                <button class="rounded-md border border-red-300 px-5 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50
                               dark:border-red-700 dark:text-red-300 dark:hover:bg-red-500/10">
                    Reset Harian
                </button>
            </form>
        </div>

        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">Daftar Menunggu</h3>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $data['waiting']->count() }} antrean
                </span>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="py-2">Nomor</th>
                            <th class="py-2">Sumber</th>
                            <th class="py-2">Diambil</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($data['waiting'] as $q)
                            <tr>
                                <td class="py-2 font-semibold">{{ $q->number }}</td>
                                <td class="py-2">{{ $q->source }}</td>
                                <td class="py-2">{{ optional($q->taken_at)->format('H:i') }}</td>
                                <td class="py-2 flex flex-wrap gap-2">
                                    <form method="POST" action="{{ route('owner.queues.skip', [$activeBranch->id, $q->id]) }}">
                                        @csrf
                                        <button class="rounded-md border border-gray-300 px-3 py-1.5 text-xs font-semibold hover:bg-gray-50
                                                       dark:border-gray-700 dark:hover:bg-gray-800">
                                            Skip
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('owner.queues.finish', [$activeBranch->id, $q->id]) }}">
                                        @csrf
                                        <button class="rounded-md bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-black
                                                       dark:bg-gray-800 dark:hover:bg-gray-700">
                                            Selesai
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada antrean menunggu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
