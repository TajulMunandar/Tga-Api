import 'package:flutter/material.dart';

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
      appBar: AppBar(title: const Text('Dashboard Pelanggan')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildCard(
              context,
              title: 'Pesan Laundry',
              routeName: '/pesan',
            ),
            _buildCard(
              context,
              title: 'Data Diri',
              routeName: '/profile',
            ),
            _buildCard(
              context,
              title: 'Informasi Pesanan',
              routeName: '/informasi',
            ),
            // Bisa tambahkan opsi lain jika diperlukan
          ],
        ),
      ),
    );
  }

  // Fungsi untuk membuat card yang bisa ditekan
  Widget _buildCard(BuildContext context,
      {required String title, required String routeName}) {
    return Card(
      elevation: 5,
      margin: const EdgeInsets.symmetric(vertical: 8.0),
      child: InkWell(
        onTap: () {
          Navigator.pushNamed(context, routeName);
        },
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                title,
                style:
                    const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
              ),
              const Icon(Icons.arrow_forward_ios),
            ],
          ),
        ),
      ),
    );
  }
}
