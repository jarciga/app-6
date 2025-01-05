import 'package:json_annotation/json_annotation.dart';

part 'alarm_params_model.g.dart';

@JsonSerializable()
class AlarmParams {
  @JsonKey(name: 'alarm_id')
  final int? alarmId;

  //@JsonKey(name: 'name')
  final String? name;

  //@JsonKey(name: 'device_id')
  final int? deviceId;

  //@JsonKey(name: 'user_id')
  final int? userId;

  //@JsonKey(name: 'temperature')
  final double? temperature;

  //@JsonKey(name: 'current')
  final double? current;

  //@JsonKey(name: 'humidity')
  final double? humidity;

  //@JsonKey(name: 'message')
  final String? message;

  AlarmParams({
    required this.alarmId,
    required this.name,
    required this.deviceId,
    required this.userId,
    required this.temperature,
    required this.current,
    required this.humidity,
    required this.message
  });

  factory AlarmParams.fromJson(Map<String, dynamic> json) => _$AlarmParamsFromJson(json);

  Map<String, dynamic> toJson() => _$AlarmParamsToJson(this);
}