<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Antrean Publik</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $branch->nama_cabang }} • {{ $today }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $branch->nama_cabang }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Scan untuk ambil antrean</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-2 dark:border-gray-800 dark:bg-gray-900">
                    {!! QrCode::size(96)->margin(1)->generate($publicUrl) !!}
                </div>
            </div>
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

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">Sedang dipanggil</p>
            <p class="mt-1 text-4xl font-bold text-indigo-600 dark:text-indigo-300">
                {{ $currentlyCalled?->number ?? '-' }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">Menunggu</p>
            <p class="mt-1 text-4xl font-bold text-gray-900 dark:text-white">{{ $waitingCount }}</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs text-gray-500 dark:text-gray-400">Nomor terakhir diambil</p>
            <p class="mt-1 text-4xl font-bold text-gray-900 dark:text-white">{{ $lastTaken ?? '-' }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-300">Nomor antrean kamu</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $myNumber ?? '-' }}</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('public.queue.take', ['public_code' => $branch->public_code]) }}">
                    @csrf
                    <input type="hidden" name="source" value="online">
                    <button class="rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        Ambil Antrean Online
                    </button>
                </form>

                <form method="POST" action="{{ route('public.queue.take', ['public_code' => $branch->public_code]) }}">
                    @csrf
                    <input type="hidden" name="source" value="onsite">
                    <button class="rounded-md border border-gray-300 px-5 py-2.5 text-sm font-semibold hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                        Ambil Antrean di Tempat (QR)
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
            Catatan: nomor antrean otomatis naik per hari untuk cabang ini.
        </p>
    </div>
</x-app-layout>
