<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; padding: 0; font-size: 20px; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMINJAMAN ALAT</h1>
        <p>Aplikasi Peminjaman Alat</p>
        <p>
            @if($startDate && $endDate)
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}<br>
            @endif
            Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th>Peminjam</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali (Rencana)</th>
                <th>Status</th>
                <th>Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ optional($item->user)->nama ?? optional($item->user)->username ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ $item->tanggal_kembali_rencana ? \Carbon\Carbon::parse($item->tanggal_kembali_rencana)->format('d/m/Y') : '-' }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>{{ optional($item->approver)->nama ?? optional($item->approver)->username ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
