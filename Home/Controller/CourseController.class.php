<?php
/**
 * @Author: y
 * @Date:   2016-07-02 14:11:28
 * @Last Modified by:   Weak1
 * @Last Modified time: 2016-07-16 00:25:50
 */

namespace Home\Controller;
use Think\Controller;
use Think\Model;
class CourseController extends BaseController {

  public function index()
  {
    if(IS_GET){

        $where = " 2>1 AND ";
        $id = isset($_GET['id']) ? $_GET['id'] : "0";

        if(isset($_GET['cid'])){
            $cid = $_GET['cid'];
            //分类 数据
            $where .= "category_id = {$cid}";
        }else{
            //方向 数据
            $data2 = M("category")->where("parent_id = {$id}")->select();
            $ids = array();
            foreach ($data2 as $key => $value) {
                $ids[$key] = $value['id'];
            }
            $ids = implode($ids, ",");
            // echo $ids;die;
            // echo $ids;
            $where .= " category_id IN ($ids)";
        }

        if(isset($_GET['status'])){
            $status = $_GET['status'];
            $where .= " AND status = {$status}";
        }
        // echo $where;
        $this->data2 = M("course")->where($where)->select();




         //获取分类
        $catList = M("Category")->where("parent_id = $id")->select();
        $arr1 = array("id"=>0 ,"name"=>"全部");
        array_unshift($catList, $arr1);
        // p($catList);
        $this->assign("catList", $catList);





    }

            //获取难度
        $slist = array(0=>"全部", 1=>"初级", 2=>"中级", 3=>"高级");
        $this->assign("slist", $slist);

    //获取课程方向
    $this->getCurrFX();
    $this->display();
  }

    // //获取分类
    // public function getCurrCate($list)
    // {
    //     $pid = $list[0]['id'];
    //     $this->currTwo = M("Category")->where("parent_id = {$pid}")->select();

    // }


       //获取所有课程方向
    public function getCurrFX($list)
    {
        $currOne = M("Category")->where("parent_id=0")->select();
        // p($currOne);
        return $this->currOne = M("Category")->where("parent_id=0")->select();
    }



}