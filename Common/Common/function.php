<?php
/**
 * @Author: y
 * @Date:   2016-06-29 17:00:59
 * @Last Modified by:   potato520
 * @Last Modified time: 2016-07-20 14:09:01
 */

// 自动加载的函数


function p2($res){
	echo "<pre>";
  print_r($res);
  echo "</pre>";
}


function node_merge($node, $access=null, $pid = 0){
	$arr = array();

	foreach ($node as $key => $v) {
		if(is_array($access)){
			$v['access'] = in_array($v['id'], $access) ? 1 : 0;

		}
		if($v['pid'] == $pid){
			$v['child'] = node_merge($node, $access, $v['id']);
			$arr[] = $v;
		}
	}
	return $arr;
}


//curl函数的封装
function request($url, $https=true, $method="get", $data=null){
	//1.初始化url
	$ch = curl_init($url);
	//2.设置相关的参数
	//字符串不能直接输出，进行一个变量的存储
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//判断是否为https 请求
	if($https === true){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	}
	//3.发送请求
	$str = curl_exec($ch);
	curl_close($ch);
	return $str;
}     

  

#封装邮件操作方法
/**
*@param $subject 信件标题
*@param $msghtml 信件内容
*@param $sendAddress 收件人邮箱
**/
function sendMail($subject,$msghtml,$sendAddress){
  //因为如果发送到的地址已经为空了，我们就没必要去加载类文件
  if($sendAddress == ''){
    //邮件为空，返回false;
    return false;
  }
  //引入发送类phpmailer.php
  include './Component/class.phpmailer.php';
  //实列化对象
  $mail             = new PHPMailer();
  /*服务器相关信息*/
  $mail->IsSMTP();                        //设置使用SMTP服务器发送
  $mail->SMTPAuth   = true;               //开启SMTP认证
  $mail->Host       = 'smtp.163.com';         //设置 SMTP 服务器,自己注册邮箱服务器地址
  $mail->Username   = 'woai281';      //发信人的邮箱用户名
  $mail->Password   = 'itcastphp123';          //发信人的邮箱密码
  /*内容信息*/
  $mail->IsHTML(true);               //指定邮件内容格式为：html
  $mail->CharSet    ="UTF-8";          //编码
  $mail->From       = 'woai281@163.com';       //发件人完整的邮箱名称
  $mail->FromName   ="php47的商城";      //发信人署名
  $mail->Subject    = $subject;         //信的标题
  $mail->MsgHTML($msghtml);           //发信主体内容
  // $mail->AddAttachment("fish.jpg");      //附件
  /*发送邮件*/
  $mail->AddAddress($sendAddress);        //收件人地址
  //使用send函数进行发送
  if($mail->Send()) {
      //发送成功返回真
      return true;
     // echo '您的邮件已经发送成功！！！';
  } else {
     return  $mail->ErrorInfo;//如果发送失败，则返回错误提示
  }
}     



// 防止xss攻击函数
function fangXSS($string) {
  require_once './Public/Admin/Plugin/htmlpurifier/HTMLPurifier.auto.php';
  // 生成配置对象
  $config = HTMLPurifier_Config::CreateDefault();
  // 配置$
  $config ->set('Core.Encoding' , "UTF-8");
  // 设置允许html的标签
  $config->set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width,height|alt|src]');
  // 设置允许的css样式
  $config->set('CSS.AllowedProperties','font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
  // 设置a标签上的target=“_blank”
  $config->set('HTML.targetBlack',TRUE);
  // 使用配置初始化对象
  $hpf = new HTMLPurifier($config);
  // 过滤字符串
  return $hpf->purify($string);
}











