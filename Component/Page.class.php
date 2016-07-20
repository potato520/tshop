<?php
/**
 * @Author [ Wake1 ]
 * @Email [ 573358951@qq.com ]
 * @Last Modified time: 2016-06-23 11:12:24
 */

namespace Component;

class Page{
	private $total;//总条数
	private $selfPage;//当前页
	private $totalPage;//总页数
	private $row;//每页显示条数
	private $url;//url地址（不包含页码）
	private $pageNum;//页码数量
	/**
	 * 构造函数
	 * @param [type] $total [总条数]
	 */
	public function __construct($total,$row=1,$pageNum=10){
		$this->total = $total;
		$this->row = $row;
		$this->totalPage=$this->getTotalPage();
		$this->selfPage=$this->getSelfPage();
		$this->url=$this->getUrl();
		$this->pageNum=$pageNum;
	}
	//获得url地址
	private function getUrl(){
		if(isset($_GET['page']))unset($_GET['page']);
		$url='?';
		foreach($_GET as $name=>$value){
			$url.=$name.'='.$value.'&';
		}
		return $url.'page=';
	}
	//获得当前页
	private function getSelfPage(){
		if(!isset($_GET['page']) || !intval($_GET['page'])){
			return 1;
		}else{
			return $_GET['page']<1?1:($_GET['page']>$this->totalPage?$this->totalPage:$_GET['page']);
		}
		
	}
	//用于返回sql查询的Limit部分
	public function limit(){
		//10 3  0,3  (2-1)*3
		return ($this->selfPage-1)*$this->row.",".$this->row;
	}
	//获得总页数
	private function getTotalPage(){
		//7  2  3.5
		return ceil($this->total/$this->row);
	} 
	//分页页码
	private function getPageList(){
		$start=$end=0;
		//当前页左右各取页码数量 7  8     7   6
		$n=$this->pageNum/2;
		if($this->selfPage-$n<1){
			$start=1;
			$end = min($this->pageNum,$this->totalPage);
		}else if($this->selfPage+$n>$this->totalPage){
			$start=max($this->totalPage-$this->pageNum,1);
			$end=$this->totalPage;
		}else{
			$start = $this->selfPage-$n;
			$end = $this->selfPage+$n;
		}
		$str='';
		for($p=$start;$p<=$end;$p++){
			if($this->selfPage==$p){
				$str.="<li class='am-active'><a href='#'>{$p}</a></li>";
			}else{
				$url = $this->url.$p;
				$str.="<li><a href='$url'>{$p}</a></li>";
			}
		}
		return $str;
	}
	//上一页
	private function getPre(){
		if($this->selfPage>1){
			return "<li><a href='".$this->url.($this->selfPage-1)."'>上一页</a></li>";
		}else{
			return "<li class='am-disabled'><a href='#'>上一页</a></li>";
		}
	}

		//首页
	private function getIndex()
	{
		//如果地址栏参数==1
		if($this->selfPage==1){
			return "<span>首页</span>";
		}else{
			return "<li><a href='".$this->url."'>首页</a></li>";
		}
	}


	//下一页
	private function getNext(){
		if($this->selfPage<$this->totalPage){
			return "<li><a href='".$this->url.($this->selfPage+1)."'>下一页</a></li>";
		}else{
			return "<li class='am-disabled'><a href='#'>下一页</a></li>";
		}
	}


	//尾页
	private function getLast()
	{
		//如果地址栏参数等于总页数
		if($this->selfPage==$this->totalPage){
			return "<li><span>尾页</span></li>";
		}else{
			return "<li><a href='".$this->url.$this->totalPage."'>尾页</a></li>";
		}
	}

	public function show($style=3){
		switch($style){
			case 1:
				return $this->getPageList();	
				break;
			case 2:
				return $this->getPre().$this->getPageList().$this->getNext();
				break;
			case 3:
				return $this->getIndex().$this->getPre().$this->getPageList().$this->getNext().$this->getLast();
				break;

		}
		
	}
}






