<?php
/**
 * @Author: y
 * @Date:   2016-06-29 23:44:48
 * @Last Modified by:   y
 * @Last Modified time: 2016-06-30 00:31:16
 */

namespace Admin\Model;
use Think\Model\RelationModel;

class UserRelationModel extends RelationModel
{
	//定义主表名称
	Protected $tableName = "user";

	//定义关联关系
	Protected $_link = array(
		"think_role" => array(
				"mapping_type" => MANY_TO_MANY,
				// 指定中间表名称
				"relation_table" => "think_role_user",
				"foreign_key" => "user_id",
				"relation_foreign_key" => "role_id"
			)
		);
}