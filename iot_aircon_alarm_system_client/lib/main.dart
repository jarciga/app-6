import 'package:flutter/material.dart';

//import 'package:iot_aircon_alarm_system/views/setup_ip_address_1.dart';
import 'package:iot_aircon_alarm_system/views/log_in_screen_1.dart'; //Initial Screen


void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'IOT Aircon Alarm System',
      theme: ThemeData(
        // This is the theme of your application.
        //
        // Try running your application with "flutter run". You'll see the
        // application has a blue toolbar. Then, without quitting the app, try
        // changing the primarySwatch below to Colors.green and then invoke
        // "hot reload" (press "r" in the console where you ran "flutter run",
        // or simply save your changes to "hot reload" in a Flutter IDE).
        // Notice that the counter didn't reset back to zero; the application
        // is not restarted.
        brightness: Brightness.light,
        primarySwatch: Colors.indigo,
        scaffoldBackgroundColor: Colors.indigo.shade700,
        inputDecorationTheme: const InputDecorationTheme(
          labelStyle: TextStyle(color: Colors.white),
          hintStyle: TextStyle(color: Colors.white54),
          enabledBorder: OutlineInputBorder(
            borderSide: BorderSide(width: 1, color: Colors.white),
          ),
          focusedBorder: OutlineInputBorder(
            borderSide: BorderSide(width: 1, color: Colors.white),
          ),
        ),
        textSelectionTheme: const TextSelectionThemeData(
          cursorColor: Colors.white,
          selectionColor: Colors.blue,
          selectionHandleColor: Colors.blue,
        ),
        textTheme: const TextTheme(
          subtitle1: TextStyle(color: Colors.white),
        ),

      ),
      initialRoute: '/',
      routes: {
        '/': (context) => const LoginScreen(), // Default
        //'/': (context) => const SetupIPAddressScreen(), // Default
      },
    );
  }
}
