import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:laundry_app/screens/dashboard_cashier_screen.dart';
import 'package:laundry_app/screens/dashboard_customer_screen.dart';
import 'package:laundry_app/screens/information_pesanan_screen.dart';
import 'package:laundry_app/screens/pesan_laundry_screen.dart';
import 'package:laundry_app/screens/profile_screen.dart';
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

import 'package:laundry_app/screens/register_screen.dart';
import 'package:laundry_app/screens/view_customers_screen.dart';
import 'package:laundry_app/screens/view_stock_screen.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Laundry App',
      theme: ThemeData(primarySwatch: Colors.green),
      initialRoute: '/',
      routes: {
        '/': (context) => const LoginScreen(),
        '/register': (context) => const RegisterScreen(),
        '/customer-data': (context) => const ViewCustomersScreen(),
        '/management-stock': (context) => const ViewStockScreen(),
        '/profile': (context) => ProfileScreen(),
        '/informasi': (context) => InformasiPesananScreen(),
        '/pesan': (context) => ScreenPesanLaundry(),
      },
    );
  }
}

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  @override
  void dispose() {
    _usernameController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> login() async {
    if (_formKey.currentState!.validate()) {
      try {
        final response = await http.post(
          Uri.parse('http://127.0.0.1:8000/api/login'),
          headers: {'Content-Type': 'application/json'},
          body: json.encode({
            'username': _usernameController.text,
            'password': _passwordController.text,
          }),
        );

        if (response.statusCode == 200) {
          final responseBody = json.decode(response.body);
          final userRole = responseBody['data']['user']['role'];
          final profile = responseBody['data']['profile'];
          final token = responseBody['data']['token'];
          final user_id = responseBody['data']['user']['id'];

          SharedPreferences prefs = await SharedPreferences.getInstance();
          await prefs.setInt('user_id', user_id);
          await prefs.setString('token', token);

          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Login berhasil!')),
          );
          if (userRole == 2) {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(
                  builder: (context) => const DashboardCashierScreen()),
            );
          } else if (userRole == 3) {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(
                  builder: (context) => const DashboardCustomerScreen()),
            );
          } else {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Role tidak dikenali')),
            );
          }
        } else {
          final responseBody = json.decode(response.body);
          final errorMessage = responseBody['message'] ??
              'Gagal login! Cek username dan password.';
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Error: $errorMessage')),
          );
        }
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Terjadi kesalahan: $e')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Login')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              TextFormField(
                controller: _usernameController,
                decoration: const InputDecoration(labelText: 'Username'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Username harus diisi';
                  }
                  return null;
                },
              ),
              TextFormField(
                controller: _passwordController,
                decoration: const InputDecoration(labelText: 'Password'),
                obscureText: true,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Password harus diisi';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 20),
              ElevatedButton(
                onPressed: login,
                child: const Text('Login'),
              ),
              const SizedBox(height: 10),
              TextButton(
                onPressed: () {
                  Navigator.pushNamed(context, '/register');
                },
                child: const Text('Belum punya akun? Daftar di sini'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
