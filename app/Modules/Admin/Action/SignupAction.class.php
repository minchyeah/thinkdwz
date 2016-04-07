<?php

class SignupAction extends AdminAction
{
	public function index()
	{
		$model = D('Signup');
		$where = '';
		$key = trim(strval($_REQUEST['search_key']));
		if($key){
			$where .= ' AND ( `name` LIKE "%'.$key.'%" OR `address` LIKE "%'.$key.'%" )';
		}
		$where = trim($where, ' AND');
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
		$this->display();
	}

	public function delete()
	{
		$model = D('Signup');
		$model->startTrans();
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['id'] = $id;
		$orgStore = $model->find($id);
		$dids = explode(',', $orgStore['locations']);
		foreach ($dids as $v){
			$lid = intval($v);
			$lid && $model->where(array('id'=>$lid))->setDec('store_count');
		}
		$rs = $model->where($where)->delete();
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
		$_POST['recommend'] = intval($_POST['recommend']);
		$_POST['sort_order'] = intval($_POST['sort_order']);
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		$model->startTrans();
		if (!$data['id']) {
			$data['dateline'] = time();
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
		$model = D('SignupArea');
		$totalCount = $model->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->order('sort_order ASC,id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
		$this->display();
	}
	
	public function addarea()
	{
		$this->display();
	}
	
	public function editarea()
	{
		$id =  intval($_GET['id']);
		$model = D('SignupArea');
		$this->assign('vo', $model->find($id));
		$this->display('addarea');
	}
	
	public function savearea()
	{
		$model = D('SignupArea');
		$data = $model->create();
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
		$model = D('SignupLevel');
		$where = '';
		$key = trim(strval($_REQUEST['search_key']));
		if($key){
			$where .= ' AND ( `name` LIKE "%'.$key.'%" OR `address` LIKE "%'.$key.'%" )';
		}
		$where = trim($where, ' AND');
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
	
	public function addleval()
	{
		$this->display();
	}
	
	/**
	 * 用户报错
	 */
	public function editlevel()
	{
		$model = D('SignupLevel');
		$this->display();
	}
	
	/**
	 * 评论
	 */
	public function savelevel()
	{
		$model = D('SignupLevel');
		$location_id = intval($_REQUEST['location_id']);
		$where = '';
		$location_id && $where = 'FIND_IN_SET('.$location_id.',`locations`)';
		$key = trim(strval($_REQUEST['search_key']));
		if($key){
			$where .= ' AND ( `name` LIKE "%'.$key.'%" OR `address` LIKE "%'.$key.'%" )';
		}
		$where = trim($where, ' AND');
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
		$this->assign('location_id', $location_id);
		$this->assign('locations', F('locations'));
		$this->display();
	}
	
	public function deletelevel()
	{
		$model = D('SignupLevel');
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['id'] = $id;
		$data = $model->where($where)->find();
		$rs = $model->where($where)->delete();
		if($rs){
			$store = D('Stores');
			$rs = $store->where(array('id'=>$data['store_id']))->setDec('rating', $store->stars[$data['rate']]);
			$this->dwzSuccess('删除成功');
		}else{
			$this->dwzError('删除失败');
		}
	}
}
?>
