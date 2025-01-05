import 'package:json_annotation/json_annotation.dart';

part 'user_group_model.g.dart';

@JsonSerializable()
class UserGroup {
  @JsonKey(name: 'group_id')
  final String? groupId;

  @JsonKey(name: 'name')
  final String? name;

  @JsonKey(name: 'description')
  final String? description;

  //@JsonKey(name: 'message')
  final String? message;

  UserGroup({
    required this.groupId,
    required this.name,
    required this.description,
    required this.message
  });

  factory UserGroup.fromJson(Map<String, dynamic> json) => _$UserGroupFromJson(json);

  Map<String, dynamic> toJson() => _$UserGroupToJson(this);
}