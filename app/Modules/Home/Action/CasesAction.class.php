<?php

class CasesAction extends HomeAction
{
	public function index()
	{
	    $model = M('Cases');
	    $where = array('state'=>1,'type'=>'catalog');
	    $total = $model->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 12, __APP__.'/cases/page-__PAGE__.html');
	    
	    $volist = $model->where($where)->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
		$this->display('Cases:cases_list');
	}
	
	public function detail($id = 0)
	{
	    $cid = intval($id);
	    $model = M('Cases');
	    $where = array('state'=>1,'type'=>'case','cid'=>$cid);
	    $total = $model->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 12, __APP__.'/cases/p-'.$cid.'-__PAGE__.html');
	     
	    $volist = $model->where($where)->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
	    $this->display('Cases:index');
	}
	
	public function _empty($name)
	{
	    if('page-' == substr($name, 0, 5)){
	        $_GET['page'] = substr($name, 5);
	        return $this->index();
	    }
	    if('p-' == substr($name, 0, 2)){
	        $tar = explode('-', $name);
	        $_GET['page'] = $tar[2];
	        return $this->detail($tar[1]);
	    }
	    if (is_numeric($name)) {
	        $this->detail($name);
	    }
	}
}
?>
