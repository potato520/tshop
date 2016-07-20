<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-07-01 19:16:20
 */
namespace Admin\Controller;
use \Think\Controller;
// use \Component\Rbac;


class LoginController extends Controller
{
	#md 文件夹名称
	#echo 3>1.php
	#del 文件名
	public function login()
	{	
		// dump($_SESSION);die;

		if(IS_POST){
			$username = I("post.username");
			$password = md5(I("post.password"));
			$code = I("post.code");

			//检测验证码
			if(!$this->check_verify($code)){
				$this->error("验证码错误", U("Login/login"), 2);die;
			}
			$model = D("User");
			if($model->checkUser($username, $password)){

				//         $auth=new \Think\Auth();
// $rule_name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
// $result=$auth->check($rule_name,$_SESSION['user']['id']);
// if(!$result){
//     $this->error('您没有权限访问');
// }


				$this->success("登录成功", U("Index/index"), 2);
			}else{
				$this->error("登录失败用户名或密码错误", U("Login/login"), 2);
			}

			return;
		}
		if($_SESSION['user']!=""){
			$this->success("登录成功", U("Index/index"), 1);
		}
		$this->display();
	}

	public function code()
	{
		$config = array(
			"imageW" => "130px",
			"imageH" => "40px",
			"fontSize" => 18,
			"length" => 4,
			"useCurve" => false,
			// "useImgBg" =>true,
		);
		$_SESSION=array();
		$Verify = new \Think\Verify($config);

		$Verify->entry();
	}


		//验证验证码方法
	public function check_verify($code)
	{ 
		$verify = new \Think\Verify();  
		return $verify->check($code);
	}
	
	public function loginOut()
	{
		//删除session
		$_SESSION = array();
		//删除session文件
		session_destroy();   
		$this->success("退出成功", U("Login/login"), 2);die;
	}





/**
	以下是练习
**/


	
	public function show1()
	{
		$str = "abcdeg";
		$this->assign("str", $str);
		echo time();
		echo "<br>";
		echo U("show2");
		echo "<br>";
		echo U("Index/show2");
		echo "<br>";
		echo U("Home/Login/show2");
		echo "<br>";
		echo "下面是url传参,请注意！URL_MODEL 这个配置会影响U方法生成的结果";
		echo "<br>";
		echo U("Admin/Test/index", array("id"=>10086, "pid"=>110));
		echo "<hr>";
		
		echo "模版中的常量替换机制在模版中有些内置的常量在经过模版引擎的处理时候会被转化成真实表示内容：";
		echo "<br>";
		echo "<br>";
		echo "__MODULE__, 表示从入口文件开始向后，一直找到分组为止：". __MODULE__;
		echo "<br>";
		echo "<br>";
		echo "__CONTROLLER__, 表示从入口文件开始，一直找到控制器为止：" . __CONTROLLER__;
		echo "<br>";
		echo "<br>";
		echo "__ACTION__, 表示从入口文件开始，一直找到方法为止：" . __ACTION__;
		echo "<br>";
		echo "<br>";
		echo "__SELF__, 表示从入口文件开始， 一直找到最后（包括参数）" . __SELF__;
		echo "<br>";
		echo "<br>";
		echo "__PUBLIC__, 表示根目录下面的Public目录地址：" ;
		echo 123;
	
		$this->display();

	}
	
	public function defin()
	{
		$this->success("服务器挂了", U("show1"), 3);
	}
}