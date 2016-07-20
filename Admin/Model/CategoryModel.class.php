<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-06-20 19:18:39
 */

namespace Admin\Model;
use Think\Model;

class CategoryModel extends Model
{
	//自动验证
	protected $_validate = array(
		array("name", "require", "栏目名称是必填项"),
		array("name", "", "栏目名称已经存在", 0, "unique", 1)
	);

	//获取树状分类信息 N0.2
	public function catTree()
	{
		$cats = $this->order("id asc")->select();
		return $this->tree($cats);
	}


	//对给定数组递归成树形 No.1
	public function tree($arr,$pid=0,$level=0)
	{
		static $tree = array();
		foreach ($arr as $v) {
			if($v['parent_id'] == $pid){
				$v['level'] = $level;  //保存当前分类的层级
				$tree[] = $v;
				//递归就使用主键cid，调用一次就把分类层级+1
				$this->tree($arr,$v['id'],$level+1);
			}
		}
		return $tree;
	}



	//获取无限极分类 [单独]
    public function showCate($data, $level = 0, $parent_id = 0)
    {
        static $arr = array();
        foreach ($data as $key => $v) {
           if($v['parent_id'] == $parent_id){
                $v['level'] = $level;
                $arr[] = $v;
           $this->showcate($data, $level+1, $v['id']);

           }
        }
        return $arr;
    }

    public function addData($data)
    {
    	  // 对data数据进行验证
        if(!$data=$this->create($data)) return false;
        
        $result=$this->add($data);
        return $result;
    }

    public function delCategory($map, $id)
    {
    	$cou = $this->where($map)->select();
    	if(empty($cou)){
    		$result=$this->where("id = {$id}")->delete();
    		return $result;
    	}else{
    		return false;
    	}
    }

}