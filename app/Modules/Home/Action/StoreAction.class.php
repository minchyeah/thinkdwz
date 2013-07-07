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
		$this->display();
	}
}
?>