<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Produk Terlaris</title>
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

        table.produk {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.produk th,
        table.produk td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table.produk th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        table.produk tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .ranking {
            background-color: #FFD700;
            font-weight: bold;
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
        <h2>LAPORAN PRODUK TERLARIS</h2>
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

    <table class="produk">
        <thead>
            <tr>
                <th width="50" class="text-center">Peringkat</th>
                <th width="100">Kode Produk</th>
                <th>Nama Produk</th>
                <th width="100" class="text-right">Harga</th>
                <th width="80" class="text-center">Jumlah Terjual</th>
                <th width="80" class="text-center">Total Transaksi</th>
                <th width="120" class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $produk)
                <tr>
                    <td class="text-center {{ $index < 3 ? "ranking" : "" }}">{{ $index + 1 }}</td>
                    <td>{{ $produk->kode_produk }}</td>
                    <td>{{ $produk->nama_produk }}</td>
                    <td class="text-right">Rp {{ number_format($produk->harga, 0, ",", ".") }}</td>
                    <td class="text-center">{{ number_format($produk->total_terjual) }}</td>
                    <td class="text-center">{{ $produk->total_transaksi }}</td>
                    <td class="text-right">Rp {{ number_format($produk->total_pendapatan, 0, ",", ".") }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">TOTAL</th>
                <th class="text-center">{{ number_format(collect($data)->sum("total_terjual")) }}</th>
                <th class="text-center">-</th>
                <th class="text-right">Rp {{ number_format(collect($data)->sum("total_pendapatan"), 0, ",", ".") }}
                </th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada {{ date("d/m/Y H:i:s") }} | Toko Sehat</p>
    </div>
</body>

</html>
