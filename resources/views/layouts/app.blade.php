<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'QueueNow') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script>
        (function() {
            try {
                const theme = localStorage.getItem('theme') || 'light';
                if (theme === 'dark') document.documentElement.classList.add('dark');
                else document.documentElement.classList.remove('dark');
            } catch (e) {}
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100">
    <div class="min-h-screen">
        <!-- Top Navbar (Unified: public + auth) -->
        <header
            class="sticky top-0 z-50 border-b border-gray-200 bg-white/80 backdrop-blur dark:border-gray-800 dark:bg-gray-900/70">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <!-- Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span
                        class="h-10 w-10 rounded-xl bg-indigo-600 text-white grid place-items-center font-bold">Q</span>
                    <span class="text-lg font-bold">QueueNow</span>
                </a>

                <!-- Right -->
                <div class="flex items-center gap-2">
                    <!-- Theme Toggle -->
                    <button type="button" onclick="toggleTheme()"
                        class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold hover:bg-gray-50
                                   dark:border-gray-700 dark:hover:bg-gray-800"
                        title="Ubah tema">
                        🌙/☀️
                    </button>

                    @auth
                        <!-- Desktop menu -->
                        <nav class="hidden md:flex items-center gap-2">
                            <a href="{{ route('dashboard') }}"
                                class="rounded-md px-3 py-2 text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-800">
                                Dashboard
                            </a>

                            {{-- aktifkan saat routes cabang sudah dibuat --}}
                            {{-- <a href="{{ route('branches.index') }}" class="rounded-md px-3 py-2 text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-800">Cabang</a> --}}

                            <a href="{{ url('/pricing') }}"
                                class="rounded-md px-3 py-2 text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-800">
                                Pricing
                            </a>

                            {{-- aktifkan saat reports sudah dibuat --}}
                            {{-- <a href="{{ route('reports.index') }}" class="rounded-md px-3 py-2 text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-800">Reports</a> --}}
                        </nav>

                        <!-- User dropdown + logout -->
                        <div class="hidden md:flex items-center gap-2">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->name }}
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-black
                                               dark:bg-gray-800 dark:hover:bg-gray-700">
                                    Logout
                                </button>
                            </form>
                        </div>

                        <!-- Mobile menu button -->
                        <button type="button"
                            class="md:hidden inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold hover:bg-gray-50
                                       dark:border-gray-700 dark:hover:bg-gray-800"
                            x-data="{ open: false }" x-on:click="open = !open" x-init="$watch('open', value => document.getElementById('mobileMenu').classList.toggle('hidden', !value))">
                            Menu
                        </button>
                    @else
                        <a href="{{ route('login') }}"
                            class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold hover:bg-gray-50
                                  dark:border-gray-700 dark:hover:bg-gray-800">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>

            @auth
                <!-- Mobile Menu Panel -->
                <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200 dark:border-gray-800">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3 space-y-1">
                        <a href="{{ route('dashboard') }}"
                            class="block rounded-md px-3 py-2 text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-800">
                            Dashboard
                        </a>

                        {{-- <a href="{{ route('branches.index') }}" class="block rounded-md px-3 py-2 text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-800">Cabang</a> --}}

                        <a href="{{ url('/pricing') }}"
                            class="block rounded-md px-3 py-2 text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-800">
                            Pricing
                        </a>

                        {{-- <a href="{{ route('reports.index') }}" class="block rounded-md px-3 py-2 text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-800">Reports</a> --}}

                        <div class="pt-2 border-t border-gray-200 dark:border-gray-800">
                            <div class="px-3 py-2 text-sm text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->name }} • {{ auth()->user()->email }}
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full mt-2 rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-black
                                               dark:bg-gray-800 dark:hover:bg-gray-700">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth
        </header>

        <!-- Optional Heading -->
        @isset($header)
            <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <!-- Page Content -->
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-200 dark:border-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 text-xs text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} QueueNow • Laravel 12
            </div>
        </footer>
    </div>
</body>

</html>
