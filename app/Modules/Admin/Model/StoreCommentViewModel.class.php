<?php

class StoreCommentViewModel extends ViewModel
{
	public $viewFields = array(
			'Stores'=>array('id'=>'store_id','name'=>'store_name','city_id'),
			'StoreComment'=>array('id','user_id','username','rate','content','dateline', '_on'=>'Stores.id=StoreComment.store_id'),
	);
	
}

?>