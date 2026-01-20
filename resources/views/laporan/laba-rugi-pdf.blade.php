<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi</title>
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
            font-weight: bold;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            color: #2c3e50;
            font-weight: bold;
        }

        .header h3 {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #34495e;
            font-weight: normal;
        }

        .info-box {
            background-color: #ecf0f1;
            padding: 12px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
            font-size: 10px;
        }

        table.laporan-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.laporan-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ecf0f1;
        }

        .section-header {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            font-size: 12px;
            padding: 10px !important;
            border-bottom: 2px solid #2980b9 !important;
        }

        .sub-section {
            background-color: #ecf0f1;
            font-weight: bold;
            padding-left: 20px !important;
        }

        .item-row {
            padding-left: 30px !important;
        }

        .total-row {
            background-color: #34495e;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        .laba-kotor-row {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }

        .laba-bersih-row {
            background-color: #16a085;
            color: white;
            font-weight: bold;
            font-size: 13px;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ecf0f1;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
        }

        .summary-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .summary-box h3 {
            margin: 0 0 10px 0;
            font-size: 12px;
        }

        .summary-item {
            display: inline-block;
            width: 48%;
            padding: 8px;
            margin: 5px 1%;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            text-align: center;
        }

        .summary-label {
            font-size: 9px;
            opacity: 0.9;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            margin-top: 3px;
        }

        .percentage {
            font-size: 10px;
            opacity: 0.85;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN LABA RUGI</h1>
        <h2>TOKO SEHAT</h2>
        <h3>Periode {{ $periode["tanggal_awal_format"] }} s/d {{ $periode["tanggal_akhir_format"] }}</h3>
    </div>

    <div class="info-box">
        <strong>Tanggal Cetak:</strong> {{ date("d F Y, H:i") }} WIB |
        <strong>Total Transaksi:</strong> {{ number_format($ringkasan["total_transaksi"]) }} transaksi
    </div>

    <table class="laporan-table">
        <!-- PENDAPATAN -->
        <tr class="section-header">
            <td colspan="2">PENDAPATAN PENJUALAN</td>
        </tr>
        <tr class="item-row">
            <td>Penjualan Barang Dagang</td>
            <td class="text-right">Rp {{ number_format($pendapatan["penjualan_barang"], 0, ",", ".") }}</td>
        </tr>
        <tr class="total-row">
            <td class="text-bold">TOTAL PENDAPATAN BERSIH</td>
            <td class="text-right text-bold">Rp {{ number_format($pendapatan["total_pendapatan_bersih"], 0, ",", ".") }}
            </td>
        </tr>

        <!-- HPP -->
        <tr style="height: 15px;">
            <td colspan="2"></td>
        </tr>
        <tr class="section-header">
            <td colspan="2">HARGA POKOK PENJUALAN (HPP)</td>
        </tr>
        <tr class="item-row">
            <td>Total Harga Pokok Penjualan</td>
            <td class="text-right">Rp {{ number_format($hpp["total_hpp"], 0, ",", ".") }}</td>
        </tr>
        <tr class="total-row">
            <td class="text-bold">TOTAL HPP</td>
            <td class="text-right text-bold">Rp {{ number_format($hpp["total_hpp"], 0, ",", ".") }}</td>
        </tr>

        <!-- LABA KOTOR -->
        <tr style="height: 15px;">
            <td colspan="2"></td>
        </tr>
        <tr class="laba-kotor-row">
            <td class="text-bold">
                LABA 
                <span class="percentage">({{ number_format($laba_rugi["persentase_laba_kotor"], 2) }}%)</span>
            </td>
            <td class="text-right text-bold">Rp {{ number_format($laba_rugi["laba_kotor"], 0, ",", ".") }}</td>
        </tr>

        <!-- LABA BERSIH -->
        {{-- <tr style="height: 15px;">
            <td colspan="2"></td>
        </tr>
        <tr class="laba-bersih-row">
            <td class="text-bold">
                LABA BERSIH
                <span class="percentage">({{ number_format($laba_rugi["persentase_laba_bersih"], 2) }}%)</span>
            </td>
            <td class="text-right text-bold">Rp {{ number_format($laba_rugi["laba_bersih"], 0, ",", ".") }}</td>
        </tr> --}}
    </table>

    <div class="summary-box">
        <h3>RINGKASAN FINANSIAL</h3>
        <div class="summary-item">
            <div class="summary-label">Total Penjualan</div>
            <div class="summary-value">Rp {{ number_format($pendapatan["total_pendapatan_bersih"], 0, ",", ".") }}
            </div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total HPP</div>
            <div class="summary-value">Rp {{ number_format($hpp["total_hpp"], 0, ",", ".") }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Laba Kotor</div>
            <div class="summary-value">Rp {{ number_format($laba_rugi["laba_kotor"], 0, ",", ".") }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Laba Bersih</div>
            <div class="summary-value">Rp {{ number_format($laba_rugi["laba_bersih"], 0, ",", ".") }}</div>
        </div>
    </div>

    <div class="footer">
        <p><strong>Toko Sehat</strong> | Dicetak pada {{ date("d F Y, H:i") }} WIB</p>
        <p style="margin-top: 5px; font-style: italic;">Laporan ini bersifat rahasia dan hanya untuk keperluan internal
        </p>
    </div>
</body>

</html>
