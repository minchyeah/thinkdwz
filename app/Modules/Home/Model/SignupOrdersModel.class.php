<?php

class SignupOrdersModel extends Model
{
    protected $trueTableName    =   'signup_orders';
    
	public $_validate = array(
			array('contact','require','请输入您的姓名'),
			array('qq','/^\d+$/','请输入您的正确QQ'),
			array('mobile','/^(12|13|14|15|16|17|18|19)[0-9]{9}$/','请正确输入您的移动电话')
		);
}

?>