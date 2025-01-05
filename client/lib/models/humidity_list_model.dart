import 'package:json_annotation/json_annotation.dart';

part 'humidity_list_model.g.dart';

@JsonSerializable()
class HumidityList {
  @JsonKey(name: 'record_id')
  final int? recordId;

  @JsonKey(name: 'device_id')
  final int? deviceId;

  @JsonKey(name: 'hmd_data')
  final double? hmdData;

  @JsonKey(name: 'record_time')
  final String? recordTime;

  @JsonKey(name: 'recommendation')
  final String? recommendation;

  //@JsonKey(name: 'message')
  final String? message;

  HumidityList({
    required this.recordId,
    required this.deviceId,
    required this.hmdData,
    required this.recordTime,
    required this.recommendation,
    required this.message
  });

  factory HumidityList.fromJson(Map<String, dynamic> json) => _$HumidityListFromJson(json);

  Map<String, dynamic> toJson() => _$HumidityListToJson(this);
}