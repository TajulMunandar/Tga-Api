<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>

    <!-- Link ke Google Fonts untuk font yang lebih menarik -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* Mengatur font secara global */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }

        /* Menambahkan padding pada bagian halaman */
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        /* Heading halaman */
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }

        /* Styling untuk tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
            font-weight: 500;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Menambahkan efek hover pada baris tabel */
        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Styling untuk link action */
        a {
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
        }

        a:hover {
            color: #0056b3;
        }

        /* Responsif untuk perangkat mobile */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            table th, table td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Laporan Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Layanan Laundry</th>
                    <th>Jumlah Bayar</th>
                    <th>Tanggal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->customer->name }}</td>
                        <td>{{ $transaction->laundryRate->service_name }}</td>
                        <td>{{ $transaction->amount_paid }}</td>
                        <td>{{ $transaction->created_at }}</td>
                        <td><a href="{{ route('management.transactions.edit', $transaction->id) }}">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
