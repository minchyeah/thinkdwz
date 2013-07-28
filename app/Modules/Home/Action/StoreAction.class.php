<?php

class StoreAction extends HomeAction
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($id = 0)
	{
		$id = intval($id);
		$model = D('Stores');
		$store = $model->find($id);
		if(!$store){
			$this->notfound();
		}
		$lids = explode(',', $store['locations']);
		$locations = D('Locations')->where(array('id'=>array('in', $lids)))->select();
		$cates = explode(',', $store['menu_category']);
		$cates_rev = array_flip($cates);
		$where = array();
		$where['store_id'] = $id;
		$rs = D('StoreMenus')->where($where)->getField('id,name,price,image,remark,category,store_id');
		$menus = array();
		if (is_array($rs)){
			foreach ($rs as $k=>$v){
				$menus[$cates_rev[$v['category']]][] = $v;
			}
		}
		ksort($menus);
		$store['city_alias'] = $this->city_alias;
		$this->assign('store', $store);
		$this->assign('district', F('district'));
		$this->assign('menus', $menus);
		$this->assign('locations', $locations);
		$this->assign('cates', $cates);
		$this->enjoy_stores();
		$this->display('Store:index');
	}
	
	public function _empty()
	{
		$id = intval($_REQUEST['_URL_'][1]);
		$this->index($id);
	}
	
	public function append()
	{
		$this->display();
	}
	
	public function enjoy_stores()
	{
		$model = D('Stores');
		$where = array();
		$where['city_id'] = $this->city_id;
		$enjoy_stores = $model->where($where)->order('rand()')->limit(12)->select();
		$this->assign('enjoy_stores', $enjoy_stores);
	}
	
	public function comment()
	{
		$params = explode('-', $_REQUEST['_URL_'][2]);
		$id = intval($params[0]);
		$_GET['page'] = intval($params[1]);
		$model = D('Stores');
		$store = $model->find($id);
		if(!$store){
			$this->notfound();
		}
		$lids = explode(',', $store['locations']);
		$locations = D('Locations')->where(array('id'=>array('in', $lids)))->select();
		
		$where = array();
		//$where['status'] = 1;
		$where['store_id'] = $id;
		$cModel = D('StoreComment');
		$count = $cModel->where($where)->count();
		$page = $this->getPage($count, 10, __APP__.'/store/comment/'.$id.'-__PAGE__.html');
		$comments = $cModel->where($where)->limit($page->firstRow,$page->listRows)->order('id DESC')->select();
		$this->assign('comments', $comments);
		$this->assign('page', $page->show());
		$store['city_alias'] = $this->city_alias;
		$this->assign('store', $store);
		$this->assign('locations', $locations);
		$this->display();
	}
	
	public function saveComment()
	{
		$model = D('StoreComment');
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		if (!$data['id']) {
			$data['dateline'] = time();
			$data['user_id'] = intval(session('user_id'));
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
	
	public function saveError()
	{
		$model = D('StoreErrors');
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		if (!$data['id']) {
			$data['dateline'] = time();
			$data['user_id'] = intval(session('user_id'));
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
	
	public function telphone()
	{
		$id = intval($_GET['id']);
		$model = D('Stores');
		$store = $model->find($id);
		import('ORG.Util.Image');
		Image::buildString($store['telphone'], array(255,0,0), '', 'png', 0, false);
	}
}
?>