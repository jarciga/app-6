import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;

import 'package:iot_aircon_alarm_system/views/main_menu_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/temperature_list_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/humidity_list_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/current_list_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/refrigerant_list_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/vibration_list_screen_1.dart';

import 'package:iot_aircon_alarm_system/models/sensor_model.dart';
import 'package:iot_aircon_alarm_system/models/api.dart';

final api = Api();
final endPoint = api.endPoint();

// Single or one record
//Endpoint: http://IP Address:port/iot_aircon_alarm_system_backend/public/?req=sensor&deviceId=$deviceId
Future<Sensor> fetchSensor(String? ipAddress, int? deviceId) async {
  final String baseUrl = 'http://$ipAddress';
  //final response = await http.get(Uri.parse('$endPoint/?req=sensor&deviceId=$deviceId')); //Single or one record need to change to req=sensor&limit=1 resource
  final response = await http.get(Uri.parse('$baseUrl$endPoint/?req=sensor&deviceId=$deviceId'));
  print(response);

  if (response.statusCode == 200) {
    return Sensor.fromJson(jsonDecode(response.body));
  } else {
    throw Exception('Failed to load Sensor');
  }
}

class SensorDataScreen extends StatefulWidget {
  final String? ipAddress;
  final int? deviceIdSelected;
  final int? userId;
  const SensorDataScreen({Key? key, required this.ipAddress, required this.userId, required this.deviceIdSelected})
      : super(key: key);
  @override
  State<SensorDataScreen> createState() => _SensorDataScreenState();
}

class _SensorDataScreenState extends State<SensorDataScreen> {
  late Future<Sensor> futureSensor; // Single or one record

  /*
  * Begin: Logout
  */
  bool isLoggedIn = false;
  int? _userId = 0;
  String? _userName = '';
  int? _groupId = 0;
  String? _ipAddress = '';

  Future<void> _getLoginUserName() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _userName = prefs.getString('username');
    });
  }

  Future<void> _getLoginUserId() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _userId = prefs.getInt('userId');
    });
  }

  Future<void> _getLoginGroupId() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _groupId = prefs.getInt('groupId');
    });
  }

  Future<void> _removeLoginUserName() async {
    final prefs = await SharedPreferences.getInstance();
    prefs.remove('username');
    setState(() {
      _userId = prefs.getInt('userId');
      isLoggedIn = false;
    });
  }

  Future<void> _removeLoginUserId() async {
    final prefs = await SharedPreferences.getInstance();
    prefs.remove('userId');
  }

  Future<void> _removeLoginGroupId() async {
    final prefs = await SharedPreferences.getInstance();
    prefs.remove('groupId');
  }
  /*
  * End: Logout
  */

  Future<void> initIPAddress() async {
    final prefs = await SharedPreferences.getInstance();
    final ipAddress = prefs.getString('ipAddress');

    if (ipAddress != null) {
      setState(() {
        _ipAddress = ipAddress;
      });
    }
  }

  @override
  void initState() {
    super.initState();
    initIPAddress();
    //futureSensor = fetchSensor(_ipAddress, widget.deviceIdSelected); // Problem
    futureSensor = fetchSensor(widget.ipAddress, widget.deviceIdSelected); // Problem
    _getLoginUserName();
    _getLoginUserId();
    _getLoginGroupId();
  }



  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        resizeToAvoidBottomInset: false,
        appBar: AppBar(
          leading: const Icon(Icons.checklist),
          title: const Text('Sensor Data'),
          actions: <Widget>[
            Container(
              decoration: BoxDecoration(
                border: Border.all(color: Colors.lightBlueAccent),
                borderRadius: BorderRadius.circular(3.0),
              ),
              margin: const EdgeInsets.fromLTRB(7.0, 7.0, 7.0, 7.0),
              padding: const EdgeInsets.fromLTRB(7.0, 12.0, 7.0, 12.0),
              child: Text(
                'AIRCON ${widget.deviceIdSelected}',
                style: const TextStyle(fontWeight: FontWeight.bold),
              ),
            ),
            IconButton(
              onPressed: () {
                setState(() {
                  _removeLoginUserName();
                  _removeLoginUserId();
                  _removeLoginGroupId();
                });
                Navigator.pushNamed(context, '/');
              },
              tooltip: 'Sign Out',
              icon: const Icon(Icons.logout),
            ),
          ],
        ),
        body: Center(
          child: Container(
            padding: const EdgeInsets.fromLTRB(15.0, 5.0, 15.0, 0.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: <Widget>[
                FutureBuilder<Sensor>(
                  future: futureSensor,
                  builder: (context, snapshot) {
                    if (snapshot.hasData) {
                      final temperatureText =
                          snapshot.data!.temperature ?? '0.00';
                      final humidityText = snapshot.data!.humidity ?? '0.00';
                      final currentText = snapshot.data!.current ?? '0.00';
                      final refrigerantText =
                          snapshot.data!.refrigerantRecommendation ?? '-- No data --';
                      final vibrationText =
                          snapshot.data!.vibrationRecommendation ?? '-- No data --';
                      final deviceId = snapshot.data!.deviceId;
                      return Column(
                        children: [
                          Card(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.stretch,
                              children: <Widget>[
                                ListTile(
                                  leading: const Icon(
                                    Icons.thermostat,
                                    size: 64.0,
                                    color: Colors.black,
                                  ),
                                  title: const Text(
                                    'Temperature:',
                                    style: TextStyle(
                                      fontSize: 21.0,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.black,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                  subtitle: Text(
                                    //'28.5C',
                                    temperatureText.toString() + ' \u00B0' + 'C',
                                    style: const TextStyle(
                                      fontSize: 24.0,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                ),
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.end,
                                  children: <Widget>[
                                    TextButton(
                                      onPressed: () {
                                        setState(() {
                                          Navigator.push(
                                            context,
                                            MaterialPageRoute(
                                              builder: (context) =>
                                                  TemperatureListScreen(ipAddress: widget.ipAddress, userId: widget.userId,
                                                      deviceIdSelected:
                                                          deviceId),
                                            ),
                                          );
                                        });
                                      },
                                      child: const Text('See More \→'),
                                    ),
                                  ],
                                )
                              ],
                            ),
                          ),
                          Card(
                            child: Column(
                              //mainAxisSize: MainAxisSize.min,
                              children: <Widget>[
                                ListTile(
                                  leading: const Icon(
                                    Icons.water_drop,
                                    size: 64.0,
                                    color: Colors.black,
                                  ),
                                  title: const Text(
                                    'Humidity:',
                                    style: TextStyle(
                                      fontSize: 21.0,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.black,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                  subtitle: Text(
                                    //'85.0\%',
                                    //humidityText.toString()
                                    '$humidityText %',
                                    style: const TextStyle(
                                      fontSize: 24.0,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                ),
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.end,
                                  children: <Widget>[
                                    TextButton(
                                      onPressed: () {
                                        setState(() {
                                          Navigator.push(
                                            context,
                                            MaterialPageRoute(
                                              builder: (context) =>
                                                  HumidityListScreen(ipAddress: widget.ipAddress, userId: widget.userId,
                                                      deviceIdSelected:
                                                          deviceId),
                                            ),
                                          );
                                        });
                                      },
                                      child: const Text('See More \→'),
                                    ),
                                  ],
                                )
                              ],
                            ),
                          ),
                          Card(
                            child: Column(
                              children: <Widget>[
                                ListTile(
                                  leading: const Icon(
                                    Icons.flash_on,
                                    size: 64.0,
                                    color: Colors.black,
                                  ),
                                  title: const Text(
                                    'Current:',
                                    style: TextStyle(
                                      fontSize: 21.0,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.black,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                  subtitle: Text(
                                    //'0.5 A',
                                    //currentText.toString(),
                                    '$currentText A',
                                    style: const TextStyle(
                                      fontSize: 24.0,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                ),
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.end,
                                  children: <Widget>[
                                    TextButton(
                                      onPressed: () {
                                        setState(() {
                                          Navigator.push(
                                            context,
                                            MaterialPageRoute(
                                              builder: (context) =>
                                                  CurrentListScreen(ipAddress: widget.ipAddress, userId: widget.userId,
                                                      deviceIdSelected:
                                                          deviceId),
                                            ),
                                          );
                                        });
                                      },
                                      child: const Text('See More \→'),
                                    ),
                                  ],
                                )
                              ],
                            ),
                          ),
                          Card(
                            child: Column(
                              children: <Widget>[
                                ListTile(
                                  leading: const Icon(
                                    Icons.ac_unit,
                                    size: 64.0,
                                    color: Colors.black,
                                  ),
                                  title: const Text(
                                    'Refrigerant R-32:',
                                    style: TextStyle(
                                      fontSize: 21.0,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.black,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                  subtitle: Text(
                                    refrigerantText.toString(),
                                    style: const TextStyle(
                                      fontSize: 24.0,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                ),
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.end,
                                  children: <Widget>[
                                    TextButton(
                                      onPressed: () {
                                        setState(() {
                                          Navigator.push(
                                            context,
                                            MaterialPageRoute(
                                              builder: (context) =>
                                                  RefrigerantListScreen(ipAddress: widget.ipAddress, userId: widget.userId,
                                                      deviceIdSelected:
                                                          deviceId),
                                            ),
                                          );
                                        });
                                      },
                                      child: const Text('See More \→'),
                                    ),
                                  ],
                                )
                              ],
                            ),
                          ),
                          Card(
                            child: Column(
                              children: <Widget>[
                                ListTile(
                                  leading: const Icon(
                                    Icons.vibration,
                                    size: 64.0,
                                    color: Colors.black,
                                  ),
                                  title: const Text(
                                    'Vibration:',
                                    style: TextStyle(
                                      fontSize: 21.0,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.black,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                  subtitle: Text(
                                    vibrationText.toString(),
                                    style: const TextStyle(
                                      fontSize: 24.0,
                                    ),
                                    textAlign: TextAlign.center,
                                  ),
                                ),
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.end,
                                  children: <Widget>[
                                    TextButton(
                                      onPressed: () {
                                        setState(() {
                                          Navigator.push(
                                            context,
                                            MaterialPageRoute(
                                              builder: (context) =>
                                                  VibrationListScreen(ipAddress: widget.ipAddress, userId: widget.userId,
                                                      deviceIdSelected:
                                                          deviceId),
                                            ),
                                          );
                                        });
                                      },
                                      child: const Text('See More \→'),
                                    ),
                                  ],
                                )
                              ],
                            ),
                          ),
                        ],
                      );
                    } else if (snapshot.hasError) {
                      return Text('${snapshot.error}');
                    }
                    // By default, show a loading spinner.
                    return const Center(
                      child: CircularProgressIndicator(),
                    );
                  },
                ),
              ],
            ),
          ),
        ),
        bottomNavigationBar: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: <Widget>[
            Expanded(
              child: ElevatedButton.icon(
                style: ElevatedButton.styleFrom(padding: const EdgeInsets.only(top: 16.0, bottom: 16.0) ),
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => MainMenuScreen(ipAddress: widget.ipAddress, userId: widget.userId,
                          deviceIdSelected: widget.deviceIdSelected),
                    ),
                  );
                },
                icon: const Icon(
                  Icons.chevron_left,
                  size: 24.0,
                ),
                label: const Text('Back to Main Menu'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
