<x-guest-layout>
    <form method="POST" action="{{ route('onboarding.store') }}" class="space-y-4">
        @csrf

        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900">Lengkapi Profil Bisnis</h1>
            <p class="text-sm text-gray-600">Agar kamu bisa mulai mengelola cabang dan antrean di QueueNow</p>
        </div>

        @if (session('success'))
            <div class="p-3 rounded bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Nama Owner (readonly) -->
        <div>
            <x-input-label value="Nama Owner" />
            <x-text-input class="block mt-1 w-full bg-gray-100" type="text" value="{{ $user->name }}" disabled />
        </div>

        <!-- Email (readonly) -->
        <div>
            <x-input-label value="Email" />
            <x-text-input class="block mt-1 w-full bg-gray-100" type="email" value="{{ $user->email }}" disabled />
        </div>

        <!-- Business Name -->
        <div>
            <x-input-label for="business_name" value="Nama Bisnis" />
            <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name"
                          :value="old('business_name', $user->business_name)" required autofocus />
            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" value="Nomor Telepon" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                          :value="old('phone', $user->phone)" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Business Category -->
        <div>
            <x-input-label for="business_category" value="Kategori Bisnis" />
            <x-text-input id="business_category" class="block mt-1 w-full" type="text" name="business_category"
                          :value="old('business_category', $user->business_category)" required />
            <p class="mt-1 text-xs text-gray-500">Contoh: Klinik, Salon, Restoran, Bengkel</p>
            <x-input-error :messages="$errors->get('business_category')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center">
            Simpan & Lanjutkan
        </x-primary-button>
    </form>
</x-guest-layout>
