import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class DashboardCashierScreen extends StatefulWidget {
  const DashboardCashierScreen({super.key});

  @override
  _DashboardCashierScreenState createState() => _DashboardCashierScreenState();
}

class _DashboardCashierScreenState extends State<DashboardCashierScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Dashboard Kasir')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Column(
          children: [
            ElevatedButton(
              onPressed: () {
                Navigator.pushNamed(context, '/view/customers');
              },
              child: Text('Lihat Data Pelanggan'),
            ),
            ElevatedButton(
              onPressed: () {
                Navigator.pushNamed(context, '/manage/stock');
              },
              child: Text('Kelola Stok Barang'),
            ),
            ElevatedButton(
              onPressed: () {
                Navigator.pushNamed(context, '/verify/orders');
              },
              child: Text('Verifikasi Pesanan Laundry'),
            ),
          ],
        ),
      ),
    );
  }
}
