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
		$where = array();
		$where['store_id'] = $id;
		$rs = D('StoreMenus')->where($where)->getField('id,name,price,image,remark,category,store_id');
		$menus = array();
		if (is_array($rs)){
			foreach ($rs as $k=>$v){
				$menus[$v['category']][] = $v;
			}
		}
		ksort($menus);
		$this->assign('store', $store);
		$this->assign('menus', $menus);
		$this->assign('locations', $locations);
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
		
		$this->assign('store', $store);
		$this->assign('locations', $locations);
		$this->display();
	}
}
?>