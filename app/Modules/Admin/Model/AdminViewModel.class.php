<?php

class AdminViewModel extends ViewModel
{
	protected $viewFields = array(
			'Admin'=>array('user_id','city_ids'),
			'Users'=>array('id', 'username', 'password','email','nickname','is_admin', '_on'=>'Admin.user_id=Users.id'),
		);
}

?>