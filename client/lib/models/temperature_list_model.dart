import 'package:json_annotation/json_annotation.dart';

part 'temperature_list_model.g.dart';

@JsonSerializable()
class TemperatureList {
  @JsonKey(name: 'record_id')
  final int? recordId;

  @JsonKey(name: 'device_id')
  final int? deviceId;

  @JsonKey(name: 'temp_data')
  final double? tempData;

  @JsonKey(name: 'record_time')
  final String? recordTime;

  @JsonKey(name: 'recommendation')
  final String? recommendation;

  //@JsonKey(name: 'message')
  final String? message;

  TemperatureList({
    required this.recordId,
    required this.deviceId,
    required this.tempData,
    required this.recordTime,
    required this.recommendation,
    required this.message
  });

  factory TemperatureList.fromJson(Map<String, dynamic> json) => _$TemperatureListFromJson(json);

  Map<String, dynamic> toJson() => _$TemperatureListToJson(this);
}