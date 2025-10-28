<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Periode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
        }

        .info {
            margin-bottom: 20px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 3px 0;
        }

        .ringkasan {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .ringkasan table {
            width: 100%;
        }

        .ringkasan td {
            padding: 5px;
        }

        table.transaksi {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table.transaksi th,
        table.transaksi td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table.transaksi th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        table.transaksi tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .detail-items {
            margin: 10px 0;
            padding-left: 20px;
        }

        .detail-items table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .detail-items th,
        .detail-items td {
            border: 1px solid #ccc;
            padding: 5px;
        }

        .detail-items th {
            background-color: #e8e8e8;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI PENJUALAN</h2>
        <h3>PERIODE {{ date("d/m/Y", strtotime($periode["tanggal_awal"])) }} -
            {{ date("d/m/Y", strtotime($periode["tanggal_akhir"])) }}</h3>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="150"><strong>Tanggal Cetak</strong></td>
                <td>: {{ date("d/m/Y H:i:s") }}</td>
            </tr>
        </table>
    </div>

    <div class="ringkasan">
        <h3 style="margin-top: 0;">Ringkasan</h3>
        <table>
            <tr>
                <td width="200"><strong>Total Transaksi</strong></td>
                <td>: {{ $ringkasan["total_transaksi"] }}</td>
            </tr>
            <tr>
                <td><strong>Total Item Terjual</strong></td>
                <td>: {{ number_format($ringkasan["total_item_terjual"]) }}</td>
            </tr>
            <tr>
                <td><strong>Total Pendapatan</strong></td>
                <td>: Rp {{ number_format($ringkasan["total_pendapatan"], 0, ",", ".") }}</td>
            </tr>
        </table>
    </div>

    <h3>Detail Transaksi</h3>
    @foreach ($data as $transaksi)
        <div style="margin-bottom: 20px; page-break-inside: avoid;">
            <table class="transaksi">
                <tr>
                    <td width="150"><strong>No. Nota</strong></td>
                    <td>{{ $transaksi["no_nota"] }}</td>
                    <td width="150"><strong>Tanggal</strong></td>
                    <td>{{ date("d/m/Y H:i", strtotime($transaksi["tgl_transaksi"])) }}</td>
                </tr>
                <tr>
                    <td><strong>Kasir</strong></td>
                    <td>{{ $transaksi["kasir"]["nama"] }}</td>
                    <td><strong>Total Item</strong></td>
                    <td>{{ $transaksi["total_item"] }}</td>
                </tr>
            </table>

            <div class="detail-items">
                <table>
                    <thead>
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th width="100">Kode Produk</th>
                            <th>Nama Produk</th>
                            <th width="100" class="text-right">Harga</th>
                            <th width="60" class="text-center">Qty</th>
                            <th width="120" class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi["detail_items"] as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item["kode_produk"] }}</td>
                                <td>{{ $item["nama_produk"] }}</td>
                                <td class="text-right">Rp {{ number_format($item["harga_satuan"], 0, ",", ".") }}</td>
                                <td class="text-center">{{ $item["jumlah"] }}</td>
                                <td class="text-right">Rp {{ number_format($item["subtotal"], 0, ",", ".") }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total</strong></td>
                            <td class="text-right"><strong>Rp
                                    {{ number_format($transaksi["harga_total"], 0, ",", ".") }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <div class="footer">
        <p>Dicetak pada {{ date("d/m/Y H:i:s") }} | Toko Sehat</p>
    </div>
</body>

</html>
