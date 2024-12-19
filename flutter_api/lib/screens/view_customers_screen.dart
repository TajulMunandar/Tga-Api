import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class ViewCustomersScreen extends StatefulWidget {
  const ViewCustomersScreen({super.key});

  @override
  _ViewCustomersScreenState createState() => _ViewCustomersScreenState();
}

class _ViewCustomersScreenState extends State<ViewCustomersScreen> {
  List<dynamic> _customers = [];

  Future<void> fetchCustomers() async {
    final response = await http
        .get(Uri.parse('http://127.0.0.1:8000/api/management/customers'));

    if (response.statusCode == 200) {
      setState(() {
        _customers = json.decode(response.body);
      });
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal mengambil data pelanggan!')));
    }
  }

  @override
  void initState() {
    super.initState();
    fetchCustomers();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Data Pelanggan')),
      body: ListView.builder(
        itemCount: _customers.length,
        itemBuilder: (context, index) {
          return ListTile(
            title: Text(_customers[index]['name']),
            subtitle: Text(_customers[index]['email']),
          );
        },
      ),
    );
  }
}
