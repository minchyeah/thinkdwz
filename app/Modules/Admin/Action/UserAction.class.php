<?php

class UserAction extends AdminAction
{
	public function admin()
	{
		$model = D('Admin');
		$where = array();
		$totalCount = $model->where($where)->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->where($where)->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
		$model = D('District');
		$where = array();
		$where['pid'] = array('neq', 0);
		$where['type'] = 'city';
		$cities = $model->where($where)->getField('id,title');
		$this->assign('cities', $cities);
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
		$this->display();
	}
	
	public function edit_admin()
	{
		$id = intval($_REQUEST['id']);
		if(session('admin_id') != $id && session('admin_id') != 1){
			exit('普通管理员只能修改自己的用户信息');
		}
		$vo = D('Admin')->find($id);
		$model = D('District');
    	$where = array();
    	$where['pid'] = 0;
    	$provinces = $model->where($where)->select();
    	$province_id = $model->where(array('id'=>$vo['city_ids']))->getField('pid');
    	$cities = $model->where(array('pid'=>$province_id))->select();
    	$this->assign('provinces', $provinces);
    	$this->assign('province_id', $province_id);
    	$this->assign('cities', $cities);
    	$this->assign('vo', $vo);
		$this->display('add_admin');
	}
	
	public function save_admin()
	{
		$model = D('Admin');
		$data = $_POST;
		if ($data['password']) {
			$data['pwdkey'] = rand_string(8,6);
			$data['password'] = md5(md5($data['password']).$data['pwdkey']);
		}
		$data = $model->create($data);
		if(!$data){
			$this->error($model->getError());
		}
		if (!$data['id']) {
			$data['create_time'] = time();
			$rs = $model->add($data);
		}else{
			$rs = $model->save($data);
		}
		if(false !== $rs){
			$this->success('保存成功！');
		}else{
			$this->error('保存失败！'.dump($data, false).$model->getDbError());
		}
	}
	
	public function delete_admin()
	{
		if(session('admin_id') != 1){
			$this->error('只有超级管理员方可执行此删除操作');
		}
		$model = D('Admin');
		$id = intval($_REQUEST['id']);
		$this->success('ss');
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
