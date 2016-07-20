<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-06-23 11:50:02
 */
namespace Admin\Controller;
use \Think\Controller;

class CacheController extends BaseController
{
		//设置缓存
	public function s1()
	{
		S("name","tom");
		echo "success..";
	}
	//读取缓存
	public function s2()
	{
		echo S("name");
	}
	//删除缓存
	public function s3()
	{
		S("name", NULL);
		echo "删除缓存成功";
	}

	//设置缓存
	public function getCache()
	{
		$info = S("data");
		//存在缓存
		if($info){
			return $info;
		}else{
			//从数据库读取缓存
			// $data = M("article")->find();
			$data = "data数据" . time();

							#第三个参数是设置过期时间
			S("data", $data, 10);
			return $data;
		}
	}


	//获取缓存数据
	public function getData()
	{
		echo $this->getCache();
	}
}