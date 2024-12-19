import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class SendCompletionMessageScreen extends StatefulWidget {
  const SendCompletionMessageScreen({super.key});

  @override
  _SendCompletionMessageScreenState createState() =>
      _SendCompletionMessageScreenState();
}

class _SendCompletionMessageScreenState
    extends State<SendCompletionMessageScreen> {
  final _customerIdController = TextEditingController();
  bool _messageSent = false;

  Future<void> sendCompletionMessage() async {
    final customerId = _customerIdController.text;

    if (customerId.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('ID Pelanggan tidak boleh kosong!')));
      return;
    }

    final response = await http.post(
      Uri.parse('http://127.0.0.1:8000/api/customers/complete'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode({
        'customer_id': int.parse(customerId),
        'message': 'Laundry Anda telah selesai diproses.',
      }),
    );

    if (response.statusCode == 200) {
      setState(() {
        _messageSent = true;
      });
      ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Pesan selesai laundry telah dikirim!')));
    } else {
      final error = json.decode(response.body)['message'] ?? 'Terjadi kesalahan';
      ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal mengirim pesan: $error')));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Kirim Pesan Laundry Selesai')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Column(
          children: [
            TextFormField(
              controller: _customerIdController,
              decoration: InputDecoration(labelText: 'ID Pelanggan'),
              keyboardType: TextInputType.number,
            ),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: sendCompletionMessage,
              child: Text('Kirim Pesan Selesai'),
            ),
            if (_messageSent)
              Padding(
                padding: const EdgeInsets.only(top: 20.0),
                child: Text(
                  'Pesan berhasil dikirim!',
                  style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: Colors.green),
                ),
              ),
          ],
        ),
      ),
    );
  }
}
