<?php

class EnvAction extends HomeAction
{
	public function index()
	{
	    $model = M('Env');
	    $where = array('state'=>1);
	    $total = $model->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 12, __APP__.'/cases/page-__PAGE__.html');
	    
	    $volist = $model->where($where)->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
		$this->display('Cases:env_list');
	}
	
	public function detail($id = 0)
	{
	    $model = M('Env');
	    $where = array('state'=>1);
	    $total = $model->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 12, __APP__.'/cases/page-__PAGE__.html');
	     
	    $volist = $model->where($where)->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
	    $this->display('Cases:env');
	}
	
	public function _empty($name)
	{
	    if('page-' == substr($name, 0, 5)){
	        $_GET['page'] = substr($name, 5);
	        return $this->index();
	    }
	    if (is_numeric($name)) {
	        $this->detail($name);
	    }
	}
}
?>