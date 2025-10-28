<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Periode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #2c3e50;
        }

        .header h3 {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #34495e;
            font-weight: normal;
        }

        .info-box {
            background-color: #ecf0f1;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 4px 0;
            font-size: 11px;
        }

        .summary-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
        }

        .summary-box h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 10px;
        }

        .summary-label {
            font-size: 10px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table th {
            background-color: #34495e;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
        }

        table.data-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 10px;
        }

        table.data-table tr:hover {
            background-color: #f8f9fa;
        }

        table.data-table tr:last-child td {
            border-bottom: 2px solid #34495e;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ecf0f1;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN TRANSAKSI PENJUALAN</h1>
        <h3>Periode {{ date("d F Y", strtotime($periode["tanggal_awal"])) }} -
            {{ date("d F Y", strtotime($periode["tanggal_akhir"])) }}</h3>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td width="120"><strong>Tanggal Cetak</strong></td>
                <td>: {{ date("d F Y, H:i") }} WIB</td>
                <td width="120" class="text-right"><strong>Total Hari</strong></td>
                <td class="text-right">:
                    {{ \Carbon\Carbon::parse($periode["tanggal_awal"])->diffInDays(\Carbon\Carbon::parse($periode["tanggal_akhir"])) + 1 }}
                    Hari</td>
            </tr>
        </table>
    </div>

    <div class="summary-box">
        <h3>RINGKASAN PERIODE</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Transaksi</div>
                <div class="summary-value">{{ number_format($ringkasan["total_transaksi"]) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Item Terjual</div>
                <div class="summary-value">{{ number_format($ringkasan["total_item_terjual"]) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value">Rp {{ number_format($ringkasan["total_pendapatan"], 0, ",", ".") }}</div>
            </div>
        </div>
    </div>

    <h3 style="margin: 20px 0 10px 0; color: #2c3e50;">Daftar Transaksi ({{ count($data) }} Transaksi)</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th width="100">No. Nota</th>
                <th width="110">Tanggal</th>
                <th>Kasir</th>
                <th width="60" class="text-center">Qty Item</th>
                <th width="100" class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $transaksi)
                @foreach ($data as $index => $transaksi)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $transaksi["no_nota"] }}</td>
                        <td>{{ date("d/m/Y H:i", strtotime($transaksi["tgl_transaksi"])) }}</td>
                        <td>{{ $transaksi["kasir"]["nama"] }}</td>
                        <td class="text-center">{{ $transaksi["total_item"] }}</td>
                        <td class="text-right">
                            <strong>{{ number_format($transaksi["harga_total"], 0, ",", ".") }}</strong></td>
                    </tr>
                @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #34495e; color: white; font-weight: bold;">
                <td colspan="4" class="text-right" style="padding: 12px 8px;">TOTAL KESELURUHAN</td>
                <td class="text-center">{{ number_format($ringkasan["total_item_terjual"]) }}</td>
                <td class="text-right">{{ number_format($ringkasan["total_pendapatan"], 0, ",", ".") }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p><strong>Toko Sehat</strong> | Dicetak pada {{ date("d F Y, H:i") }} WIB</p>
        <p style="margin-top: 5px; font-style: italic;">Laporan ini bersifat rahasia dan hanya untuk keperluan internal
        </p>
    </div>
</body>

</html>
