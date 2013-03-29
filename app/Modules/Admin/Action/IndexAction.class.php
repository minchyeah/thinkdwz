<?php

class IndexAction extends AdminAction
{
	private $menus = array(
			array(
				'id'=>1,
				'title'=>'管理首页',
				'subs'=>array(),
			),
			array(
				'id'=>2,
				'title'=>'用户管理',
				'subs'=>array(),
			),
			array(
				'id'=>3,
				'title'=>'分类管理',
				'subs'=>array(),
			),
			array(
				'id'=>4,
				'title'=>'内容管理',
				'subs'=>array(),
			),
			array(
				'id'=>5,
				'title'=>'管理首页',
				'subs'=>array(),
			),
		);
    public function index()
    {
    	$this->assign('topmenus', $this->menus);
        $this->display();
    }
    
    public function main()
    {
    	$this->display();
    }
    
    public function menu()
    {
    	$id = intval($_GET['id']);
    	$this->display();
    }
}
?>
