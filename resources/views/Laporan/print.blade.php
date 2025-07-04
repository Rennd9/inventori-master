<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            font-weight: bold;
        }
        
        .info {
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .text-left {
            text-align: left;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        
        .signature {
            margin-top: 50px;
            float: right;
            text-align: center;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            width: 150px;
            margin-top: 60px;
            margin-bottom: 5px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN STOK BARANG</h2>
    </div>
    
    <div class="info">
        <strong>Tanggal:</strong> {{ date('d/m/Y', strtotime($from)) }} - {{ date('d/m/Y', strtotime($to)) }}<br>
        <strong>Nama Toko:</strong> ___________________________
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Kode</th>
                <th rowspan="2">Nama Barang</th>
                <th colspan="2">Barang Masuk-Keluar</th>
                <th rowspan="2">Stok Akhir</th>
                <th rowspan="2">Keterangan</th>
            </tr>
            <tr>
                <th>Masuk</th>
                <th>Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['kode'] }}</td>
                <td class="text-left">{{ $item['nama'] }}</td>
                <td>{{ $item['masuk'] }}</td>
                <td>{{ $item['keluar'] }}</td>
                <td>{{ $item['stok_akhir'] }}</td>
                <td></td>
            </tr>
            @endforeach
            

            
            <tr class="total-row">
                <td colspan="3"><strong>Jumlah Total</strong></td>
                <td><strong>{{ collect($laporan)->sum('masuk') }}</strong></td>
                <td><strong>{{ collect($laporan)->sum('keluar') }}</strong></td>
                <td><strong>{{ collect($laporan)->sum('stok_akhir') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p>Kepala Gudang,</p>
        <div class="signature-line"></div>
        <p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
    </div>

    <div style="clear: both;"></div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>