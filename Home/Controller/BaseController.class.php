<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-07-02 13:16:43
 */
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {

	//构造方法
	public function __construct()
	{
		//一定要调用父类的构造方法 Controller
		parent::__construct();

	}

	public function _initialize () {
        // $AUTH = new \Think\Auth();
        // //类库位置应该位于ThinkPHP\Library\Think\
        // if(!$AUTH->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME, session('id'))){
        // 	dump($session);die;
        //     $this->error('没有权限');
        // }


    }
	
	//验证是否登录
	public function checkLogin()
	{
		//检查session中有没有admin
		if(!$_SESSION['user']){
			$this->error("您还没有登录",U("Login/login"));die;
		}
	}

	// #空操作
	// public function _empty()
	// {
	// 	$this->error("非法操作", U("Index/index"), 2);
	// }



	protected function getMenu($items, $id = 'id', $pid = 'pid', $son = 'children')
    {
        $tree = array();
        $tmpMap = array();

        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }

        foreach ($items as $item) {
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        return $tree;
    }


    protected function getMenu2($items, $id = 'id', $pid = 'parent_id', $son = 'children')
    {
        $tree = array();
        $tmpMap = array();

        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }

        foreach ($items as $item) {
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        return $tree;
    }

    
    //对给定数组递归成树形 No.1
    public function tree($arr,$pid=0,$level=0)
    {
        static $tree = array();
        foreach ($arr as $v) {
            if($v['parent_id'] == $pid){
                $v['level'] = $level;  //保存当前分类的层级
                $tree[] = $v;
                //递归就使用主键cid，调用一次就把分类层级+1
                $this->tree($arr,$v['id'],$level+1);
            }
        }
        return $tree;
    }

}