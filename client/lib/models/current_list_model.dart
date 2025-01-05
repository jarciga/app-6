import 'package:json_annotation/json_annotation.dart';

part 'current_list_model.g.dart';

@JsonSerializable()
class CurrentList {
  @JsonKey(name: 'record_id')
  final int? recordId;

  @JsonKey(name: 'device_id')
  final int? deviceId;

  @JsonKey(name: 'amp_data')
  final double? ampData;

  @JsonKey(name: 'record_time')
  final String? recordTime;

  @JsonKey(name: 'recommendation')
  final String? recommendation;

  //@JsonKey(name: 'message')
  final String? message;

  CurrentList({
    required this.recordId,
    required this.deviceId,
    required this.ampData,
    required this.recordTime,
    required this.recommendation,
    required this.message
  });

  factory CurrentList.fromJson(Map<String, dynamic> json) => _$CurrentListFromJson(json);

  Map<String, dynamic> toJson() => _$CurrentListToJson(this);
}