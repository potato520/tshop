<?php
/**
 * @Author: y
 * @Date:   2016-06-30 19:47:20
 * @Last Modified by:   y
 * @Last Modified time: 2016-07-02 13:25:52
 */

namespace Admin\Controller;
use \Think\Controller;
class GroupController extends BaseController
{
	public function index()
	{
		$list = M("auth_group")->select();
		$this->assign("list", $list);
		$this->display();
	}


	//添加用户的规则到用户组
	public function add()
	{

		if(IS_POST){
			//如果存在 用户组的名称，去除首位处的空白字符。
			$data['title'] = isset($_POST['title']) ? trim($_POST['title']) : false;
			$data['status'] = isset($_POST['status']) ? $_POST['title'] : 0;
			if($data['title']){
				if($data['status']){
					$data['status']=1;
				}else{
					$data['status']=0;
				}

				$rules = isset($_POST['rules']) ? $_POST['rules'] : false;
				//二维数组
				if(is_array($rules)){
					foreach ($rules as $key => $value) {
						//一位数组转换成字符串
						$rules[$k] = intval($v);
					}
					//变成字符串权限
					$rules = implode(',',$rules);
					$data['rules'] = $rules;
				}	
				//将权限存入数据
				if(M("auth_group")->add($data)){
					$this->success("权限组添加成功");
				}else{
					$this->error("权限组添加失败");
				}
			}else{
				$this->error("用户名不能为空");
			}


			return;
		}



		//获取所有用户规则
		$rule = M("auth_rule")->field("id,pid,title")->select();
		$rule = $this->getMenu($rule);
		// p($rule);
		$this->assign('rule',$rule);

		$this->display();
	}
}