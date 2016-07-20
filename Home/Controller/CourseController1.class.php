<?php
/**
 * @Author: y
 * @Date:   2016-07-02 14:11:28
 * @Last Modified by:   y
 * @Last Modified time: 2016-07-15 20:15:10
 */

namespace Home\Controller;
use Think\Controller;
class CourseController extends BaseController {

  public function list2()
  {
        if(IS_GET){

            $s = I("get.s");
            $s = isset($_GET['s']) ? $_GET['s'] : false;

            if($s){
                $map = array(
                    "nickname" => $s,
                    );
            $list = M("Category")->where($map)->select();
            // p($list);
            // 获取所有分类 star
            $this->catAll = $list[0]['nickname'];
            // 获取所有分类 end

            #如果parent_id 为0表示是顶级分类，要获取当前分类下面的所有数据
            if($list[0]['parent_id'] == 0){
                //获取所有课程内容 [方向]
                $this->getCurrList($list);
            }else{
                echo "不是顶级分类<br>";
                #获取课程二级分类 [分类]

            }
            

   			//获取课程方向
            $this->getCurrFX();

            //获取分类
            $this->getCurrCate($list);

            // $this->getCatList($list);
            }else{
              $this->error("参数错误");
            }
        }

   	 $this->display("list");
    }

  	//获取分类
    public function getCurrCate($list)
    {
        $pid = $list[0]['id'];
        $this->currTwo = M("Category")->where("parent_id = {$pid}")->select();
    }

    //获取所有课程方向
    public function getCurrFX($list)
    {
        return $this->currOne = M("Category")->where("parent_id=0")->select();
    }

    //获取内容
    public function getCurrList($list)
    {
    	    $id = $list[0]['id'];
            // echo $id;
            // echo "<br>";

            $data = M("Category")->field("id")->where("parent_id={$id}")->select();
            // p($data);


            $sid= "";
            foreach ($data as $key => $value) {
                foreach ($value as $k => $v) {
                    $sid.= "category_id = $v OR ";
                }
            }
            $sid = rtrim($sid, " OR");
            $sid = explode(",", $sid);
            $currList = M("course")->where($sid[0])->select();

            //获取课程难度并显示(这里要循环)
              if($currList[0]['status'] ==1){
                $this->easy = "初级";
            }else if($currList['status'] ==2){
                $this->easy = "中级";
            }else{
                $this->easy = "高级";
            }
            // p($currList);
            $this->assign("list", $currList);
    }

    //获取分类内容
    public function getCatList($list)
    {
         $id = $list[0]['id'];
            // echo $id;
            // echo "<br>";
            $data = M("Category")->field("id")->where("parent_id={$id}")->select();
            // p($data);


            $sid= "";
            foreach ($data as $key => $value) {
                foreach ($value as $k => $v) {
                    $sid.= "category_id = $v OR ";
                }
            }
            $sid = rtrim($sid, " OR");
            $sid = explode(",", $sid);
            $currList = M("course")->where($sid[0])->select();

            // p($currList);
            $this->assign("list", $currList);
    }


    public function cat()
    {
            $s = I("get.s");
            $s = isset($_GET['s']) ? $_GET['s'] : false;
            $n = isset($_GET['n']) ? $_GET['n'] : false;
            if($s){
                $map = array(
                    "nickname" => $s,
                    );
            $list = M("Category")->where($map)->select();
            // p($list);

            // 获取所有分类 star
            $pr_id = $list[0]['parent_id'];
            $catAll = M("Category")->where("id=$pr_id")->find();
            $this->catAll = $catAll['nickname'];
            // 获取所有分类 end

            #如果parent_id 为0表示是顶级分类，要获取当前分类下面的所有数据
          
                //获取所有课程内容 [方向]
             $pid = $list[0]['parent_id'];
             $this->currTwo = M("Category")->where("parent_id = {$pid}")->select();

            //获取课程方向
            $this->getCurrFX();

            //获取内容
            $id = $list[0]['id'];

            $this->getConn($id, $n);

            }

        $this->display("list");

    }


    public function getConn($id, $n)
    {
        if(!empty($n)){
             $list = M("course")->where("category_id = {$id} && status = {$n}")->select();
            $this->assign("list", $list);

        }else{
            $list = M("course")->where("category_id = {$id}")->select();
            // p($list);
            //获取课程难度并显示
            if($list[0]['status'] ==1){
                $this->easy = "初级";
            }else if($list['status'] ==2){
                $this->easy = "中级";
            }else{
                $this->easy = "高级";
            }
            $this->assign("list", $list);
        }
       
        $this->url1 =  __SELF__."/n/1";
        $this->url2 =  __SELF__."/n/2";
        $this->url3 =  __SELF__."/n/3";
    }

}

?>