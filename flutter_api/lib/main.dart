import 'package:flutter/material.dart';
import 'screens/register_cashier_screen.dart';
import 'screens/register_customer_screen.dart';
import 'screens/login_cashier_screen.dart';
import 'screens/login_customer_screen.dart';

void main() {
  runApp(MyApp());
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
        '/': (context) => MyHomePage(),
        '/register/cashier': (context) => RegisterCashierScreen(),
        '/register/customer': (context) => RegisterCustomerScreen(),
        '/login/cashier': (context) => LoginCashierScreen(),
        '/login/customer': (context) => LoginCustomerScreen(),
      },
    );
  }
}

class MyHomePage extends StatelessWidget {
  const MyHomePage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Laundry App')),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            ElevatedButton(
              onPressed: () {
                Navigator.pushNamed(context, '/register/cashier');
              },
              child: Text('Register Kasir'),
            ),
            ElevatedButton(
              onPressed: () {
                Navigator.pushNamed(context, '/register/customer');
              },
              child: Text('Register Pelanggan'),
            ),
            ElevatedButton(
              onPressed: () {
                Navigator.pushNamed(context, '/login/cashier');
              },
              child: Text('Login Kasir'),
            ),
            ElevatedButton(
              onPressed: () {
                Navigator.pushNamed(context, '/login/customer');
              },
              child: Text('Login Pelanggan'),
            ),
          ],
        ),
      ),
    );
  }
}
