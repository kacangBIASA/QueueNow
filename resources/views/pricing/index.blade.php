<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Pricing</h2>
    </x-slot>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="text-lg font-bold">Free</h3>
            <p class="mt-2 text-3xl font-bold">Rp0</p>

            <ul class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                <li>• 1 cabang</li>
                <li>• Antrean online & onsite</li>
                <li>• Riwayat maksimal 1 bulan</li>
            </ul>

            <div class="mt-6">
                <span class="text-xs text-gray-500 dark:text-gray-400">Cocok untuk mulai.</span>
            </div>
        </div>

        <div class="rounded-2xl border border-indigo-200 bg-white p-6 ring-1 ring-indigo-100 dark:border-indigo-700 dark:bg-gray-900 dark:ring-indigo-700/20">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">{{ $proName }}</h3>
                <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-300">RECOMMENDED</span>
            </div>

            <p class="mt-2 text-3xl font-bold">Rp{{ number_format($proPrice, 0, ',', '.') }}</p>

            <ul class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                <li>• Cabang unlimited</li>
                <li>• Riwayat tanpa batas</li>
                <li>• Dashboard grafik (Chart.js)</li>
                <li>• Export PDF & Excel</li>
            </ul>

            <div class="mt-6">
                @auth
                    @if(auth()->user()->subscription_type === 'pro')
                        <div class="rounded-md bg-green-50 p-3 text-green-700 dark:bg-green-500/10 dark:text-green-300">
                            Akun kamu sudah Pro ✅
                        </div>
                    @else
                        <form method="POST" action="{{ route('pricing.upgrade') }}">
                            @csrf
                            <button class="w-full rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                Upgrade ke Pro (Midtrans)
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="block text-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        Login untuk Upgrade
                    </a>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
