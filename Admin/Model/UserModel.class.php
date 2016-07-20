<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-06-30 22:17:55
 */
namespace Admin\Model;
use \Think\Model;

class UserModel extends Model
{
	protected $_validate = array(
		array("username", "require", "用户名是必填项"),
		array("username", "", "用户名已经存在", 0, "unique", 1),
		array('password_confirm','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
		// array("userpic", "require", "请上传头像"),
		
	);

	protected $_auto = array (         
		array('created_at','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
        array('last_login_time','time',3,'function'), // 对date字段在新增的时候写入当前时间戳
	);

	public function checkUser($username, $password)
	{
		$data=$this->where("username = '{$username}' AND password = '{$password}'")->find();
		if($data){
			$_SESSION['user'] = $data;
			// p($_SESSION);die;
			return true;
		}else{
			return false;
		}
	}

	public function addData($data)
	{
		 if(!$data = $this->create($data)){
		 	return false;
		 }
		 return $this->add($data);
	}


	public function getDataById($id)
	{
		$data = $this->find($id);
		return $data;
	}

	public function modData($map,$data)
	{
		//不修改IM
		if($data['password'] == ""){
			unset($data['password']);
			//删除重复密码
			unset($data['password_confirm']);
		}
		//不修改头像
		if($data['userpic'] == NULL){
			unset($data['userpic']);
		}

		// dump($data);die;
		if(!$data = $this->create($data)){
			return false;
		}else{
			
			return $this->where($map)->save($data);
		}
	}

	public function delData($id)
	{
		$result = $this->delete($id);
		return $result;
	}


}