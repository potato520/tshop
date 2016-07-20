<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-07-10 17:44:18
 */

namespace Home\Model;
use \Think\Model;

class CourseModel extends Model
{
	protected $_validate = array(
		array("title", "require", "标题名称是必填项"),
		array("content", "require", "内容不能为空.."),
	);

	public function addCourse($data)
	{
		if($this->create($data)){
			$result = $this->add($data);
			return $result;
		}else{
			return false;
		}
	}


	public function getFindById($map)
	{
		$result = $this->where($map)->find();
		return $result;
	}

	public function modCourse($map, $data)
	{
		if(!$data = $this->create($data)){
			return false;
		}else{
			$result = $this->where($map)->save($data);
			return $result;
		}
	}

	public function delFindById($map)
	{
		$result = $this->where($map)->delete();
		return $result;
	}
}