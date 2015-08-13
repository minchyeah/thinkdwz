<?php

class OrderModel extends Model
{
    protected $trueTableName    =   'product_orders';
    
	public $_validate = array(
			array('contact','require','请输入您的名字'),
			array('phone','require','请输入您的电话'),
			array('phone','/^[0-9\-]{5,16}$/i','电话格式错误', 0),
			array('address','require','请输入您地址'),
		);
}

?>