<?php

class MsgboardModel extends Model
{
	public $_validate = array(
			array('name','require','请输入您的名字'),
			array('mobile','/^[0-9\-]{5,16}$/i','电话格式错误', 0),
			array('email','email','Email格式错误', 0),
			array('address','require','请输入您的地址'),
			array('content','require','请输入留言内容'),
		);
}

?>