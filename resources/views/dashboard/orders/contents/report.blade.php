<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Remix Icons (LIBRARY) --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />

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

        a {
            text-decoration: none;
        }

        .title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .title h1 {
            margin-block: 2rem 0;
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

        .summary {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #dddddd;
            background-color: #f9f9f9;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }

        .json-output {
            background-color: #f0f0f0;
            padding: 10px;
            border: 1px solid #dddddd;
            white-space: pre-wrap; /* Memastikan format JSON tetap terjaga */
            font-family: monospace; /* Menggunakan font monospace untuk tampilan yang lebih baik */
        }
    </style>
</head>

<body>
    @if (empty($orders))
        <h1>Tidak ada Data</h1>
    @else

        <div class="title">
            <h1>Laporan Penjualan</h1>
            <a href='{{ url("dashboard/orders/report/print/$startDate/$endDate") }}' title="Cetak Laporan">
                <h1><i class="ri-printer-line"></i></h1>
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Penjualan</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Metode Pembayaran</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Detail Produk</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->date }}</td>
                        <td>{{ $order->customer }}</td>
                        <td>{{ $order->payment_method }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ rupiah($order->total) }}</td>
                        <td>
                            <div class="json-output">{{ jsonProducts($order->products) }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2 style="margin-top: 3rem;text-align:center">Ringkasan Produk Paling Banyak Dibeli</h2>
        <div class="summary">
            @php
                $topProductsArray = [];
                foreach ($topProducts as $productId => $quantity) {
                    $topProductsArray[] = [
                        'id' => $productId,
                        'quantity' => $quantity,
                    ];
                }
            @endphp
            <div class="json-output">{{ jsonProducts($topProductsArray) }}</div>
        </div>

        <footer>
            <p>Data Produk dari tanggal {{ dateFormatter($startDate) }} - {{ dateFormatter($endDate) }}</p>
            <p>&copy; Web Programming | Studi Kasus Toko Online</p>
        </footer>
    @endif
</body>

</html>
