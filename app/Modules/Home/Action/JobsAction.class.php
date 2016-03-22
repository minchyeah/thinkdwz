<?php

class JobsAction extends HomeAction
{   
	public function index()
	{
	    $model = M('Jobs');
	    $where = array('state'=>1);
	    $total = $model->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 10, __APP__.'/jobs/page-__PAGE__.html');
	    
	    $volist = $model->where($where)->limit($pager->firstRow, $pager->listRows)->order('dateline DESC')->select();
	    
	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
		$this->display('Jobs:index');
	}
	
	public function detail($id = 0)
	{
	    $model = M('Jobs');
	    $where = array('state'=>1);
	    $job = $model->where($where)->find($id);
	    $contact = substr($job['contact'], 0, 3).'****'.substr($job['contact'], -4, 4);
	    $job['contact'] = $contact;
	    $job['others'] = $this->_others($job['others']);
	    $this->assign('job', $job);
	    $this->display('Jobs:detail');
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
	
	private function _others($str) 
	{
	    $arr = array();
	    $others = explode("\n", $str);
	    if(is_array($others)){
	        foreach ($others as $oth){
	            $ostr = str_replace('：', ':', trim($oth));
	            $arr[] = explode(':', $ostr);
	        }
	    }
	    return $arr;
	}
}
?>
