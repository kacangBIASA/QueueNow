<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Owner QueueNow</h1>
            <p class="text-sm text-gray-600">Buat akun untuk mengelola antrean bisnis kamu</p>
        </div>

        <!-- Google -->
        <a href="{{ route('google.redirect') }}"
           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <span>Daftar dengan Google</span>
        </a>

        <div class="flex items-center gap-3">
            <div class="h-px flex-1 bg-gray-200"></div>
            <span class="text-xs text-gray-500">atau</span>
            <div class="h-px flex-1 bg-gray-200"></div>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Owner')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                          :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Business Name -->
        <div>
            <x-input-label for="business_name" value="Nama Bisnis" />
            <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name"
                          :value="old('business_name')" required autocomplete="organization" />
            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" value="Nomor Telepon" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                          :value="old('phone')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Business Category -->
        <div>
            <x-input-label for="business_category" value="Kategori Bisnis" />
            <x-text-input id="business_category" class="block mt-1 w-full" type="text" name="business_category"
                          :value="old('business_category')" required />
            <p class="mt-1 text-xs text-gray-500">Contoh: Klinik, Salon, Restoran, Bengkel</p>
            <x-input-error :messages="$errors->get('business_category')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                Sudah punya akun? Masuk
            </a>

            <x-primary-button>
                Daftar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
