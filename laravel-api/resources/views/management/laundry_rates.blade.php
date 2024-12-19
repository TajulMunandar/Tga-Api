<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tarif Laundry</title>
    <style>
        /* Mengatur latar belakang dan font */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Container untuk form */
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Judul halaman */
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        /* Pesan sukses */
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Label dan input */
        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
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
            transition: background-color 0.3s;
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
        <h2>Tambah Tarif Laundry</h2>

        <!-- Menampilkan pesan sukses jika ada -->
        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <!-- Form untuk menambah tarif laundry -->
        <form action="{{ route('laundry.rate.add') }}" method="POST">
            @csrf

            <label for="service_name">Nama Layanan:</label>
            <input type="text" name="service_name" required>

            <label for="rate">Tarif:</label>
            <input type="number" name="rate" required step="0.01">

            <button type="submit">Tambah Tarif Laundry</button>
        </form>
    </div>

</body>
</html>
