import 'package:json_annotation/json_annotation.dart';

part 'sensor_model.g.dart';

@JsonSerializable()
class Sensor {
  @JsonKey(name: 'device_id')
  final int? deviceId;

  //@JsonKey(name: 'name')
  //final int? deviceName;

  @JsonKey(name: 'temperature')
  final double? temperature;

  @JsonKey(name: 'humidity')
  final double? humidity;

  @JsonKey(name: 'current')
  final double? current;

  @JsonKey(name: 'refrigerant_vout_data')
  final double? refrigerantVOutData;

  @JsonKey(name: 'refrigerant_vref_data')
  final double? refrigerantVRefData;

    @JsonKey(name: 'refrigerant_recommendation')
  final String? refrigerantRecommendation;

  @JsonKey(name: 'vibration')
  final double? vibration;

  @JsonKey(name: 'vibration_recommendation')
  final String? vibrationRecommendation;

  //@JsonKey(name: 'message')
  final String? message;

  Sensor({
    required this.deviceId,
    required this.temperature,
    required this.humidity,
    required this.current,
    required this.refrigerantVOutData,
    required this.refrigerantVRefData,
    required this.refrigerantRecommendation,
    required this.vibrationRecommendation,
    required this.vibration,
    required this.message
  });

  factory Sensor.fromJson(Map<String, dynamic> json) => _$SensorFromJson(json);

  Map<String, dynamic> toJson() => _$SensorToJson(this);
}