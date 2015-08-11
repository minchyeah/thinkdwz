<?php

class CaseAction extends AdminAction
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

	public function delete()
	{
		$model = D('Stores');
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
		$_POST['recommend'] = intval($_POST['recommend']);
		$_POST['sort_order'] = intval($_POST['sort_order']);
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
}
?>
