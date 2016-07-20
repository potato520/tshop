<?php
/**
 * @Author: y
 * @Date:   2016-06-30 18:23:18
 * @Last Modified by:   y
 * @Last Modified time: 2016-06-30 19:44:22
 */

namespace Admin\Controller;
use \Think\Controller;

class MenuController extends BaseController
{
	public function index()
	{
		//获取所有分类
		$list = M("auth_rule")->select();
		// $list = $this->getMenu($list);
		$this->assign("list", $list);
		$this->display();
	}

	public function add()
	{
		if(IS_POST){
			$data = I("post.");
			M("auth_rule")->add($data);
			$this->success("添加菜单成功");die;
		}

		//获取所有分类
		$option = M("auth_rule")->select();
		$option = $this->getMenu($option);
		$this->assign("option", $option);
		$this->display();
	}
}