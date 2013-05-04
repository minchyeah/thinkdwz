<?php

class UserAction extends AdminAction
{
	public function admin()
	{
		$model = D('AdminView');
		$where = array();
		$where['is_admin'] = 1;
		$totalCount = $model->where($where)->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->where($where)->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();

		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
		$this->display();
	}
	
	public function add_admin()
	{
		$model = D('District');
    	$where = array();
    	$where['pid'] = 0;
    	$provinces = $model->where($where)->select();
    	$this->assign('provinces', $provinces);
		$this->assign('type', $type);
		$this->display();
	}
	
    public function index()
    {
    	$model = D('Users');
		$where = array();
		$where['is_admin'] = 0;
    	$totalCount = $model->where($where)->count();
    	$currentPage = intval($_REQUEST['pageNum']);
    	$currentPage = $currentPage ? $currentPage : 1;
    	$numPerPage = 20;
    	$rowOffset = ($currentPage-1) * $numPerPage;
    	$list = $model->where($where)->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
    	
    	$this->assign('list', $list);
    	$this->assign('totalCount', $totalCount);
    	$this->assign('numPerPage', $numPerPage);
    	$this->assign('currentPage', $currentPage);
        $this->display();
    }
    
    public function add()
    {
    	$model = D('Users');
    	$this->assign('topmenus', $model->topMenus());
    	$this->display();
    }
    
    public function edit()
    {
    	$id =  intval($_GET['id']);
    	$model = D('Users');
    	$this->assign('topmenus', $model->topMenus());
    	$this->assign('vo', $model->find($id));
    	$this->display('add');
    }
    
    public function save()
    {
    	$model = D('Users');
    	$data = $model->create();
    	$data['params'] = '';
    	$data['group_name'] = '';
    	if(!$data){
    		$this->error($model->getError());
    	}
    	if (!$data['id']) {
    		$rs = $model->add();
    	}else{
    		$rs = $model->save();
    	}
    	if(false !== $rs){
    		$this->success('保存成功！');
    	}else{
    		$this->error('保存失败！'.dump($data, false).$model->getDbError());
    	}
    }
}
?>
