<?php

class CasesAction extends HomeAction
{
	public function index()
	{
	    $model = M('Cases');
	    $where = array('state'=>1);
	    $total = $model->field('COUNT(1) count')->where($where)->select();
	    $total_count = intval($total[0]['count']);
	    $current_page = intval($_GET['page']);
	    $current_page = $current_page ? $current_page : 1;
	    $total_page = ceil($total_count/6);
	    $pre_page = '';
	    $next_page = '';
	    
	    $volist = $model->where($where)->limit(($current_page-1)*6, 6)->select();
	    
	    $this->assign('current_page', $current_page);
	    $this->assign('total_page', $total_page);
	    $this->assign('total_count', $total_count);
	    $this->assign('pre_page', $pre_page);
	    $this->assign('next_page', $next_page);
	    $this->assign('volist', $volist);
		$this->display();
	}
}
?>
