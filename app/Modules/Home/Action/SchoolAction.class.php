<?php

class SchoolAction extends HomeAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('current_nav', 'school');
    }
    
	public function index()
	{
	    $model = M('School');
	    $where = array();
	    $total = $model->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    $pager = $this->getPage($total_count, 12, __APP__.'/school/page-__PAGE__.html');
	    
	    $volist = $model->field('id,name,image,content')->where($where)->limit($pager->firstRow, $pager->listRows)->order('sort_order ASC, dateline DESC')->select();

	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
		$this->display('School:index');
	}
	
	public function detail($id = 0)
	{
	    $cid = intval($id);
	    $model = M('School');
	    $where = array('id'=>$id);
	    $school = $model->where($where)->find();
	    if(!$school){
	        $this->notfound();
	    }

	    $next_page = $model->field('id,name')->where("id<>{$school['id']} AND(sort_order>{$school['sort_order']} OR (sort_order={$school['sort_order']} OR dateline<{$school['dateline']}))")
                	    ->order('sort_order ASC, dateline DESC')
                	    ->find();
	    $prev_page = $model->field('id,name')->where("id<>{$school['id']} AND(sort_order<{$school['sort_order']} OR (sort_order={$school['sort_order']} OR dateline>{$school['dateline']}))")
                	    ->order('sort_order DESC, dateline ASC')
                	    ->find();
	    
	    $this->assign('prev_page', $prev_page);
	    $this->assign('next_page', $next_page);
	    
	    $this->assign('school', $school);
	    $this->display('School:detail');
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
