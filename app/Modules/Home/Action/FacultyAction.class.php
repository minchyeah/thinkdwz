<?php

class FacultyAction extends HomeAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('current_nav', 'faculty');
    }
    
	public function index()
	{
	    $id = intval($_GET['id']);
	    if($id){
	        return $this->detail();
	    }
	    $where = array('state'=>1);
	    $total =  M('Teacher')->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    
	    $pager = $this->getPage($total_count, 10, __APP__.'/faculty/page-__PAGE__.html');
	    $volist =  M('Teacher')->where($where)->limit($pager->firstRow, $pager->listRows)->order('dateline DESC')->select();

	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
		$this->display('Faculty:list');
	}
	
	public function detail($id=0)
	{
	    $id = $id ? $id : intval($_GET['id']);
	    $teacher = M('Teacher')->where(array('state'=>1))
	                       ->where(array('id'=>$id))
	                       ->find();
	    $next_page = M('Teacher')->field('id,name')->where(array('state'=>1))
	                       ->where("id<{$id}")
	                       ->order('id DESC')
	                       ->find();
	    $prev_page = M('Teacher')->field('id,name')->where(array('state'=>1))
	                       ->where("id>{$id}")
	                       ->order('id ASC')
	                       ->find();
	    $this->assign('teacher', $teacher);
	    $this->assign('prev_page', $prev_page);
	    $this->assign('next_page', $next_page);
		$this->display('Faculty:detail');
	}

	public function _empty($name)
	{
	    if('page-' == substr($name, 0, 5)){
	        $_GET['page'] = substr($name, 5);
	        return $this->index();
	    }
	    if (is_numeric($name)) {
	        return $this->detail($name);
	    }
	}
}
?>
