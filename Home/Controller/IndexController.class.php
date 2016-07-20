<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){

      //获取方向栏目
      $map = array(
        "parent_id" =>
0,
      );
      $topCate = M("category")->where($map)->select();

    
      $this->assign("topCate", $topCate);
      $content = M("course")->order("id desc")->select();
      $this->assign("content", $content);

      $data=M("Category")->select();
      $data=$this->getMenu2($data);
      $this->assign("data", $data);

      // p($data);

      $this->display();
    }

  

}