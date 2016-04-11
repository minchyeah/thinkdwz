<?php

class SignupAction extends AdminAction
{
	public function index()
	{
		$model = D('Signup');
		$where = '';
		$key = trim(strval($_REQUEST['search_key']));
		if($key){
			$where .= ' AND ( `name` LIKE "%'.$key.'%")';
		}
		$where = trim($where, ' AND');
		$totalCount = $model->where($where)->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->where($where)->order('sort_order ASC,dateline DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
		$this->display();
	}
	
	public function add()
	{
		$this->display();
	}

	public function delete()
	{
		$model = D('Signup');
		$model->startTrans();
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['id'] = $id;
		$rs = $model->where($where)->delete();
		D('SignupLevel')->where(array('signup_id'=>$id))->delete();
		D('SignupArea')->where(array('signup_id'=>$id))->delete();
		if($rs){
			$model->commit();
			$this->dwzSuccess('删除成功');
		}else{
			$model->rollback();
			$this->dwzError('删除失败');
		}
	}
	
	public function edit()
	{
		$id =  intval($_GET['id']);
		$model = D('Signup');
		$school = $model->find($id);
		$this->assign('vo', $school);
		$this->display('add');
	}
	
	public function save()
	{
		$model = D('Signup');
		if ($_FILES['imgfile']['name']) {
			$image = $this->saveImage($_FILES['imgfile']);
			if ($image) {
				$_POST['image'] = $image;
			}
		}
		$_POST['sort_order'] = intval($_POST['sort_order']);
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		$data['dateline'] = time();
		$model->startTrans();
		if (!$data['id']) {
			$rs = $model->add($data);
		}else{
			$rs = $model->save($data);
		}
		if(false !== $rs){
			$model->commit();
			$this->success('保存成功！');
		}else{
			$model->rollback();
			$this->error('保存失败！'.$model->getDbError());
		}
	}
	
	public function area()
	{
		$sid = intval(strval($_REQUEST['sid']));
		if(!$sid){
		    $this->error('参数异常');
		}
		$where = array('signup_id'=>$sid);
		$model = D('SignupArea');
		$totalCount = $model->where($where)->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->where($where)->order('sort_order ASC,dateline DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
		$this->assign('signup', D('Signup')->find($sid));
		$this->display();
	}
	
	public function addarea()
	{
	    $sid = intval($_REQUEST['sid']);
		$this->assign('signup', D('Signup')->find($sid));
		$this->display('areaform');
	}
	
	public function editarea()
	{
		$id =  intval($_GET['id']);
		$model = D('SignupArea');
		$area = $model->find($id);
		$this->assign('vo', $area);
		
		$this->assign('signup', D('Signup')->find($area['signup_id']));
		
		$this->display('areaform');
	}
	
	public function savearea()
	{
		$model = D('SignupArea');
		$_POST['sort_order'] = intval($_POST['sort_order']);
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		$data['dateline'] = time();
		if (!$data['id']) {
			$rs = $model->add($data);
		}else{
			$rs = $model->save($data);
		}
		if(false !== $rs){
			$this->success('保存成功！');
		}else{
			$this->error('保存失败！'.$model->getDbError());
		}
	}

	public function deletearea()
	{
		$model = D('SignupArea');
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['id'] = $id;
		$rs = $model->where($where)->delete();
		if($rs){
			$this->dwzSuccess('删除成功');
		}else{
			$this->dwzError('删除失败');
		}
	}
	
	public function selarea()
	{
		$model = D('SignupArea');
		$map = array();
		$tree = $model->tree($map,$_REQUEST['link'],$_REQUEST['selparent']);
		$this->assign('tree',$tree);
		$this->display('Public:multiselect');
	}
	
	/**
	 * 用户添加
	 */
	public function level()
	{
		$sid = intval(strval($_REQUEST['sid']));
		if(!$sid){
		    $this->error('参数异常');
		}
		$where = array('signup_id'=>$sid);
		$model = D('SignupLevel');
		$totalCount = $model->where($where)->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->where($where)->order('sort_order ASC,dateline DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
		$this->assign('signup', D('Signup')->find($sid));
		$this->display();
	}
	
	public function addlevel()
	{
	    $sid = intval($_REQUEST['sid']);
		$this->assign('signup', D('Signup')->find($sid));
		$this->display('levelform');
	}
	
	/**
	 * 用户报错
	 */
	public function editlevel()
	{
		$id =  intval($_GET['id']);
		$model = D('SignupLevel');
		$level = $model->find($id);
		$this->assign('vo', $level);
		
		$this->assign('signup', D('Signup')->find($level['signup_id']));
		
		$this->display('levelform');
	}
	
	public function savelevel()
	{
		$model = D('SignupLevel');
		$_POST['sort_order'] = intval($_POST['sort_order']);
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		$data['dateline'] = time();
		$model->startTrans();
		if (!$data['id']) {
			$rs = $model->add($data);
		}else{
			$rs = $model->save($data);
		}
		if(false !== $rs){
			$model->commit();
			$this->success('保存成功！');
		}else{
			$model->rollback();
			$this->error('保存失败！'.$model->getDbError());
		}
	}
	
	public function deletelevel()
	{
		$model = D('SignupLevel');
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['id'] = $id;
		$rs = $model->where($where)->delete();
		if($rs){
			$this->dwzSuccess('删除成功');
		}else{
			$this->dwzError('删除失败');
		}
	}
	
	public function orders()
	{
		$model = D('SignupOrders');
		$where = '';
		$totalCount = $model->where($where)->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->where($where)->order('dateline DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
	    $this->display();
	}
	
	public function orderdetail()
	{
		$model = D('SignupOrders');
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['id'] = $id;
		$order = $model->where($where)->find();
		$this->assign('vo', $order);
	    $this->display();
	}
	
	public function deleteorder()
	{
		$model = D('SignupOrders');
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['id'] = $id;
		$rs = $model->where($where)->delete();
		if($rs){
			$this->dwzSuccess('删除成功');
		}else{
			$this->dwzError('删除失败');
		}
	}
}
?>
