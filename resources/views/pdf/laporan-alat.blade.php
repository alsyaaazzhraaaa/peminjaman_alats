<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Alat</title>
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
        <h1>LAPORAN DATA ALAT</h1>
        <p>Aplikasi Peminjaman Alat</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kategori</th>
                <th>Nama Alat</th>
                <th>Jumlah</th>
                <th>Kondisi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->kategori?->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->nama_alat }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ ucfirst($item->kondisi) }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data alat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
