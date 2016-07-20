<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-06-06 15:46:15
 */
namespace vendor;
#单列模式 [ 验证码类 ]

// header("Content-Type:text/html;charset=utf-8");

class Code{
	//1私
	private static $obj;
	//宽度
	public $width=200;
	public $height=30;
	//高度
	//文字大小
	private $fontsize = 10;
	//验证码长度
	private $length = 4;
	//验证码资源画布
	private $Img_res;
	private $dataStr = "abacdefghijkmoprsuvwxyz23456789";
	//自定义背景颜色
	public $colour = "#ccc";

	//2私
	private function __construct($config = array())
	{
		$this->width = isset($config['width']) ? $config['width'] : $this->width;
		$this->height = isset($config['height']) ? $config['height'] : $this->height;
		$this->fontsize = isset($config['fontsize']) ? $config['fontsize'] : $this->fontsize;
		$this->length = isset($config['length']) ? $config['length'] : $this->length;
		$this->colour = isset($config['colour']) ? $config['colour'] : $this->colour;
		// session_start();
		//创建画布资源
		$this->create_res();
		// //添加验证码内容
		$this->add_text();
		//添加随机的点
		$this->add_t1();
		//干扰线
		$this->add_t2();

	}

	//3私
	private function __clone(){}

	//1公 (只能通过这个方法来创建对象)
	public static function getCode($config = array())
	{
		//对象不存在类中
		if(!self::$obj instanceof self){
			//创建一个对象
			self::$obj = new self($config);
		}

		return self::$obj;
	}

	

	//获取画布资源
	private function create_res()
	{
		$this->Img_res = imagecreatetruecolor($this->width,$this->height);
		//创建画布背景颜色

		//将 RGB 转换成 HEX
		$hexColor=$this->hex($this->colour);
		// print_r($hexColor);die;

		$bgcolor = imagecolorallocate($this->Img_res,$hexColor['red'],$hexColor['green'],$hexColor['blue']);
		//给背景填充颜色
		imagefill($this->Img_res, 0, 0 ,$bgcolor);
	}

	//添加水印内容
	private function add_text()
	{
		$code = "";
		//添加水印内容
		for ($i=0; $i <$this->length; $i++) { 
			$fontcolor = imagecolorallocate($this->Img_res,rand(0,120),rand(0,120),rand(0,120));
			$fontcontent =  substr($this->dataStr,rand(0,strlen($this->dataStr)),1);
			$code .= $fontcontent;
			$x = ($i*90/$this->length) +rand(5,10);
			$y = rand(5,10);
			//写入缩略图
			//strtoupper($fontcontent) 	转大写
		imagestring($this->Img_res,$this->fontsize,$x,$y,strtoupper($fontcontent),$fontcolor);
		}
		//存入session
		$_SESSION['code'] = strtoupper($code);
	}
		//增加随机的点
	private function add_t1()
	{
		for ($i=0; $i <200 ; $i++) { 
			$pointcolor = imagecolorallocate($this->Img_res,rand(50,200),rand(50,200),rand(50,200));
		imagesetpixel($this->Img_res, rand(1,99), rand(1,99), $pointcolor);
		}
	}

	//添加随机的干扰线
	private function add_t2()
	{
		for ($i=0; $i < 10; $i++) { 
		$linecolor = imagecolorallocate($this->Img_res,rand(80,220),rand(80,220),rand(80,220));
		imageline($this->Img_res,rand(1,99),rand(1,99),rand(1,59),rand(1,99),$linecolor);
		}
	}

	//显示验证码
	public function show()
	{
		header("Content-type:image/png");
		//输出图像
		imagejpeg($this->Img_res);
		imagedestroy($this->Img_res);
	}


	private function hex()
	{
		if ( $this->colour[0] == '#' ) { 
	        $this->colour = substr( $this->colour, 1 ); 
	    } 
	    if ( strlen( $this->colour ) == 6 ) { 
	        list( $r, $g, $b ) = array( $this->colour[0] . $this->colour[1], $this->colour[2] . $this->colour[3], $this->colour[4] . $this->colour[5] ); 
	    } elseif ( strlen( $this->colour ) == 3 ) { 
	        list( $r, $g, $b ) = array( $this->colour[0] . $this->colour[0], $this->colour[1] . $this->colour[1], $this->colour[2] . $this->colour[2] ); 
	    } else { 
	        return false; 
	    } 
	    $r = hexdec( $r ); 
	    $g = hexdec( $g ); 
	    $b = hexdec( $b ); 
	    return array( 'red' => $r, 'green' => $g, 'blue' => $b ); 
	}


}
//自定义宽度和高度
// $obj = Code::getCode(array("width"=>100,"length"=>4,"colour"=>"#f0f0f0"));
// //显示验证码
// $obj->show();
//测试 验证码是否存入session
