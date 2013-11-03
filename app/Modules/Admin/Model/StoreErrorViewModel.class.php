<?php

class StoreErrorViewModel extends ViewModel
{
	public $viewFields = array(
			'Stores'=>array('id'=>'store_id','name'=>'store_name','city_id','locations'),
			'StoreErrors'=>array('id','content','email','dateline','ip', '_on'=>'Stores.id=StoreErrors.store_id'),
	);
	
}

?>