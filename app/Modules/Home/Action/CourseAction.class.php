<?php

class CourseAction extends HomeAction
{
	public function index()
	{
	    $id = intval($_GET['id']);
	    if($id){
	        return $this->detail();
	    }
	    $products = M('Courses');
	    $where = array('state'=>1);
	    $total = $products->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    
	    $pager = $this->getPage($total_count, 10, __APP__.'/course/page-__PAGE__.html');
	    $volist = $products->where($where)->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
	    
	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
	    $this->display('Course:index');
	}
	
	public function detail($id=0)
	{
	    $id = $id ? $id : intval($_GET['id']);
	    $course = M('Courses')->where(array('state'=>1))
	                       ->where(array('id'=>$id))
	                       ->find();
	    $this->assign('course', $course);
		$this->display('Course:detail');
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
