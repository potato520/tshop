<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-07-04 19:25:28
 */
namespace Admin\Controller;
use \Think\Controller;
class CourseController extends BaseController
{
	public function index()
	{

		$model = D("Course");
		//练习多表连接 star
		// $b=$model->field("Course.*,ca.name")->join("
		// 	LEFT JOIN category AS ca 
		// 	ON Course.category_id=ca.id")->select();
		// dump($b);
		//联系多表连接 end

		$count = $model->order("id asc")->count();// 查询满足要求的总记录数
		$Page  = new \Think\Page($count);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$Page->setConfig("prev","上一页");
        $Page->setConfig("next","下一页");
        $Page->setConfig("first","首页");
        $Page->setConfig("last","末页");
		$page->lastSuffix = false; // 最后一页是否显示总页数

        $Page->setConfig('theme',"<ul class='pagination'><li><a>共%TOTAL_ROW% %FIRST%条数据%NOW_PAGE%/%TOTAL_PAGE% 页</a></li><li>%FIRST%</li><li>%UP_PAGE%</li><li>%LINK_PAGE%</li><li>%DOWN_PAGE%</li><li>%END%</li></ul>");
        //前台展示
		$show  = $Page->show();// 分页显示输出

		//多表关联获取 [ 文章所属的栏目 ]
		$data = $model->limit($Page->firstRow.','.$Page->listRows)->field("Course.*,ca.name")->join("
			LEFT JOIN category AS ca 
			ON Course.category_id=ca.id")->select();
		$this->assign('data',$data);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出

		// p($data);
		$this->display();
	}

	public function add()
	{
		if(IS_POST){
			$data = I("post.");

			#判断是否有文件上传
			
			if(!empty($_FILES['thumb']['name'])){
				//上传附件 (获得文件名、文件路径)
				$data2 = $this->upload();
				$data['thumb'] = $data2['filepath'];
				// $data['filename'] = $data2['filename'];
			}

			$course_id = M("Course")->add($data);


			$title = $_REQUEST['class_title']; 
			$content = $_REQUEST['class_content']; 
			// p($title);
			// p($content);


			$arr = array();
			foreach ($title as $key => $value) {
				$arr[$value] = $content[$key];

			}
			$model = M("Curr");

			/**
			 * @param $key [ 问题标题 ]
			 * @param $value [ 问题内容 ]
			 * @param $course_id [ 关联父表返回的主键id ]
			 */

			foreach ($arr as $key => $value) {
				$model->class_title = $key;
				$model->class_content = $value;
				$model->course_id = $course_id;
				$model->add();
			}

			
			if($model){
				$this->success("课程添加成功..", U("Course/index"), 2);die;
			}else{
				$this->error(D("Course")->getError());

			}

			return;
		}

		//获取所有分类
		$cate = D("Category")->catTree();
		$this->assign("cate", $cate);
		$this->display();
	}


	public function upload()
	{
		$conf =  array(
			"maxSize" => 3145728,
			"rootPath" => "Public/thumb/",		#保存的根目录
		);
		$upload = new \Think\Upload($conf);// 实例化上传类    

		$info   =   $upload->upload(); 
		if(!$info) {// 上传错误提示错误信息        
			$this->error($upload->getError());   
		 }else{// 上传成功        
		  	#组合上传文件名称和路径
		  
		  	$data2['filepath'] = $conf['rootPath'] . $info['thumb']['savepath'] . $info['thumb']['savename'];

		  	//图片等比例缩放 star
		  	$image = new \Think\Image(); 
		  	$image->open($data2['filepath']);//将图片裁剪为400x400并保存为corp.jpg

		  	$width = $image->width();		#获取图片宽度
		  	$height = $image->height();		#获取图片的高度

		  	#判断图片大小再进行裁切
		  	if($width > 241 || $height > 136){
		  		// $image->crop(238, 186)->save($data2['filepath']);
		  		$image->thumb(240, 135,\Think\Image::IMAGE_THUMB_CENTER)->save($data2['filepath']);
		  	}

		  	//图片等比例缩放 end


		  	// $data2['filename'] = $info['thumb']['savename'];
		  	return $data2;
		}
	}

	//修改文章
	public function mod()
	{

		if(IS_POST)
		{
			$data = I("post.");
			
			#判断是否有文件上传
			
			if(!empty($_FILES['thumb']['name'])){
				//上传附件 (获得文件名、文件路径)
				$data2 = $this->upload();
				$data['thumb'] = $data2['filepath'];
				// $data['filename'] = $data2['filename'];
			}

			$id = I("post.id");
			$map = array(
				"id" => $id,
			);

			if(D("Course")->modCourse($map, $data)){
				$this->success("修改文章成功..", U("Course/index"), 2);die;
			}else{
				$this->error(D("Course")->getError());
			}

			return;
		}


		$id = I("get.id");
		$map = array(
			"id" => $id,
		);
		$data = D("Course")->getFindById($map);
		$this->assign("data", $data);

		//获取所有分类
		$cate = D("Category")->catTree();
		$this->assign("cate", $cate);

		$this->display();
	}


	public function del()
	{
		$id = I("get.id");
		$map = array(
			"id" => $id,
		);

		if(D("Course")->delFindById($map)){
			$this->success("删除文章成功..", U("Course/index"), 2);die;
		}else{
			$this->success("删除文章失败..", U("Course/index"), 2);die;
		}
	}
}