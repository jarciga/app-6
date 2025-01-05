// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'refrigerant_list_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

RefrigerantList _$RefrigerantListFromJson(Map<String, dynamic> json) =>
    RefrigerantList(
      recordId: json['record_id'] as int?,
      deviceId: json['device_id'] as int?,
      vOutData: (json['vout_data'] as num?)?.toDouble(),
      vRefData: (json['vref_data'] as num?)?.toDouble(),
      vOutStatus: json['vout_status'] as String?,
      vRefStatus: json['vref_status'] as String?,
      alarmStatus: json['alarm_status'] as String?,
      recordTime: json['record_time'] as String?,
      refrigerant: json['refrigerant'] as String?,
      recommendation: json['recommendation'] as String?,
      message: json['message'] as String?,
    );

Map<String, dynamic> _$RefrigerantListToJson(RefrigerantList instance) =>
    <String, dynamic>{
      'record_id': instance.recordId,
      'device_id': instance.deviceId,
      'vout_data': instance.vOutData,
      'vref_data': instance.vRefData,
      'vout_status': instance.vOutStatus,
      'vref_status': instance.vRefStatus,
      'alarm_status': instance.alarmStatus,
      'record_time': instance.recordTime,
      'refrigerant': instance.refrigerant,
      'recommendation': instance.recommendation,
      'message': instance.message,
    };
