<x-app-layout>
    <div class="p-6">

        {{-- STAT CARD --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            <div class="bg-white p-4 rounded shadow">
                <p class="text-sm text-gray-500">Antrean Hari Ini</p>
                <h2 class="text-2xl font-bold">{{ $todayCount }}</h2>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <p class="text-sm text-gray-500">Antrean Bulan Ini</p>
                <h2 class="text-2xl font-bold">{{ $monthCount }}</h2>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <p class="text-sm text-gray-500">Subscription</p>
                <span class="px-3 py-1 rounded text-white
                    {{ auth()->user()->isPro() ? 'bg-green-600' : 'bg-gray-500' }}">
                    {{ auth()->user()->isPro() ? 'PRO' : 'FREE' }}
                </span>
            </div>

            <a href="{{ route('branches.index') }}"
               class="bg-blue-600 text-white flex items-center justify-center rounded hover:bg-blue-700">
                Kelola Cabang
            </a>

        </div>

        {{-- GRAFIK PRO --}}
        @if(auth()->user()->isPro())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-2">Antrean 7 Hari Terakhir</h3>
                <canvas id="dailyChart"></canvas>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-2">Antrean 12 Bulan</h3>
                <canvas id="monthlyChart"></canvas>
            </div>

        </div>
        @else
        <div class="mt-6 p-4 bg-yellow-100 text-yellow-700 rounded">
            Grafik hanya tersedia untuk akun <strong>PRO</strong>.
            <a href="/pricing" class="underline">Upgrade sekarang</a>
        </div>
        @endif
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if(auth()->user()->isPro())
    <script>
        const dailyData = @json($dailyData);
        const monthlyData = @json($monthlyData);

        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: dailyData.map(d => d.date),
                datasets: [{
                    label: 'Antrean',
                    data: dailyData.map(d => d.total),
                    borderWidth: 2
                }]
            }
        });

        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthlyData.map(m => m.month),
                datasets: [{
                    label: 'Antrean',
                    data: monthlyData.map(m => m.total),
                }]
            }
        });
    </script>
    @endif
</x-app-layout>
