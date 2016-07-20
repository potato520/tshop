<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-07-14 17:33:22
 */
namespace Admin\Controller;
use \Think\Controller;
class APIController extends Controller
{
	//将php 数组变成json 字符串
	public function createJSON()
	{
		$arr = array(
			"name" => "tom",
			"sex" => "女",
			"hobby" => "上网"
		);
		//把php 数组变成json 字符串
		$json = json_encode($arr);
		//p($json);
		return $json;

	}
	
	//可以把获取到的JSON 字符串转化成php 的对象或者数组
	public function getArrObj()
	{
		$json = $this->createJSON();
		//把json 字符串转成php 数组
		$arr = json_decode($json);
		dump($arr);
		
		//如果第二个参数为true 的话把json 字符串转成php 对象
		$obj = json_decode($json, true);
		dump($obj);
	}
	
	//获取手机号码归属地
	public function phone()
	{
		$phone = I("get.phone");
		$url = "http://cx.shouji.360.cn/phonearea.php?number=".$phone;
		
		$data = request($url, false);
		
		//对象方式获取数据
		$obj = json_decode($data);
		//dump($obj);
		echo "省份是：".$obj->data->province . "<br/>";
		echo "城市".$obj->data->city . "<br/>";
		echo "运营商：".$obj->data->sp . "<br/>";
		
		
		//获取到json 数据
		//$arrPhone = json_decode($data, true);
		//数组方式获取数据
		//echo "省份:" . $arrPhone[data]['province'] . "<br />";
		//echo "城市:" . $arrPhone[data]['city'] . "<br />";
		//echo "运营商：" . $arrPhone[data]['sp'] . "<br />";
	
	}
	
	//添加接口查询
	public function tianqi()
	{
		$city = I("get.city");
		$url = 'http://api.map.baidu.com/telematics/v2/weather?location='.$city.'&ak=B8aced94da0b345579f481a1294c9094';

		$data = request($url, false);

		//处理xml内容(解析XML内容)
		$obj = simplexml_load_string($data);
		//dump($obj);
		$todayobj= $obj->results->result[0]->date;
		$weather= $obj->results->result[0]->weather;
		$wind= $obj->results->result[0]->wind;
		
		$str = "星期：{$todayobj}\n天气：{$weather}\n 温度：{$wind}\n";
		echo $str;
		
	}

	//快递接口 【1】
	public function kuaidi($type, $postid)
	{

     // $type = 'yuantong';
     // $postid = '881443775034378914';
		// $url = "https://www.kuaidi100.com/query?type=".$type."&postid=".$postid."&id=1&valicode=&temp=0.18834344408132786";
		$url = 'https://www.kuaidi100.com/query?type='.$type.'&postid='.$postid.'&id=1&valicode=&temp=0.18834344408132786';
		$data = request($url);

		$data = json_decode($data);
		// dump($data);
		$data = $data->data;
		// p($data);
		foreach ($data as $key => $value) {
			echo $value->time ."#". $value->context . "<br>";
		}
	}

	//调用快递接口 【2】
	public function getKuaidi()
	{
		 $type = 'yuantong';
    	 $postid = '881443775034378914';
		$this->kuaidi($type, $postid);
	}


	//测试邮件发送方法
	public function Mail()
	{
		$title = "你好，老朋友!";	#发信标题
		$conn = "我是邮件内容"; #信件内容
		$addr = "573358951@qq.com";	#收件箱地址
		sendMail($title, $conn, $addr);
		// sendMail();
	}
	
	//用户注册发送邮件
	public function toSendMail()
	{
		if(IS_POST){
			$data = I("post.");
			$email = I("post.email");
			if($id = M("res_mail")->add($data)){

				$mailRs = sendMail("恭喜您！注册成功！欢迎使用紫霞用户中心", "请点击点击连接进行激活<a href='http://im.com/index.php/Admin/API/jihuo/id/{$id}'>点我激活</a>", $email);

				$tmp=1;	#注册成功后返回提示信息
				a:

				if($mailRs == true){
					$this->success("注册成功,邮件已经发送到您的邮箱",U("API/toSendMail"), 5);die;
				}else if($tmp ==2){
            		$this->error('已经发送了两次，我也没招儿了！！！',U('API/toSendMail'),5); die;
				}

				//邮件没有发送成功在发送了一次 $tmp=2  然后在走到了 133行 a 哪里，再判断一下$mailRs 的返回值是否等true
				$mailRs = sendMail("恭喜您！注册成功！欢迎使用紫霞用户中心", "请点击点击连接进行激活<a href='http://im.com/index.php/Admin/API/jihuo/id/{$id}'>点我激活</a>", $email);	
				$tmp = 2;
				// echo "我走到了第二次";dump($mailRs);
				goto a;

			}else{
				$this->error("注册失败");die;
			}

			return;
		}

		$this->display();
	}

	//激活操作
	public function jihuo()
	{
		$id = I("get.id");
		$res=M("res_mail")->where("id = {$id}")->setField("active", 1);
		if($res){
			$this->success("用户激活成功",U("API/toSendMail"));
		}else{
			$this->success("用户激活失败",U("API/toSendMail"));
		}
	}


	//高德地图
	public function map1()
	{
		$this->display();
	}
	

	//高德地图2
	public function map2()
	{
		$this->display();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}