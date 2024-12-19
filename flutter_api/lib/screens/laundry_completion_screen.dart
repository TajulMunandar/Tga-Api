import 'package:flutter/material.dart';

class LaundryCompletionScreen extends StatelessWidget {
  final String completionTime = "2024-12-20 15:00";

  const LaundryCompletionScreen({super.key});  // Contoh waktu selesai

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Waktu Selesai Laundry')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Column(
          children: [
            Text('Proses laundry Anda selesai pada: $completionTime'),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                Navigator.pop(context);
              },
              child: Text('Kembali ke Dashboard'),
            ),
          ],
        ),
      ),
    );
  }
}
