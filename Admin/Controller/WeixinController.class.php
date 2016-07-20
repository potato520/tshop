<?php
/**
 * @Author: y
 * @Date:   2016-07-09 18:05:13
 * @Last Modified by:   y
 * @Last Modified time: 2016-07-12 00:23:00
 */
namespace Admin\Controller;
use \Think\Controller;
class WeixinController extends Controller
{
	private $appid = "wxc3ec3fc5c2957492";
	private $appsecret = "850c69b5ee23fe828cc2e33f9bc61819";
	private $filename = "./Public/Admin/weixin.txt";
	private $filePic = "./Public/Admin/qrcode.jpg";

	// public function __construct()
	// {

	// }

	//curl函数的封装
  public function request($url,$https=true,$method='get',$data=null){
    //1.初始化url
    $ch = curl_init($url);
    //2.设置相关的参数
    //字符串不直接输出,进行一个变量的存储
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //判断是否为https请求
    if($https === true){
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    //判断是否为post请求
    if($method == 'post'){
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //3.发送请求
    $str = curl_exec($ch);
    //4.关闭连接
    curl_close($ch);
    //返回请求到的结果
    return $str;
  }


	//获取token
	public function getToken()
	{
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;

		#返回json字符串获取里面的access_token 数据
		$data = $this->request($url);
		$data = json_decode($data);

		$access_token = $data->access_token;
		// echo $access_token;
		file_put_contents($this->filename, $access_token);
	}

	//获取缓存中的 token
	public function getCacheToken()
	{
		$access_token = file_get_contents($this->filename);
		// echo $access_token;
		return $access_token;
	}

	//获取 ticket  [1.获取ticket 码来换取二维码]
	public function getTicket($tmp=0, $scene_id="123")
	{
		//获取ticket 秘钥的url 是通过post 方式请求
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->getCacheToken();
		// echo $url;

		if($tmp == '1'){
      		$data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
    	}else{
     		 $data = '{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
   		}

		//通过post 方式发送请求数据
		$content = $this->request($url, true, "post", $data);
		$content = json_decode($content);

		//获取ticket
		return $content->ticket;
	}

	//生成二维码 [2.通过以上方法获取到的ticket 生成二维码]
	public function getCode()
	{
		$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$this->getTicket();
		// echo $this->getTicket();
		$data = $this->request($url);
		file_put_contents($this->filePic, $data);
	}

	//生成菜单
	public function createMenu()
	{
		//使用 post协议
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->getCacheToken();
		$data =  '{
    "button": [
        {
            "name": "扫码", 
            "sub_button": [
                {
                    "type": "scancode_waitmsg", 
                    "name": "扫码带提示", 
                    "key": "rselfmenu_0_0", 
                    "sub_button": [ ]
                }, 
                {
                    "type": "scancode_push", 
                    "name": "扫码推事件", 
                    "key": "rselfmenu_0_1", 
                    "sub_button": [ ]
                }
            ]
        }, 
        {
            "name": "发图", 
            "sub_button": [
                {
                    "type": "pic_sysphoto", 
                    "name": "系统拍照发图", 
                    "key": "rselfmenu_1_0", 
                   "sub_button": [ ]
                 }, 
                {
                    "type": "pic_photo_or_album", 
                    "name": "拍照或者相册发图", 
                    "key": "rselfmenu_1_1", 
                    "sub_button": [ ]
                }, 
                {
                    "type": "pic_weixin", 
                    "name": "微信相册发图", 
                    "key": "rselfmenu_1_2", 
                    "sub_button": [ ]
                }
            ]
        }, 
        {
            "name": "发送位置", 
            "type": "location_select", 
            "key": "rselfmenu_2_0"
        }
    ]
}';


	 	$data = $this->request($url, true, "post", $data);
	 	$data = json_decode($data);
	 	$status = $data->errmsg;

	 	if($status == "ok"){
	 		echo "生成菜单成功..";
	 	}else{
	 		echo "生成菜单失败,错误代码" . $this->$data->errcode;
	 	}
	}

	//删除菜单
	public function delMenu()
	{
		//请求方式get
		$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$this->getCacheToken();
		$data = $this->request($url);
		$data = json_decode($data);
	 	$status = $data->errmsg;

	 	if($status == "ok"){
	 		echo "删除菜单成功..";
	 	}else{
	 		echo "删除菜单失败,错误代码" . $this->$data->errcode;
	 	}
	}

	//查询菜单
	public function selectMenu()
	{
		//请求方式get
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this->getCacheToken();
		$data = $this->request($url);
		$data = json_decode($data);
		p($data);
		p($data->menu->button[0]->name);
		p($data->menu->button[1]->name);
	}

	//获取用户列表
	public function getUserList()
	{
		// 请求方式get
		$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->getCacheToken()."&next_openid=";
		$data = $this->request($url);
		$data = json_decode($data, true);

		//获取用户列表
		$list = $data['data']['openid'];
		$this->assign("list", $list);
		$this->display();


	}

	public function getUserConn()
	{

		if(IS_GET)
		{
			$openid = I("get.openid");
			echo $openid;

			//请求方式get
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->getCacheToken()."&openid=".$openid."&lang=zh_CN";

			$data = $this->request($url);
			$data = json_decode($data, true);
			// p($data);
			echo "用户名：".$data['nickname']."<br />";
			echo "用户名：".$data['sex']."<br />";
			echo "用户名：".$data['city']."<br />";
			echo "用户名：".$data['province']."<br />";
			echo "用户名：".$data['country']."<br />";
			echo '用户名：<img src="'.$data['headimgurl'].'" > <br />';
			// $this->assign("data", $data);
			// $this->display();
		}

	
	}


}