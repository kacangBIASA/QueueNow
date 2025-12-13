<x-app-layout>
    <div class="py-6">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <!-- Left -->
            <div>
                <span
                    class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700
                             dark:bg-indigo-500/10 dark:text-indigo-300">
                    SaaS Manajemen Antrian
                </span>

                <h1 class="mt-4 text-4xl sm:text-5xl font-bold tracking-tight">
                    QueueNow — Kelola antrean bisnis lebih cepat & rapi
                </h1>

                <p class="mt-4 text-gray-600 dark:text-gray-300 leading-relaxed">
                    Buat cabang, generate QR, terima antrean online/onsite, panggil/skip/selesai, lihat riwayat,
                    dan upgrade ke Pro untuk laporan PDF/Excel & grafik.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    @guest
                        <a href="{{ route('register') }}"
                            class="rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                            Mulai Gratis
                        </a>
                        <a href="{{ route('login') }}"
                            class="rounded-md border border-gray-300 px-5 py-2.5 text-sm font-semibold hover:bg-gray-50
                                  dark:border-gray-700 dark:hover:bg-gray-800">
                            Login Owner
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                            class="rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                            Masuk ke Dashboard
                        </a>
                    @endguest

                    <a href="{{ url('/pricing') }}"
                        class="rounded-md border border-gray-300 px-5 py-2.5 text-sm font-semibold hover:bg-gray-50
                              dark:border-gray-700 dark:hover:bg-gray-800">
                        Lihat Pricing
                    </a>
                </div>

                <div class="mt-10 grid sm:grid-cols-2 gap-4 text-sm">
                    <div
                        class="rounded-xl border border-gray-200 p-4 bg-white shadow-sm dark:bg-gray-900 dark:border-gray-800">
                        <p class="font-semibold">QR + Antrean Online</p>
                        <p class="mt-1 text-gray-600 dark:text-gray-300">
                            Customer scan QR untuk antrean di tempat atau ambil antrean online dari halaman publik.
                        </p>
                    </div>
                    <div
                        class="rounded-xl border border-gray-200 p-4 bg-white shadow-sm dark:bg-gray-900 dark:border-gray-800">
                        <p class="font-semibold">Dashboard Owner</p>
                        <p class="mt-1 text-gray-600 dark:text-gray-300">
                            Kelola cabang, panggil/skip/selesai, riwayat, dan upgrade ke Pro untuk grafik & laporan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right -->
            <div class="relative">
                <div
                    class="absolute -inset-4 rounded-2xl bg-gradient-to-r from-indigo-200 via-sky-200 to-purple-200 blur-2xl opacity-60 dark:opacity-20">
                </div>

                <div
                    class="relative rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold">Live Queue Preview</p>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Demo</span>
                    </div>

                    <div class="mt-5 space-y-3">
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-800">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Sedang dipanggil</p>
                            <p class="mt-1 text-3xl font-bold text-indigo-600 dark:text-indigo-300">A-12</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Antrean hari ini</p>
                                <p class="mt-1 text-xl font-semibold">48</p>
                            </div>
                            <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Status Paket</p>
                                <p class="mt-1 text-xl font-semibold">Free</p>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Fitur Pro</p>
                            <ul class="mt-2 space-y-1 text-sm">
                                <li>• Riwayat tanpa batas</li>
                                <li>• Export PDF/Excel</li>
                                <li>• Grafik harian/bulanan</li>
                            </ul>
                            <a href="{{ url('/pricing') }}"
                                class="mt-3 inline-flex text-sm font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-300">
                                Upgrade Pro →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section bawah -->
        <div class="mt-14 grid md:grid-cols-3 gap-4">
            <div
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:bg-gray-900 dark:border-gray-800">
                <p class="font-semibold">1) Buat Cabang</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Tambah cabang, alamat, jam operasional, dan
                    nomor antrean awal.</p>
            </div>
            <div
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:bg-gray-900 dark:border-gray-800">
                <p class="font-semibold">2) Share QR</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Tempel QR di lokasi, customer scan untuk ambil
                    antrean.</p>
            </div>
            <div
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:bg-gray-900 dark:border-gray-800">
                <p class="font-semibold">3) Kelola Antrean</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Panggil, skip, selesai, reset harian, dan lihat
                    riwayat.</p>
            </div>
        </div>
    </div>
</x-app-layout>
