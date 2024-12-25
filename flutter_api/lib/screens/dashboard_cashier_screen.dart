import 'package:flutter/material.dart';

class DashboardCashierScreen extends StatefulWidget {
  const DashboardCashierScreen({super.key});

  @override
  _DashboardCashierScreenState createState() => _DashboardCashierScreenState();
}

class _DashboardCashierScreenState extends State<DashboardCashierScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Dashboard Kasir')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            _buildCard(
              context,
              icon: Icons.people,
              title: 'Lihat Data Pelanggan',
              description: 'Akses data pelanggan yang terdaftar.',
              onTap: () {
                Navigator.pushNamed(context, '/customer-data');
              },
            ),
            _buildCard(
              context,
              icon: Icons.inventory,
              title: 'Kelola Stok Barang',
              description: 'Periksa dan atur stok barang laundry.',
              onTap: () {
                Navigator.pushNamed(context, '/management-stock');
              },
            ),
            _buildCard(
              context,
              icon: Icons.receipt,
              title: 'Lihat Semua Transaksi',
              description: 'Lihat daftar semua transaksi yang telah dilakukan.',
              onTap: () {
                Navigator.pushNamed(context, '/transaksi-list');
              },
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCard(BuildContext context,
      {required IconData icon,
      required String title,
      required String description,
      required VoidCallback onTap}) {
    return Card(
      elevation: 4.0,
      margin: const EdgeInsets.symmetric(vertical: 8.0),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(8.0),
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Row(
            children: [
              Icon(icon, size: 40, color: Theme.of(context).primaryColor),
              const SizedBox(width: 16.0),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      title,
                    ),
                    const SizedBox(height: 4.0),
                    Text(
                      description,
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
