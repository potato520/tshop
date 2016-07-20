<?php

/**
 * @Author: y
 * @Date:   2016-07-11 10:57:52
 * @Last Modified by:   y
 * @Last Modified time: 2016-07-14 20:34:27
 */

namespace Home\Controller;
use \Think\Controller;
use Think\Model;
class CourseListController extends Controller
{
	//课程列表页面
	public function index()
	{	
		if(IS_GET){
			$id = I("get.id");
			$title = M("course")->where("id = {$id}")->find();
			//获取大标题内容
			$this->assign("title", $title);
			// p($title);
			//获取文章所属栏目地址

			$catUrl = M("category")->where("id = {$title['category_id']}")->find();
			$catUrl2 = M("category")->where("id = {$catUrl['parent_id']}")->find();
			$url=array();
			$url['pname'] = $catUrl2['name'];
			$url['name'] = $catUrl['name'];
			$this->assign("url", $url);
			//获取课程列表
			// $model = new Model();
			// $sql = "SELECT * FROM curr AS cu LEFT JOIN course AS co ON co.id=cu.course_id WHERE co.id={$id}";
			// $list = $model->query($sql);
			$list = M("curr")->where("course_id = {$title['id']}")->select();
			// p($list);
			$this->assign("list", $list);
		}

		$this->display();
	}

	public function list2()
	{
		$this->display();
	}

	//课程详细页面
	public function video()
	{
		if(IS_GET){
			$id = I("get.id");
			$this->video = M("curr")->where("id = {$id}")->find();

		}
		
		$this->display();
	}


}