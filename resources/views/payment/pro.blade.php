<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Pembayaran Pro</h2>
    </x-slot>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Order ID: <span class="font-semibold">{{ $trx->order_id }}</span>
        </p>

        <div class="mt-4 flex flex-wrap gap-3">
            <button id="pay-button"
                class="rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                Bayar Sekarang
            </button>

            <a href="{{ route('dashboard') }}"
                class="rounded-md border border-gray-300 px-5 py-2.5 text-sm font-semibold hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                Kembali ke Dashboard
            </a>
        </div>

        <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
            Setelah pembayaran sukses, akun akan otomatis menjadi Pro.
        </p>
    </div>

    <script
        src="{{ $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $clientKey }}"></script>


    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            window.snap.pay(@json($trx->snap_token), {
                onSuccess: function() {
                    window.location.href = @json(route('dashboard'));
                },
                onPending: function() {
                    alert('Pembayaran pending. Cek lagi nanti.');
                },
                onError: function() {
                    alert('Pembayaran gagal. Coba lagi.');
                },
                onClose: function() {
                    /* user menutup popup */ }
            });
        });
    </script>
</x-app-layout>
