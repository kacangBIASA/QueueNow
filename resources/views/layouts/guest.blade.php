<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'QueueNow') }}</title>

    <!-- EARLY THEME INIT (biar konsisten & tidak kedip) -->
    <script>
        (function () {
            try {
                const theme = localStorage.getItem('theme') || 'light';
                if (theme === 'dark') document.documentElement.classList.add('dark');
                else document.documentElement.classList.remove('dark');
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100">
    <div class="min-h-screen">
        <!-- background blobs (ikut tema) -->
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-40 left-1/2 h-[500px] w-[500px] -translate-x-1/2 rounded-full bg-indigo-200 blur-3xl opacity-50 dark:opacity-20"></div>
            <div class="absolute -bottom-40 right-10 h-[500px] w-[500px] rounded-full bg-sky-200 blur-3xl opacity-50 dark:opacity-20"></div>
        </div>

        <div class="min-h-screen flex items-center justify-center px-4 py-10">
            <div class="w-full max-w-md relative">

                <!-- Toggle Theme -->
                <button type="button"
                        onclick="toggleTheme()"
                        class="absolute top-0 right-0 inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold hover:bg-gray-50
                               dark:border-gray-700 dark:hover:bg-gray-800"
                        title="Ubah tema">
                    🌙/☀️
                </button>

                <div class="mb-6 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 justify-center">
                        <span class="h-10 w-10 rounded-xl bg-indigo-600 text-white grid place-items-center font-bold">Q</span>
                        <span class="text-xl font-bold">QueueNow</span>
                    </a>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Kelola antrean bisnis jadi lebih rapi</p>
                </div>

                <!-- Card -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} QueueNow • Laravel 12
                </p>
            </div>
        </div>
    </div>
</body>
</html>
