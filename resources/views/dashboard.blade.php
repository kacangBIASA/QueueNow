<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard
            </h2>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('branches.index') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-800">
                    Kelola Cabang
                </a>

                <a href="{{ route('owner.queues.index') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-800">
                    Kelola Antrean
                </a>

                <a href="{{ route('queue.history') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-800">
                    Riwayat Antrean
                </a>

                @if(auth()->user()->subscription_type === 'pro')
                    <a href="{{ route('reports.index') }}"
                       class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        Laporan (Pro)
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Card -->
            <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400">Selamat datang,</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ auth()->user()->name }}
                </p>

                <div class="mt-4 grid md:grid-cols-3 gap-4">
                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nama Bisnis</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                            {{ auth()->user()->business_name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kategori</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                            {{ auth()->user()->business_category ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Subscription</p>

                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                            {{ strtoupper(auth()->user()->subscription_type ?? 'free') }}
                        </p>

                        @if(auth()->user()->subscription_type !== 'pro')
                            <a href="{{ route('pricing') }}"
                               class="inline-block mt-1 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                                Upgrade Pro →
                            </a>
                        @else
                            <p class="mt-1 text-sm text-emerald-600 font-semibold">
                                Akun Pro aktif ✅
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Scorecards -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Antrean Hari Ini</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $todayCount ?? 0 }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Antrean Bulan Ini</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $monthCount ?? 0 }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah Cabang</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $branches?->count() ?? 0 }}</p>
                    <a href="{{ route('branches.index') }}"
                       class="mt-2 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                        Kelola Cabang →
                    </a>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ strtoupper(auth()->user()->subscription_type ?? 'free') }}
                    </p>
                    @if(auth()->user()->subscription_type !== 'pro')
                        <a href="{{ route('pricing') }}"
                           class="mt-2 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                            Upgrade Pro →
                        </a>
                    @else
                        <span class="mt-2 inline-block text-sm font-semibold text-emerald-600">Pro aktif ✅</span>
                    @endif
                </div>
            </div>

            <!-- Chart (Pro only) -->
            @if(auth()->user()->subscription_type === 'pro')
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Grafik Antrean Harian (Bulan Ini)</h3>

                    <div class="mt-4 h-64">
                        <canvas id="queueDailyChart"></canvas>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const labels = @json($chartLabels ?? []);
                    const values = @json($chartValues ?? []);

                    new Chart(document.getElementById('queueDailyChart'), {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Total antrean',
                                data: values,
                                tension: 0.3,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true } }
                        }
                    });
                </script>
            @else
                <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 p-6 text-gray-600 dark:text-gray-400">
                    Grafik antrean hanya tersedia untuk akun <b>Pro</b>. <a class="font-semibold text-indigo-600 hover:text-indigo-700" href="{{ route('pricing') }}">Upgrade Pro →</a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
