<?php

class StoreMenuViewModel extends ViewModel
{
	public $viewFields = array(
			'Stores'=>array('id'=>'store_id','name'=>'store_name','city_id'),
			'StoreMenus'=>array('id','name','price','image','category','remark', '_on'=>'Stores.id=StoreMenus.store_id'),
	);
	
}

?>