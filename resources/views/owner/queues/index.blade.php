<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Kelola Antrean</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $today }}</p>
            </div>

            @if($activeBranch)
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $activeBranch->nama_cabang }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">QR Halaman Publik</p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-2 dark:border-gray-800 dark:bg-gray-900">
                        @if(!empty($activeBranch->public_code))
                            {!! QrCode::size(96)->margin(1)->generate(route('public.queue.show', ['public_code' => $activeBranch->public_code])) !!}
                        @else
                            <div class="h-[96px] w-[96px] grid place-items-center text-center text-[10px] text-gray-500 dark:text-gray-400">
                                public_code<br>belum ada
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 text-green-700 p-3 dark:bg-green-500/10 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 text-red-700 p-3 dark:bg-red-500/10 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if(!$activeBranch)
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-gray-600 dark:text-gray-300">
                Belum ada cabang. Minimal kamu butuh 1 cabang untuk menjalankan antrean.
            </p>
            <div class="mt-4">
                <a href="{{ route('branches.create') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Tambah Cabang
                </a>
            </div>
        </div>
    @else
        {{-- Scorecards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Cabang Aktif</p>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $activeBranch->nama_cabang }}</p>
                @if(!empty($activeBranch->public_code))
                    <a class="mt-2 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                       href="{{ route('public.queue.show', ['public_code' => $activeBranch->public_code]) }}"
                       target="_blank" rel="noopener">
                        Buka Halaman Publik →
                    </a>
                @endif
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Sedang dipanggil</p>
                <p class="mt-1 text-3xl font-bold text-indigo-600 dark:text-indigo-300">
                    {{ $data['called']?->number ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Hari Ini</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $data['totalToday'] }}</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Selesai</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $data['finishedCount'] }}</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex flex-wrap gap-3">
            <form method="POST" action="{{ route('owner.queues.callNext', ['branch' => $activeBranch->id]) }}">
                @csrf
                <button class="rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                    Panggil Berikutnya
                </button>
            </form>

            <form method="POST" action="{{ route('owner.queues.resetDaily', ['branch' => $activeBranch->id]) }}"
                  onsubmit="return confirm('Reset antrean hari ini? Ini akan menghapus antrean hari ini.')">
                @csrf
                <button class="rounded-md border border-red-300 px-5 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50
                               dark:border-red-700 dark:text-red-300 dark:hover:bg-red-500/10">
                    Reset Harian
                </button>
            </form>
        </div>

        {{-- Waiting list --}}
        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Menunggu</h3>
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
                                <td class="py-2 font-semibold text-gray-900 dark:text-white">{{ $q->number }}</td>
                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ strtoupper($q->source) }}</td>
                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ optional($q->taken_at)->format('H:i') ?? '-' }}</td>
                                <td class="py-2">
                                    <div class="flex flex-wrap gap-2">
                                        <form method="POST" action="{{ route('owner.queues.skip', ['branch' => $activeBranch->id, 'queue' => $q->id]) }}">
                                            @csrf
                                            <button class="rounded-md border border-gray-300 px-3 py-1.5 text-xs font-semibold hover:bg-gray-50
                                                           dark:border-gray-700 dark:hover:bg-gray-800">
                                                Skip
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('owner.queues.finish', ['branch' => $activeBranch->id, 'queue' => $q->id]) }}">
                                            @csrf
                                            <button class="rounded-md bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-black
                                                           dark:bg-gray-800 dark:hover:bg-gray-700">
                                                Selesai
                                            </button>
                                        </form>
                                    </div>
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
