<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-size: 1rem;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #f9f9f9;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group select {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Transaksi</h2>
        <form action="{{ url('management/transactions/edit/' . $transaction->id) }}" method="POST">
            @csrf
            @method('POST')

            <div class="form-group">
                <label for="customer_id">Pelanggan:</label>
                <select name="customer_id" required>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @if($transaction->customer_id == $customer->id) selected @endif>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="laundry_rate_id">Layanan Laundry:</label>
                <select name="laundry_rate_id" required>
                    @foreach ($laundryRates as $laundryRate)
                        <option value="{{ $laundryRate->id }}" @if($transaction->laundry_rate_id == $laundryRate->id) selected @endif>
                            {{ $laundryRate->service_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="amount_paid">Jumlah Bayar:</label>
                <input type="number" name="amount_paid" value="{{ $transaction->amount_paid }}" required>
            </div>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
