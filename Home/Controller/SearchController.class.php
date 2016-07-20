<?php
/**
 * @Author: Weak1
 * @Date:   2016-07-16 01:03:45
 * @Last Modified by:   Weak1
 * @Last Modified time: 2016-07-17 18:21:32
 */
namespace Home\Controller;
use Think\Controller;
class SearchController extends Controller
{
	public function index()
	{
		$sphinxapi = "./Component/sphinxapi.php";
		$a=include $sphinxapi;
		var_dump($a);
		if(IS_POST){
			$data = I("post.data");
			$list = M("course")->where("title like '%{$data}%' OR summary like '%{$data}%'")->select();
			$this->assign("list", $list);
			// p($list);
		}
		$this->display();
	}
}