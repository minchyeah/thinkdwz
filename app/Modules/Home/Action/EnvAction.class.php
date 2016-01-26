<?php

class EnvAction extends HomeAction
{
	public function index()
	{
	    $model = M('Env');
	    $where = array('state'=>1);
	    $total = $model->field('COUNT(1) count')->where($where)->group('catalog')->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 10, __APP__.'/env/page-__PAGE__.html');
	    
	    $volist = $model->field('catalog')->where($where)->limit($pager->firstRow, $pager->listRows)->group('catalog')->select();
	    
	    if(is_array($volist)){
	        foreach ($volist as $k=>$v){
	            $volist[$k]['items'] = $model->where(array('state'=>1,'catalog'=>$v['catalog']))->limit(0, 12)->order('sort_order DESC, dateline DESC')->select();
	        }
	    }
	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
		$this->display('Cases:env_list');
	}
	
	public function detail($catalog = '')
	{
	    $model = M('Env');
	    $where = array('state'=>1,'catalog'=>$catalog);
	    $total = $model->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 12, __APP__.'/env/tag-__PAGE__-'.$catalog.'.html');
	     
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
	    if('tag-' == substr($name, 0, 4)){
	        $tar = explode('-', $name,3);
	        $_GET['page'] = $tar[1];
	        return $this->detail($tar[2]);
	    }
	    $this->notfound();
	}
}
?>
