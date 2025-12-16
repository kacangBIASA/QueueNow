<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Cabang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- Error validation --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Cabang --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Nama Cabang</label>
                        <input
                            type="text"
                            name="nama_cabang"
                            value="{{ old('nama_cabang', $branch->nama_cabang) }}"
                            class="w-full border rounded px-3 py-2"
                            required
                        >
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Alamat</label>
                        <textarea
                            name="alamat"
                            rows="3"
                            class="w-full border rounded px-3 py-2"
                            required
                        >{{ old('alamat', $branch->alamat) }}</textarea>
                    </div>

                    {{-- Nomor Antrean Awal --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Nomor Antrean Awal</label>
                        <input
                            type="number"
                            name="nomor_antrean_awal"
                            value="{{ old('nomor_antrean_awal', $branch->nomor_antrean_awal) }}"
                            class="w-full border rounded px-3 py-2"
                            min="1"
                            required
                        >
                    </div>

                    {{-- Jadwal Operasional --}}
                    <div class="mb-6">
                        <label class="block font-medium mb-1">Jadwal Operasional</label>
                        <input
                            type="text"
                            name="jadwal_operasional"
                            value="{{ old('jadwal_operasional', $branch->jadwal_operasional) }}"
                            class="w-full border rounded px-3 py-2"
                            required
                        >
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-between">
                        <a href="{{ route('branches.index') }}"
                           class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                            Kembali
                        </a>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Update Cabang
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
