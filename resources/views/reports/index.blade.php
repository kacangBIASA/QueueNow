<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl">Laporan (Pro)</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">Export PDF & Excel</p>
            </div>
        </div>
    </x-slot>

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 text-red-700 p-3 dark:bg-red-500/10 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form method="GET" action="{{ route('reports.index') }}" class="grid md:grid-cols-4 gap-4">
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Cabang</label>
                <select name="branch_id" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" @selected($branchId == $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Dari</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                       class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Sampai</label>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                       class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
            </div>

            <div class="flex items-end gap-2">
                <button class="w-full rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    @if($data)
        <div class="mt-6 grid md:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total antrean</p>
                <p class="mt-1 text-3xl font-bold">{{ $data['total'] }}</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Jam ramai</p>
                <p class="mt-1 text-3xl font-bold">{{ $data['peakHour'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">({{ $data['peakCount'] }} antrean)</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                <div class="mt-2 text-sm space-y-1">
                    @foreach($data['statusBreakdown'] as $status => $count)
                        <div class="flex justify-between">
                            <span class="capitalize">{{ $status }}</span>
                            <span class="font-semibold">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">Antrean per Hari</h3>

                <div class="flex gap-2">
                    <a class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                       href="{{ route('reports.pdf', ['branch_id'=>$branchId,'from'=>$from->format('Y-m-d'),'to'=>$to->format('Y-m-d')]) }}">
                        Export PDF
                    </a>
                    <a class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-black dark:bg-gray-800 dark:hover:bg-gray-700"
                       href="{{ route('reports.excel', ['branch_id'=>$branchId,'from'=>$from->format('Y-m-d'),'to'=>$to->format('Y-m-d')]) }}">
                        Export Excel
                    </a>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="py-2">Tanggal</th>
                            <th class="py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($data['perDay'] as $row)
                            <tr>
                                <td class="py-2">{{ $row->day }}</td>
                                <td class="py-2 font-semibold">{{ $row->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
