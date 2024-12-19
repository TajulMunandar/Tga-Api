import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class PaymentScreen extends StatefulWidget {
  @override
  _PaymentScreenState createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  final _amountController = TextEditingController();
  final _customerIdController = TextEditingController();
  final _laundryRateIdController = TextEditingController();
  bool _paymentSuccess = false;
  String? _receipt;

  Future<void> processPayment() async {
    final response = await http.post(
      Uri.parse('http://127.0.0.1:8000/api/management/transactions'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode({
        'customer_id': _customerIdController.text,
        'laundry_rate_id': _laundryRateIdController.text,
        'amount': _amountController.text,
      }),
    );

    if (response.statusCode == 201) {
      final data = json.decode(response.body);
      setState(() {
        _paymentSuccess = true;
        _receipt = data['receipt_number'];
      });
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text('Pembayaran berhasil!')));
    } else {
      final error =
          json.decode(response.body)['message'] ?? 'Terjadi kesalahan';
      ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal melakukan pembayaran: $error')));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Pembayaran')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: SingleChildScrollView(
          child: Column(
            children: [
              TextFormField(
                controller: _customerIdController,
                decoration: InputDecoration(labelText: 'ID Pelanggan'),
                keyboardType: TextInputType.number,
              ),
              SizedBox(height: 10),
              TextFormField(
                controller: _laundryRateIdController,
                decoration: InputDecoration(labelText: 'ID Tarif Laundry'),
                keyboardType: TextInputType.number,
              ),
              SizedBox(height: 10),
              TextFormField(
                controller: _amountController,
                decoration: InputDecoration(labelText: 'Jumlah Pembayaran'),
                keyboardType: TextInputType.number,
              ),
              SizedBox(height: 20),
              ElevatedButton(
                onPressed: processPayment,
                child: Text('Bayar'),
              ),
              if (_paymentSuccess && _receipt != null)
                Padding(
                  padding: const EdgeInsets.only(top: 20.0),
                  child: Text(
                    'Pembayaran berhasil! Bukti pembayaran:\n$_receipt',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    textAlign: TextAlign.center,
                  ),
                ),
            ],
          ),
        ),
      ),
    );
  }
}
