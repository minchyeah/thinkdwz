<?php

class AdminModel extends Model
{
	public $_validate = array(
			array('account','/^[a-z0-9]\w{3,}$/i','帐号格式错误'),
			array('account','','帐号已经存在',0,'unique',self::MODEL_INSERT),
		);
	
	/**
	 * 设置登录状态
	 * @param int|array $admin
	 */
	public function setLogin($admin)
	{
		if(is_numeric($admin)){
			$admin = $this->find($admin);
		}
		session('admin_id', $admin['id']);
		session('admin_account', $admin['account']);
		session('admin_name', $admin['username']);
		session('admin', $admin);
		
		$data = array();
		$data['login_ip'] = get_client_ip();
		$data['login_time'] = time();
		$data['login_count'] = array('exp', '`login_count`+1');
		$this->where(array('id'=>$admin['id']))->save($data);
	}
	
	public function logout()
	{
		session('admin_id', null);
		session('admin_account', null);
		session('admin_name', null);
		session('admin', null);
	}
}

?>