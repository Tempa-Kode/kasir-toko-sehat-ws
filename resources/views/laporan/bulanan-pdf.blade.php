<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 12px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #2c3e50;
        }

        .header h3 {
            margin: 5px 0 0 0;
            font-size: 13px;
            color: #34495e;
            font-weight: normal;
        }

        .info-box {
            background-color: #ecf0f1;
            padding: 12px;
            margin-bottom: 15px;
            border-left: 4px solid #3498db;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 3px 0;
            font-size: 10px;
        }

        .summary-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .summary-box h3 {
            margin: 0 0 10px 0;
            font-size: 12px;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 8px;
        }

        .summary-label {
            font-size: 9px;
            opacity: 0.9;
            margin-bottom: 3px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }

        .date-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .date-header {
            background-color: #3498db;
            color: white;
            padding: 8px 12px;
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 3px;
        }

        .transaction-card {
            border: 1px solid #dfe6e9;
            margin-bottom: 12px;
            padding: 10px;
            background-color: #ffffff;
            border-radius: 4px;
            page-break-inside: avoid;
        }

        .transaction-header {
            display: table;
            width: 100%;
            background-color: #f8f9fa;
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 3px;
        }

        .transaction-header .left {
            display: table-cell;
            width: 50%;
        }

        .transaction-header .right {
            display: table-cell;
            width: 50%;
            text-align: right;
        }

        .transaction-info {
            font-size: 10px;
            color: #2c3e50;
        }

        .transaction-info strong {
            color: #000;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.items-table th {
            background-color: #34495e;
            color: white;
            padding: 6px 5px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
        }

        table.items-table td {
            padding: 6px 5px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 9px;
        }

        table.items-table tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #ecf0f1;
            text-align: center;
            font-size: 8px;
            color: #7f8c8d;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN TRANSAKSI PENJUALAN BULANAN</h1>
        <h3>{{ strtoupper($periode["nama_bulan"]) }} {{ $periode["tahun"] }}</h3>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td width="100"><strong>Tanggal Cetak</strong></td>
                <td>: {{ date("d F Y, H:i") }} WIB</td>
                <td width="100" class="text-right"><strong>Periode</strong></td>
                <td class="text-right">: {{ date("d M", strtotime($periode["tanggal_awal"])) }} -
                    {{ date("d M Y", strtotime($periode["tanggal_akhir"])) }}</td>
            </tr>
        </table>
    </div>

    <h3 style="margin: 15px 0 10px 0; color: #2c3e50; font-size: 12px;">Detail Transaksi Per Tanggal</h3>

    @foreach ($data_per_tanggal as $perTanggal)
        <div class="date-section">
            <div class="date-header">
                {{ date("d F Y", strtotime($perTanggal["tanggal"])) }} |
                {{ $perTanggal["total_transaksi"] }} Transaksi |
                Pendapatan: Rp {{ number_format($perTanggal["total_pendapatan"], 0, ",", ".") }}
            </div>

            @foreach ($perTanggal["transaksi"] as $transaksi)
                <div class="transaction-card">
                    <div class="transaction-header">
                        <div class="left">
                            <div class="transaction-info">
                                <strong>{{ $transaksi["no_nota"] }}</strong> |
                                {{ date("H:i", strtotime($transaksi["tgl_transaksi"])) }} WIB
                            </div>
                        </div>
                        <div class="right">
                            <div class="transaction-info">
                                Kasir: <strong>{{ $transaksi["kasir"]["nama"] }}</strong>
                            </div>
                        </div>
                    </div>

                    <table class="items-table">
                        <thead>
                            <tr>
                                <th width="25" class="text-center">No</th>
                                <th width="70">Kode</th>
                                <th>Nama Produk</th>
                                <th width="70" class="text-right">Harga</th>
                                <th width="35" class="text-center">Qty</th>
                                <th width="85" class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi["detail_items"] as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item["kode_produk"] }}</td>
                                    <td>{{ $item["nama_produk"] }}</td>
                                    <td class="text-right">{{ number_format($item["harga_satuan"], 0, ",", ".") }}</td>
                                    <td class="text-center">{{ $item["jumlah"] }}</td>
                                    <td class="text-right">{{ number_format($item["subtotal"], 0, ",", ".") }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="4" class="text-right">TOTAL</td>
                                <td class="text-center">{{ $transaksi["detail_items"]->sum("jumlah") }}</td>
                                <td class="text-right">Rp {{ number_format($transaksi["harga_total"], 0, ",", ".") }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="footer">
        <p><strong>Toko Sehat</strong> | Dicetak pada {{ date("d F Y, H:i") }} WIB</p>
        <p style="margin-top: 3px; font-style: italic;">Laporan ini bersifat rahasia dan hanya untuk keperluan internal
        </p>
    </div>
</body>

</html>
