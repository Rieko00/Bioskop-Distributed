<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Transaksi Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">LAPORAN LOG TRANSAKSI</div>
        <div>Sistem Manajemen Bioskop</div>
    </div>

    <div class="info">
        <p><strong>Tanggal Cetak:</strong> {{ date('d F Y H:i') }}</p>
        <p><strong>Total Data:</strong> {{ count($logTransaksis) }} transaksi</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>No Transaksi</th>
                <th>Cabang</th>
                <th>Pelanggan</th>
                <th>Film</th>
                <th>Studio</th>
                <th>Kursi</th>
                <th>Total Bayar</th>
                <th>Status</th>
                <th>Waktu Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logTransaksis as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->no_transaksi ?? '-' }}</td>
                    <td>{{ $log->nama_cabang ?? '-' }}</td>
                    <td>{{ $log->nama_pelanggan ?? '-' }}</td>
                    <td>{{ $log->judul_film ?? '-' }}</td>
                    <td>{{ $log->nama_studio ?? '-' }}</td>
                    <td>{{ $log->seat_code ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($log->total_bayar ?? 0, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($log->status_pembayaran ?? 'N/A') }}</td>
                    <td>{{ $log->waktu_transaksi ? \Carbon\Carbon::parse($log->waktu_transaksi)->format('d/m/Y H:i') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'System' }} | {{ config('app.name') }}</p>
    </div>
</body>

</html>
