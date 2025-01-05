import 'package:json_annotation/json_annotation.dart';

part 'device_model.g.dart';

@JsonSerializable()
class Device {
  @JsonKey(name: 'device_id')
  final String? deviceId;

  @JsonKey(name: 'name')
  final String? name;

  @JsonKey(name: 'type')
  final String? type;

  @JsonKey(name: 'description')
  final String? description;

  @JsonKey(name: 'create_date')
  final String? createDate;

  @JsonKey(name: 'update_date')
  final String? updateDate;

  //@JsonKey(name: 'message')
  final String? message;

  Device({
    required this.deviceId,
    required this.name,
    required this.type,
    required this.description,
    required this.createDate,
    required this.updateDate,
    required this.message
  });

  factory Device.fromJson(Map<String, dynamic> json) => _$DeviceFromJson(json);

  Map<String, dynamic> toJson() => _$DeviceToJson(this);
}