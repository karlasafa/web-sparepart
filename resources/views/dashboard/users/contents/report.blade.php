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
    @if (empty($users))
        <h1>Tidak ada Data</h1>
    @else
        <h1>Daftar Pengguna</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->status ? 'Aktif' : 'Tidak Aktif' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <footer>
            <p>Data Pengguna dari tanggal {{ dateFormatter($startDate) }} - {{ dateFormatter($endDate) }}</p>
            <p>&copy; Web Programming | Studi Kasus Toko Online</p>
        </footer>
    @endif
</body>

</html>
