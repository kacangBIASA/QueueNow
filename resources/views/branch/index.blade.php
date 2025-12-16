<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Daftar Cabang
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Kelola cabang bisnis kamu.
                </p>
            </div>

            @php
                $isPro = (auth()->user()->subscription_type ?? 'free') === 'pro';
                $branchCount = $branches->count();
                $freeLimitReached = !$isPro && $branchCount >= 1;
            @endphp

            @if($freeLimitReached)
                <button disabled
                        class="inline-flex items-center rounded-md bg-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 cursor-not-allowed dark:bg-gray-800 dark:text-gray-400">
                    Tambah Cabang (Limit Free)
                </button>
            @else
                <a href="{{ route('branches.create') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    + Tambah Cabang
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-50 text-green-700 p-3 dark:bg-green-500/10 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-50 text-red-700 p-3 dark:bg-red-500/10 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-800">
                <div class="p-6">

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300 dark:border-gray-800 text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr class="text-gray-700 dark:text-gray-200">
                                    <th class="border border-gray-300 dark:border-gray-800 p-2">Nama Cabang</th>
                                    <th class="border border-gray-300 dark:border-gray-800 p-2">Alamat</th>
                                    <th class="border border-gray-300 dark:border-gray-800 p-2">No. Antrean Awal</th>
                                    <th class="border border-gray-300 dark:border-gray-800 p-2">Jadwal Operasional</th>
                                    <th class="border border-gray-300 dark:border-gray-800 p-2 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="text-gray-800 dark:text-gray-100">
                                @forelse ($branches as $branch)
                                    <tr>
                                        <td class="border border-gray-300 dark:border-gray-800 p-2">{{ $branch->nama_cabang }}</td>
                                        <td class="border border-gray-300 dark:border-gray-800 p-2">{{ $branch->alamat }}</td>
                                        <td class="border border-gray-300 dark:border-gray-800 p-2">{{ $branch->nomor_antrean_awal }}</td>
                                        <td class="border border-gray-300 dark:border-gray-800 p-2">{{ $branch->jadwal_operasional }}</td>
                                        <td class="border border-gray-300 dark:border-gray-800 p-2 text-center">
                                            <a href="{{ route('branches.edit', $branch->id) }}"
                                               class="text-indigo-600 hover:underline mr-3">
                                                Edit
                                            </a>

                                            <form action="{{ route('branches.destroy', $branch->id) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-6 text-gray-500 dark:text-gray-400">
                                            Belum ada cabang
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
