import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:laundry_app/screens/laundry_order_screen.dart';

class DashboardCustomerScreen extends StatefulWidget {
  const DashboardCustomerScreen({super.key});

  @override
  _DashboardCustomerScreenState createState() =>
      _DashboardCustomerScreenState();
}

class _DashboardCustomerScreenState extends State<DashboardCustomerScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Dashboard Pelanggan')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ElevatedButton(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => LaundryOrderScreen()),
                );
              },
              child: Text('Pesan Laundry'),
            ),
            // Bisa tambahkan opsi lain jika diperlukan
          ],
        ),
      ),
    );
  }
}
