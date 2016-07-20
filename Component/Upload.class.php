<?php
/**
 * @author 土豆 <[alexa456@163.com]>
 */
namespace vendor;
class Upload{
	//保存目录
	private $saveDir;

	//初始化构造函数 自动运行
	public function __construct($saveDir){
		$this->saveDir=$saveDir;
	}

	//得到保存目录
	public function getSaveDir(){
		return $this->saveDir;
	}

	//上传方法
	public function upload(){
		//获取统一的二维数组
		$file=$this->format();//1.
		//验证错误文件
		$file=$this->check($file);//2.
		//获取文件名移动
		return $file=$this->moveFile($file);

	}

	//获取文件名移动
	private function moveFile($file){
		foreach ($file as $key => $value) {
			$info=pathinfo($value['name']);
			$dirName=$this->saveDir;	//保存目录
			//文件名字
			$fileName = substr(md5($info['filename']),27).mt_rand(1,1000).$info['dirname'].$info['extension'];
			$newfileName = $dirName . $fileName;
			if(move_uploaded_file($value['tmp_name'], $newfileName)){
//				echo 'ok';
				 return  $fileName;
			}
		}
	}


	//验证错误
	private function check($file){
		$data=array();
		foreach ($file as $key => $value) {
			if($value['error']!=0)continue;
			if(!is_uploaded_file($value['tmp_name']))continue;
			$data[]=$value;
		}
		return $data;
	}

	private function format(){
		$data=array();
		$num=0;
		foreach ($_FILES as $key => $value) {
			// return print_r($_FILES);
			if(is_array($value['name'])){
				foreach ($value['name'] as $id => $v) {
					 // echo $value['name'][$id];//数组的每一个值
					$data[$num]['name']=$value['name'][$id];
					$data[$num]['tmp_name']=$value['tmp_name'][$id];
					$data[$num]['type']=$value['type'][$id];
					$data[$num]['error']=$value['error'][$id];
					$data[$num]['size']=$value['size'][$id];
					$num++;
				}
			}else{
				$data[$num]=$value;
				$num++;
			}
		}
		return $data;
	}
}