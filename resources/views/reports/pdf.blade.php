<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>QueueNow Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .h1 { font-size: 18px; font-weight: bold; margin-bottom: 6px; }
        .muted { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f3f3; text-align: left; }
        .grid { display: table; width: 100%; margin-top: 10px; }
        .col { display: table-cell; width: 33%; vertical-align: top; padding-right: 8px; }
        .card { border: 1px solid #ddd; padding: 8px; }
        .big { font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="h1">QueueNow — Laporan Antrean</div>
    <div class="muted">Owner: {{ $user->name }} • Cabang: {{ $branch->name }}</div>
    <div class="muted">Periode: {{ $from->format('Y-m-d') }} s/d {{ $to->format('Y-m-d') }}</div>

    <div class="grid">
        <div class="col">
            <div class="card">
                <div class="muted">Total antrean</div>
                <div class="big">{{ $data['total'] }}</div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="muted">Jam ramai</div>
                <div class="big">{{ $data['peakHour'] }}</div>
                <div class="muted">{{ $data['peakCount'] }} antrean</div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="muted">Breakdown status</div>
                @foreach($data['statusBreakdown'] as $status => $count)
                    <div>{{ ucfirst($status) }}: <b>{{ $count }}</b></div>
                @endforeach
            </div>
        </div>
    </div>

    <h3 style="margin-top:14px;">Antrean per Hari</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['perDay'] as $row)
                <tr>
                    <td>{{ $row->day }}</td>
                    <td>{{ $row->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="muted" style="margin-top:12px;">
        Generated at: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>
