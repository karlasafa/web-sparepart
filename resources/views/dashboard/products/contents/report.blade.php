<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Report</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0 2rem;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #cb0c9f;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
    </style>
</head>

<body>
    @if (empty($products))
        <h1>Tidak ada Data</h1>
    @else
        <h1>Daftar Produk</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->category->title }}</td>
                    <td>{{ rupiah($product->price) }}</td>
                    <td>{{ $product->stock }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <footer>
            <p>Data Produk dari tanggal {{ dateFormatter($startDate) }} - {{ dateFormatter($endDate) }}</p>
            <p>&copy; Web Programming | Studi Kasus Toko Online</p>
        </footer>
    @endif
</body>

</html>
