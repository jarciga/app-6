import 'package:json_annotation/json_annotation.dart';

part 'vibration_list_model.g.dart';

@JsonSerializable()
class VibrationList {
  @JsonKey(name: 'record_id')
  final int? recordId;

  @JsonKey(name: 'device_id')
  final int? deviceId;

  @JsonKey(name: 'r_data')
  final double? rData;

  @JsonKey(name: 'record_time')
  final String? recordTime;

  @JsonKey(name: 'recommendation')
  final String? recommendation;

  //@JsonKey(name: 'message')
  final String? message;

  VibrationList({
    required this.recordId,
    required this.deviceId,
    required this.rData,
    required this.recordTime,
    required this.recommendation,
    required this.message
  });

  factory VibrationList.fromJson(Map<String, dynamic> json) => _$VibrationListFromJson(json);

  Map<String, dynamic> toJson() => _$VibrationListToJson(this);
}