<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-06-26 21:42:36
 */
namespace Admin\Controller;
use \Think\Controller;

class CategoryController extends BaseController
{
	public function index()
	{
		$model = D("Category");
		//获取无限极分类
		$data = $model->catTree();

		//获取所属的父级分类
		foreach ($data as $key => $value) {
			#二次查询，通过查询pid所对应的数据信息，获取其中的name字段
			$info = D("Category") -> find($value['parent_id']);
			#获取其中的name字段存到$data中去
			$data[$key]['parentName'] = $info['name'];
		}
		$this->assign("data", $data);
		$this->display();
	}	

	public function add()
	{

		if(IS_POST){
			$data = I("post.");
			if(D("Category")->addData($data)){
            	$this->success('添加分类成功',U('Category/index'), 2);die;
			}else{
				$this->error(D("Category")->getError());
			}
		}

		//获取所有分类
		$cate = D("Category")->catTree();
		$this->assign("cate", $cate);
		$this->display();
	}

	public function mod()
	{
		$id = I("get.id");
		if(IS_POST){
			$data = I("post.");
			$id = I("post.id");
			$map = array(
				"id" => $id,
			);
			if(M("Category")->where($map)->save($data)){
            	$this->success('修改分类成功', U('Category/index'), 2);die;
			}else{
            	$this->error('修改分类成功', U('Category/index'), 2);die;
			}
		}


		$finData = M("Category")->find($id);
		$this->assign("finData", $finData);

		//获取所有分类
		$cate = D("Category")->catTree();
		$this->assign("cate", $cate);
		$this->display();
	}

	public function del()
	{
		$id = I("get.id");
		$map = array(
			"parent_id" => $id, 
		);
		//分类下面还有子栏目不能删除
		$result = D("Category")->delCategory($map, $id);
		if($result){
			//执行删除操作
			$this->success("栏目删除成功", U("Category/index"), 2);die;
		}else{
			$this->success("栏目删除失败，栏目下面或许还有子栏目", U("Category/index"), 2);die;
		}
	}
}