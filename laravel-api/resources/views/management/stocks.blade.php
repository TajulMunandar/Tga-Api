<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Stok</title>
    <style>
        /* Mengatur latar belakang halaman */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        /* Container untuk form */
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Judul halaman */
        h2 {
            text-align: center;
            color: #333;
        }

        /* Label dan input */
        label {
            font-size: 16px;
            color: #333;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #5cb85c;
            outline: none;
        }

        /* Tombol kirim */
        button {
            background-color: #5cb85c;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #4cae4c;
        }

        /* Responsif untuk perangkat mobile */
        @media (max-width: 768px) {
            .container {
                width: 80%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manajemen Stok Barang</h2>
        <form action="{{ route('stocks.add') }}" method="POST">
            @csrf
            <label for="item_name">Nama Barang:</label><br>
            <input type="text" name="item_name" required><br><br>
        
            <label for="quantity">Jumlah:</label><br>
            <input type="number" name="quantity" required><br><br>
        
            <button type="submit">Tambah Stok</button>
        </form>        
    </div>
</body>
</html>
