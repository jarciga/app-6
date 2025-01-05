// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'config_setup_ip_address_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

ConfigSetupIpAddress _$ConfigSetupIpAddressFromJson(
        Map<String, dynamic> json) =>
    ConfigSetupIpAddress(
      configId: json['config_id'] as int?,
      ipAddress: json['ipAddress'] as String?,
      port: json['port'] as String?,
      directory: json['directory'] as String?,
      message: json['message'] as String?,
    );

Map<String, dynamic> _$ConfigSetupIpAddressToJson(
        ConfigSetupIpAddress instance) =>
    <String, dynamic>{
      'config_id': instance.configId,
      'ipAddress': instance.ipAddress,
      'port': instance.port,
      'directory': instance.directory,
      'message': instance.message,
    };
