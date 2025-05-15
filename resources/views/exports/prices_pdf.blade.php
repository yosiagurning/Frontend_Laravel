<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Harga Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        h2 {
            margin-bottom: 10px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h2>Data Harga Barang</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga Awal</th>
                <th>Harga Sekarang</th>
                <th>Persentase</th>
                <th>Alasan</th>
                <th>Terakhir Diperbarui</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prices as $price)
                <tr>
                    <td>{{ $price['item_name'] }}</td>
                    <td>Rp{{ number_format($price['initial_price']) }}</td>
                    <td>Rp{{ number_format($price['current_price']) }}</td>
                    <td>{{ round($price['change_percent'], 2) }}%</td>
                    <td>{{ $price['reason'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($price['updated_at'])->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
