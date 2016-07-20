<?php
/**
 * @Author: potato520
 * @Date:   2016-07-19 23:31:03
 * @Last Modified by:   potato520
 * @Last Modified time: 2016-07-20 22:00:34
 */

namespace Admin\Controller;
use \Think\Controller;
class GoodsController extends Controller
{
	#商品列表
	public function index()
	{
		$data = M("Goods")->
order("id desc")->select();
		$this->assign("data", $data);
		// dump($data);

		$this->display();
	}

	#添加商品
	public function add()
	{
		if(IS_POST){
			$data = M("goods")->create();
			#上传logo 返回data2 包含大logo图片和小logo图片
			$this->upLogo($data);
			#上传logo end

				#防止XSS攻击屏蔽js标签
			$data['goods_introduce'] = fangXSS(I("post.goods_introduce"));
			$data['add_time'] = $data['update_time'] = time();
			#数据添加成功会返回一个商品id 用来添加商品相册表goods_pics
			if($goods_id = M("goods")->add($data)){
				#上传商品相册
				$this->upPics($goods_id);

				$this->success("添加商品成功..");die;
			}else{
				$this->success("添加商品失败..");die;
			}

		}
		$this->display();
	}




	#修改商品
	public function updata()
	{
		if(IS_POST){
			$data = I("post.");
			// 单独获取商品简介
			$data['goods_introduce'] = fangXSS($_POST['goods_introduce']);
			$data['update_time'] = time();

			#编辑logo
			$this->upLogo($data);

			#修改完成返回 布尔值
			$count = M("goods")->where("id = {$data['id']}")->save($data);

			if($count){
				$this->upPics($data['id']);
				$this->success("修改商品成功..");die;
			}else{
				$this->success("修改商品失败..");die;
			}

			return;
		}
		#获取通用信息
		$goods_id = I("get.goods_id");
		$this->goods_find_data = M("goods")->where("id = {$goods_id}")->find();

		#获取商品相册
		$pic_data = M("goods_pics")->where("goods_id = {$goods_id}")->select();
		// p($pic_data);
		$this->assign("pic_data", $pic_data);
		$this->display();
	}


		#上传logo
	public function upLogo(&$data)
	{
		#存在logo图片上传附件
		if($_FILES['goods_logo']['error']==0){
			#如果有data中有id过来 提交就是要修改了
			if($data['id']){
				$find = M("goods")->where("id = {$data['id']}")->find();
				// p($find);
				#删除原来的图片
				unlink($find['goods_big_logo']);
				unlink($find['goods_small_logo']);
			}

			 $upload = new \Think\Upload();
			 $upload->maxSize   =     3145728 ;    
			 $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');

			 #设置图片上传根目录 
			 $upload->rootPath = "./Public/"; 

			 // 设置附件上传目录   
			 $upload->savePath  =      'Admin/Uploads/logo/';   

			 // 上传单个文件     
			 $info   =   $upload->uploadOne($_FILES['goods_logo']); 
			 // p($info);  
			  if(!$info) {   
			  	$this->error($upload->getError());    
			  }else{

			  // 上传成功 获取上传文件信息         
			  	$log_pic = $upload->rootPath . $info['savepath'].$info['savename'];

			  	$image = new \Think\Image(); 

			  	$image->open($log_pic);

			  	#组合缩略图名称
			  	$log_thumb_pic = $upload->rootPath . $info['savepath'] . "s_" . $info['savename'];

			  	$image->thumb(160, 160)->save($log_thumb_pic);  

			  	#将原图和缩略图的值赋值给$data
			    $data['goods_big_logo'] = $log_pic;
			    $data['goods_small_logo'] = $log_thumb_pic;  
			    return $data;
			  }
		}
	}




	#上传商品相册
	public function upPics($goods_id)
	{
		#检测是否有图片上传
		$ishave = false;
		// p($_FILES['goods_pics']['error']);die;

		foreach ($_FILES['goods_pics']['error'] as $v) {
			if($v == 0){
				$ishave = true;
			}
		}

		if($ishave){
				#实例化上传类
			$upload = new \Think\Upload();
			$upload->maxSize   =     3145728 ;
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');

			#设置上传根目录
			$upload->rootPath = "./Public/";

			#设置附件上传目录
			$upload->savePath  =      'Admin/Uploads/pics/'; 

			#上传文件 
			$info   =   $upload->upload(array($_FILES['goods_pics']));

			// dump($info);die;
			if(!$info) {
			    $this->error($upload->getError());
			 }else{

			#实例化缩略图类 
			$image = new \Think\Image(); 

			#上传成功 获取上传文件信息    
			 	foreach($info as $file){ 

			 		#获取到的原图地址  
			 		$orgin_path = $upload->rootPath . $file['savepath'].$file['savename']; 

			 		#打开图片
			 		$image->open($orgin_path);

			 		#组合缩略图名字（大图）
			 		$big = $upload->rootPath . $file['savepath'] . "b_" . $file['savename'];
			 		$image->thumb(800, 800)->save($big);

			 		#组合缩略图名字（中图）
			 		$m = $upload->rootPath . $file['savepath'] . "m_" . $file['savename'];
			 		$image->thumb(350, 350)->save($m);

			 		#组合缩略图名字（小图）
			 		$s = $upload->rootPath . $file['savepath'] . "s_" . $file['savename'];
			 		$image->thumb(54, 54)->save($s);

			 		$map = array(
			 			"goods_id" => $goods_id,
			 			"goods_pics_b" => $big,
			 			"goods_pics_m" => $m,
			 			"goods_pics_s" => $s,
			 			);
			 		D("goods_pics")->add($map);
			 	}
			}
		}
	}

	#ajax删除图片
	public function delpic()
	{
		$id = I("post.id");
		// 获取单套图片信息
		$info = D('goods_pics')->find($id);

		// 删除旧图片(删除物理图片)
		unlink($info['goods_pics_s']);
		unlink($info['goods_pics_m']);
		unlink($info['goods_pics_b']);

		
		// 删除数据库数据
		D('goods_pics')->delete($id);

		// 是数据以json格式返回
		$this->ajaxReturn(array('status'=>1,"msg"=>'ok'));
	}





}