import 'package:json_annotation/json_annotation.dart';

part 'config_setup_ip_address_model.g.dart';

@JsonSerializable()
class ConfigSetupIpAddress {
  @JsonKey(name: 'config_id')
  final int? configId;

  //@JsonKey(name: 'ip_address')
  final String? ipAddress;

  //@JsonKey(name: 'port')
  final String? port;

  //@JsonKey(name: 'directory')
  final String? directory;

  //@JsonKey(name: 'message')
  final String? message;

  ConfigSetupIpAddress({
    required this.configId,
    required this.ipAddress,
    required this.port,
    required this.directory,
    required this.message
  });

  factory ConfigSetupIpAddress.fromJson(Map<String, dynamic> json) => _$ConfigSetupIpAddressFromJson(json);

  Map<String, dynamic> toJson() => _$ConfigSetupIpAddressToJson(this);
}