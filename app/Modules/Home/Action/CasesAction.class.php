<?php

class CasesAction extends HomeAction
{
	public function index()
	{
	    $model = M('Cases');
	    $where = array('state'=>1);
	    $total = $model->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 6, __APP__.'/cases/page-__PAGE__.html');
	    $pager->show();
	    
	    $volist = $model->where($where)->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
	    $this->assign('pager', $pager);
	    $this->assign('volist', $volist);
		$this->display('index');
	}
	
	public function _empty($name)
	{
	    if('page-' == substr($name, 0, 5)){
	        $_GET['page'] = substr($name, 5);
	        return $this->index();
	    }
	}
}
?>
