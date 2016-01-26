<?php

class EnvAction extends HomeAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('current_nav', 'env');
    }
    
	public function index()
	{
	    $model = M('Env');
	    $where = array('state'=>1,'type'=>'catalog');
	    $total = $model->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 10, __APP__.'/env/page-__PAGE__.html');
	    
	    $volist = $model->where($where)->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
	    
	    if(is_array($volist)){
	        foreach ($volist as $k=>$v){
	            $volist[$k]['items'] = $model->where(array('state'=>1,'type'=>'env','cid'=>$v['id']))->limit(0, 12)->order('dateline DESC')->select();
	        }
	    }
	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
		$this->display('Cases:env_list');
	}
	
	public function detail($id = 0)
	{
	    $cid = intval($id);
	    $model = M('Env');
	    $where = array('state'=>1,'type'=>'env','cid'=>$cid);
	    $total = $model->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 12, __APP__.'/env/p-'.$cid.'-__PAGE__.html');
	     
	    $volist = $model->where($where)->limit($pager->firstRow, $pager->listRows)->order('dateline DESC')->select();
	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
	    $this->assign('current_type', $model->find($cid));
	    $this->display('Cases:env');
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
