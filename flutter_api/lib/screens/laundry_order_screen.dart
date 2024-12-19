import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class LaundryOrderScreen extends StatefulWidget {
  const LaundryOrderScreen({super.key});

  @override
  _LaundryOrderScreenState createState() => _LaundryOrderScreenState();
}

class _LaundryOrderScreenState extends State<LaundryOrderScreen> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _laundryDetailsController = TextEditingController();
  final TextEditingController _pickupDateController = TextEditingController();

  bool _isPickup = false;  // Menentukan apakah pelanggan memilih antar jemput

  Future<void> submitOrder() async {
    if (_formKey.currentState!.validate()) {
      final response = await http.post(
        Uri.parse('http://127.0.0.1:8000/api/management/laundry-rates'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode({
          'laundry_details': _laundryDetailsController.text,
          'pickup_date': _pickupDateController.text,
          'pickup': _isPickup ? 'yes' : 'no',
        }),
      );

      if (response.statusCode == 200) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Pesanan Laundry berhasil!')));
        // Menampilkan bukti pembayaran atau langkah berikutnya
      } else {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Gagal membuat pesanan laundry!')));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Pesan Laundry')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              SwitchListTile(
                title: Text('Pilih Fasilitas Antar Jemput'),
                value: _isPickup,
                onChanged: (bool value) {
                  setState(() {
                    _isPickup = value;
                  });
                },
              ),
              TextFormField(
                controller: _laundryDetailsController,
                decoration: InputDecoration(labelText: 'Detail Laundry'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Detail laundry harus diisi';
                  }
                  return null;
                },
              ),
              TextFormField(
                controller: _pickupDateController,
                decoration: InputDecoration(labelText: 'Tanggal Pickup'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Tanggal pickup harus diisi';
                  }
                  return null;
                },
              ),
              SizedBox(height: 20),
              ElevatedButton(
                onPressed: submitOrder,
                child: Text('Pesan Laundry'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
