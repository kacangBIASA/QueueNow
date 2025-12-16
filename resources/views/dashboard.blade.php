<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
            <a href="{{ route('branches.index') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                Kelola Cabang
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Card -->
            <div class="rounded-2xl bg-white border  p-6 shadow-sm">
                <p class="text-sm">Selamat datang,</p>
                <p class="text-2xl font-bold">{{ auth()->user()->name }}</p>

                <div class="mt-4 grid md:grid-cols-3 gap-4">
                    <div class="rounded-xl border p-4">
                        <p class="text-xs ">Nama Bisnis</p>
                        <p class="mt-1 font-semibold ">{{ auth()->user()->business_name ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border  p-4">
                        <p class="text-xs ">Kategori</p>
                        <p class="mt-1 font-semibold ">{{ auth()->user()->business_category ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border  p-4">
                        <p class="text-xs ">Subscription</p>
                        <p class="mt-1 font-semibold ">
                            {{ strtoupper(auth()->user()->subscription_type ?? 'free') }}
                        </p>
                        <a href="{{ url('/pricing') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                            Upgrade Pro →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Next: nanti scorecards antrean & chart -->
            <div class="rounded-2xl border border-dashed border-gray-300 p-6 text-gray-600">
                Berikutnya: kartu “antrean hari ini / bulan ini” dan grafik (Pro).
            </div>

        </div>
    </div>
</x-app-layout>
