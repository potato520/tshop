<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-06-30 22:01:13
 */

namespace Admin\Controller;
use \Think\Controller;
use \Component\Page;


class UserController extends BaseController
{
	//定义数据表
    private $db;


    //构造函数 实例化CategoryModel 并获得整张表的数据
    public function __construct(){
        parent::__construct();
        //初始化时实例化User model
        $this->db=D('User');
    }

	public function index()
	{

		$model = $this->db;
		$count = $model->order("id asc")->count();// 查询满足要求的总记录数
		$page = new Page($count, 10);
		$limi = $page->limit();

		$show = $page->show(2);
		$list = M("User")->limit($limi)->select();


		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}



	public function add()
	{
		if(IS_POST){
			$username = I("post.username");
			$password = I("post.password");
			$password_confirm = I("post.password_confirm");
	
			if(empty($username) || empty($password)){
				$this->error("管理员名称或密码不能为空", U("User/add"), 2);
			}
			if($password != $password_confirm){
				$this->error("两次密码不一致，请重新输入", U("User/add"), 2);
			}

			$data['username'] = I("post.username");
			$data['password'] = md5(I("post.password"));
			$data['userpic'] = I("post.userpic");
			$data['email'] = I("post.email");
		

			// $data['created_at'] = time();

			//上传头像
			$data2 = $this->upload();
			$data['userpic'] = $data2['filepath'];

			$model = D("User");

			//判断是否选择用户组
				$group_id = $_POST['group_id'];
				if($_POST['group_id']==0){
					$this->error("请选择用户组");die;
				}

			//添加用户成功后返回一个用户的主键id
			if($uid= $model->addData($data)){
				//将用户的主键id插入到用户权限表 [auth_group_access]
				$map = array(
					"uid" =>$uid,
					"group_id" =>$group_id,
					);
				M("auth_group_access")->data($map)->add();

				$this->success("添加用户成功..", U("User/index"), 2);die;
			}else{
				$this->error($model->getError());
			}

		
		}

		//获取权限组

		$usergroup = M('auth_group')->field('id,title')->select();
		$this->assign('usergroup',$usergroup);
		// p($usergroup);
		$this->display();
	}


	public function upload()
	{
		$conf =  array(
			"maxSize" => 3145728,
			"rootPath" => "Public/userPic/",		#保存的根目录
		);
		$upload = new \Think\Upload($conf);// 实例化上传类    

		$info   =   $upload->upload(); 
		if(!$info) {// 上传错误提示错误信息        
			// $this->error($upload->getError());   
		 }else{// 上传成功        
		  	#组合上传文件名称和路径
		  
		  	$data2['filepath'] = $conf['rootPath'] . $info['userpic']['savepath'] . $info['userpic']['savename'];

		  	//图片等比例缩放 star
		  	// $image = new \Think\Image(); 
		  	// $image->open($data2['filepath']);//将图片裁剪为400x400并保存为corp.jpg
		  	// $image->crop(400, 400)->save($data2['filepath']);
		  	//图片等比例缩放 end

		  	// $data2['filename'] = $info['thumb']['savename'];
		  	return $data2;
		}
	}


	public function mod()
	{
		//设置获取到的get id
		$id = I("get.id");
		//设置模型
		$model = D("User");
		if(IS_POST){
		$data = I("post.");
		//存在上传信息
		if(isset($_FILES['userpic']) && !empty($_FILES)){
			$data2 = $this->upload();
			$data['userpic'] = $data2['filepath'];
		}
		if(I("post.password") !=""){
			$data['password'] = md5(I("post.password"));
			$data['password_confirm'] = md5(I("post.password_confirm"));
		}

		// //不修改密码
		// if($data['password'] == ""){
		// 	unset($data['password']);
		// }
		// //不修改头像
		// if($data['userpic'] == ""){
		// 	unset($data['userpic']);
		// }
		// dump($data);die;
			$id = I("post.id");
            $map = array(
            	"id" => $id,
            );
			if($model->modData($map, $data)){
				$this->success("修改用户成功..", U("User/index"), 2);die;
			}else{
				$this->error($model->getError());
			}
		}


		//老的数据
		$oldData = $model->getDataById($id);
		$this->assign("oldData", $oldData);
		$this->display();
	}

	public function del()
	{
		$id = I("get.id");
		$model = D("User");
		if($model->delData($id)){
			$this->success("删除用户成功..", U("User/index"), 2);die;
		}else{
			$this->success("删除用户失败..", U("User/index"), 2);die;
		}
	}
}