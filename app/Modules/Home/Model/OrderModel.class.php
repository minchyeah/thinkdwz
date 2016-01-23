<?php

class OrderModel extends Model
{
    protected $trueTableName    =   'course_orders';
    
	public $_validate = array(
			array('contact','require','请输入您的姓名'),
			array('phone','require','请输入您的电话'),
			array('phone','/^[0-9\-]{5,16}$/i','请正确输入您的电话', 0),
			array('age','/^[0-9]{1,2}$/i','请正确输入年龄', 0),
			array('address','require','请输入您地址'),
		);
}

?>