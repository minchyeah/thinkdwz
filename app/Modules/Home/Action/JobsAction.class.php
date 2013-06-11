<?php

class JobsAction extends HomeAction
{
    public function index()
    {
    	$model = D('jobs');
    	$rs = $model->where(array('status'=>1))->select();
    	$jobs = array();
    	if(is_array($rs)){
    		foreach($rs as $k=>$v){
    			$jobs[$k] = $v;
    			$jobs[$k]['requirement'] = $this->_job_filter($v['requirement']);
    			//$jobs[$k]['description'] = $this->_job_filter($v['description']);
    		}
    	}
    	$this->assign('jobs', $jobs);
    	$this->assign('jobs_description', $this->sysSetting['jobs_description']);
    	$this->assign('jobs_contact', $this->_job_filter($this->sysSetting['jobs_contact']));
        $this->display();
    }
    
    private function _job_filter($str)
    {
    	$str = trim($str);
    	$return = array();
    	$tmp = explode("\n", $str);
    	foreach ($tmp as $t){
    		list($k,$v) = explode("：", trim($t));
    		$return[trim($k)] = trim($v);
    	}
    	return $return;
    }
}
?>