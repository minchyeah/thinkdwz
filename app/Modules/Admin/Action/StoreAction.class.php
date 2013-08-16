<?php

class StoreAction extends AdminAction
{
	public function index()
	{
		$model = D('Stores');
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
	
	public function add()
	{
		$location_id = intval($_REQUEST['location_id']);
		$location = D('Locations')->find($location_id);
		$vo['city_id'] = $location['city_id'];;
		$vo['locations'] = $location_id;
		$vo['locationsName'] = $location['title'];
		$this->assign('vo', $vo);
		$this->assign('location_id', $location_id);
		$this->display();
	}
	
	public function edit()
	{
		$id =  intval($_GET['id']);
		$model = D('Stores');
		$store = $model->find($id);
		$store['locationsName'] = $this->ids2str($store['locations'], D('Locations'), 'title');
		$store['type_name'] = $this->ids2str($store['types'], D('StoreType'), 'type_name');
		$this->assign('vo', $store);
		$this->display('add');
	}
	
	public function save()
	{
		$model = D('Stores');
		if ($_FILES['imgfile']['name']) {
			$image = $this->saveImage($_FILES['imgfile']);
			if ($image) {
				$_POST['image'] = $image;
			}
		}
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		$lModel = D('Locations');
		$model->startTrans();
		if (!$data['id']) {
			$data['dateline'] = time();
			$rs = $model->add($data);
			$dids = explode(',', $data['locations']);
			foreach ($dids as $v){
				$lid = intval($v);
				$lid && $lModel->where(array('id'=>$lid))->setInc('store_count');
			}
		}else{
			$orgStore = $model->find($data['id']);
			$dids = explode(',', $orgStore['locations']);
			foreach ($dids as $v){
				$lid = intval($v);
				$lid && $lModel->where(array('id'=>$lid))->setDec('store_count');
			}
			$rs = $model->save($data);
			$dids = explode(',', $data['locations']);
			foreach ($dids as $v){
				$lid = intval($v);
				$lid && $lModel->where(array('id'=>$lid))->setInc('store_count');
			}
		}
		if(false !== $rs){
			$model->commit();
			$this->success('保存成功！');
		}else{
			$model->rollback();
			$this->error('保存失败！'.dump($data, false).$model->getDbError());
		}
	}
	
	public function lookup()
	{
		$model = D('Stores');
		$totalCount = $model->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		 
		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
		$this->display();
	}
	
	public function lookupSuggest()
	{
		$model = D('Stores');
		$totalCount = $model->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		echo json_encode($list);
	}
	
	public function type()
	{
		$model = D('StoreType');
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
	
	public function addtype()
	{
		$this->display();
	}
	
	public function edittype()
	{
		$id =  intval($_GET['id']);
		$model = D('StoreType');
		$this->assign('vo', $model->find($id));
		$this->display('addtype');
	}
	
	public function savetype()
	{
		$model = D('StoreType');
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
	
	public function seltype()
	{
		$model = D('StoreType');
		$map = array();
		$tree = $model->tree($map,$_REQUEST['link'],$_REQUEST['selparent']);
		$this->assign('tree',$tree);
		$this->display('Public:multiselect');
	}
}
?>
