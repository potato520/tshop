<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-06-23 16:44:13
 */

namespace Admin\Model;
use \Think\Model;

class ArticleModel extends Model
{
	protected $_validate = array(
		array("title", "require", "标题名称是必填项"),
		array("content", "require", "内容不能为空.."),
	);

	public function addArticle($data)
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

	public function modArticle($map, $data)
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