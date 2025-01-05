import 'package:json_annotation/json_annotation.dart';

part 'refrigerant_list_model.g.dart';

@JsonSerializable()
class RefrigerantList {
  @JsonKey(name: 'record_id')
  final int? recordId;

  @JsonKey(name: 'device_id')
  final int? deviceId;

  @JsonKey(name: 'vout_data')
  final double? vOutData;

  @JsonKey(name: 'vref_data')
  final double? vRefData;

  @JsonKey(name: 'vout_status')
  final String? vOutStatus;

  @JsonKey(name: 'vref_status')
  final String? vRefStatus;

  @JsonKey(name: 'alarm_status')
  final String? alarmStatus;

  @JsonKey(name: 'record_time')
  final String? recordTime;

  @JsonKey(name: 'refrigerant')
  final String? refrigerant;

  @JsonKey(name: 'recommendation')
  final String? recommendation;

  //@JsonKey(name: 'message')
  final String? message;

  RefrigerantList({
    required this.recordId,
    required this.deviceId,
    required this.vOutData,
    required this.vRefData,
    required this.vOutStatus,
    required this.vRefStatus,
    required this.alarmStatus,
    required this.recordTime,
    required this.refrigerant,
    required this.recommendation,
    required this.message
  });

  factory RefrigerantList.fromJson(Map<String, dynamic> json) => _$RefrigerantListFromJson(json);

  Map<String, dynamic> toJson() => _$RefrigerantListToJson(this);
}