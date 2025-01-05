import 'dart:async';
import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;

import 'package:iot_aircon_alarm_system/views/log_in_screen_1.dart';
//import 'package:iot_aircon_alarm_system/views/sensor_data_screen_1.dart';
import 'package:iot_aircon_alarm_system/views/main_menu_screen_1.dart';

import 'package:iot_aircon_alarm_system/models/device_model.dart';
//import 'package:iot_aircon_alarm_system/models/sensor_model.dart';
import 'package:iot_aircon_alarm_system/models/api.dart';

class RecommendationScreen extends StatefulWidget {
  //final int? userId;
  //final int? deviceIdSelected;
  final String title;
  final String recordId;
  final String recommendation;
  const RecommendationScreen({Key? key, required this.title, required this.recordId, required this.recommendation})
      : super(key: key);

  @override
  State<RecommendationScreen> createState() => _RecommendationScreenState();
}

class _RecommendationScreenState extends State<RecommendationScreen> {

  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    /*final json = JsonDecoder().convert(data);
    deviceDataItems = (json).map<Device>((data) {
      return Device.fromJson(data);
    }).toList();
    deviceDataValue = deviceDataItems[0].name;*/

    return SafeArea(
      child: Scaffold(
        resizeToAvoidBottomInset: false,
        appBar: AppBar(
          leading: const Icon(Icons.alarm_on),
          title: const Text('Alarm History - Recommendation'),
          /*actions: <Widget>[
            IconButton(
              onPressed: () {
                setState(() {
                  print('SIGN OUT');
                });
                Navigator.pushNamed(context, '/');
              },
              tooltip: 'Sign Out',
              icon: const Icon(Icons.logout),
            ),
          ],*/
        ),
        body: Center(
          child: Container(
            padding: const EdgeInsets.all(30.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              //crossAxisAlignment: CrossAxisAlignment.stretch,
              //mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: <Widget>[
                //Center(
                  //child: FutureBuilder<List<Sensor>>(
                  //child: Column(
                  Column(
                    children: <Widget>[
                      Icon(
                        Icons.alarm_on,
                        color: Colors.white,
                        size: 64.0,
                      ),
                      SizedBox(height: 30.0),
                      Text(widget.recommendation, textAlign: TextAlign.center, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 21.0, color: Colors.white,),),
                    ],
                  ),
                //),
                //const SizedBox(height: 15.0),
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
                  //Navigator.pushNamed(context, '/');
                  Navigator.pop(context);
                },
                icon: const Icon(
                  Icons.chevron_left,
                  size: 24.0,
                ),
                label: Text('Back to ${widget.title}: Alarm History'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
