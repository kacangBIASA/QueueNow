    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Cabang
            </h2>
        </x-slot>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                {{-- Alert jika user FREE sudah mencapai batas --}}
                @if(auth()->user()->plan === 'free' && $branches->count() >= 1)
                    <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded">
                        Akun <strong>Free</strong> hanya dapat memiliki <strong>1 cabang</strong>.
                        Upgrade ke <strong>Pro</strong> untuk menambah cabang tanpa batas.
                    </div>
                @endif

                {{-- Tombol Tambah Cabang --}}
                @if(auth()->user()->plan === 'pro' || $branches->count() < 1)
                    <div class="mb-4">
                        <a href="{{ route('branches.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            + Tambah Cabang
                        </a>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border p-2">Nama Cabang</th>
                                    <th class="border p-2">Alamat</th>
                                    <th class="border p-2">No. Antrean Awal</th>
                                    <th class="border p-2">Jadwal Operasional</th>
                                    <th class="border p-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($branches as $branch)
                                    <tr>
                                        <td class="border p-2">{{ $branch->nama_cabang }}</td>
                                        <td class="border p-2">{{ $branch->alamat }}</td>
                                        <td class="border p-2">{{ $branch->nomor_antrean_awal }}</td>
                                        <td class="border p-2">{{ $branch->jadwal_operasional }}</td>
                                        <td class="border p-2 text-center">
                                            <a href="{{ route('branches.edit', $branch->id) }}"
                                            class="text-blue-600 hover:underline mr-2">
                                                Edit
                                            </a>

                                            <form action="{{ route('branches.destroy', $branch->id) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:underline">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4 text-gray-500">
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
    </x-app-layout>
