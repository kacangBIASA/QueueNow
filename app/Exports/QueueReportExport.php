<?php

namespace App\Exports;

use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QueueReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private int $branchId,
        private Carbon $from,
        private Carbon $to
    ) {}

    public function collection(): Collection
    {
        return Queue::query()
            ->where('branch_id', $this->branchId)
            ->whereBetween('queue_date', [$this->from->toDateString(), $this->to->toDateString()])
            ->orderBy('queue_date')
            ->orderBy('number')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nomor',
            'Sumber',
            'Status',
            'Diambil',
            'Dipanggil',
            'Selesai',
        ];
    }

    public function map($q): array
    {
        return [
            $q->queue_date?->format('Y-m-d'),
            $q->number,
            $q->source,
            $q->status,
            optional($q->taken_at)->format('Y-m-d H:i:s'),
            optional($q->called_at)->format('Y-m-d H:i:s'),
            optional($q->finished_at)->format('Y-m-d H:i:s'),
        ];
    }
}
