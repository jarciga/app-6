import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;

import 'package:iot_aircon_alarm_system/views/main_menu_screen_1.dart';

import 'package:iot_aircon_alarm_system/models/alarm_params_model.dart';
import 'package:iot_aircon_alarm_system/models/api.dart';

final api = Api();
final endPoint = api.endPoint();

//Read
//Endpoint: http://IP Address:port/iot_aircon_alarm_system_backend/public/?req=alarmParams&deviceId=$deviceId
Future<AlarmParams> fetchAlarmParams(String? ipAddress, int? deviceId) async {
  final String baseUrl = 'http://$ipAddress';
  //final response = await http.get(Uri.parse('$endPoint/?req=alarmParams&deviceId=$deviceId'));
  final response = await http.get(Uri.parse('$baseUrl$endPoint/?req=alarmParams&deviceId=$deviceId'));

  if (response.statusCode == 200) {
    return AlarmParams.fromJson(jsonDecode(response.body));
  } else {
    throw Exception('Failed to load alarm parameters.');
  }
}

//Create/Update
//Endpoint: http://IP Address:port
Future<AlarmParams> createOrUpdateAlarmParams(String? ipAddress, String? userId, String? deviceId, String? temperature, String? current) async {
  final String baseUrl = 'http://$ipAddress';
  //final response = await http.post(Uri.parse('$endPoint/alarmParams.php'),
  final response = await http.post(Uri.parse('$baseUrl$endPoint/alarmParams.php'),
      body: { //Correct
        'userId': userId,
        'deviceId': deviceId,
        'temperature': temperature,
        'current': current,
      }

  );

  if (response.statusCode == 200) {
    return AlarmParams.fromJson(jsonDecode(response.body));
  } else {
    throw Exception('Failed to create alarm parameters.');
  }
}

class SetupAlarmScreen extends StatefulWidget {
  final String? ipAddress;
  final int? deviceIdSelected;
  final int? userId;
  const SetupAlarmScreen({Key? key,required this.ipAddress, required this.userId, required this.deviceIdSelected})
      : super(key: key);

  @override
  State<SetupAlarmScreen> createState() => _SetupAlarmScreenState();
}

class _SetupAlarmScreenState extends State<SetupAlarmScreen> {
  final TextEditingController _temperatureController = TextEditingController();
  final TextEditingController _currentController = TextEditingController();

  String? temperatureValue = "";
  String? currentValue = "";
  late Future<AlarmParams> _futureFetchAlarmParams;
  Future<AlarmParams>? _futureCreateOrUpdateAlarmParams;

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

  @override
  void initState() {
    // TODO: implement initState
    super.initState();
    initIPAddress();
    _futureFetchAlarmParams = fetchAlarmParams(widget.ipAddress, widget.deviceIdSelected);
    _getLoginUserName();
    _getLoginUserId();
    _getLoginGroupId();
  }

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
  void dispose() {
    // TODO: implement dispose
    _temperatureController.dispose();
    _currentController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        resizeToAvoidBottomInset: false,
        appBar: AppBar(
          leading: const Icon(Icons.alarm_add),
          title: const Text('Set Alarm'),
          actions: <Widget>[
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
          child: FutureBuilder<AlarmParams>(
              future: _futureFetchAlarmParams,
              builder: (context, snapshot) {
                if (snapshot.hasData) {
                  if (snapshot.data!.message == 'Update') { //From Fetch;
                    _temperatureController.text = snapshot.data!.temperature.toString();
                    _currentController.text = snapshot.data!.current.toString();
                    return Container(
                      alignment: Alignment.center,
                      padding: const EdgeInsets.all(30.0),
                      child:(_futureCreateOrUpdateAlarmParams == null) ? buildColumnCreateOrUpdateAlarmParams() : buildFutureBuilderCreateOrUpdateAlarmParams(),
                    );
                  } else if (snapshot.data!.message == 'Insert') { //Insert
                    return Container(
                      alignment: Alignment.center,
                      padding: const EdgeInsets.all(30.0),
                      child:(_futureCreateOrUpdateAlarmParams == null) ? buildColumnCreateOrUpdateAlarmParams() : buildFutureBuilderCreateOrUpdateAlarmParams(),
                    );
                  }
                } else if (snapshot.hasError) {
                  return Text('${snapshot.error}');
                }
                return const CircularProgressIndicator();
              }
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

  Column buildColumnCreateOrUpdateAlarmParams() {
    return Column(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: <Widget>[
        Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: <Widget>[
            TextField(
              controller: _temperatureController,
              keyboardType: TextInputType.text,
              decoration: InputDecoration(
                hintText: "Enter Temperature",
                labelText: "Temperature",
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(15),
                ),
              ),
            ),
            const SizedBox(height: 15.0),
            TextField(
              controller: _currentController,
              keyboardType: TextInputType.text,
              decoration: InputDecoration(
                hintText: "Enter Current",
                labelText: "Current",
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(15),
                ),
              ),
            ),
            const SizedBox(height: 30.0),
            ElevatedButton(
              onPressed: () {
                setState(() {
                  _futureCreateOrUpdateAlarmParams = createOrUpdateAlarmParams(widget.ipAddress, widget.userId.toString(), widget.deviceIdSelected.toString(), _temperatureController.text, _currentController.text);
                });
              },
              child: Text('SAVE',
                style: TextStyle(fontSize: 16.0,
                ),
              ),
              style: ElevatedButton.styleFrom(
                padding: const EdgeInsets.all(21.0),
              ),
            ),
          ],
        ),
        const SizedBox(width: double.infinity, height: 15.0),
      ],
    );
  }

  FutureBuilder<AlarmParams> buildFutureBuilderCreateOrUpdateAlarmParams() {
    return FutureBuilder<AlarmParams>(
      future: _futureCreateOrUpdateAlarmParams,
      builder: (context, snapshot) {
        if (snapshot.hasData) {
          return Column(
            children: <Widget>[
              Text(snapshot.data!.message.toString(), style: TextStyle(color: Colors.white,),),
              const SizedBox(height: 30.0,),
              ElevatedButton.icon(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) =>
                          SetupAlarmScreen(ipAddress: widget.ipAddress, userId: widget.userId,
                              deviceIdSelected: widget.deviceIdSelected),
                    ),
                  );
                },
                icon: const Icon(
                  Icons.login,
                  size: 36.0,
                ),
                label: const Text('Go back to the Setup Alarm page'),
              ),
            ],
          );
        } else if (snapshot.hasError) {
          return Text('${snapshot.error}');
        }
        return const CircularProgressIndicator();
      },
    );
  }
}
