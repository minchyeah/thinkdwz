<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |		 lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------

class Page {
	
	// 分页栏每页显示的页数
	public $rollPage = 3;
	// 页数跳转时要带的参数
	public $parameter  ;
	// 分页URL地址
	public $url	 =   '';
	// 默认列表每页显示行数
	public $listRows = 20;
	// 起始行数
	public $firstRow	;
	// 分页总页面数
	public $totalPages  ;
	// 总行数
	public $totalRows  ;
	// 当前页数
	public $nowPage	;
	// 分页显示定制
	protected $config  =	array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
	// 默认分页变量名
	public $varPage;
	//上一页地址
	public $prePage = '';
	//下一页地址
	public $nextPage = '';

	/**
	 * 架构函数
	 * @access public
	 * @param array $totalRows  总的记录数
	 * @param array $listRows  每页显示记录数
	 * @param array $parameter  分页跳转的参数
	 */
	public function __construct($totalRows,$listRows='',$parameter='',$url='') {
		$this->totalRows	=   $totalRows;
		$this->parameter	=   $parameter;
		$this->url			=	$url;
		$this->varPage	  =   C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
		if(!empty($listRows)) {
			$this->listRows =   intval($listRows);
		}
		$this->totalPages   =   ceil($this->totalRows/$this->listRows);	 //总页数
		$this->nowPage	  =   !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
		if($this->nowPage<1){
			$this->nowPage  =   1;
		}elseif(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
			$this->nowPage  =   $this->totalPages;
		}
		$this->firstRow	 =   $this->listRows*($this->nowPage-1);
	}

	public function setConfig($name,$value) {
		if(isset($this->config[$name])) {
			$this->config[$name]	=   $value;
		}
	}

	/**
	 * 分页显示输出
	 * @access public
	 */
	public function show() {
		if(0 == $this->totalRows) return '';
		$p			  =   $this->varPage;

		// 分析分页参数
		if($this->url){
			if(false === strpos($this->url, '__PAGE__')){
				$depr	   =   C('URL_PATHINFO_DEPR');
				$url		=   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
			}else{
				$url 		=	$this->url;
			}
		}else{
			if($this->parameter && is_string($this->parameter)) {
				parse_str($this->parameter,$parameter);
			}elseif(is_array($this->parameter)){
				$parameter	  =   $this->parameter;
			}elseif(empty($this->parameter)){
				unset($_GET[C('VAR_URL_PARAMS')]);
				$var =  !empty($_POST)?$_POST:$_GET;
				if(empty($var)) {
					$parameter  =   array();
				}else{
					$parameter  =   $var;
				}
			}
			$parameter[$p]  =   '__PAGE__';
			$url			=   U('',$parameter);
		}
		//上下翻页字符串
		$upRow		  =   $this->nowPage-1;
		$downRow		=   $this->nowPage+1;
		if ($upRow>0){
		    $this->prePage = str_replace('__PAGE__',$upRow,$url);
			$upPage	 =   "<a href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
		}else{
			$upPage	 =   '';
		}

		if ($downRow <= $this->totalPages){
		    $this->nextPage = str_replace('__PAGE__',$downRow,$url);
			$downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>";
		}else{
			$downPage   =   '';
		}
		// << < > >>
		if($this->nowPage-$this->rollPage > 1){
			$theFirst   =   "<a href='".str_replace('__PAGE__',1,$url)."' >".$this->config['first']."</a>";
			$prePage	=	'...';
		}else{
			$theFirst   =   '';
			$prePage	=	'';
		}
		if($this->nowPage+$this->rollPage < $this->totalPages){
			$theEnd	 =   "<a href='".str_replace('__PAGE__',$this->totalPages,$url)."' >".$this->config['last']."</a>";
			$nextPage	=	'...';
		}else{
			$theEnd	 =   '';
			$nextPage	=	'';
		}
		// 1 2 3 4 5
		$linkPage = "";
		$i = $this->nowPage - $this->rollPage;
		$i = $i<1 ? 1 : $i;
		
		$j = $this->nowPage + $this->rollPage;
		$j = $j>$this->totalPages ? $this->totalPages : $j;
		for($i; $i<=$j; $i++){
			$page = $i;
			if($page!=$this->nowPage){
				if($page<=$this->totalPages){
					$linkPage .= "&nbsp;<a href='".str_replace('__PAGE__',$page,$url)."'>&nbsp;".$page."&nbsp;</a>";
				}else{
					break;
				}
			}else{
				if($this->totalPages != 1){
					$linkPage .= "&nbsp;<span class='current'>".$page."</span>";
				}
			}
		}
		$pageStr	 =   str_replace(
			array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
			array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
		return $pageStr;
	}

}