<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Kasir</title>
    <style>
        /* Mengatur latar belakang halaman */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Container untuk form */
        .container {
            width: 40%;
            margin: 50px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Judul halaman */
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Label dan input */
        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
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

        /* Pesan kesalahan */
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
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
        <h1>Register Kasir</h1>

        <!-- Menampilkan pesan kesalahan jika ada -->
        @if($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form untuk register kasir -->
        <form action="{{ route('register.cashier') }}" method="POST">
            @csrf

            <label for="name">Nama</label>
            <input type="text" name="name" required>

            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>

            <button type="submit">Daftar Kasir</button>
        </form>
    </div>

</body>
</html>
