<?php

class AdminAction extends CommonAction
{
	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		parent::_initialize();
		C('TMPL_ACTION_ERROR', 'Public:error'); // 默认错误跳转对应的模板文件
		C('TMPL_ACTION_SUCCESS', 'Public:success'); // 默认成功跳转对应的模板文件
		$this->_login();
		$this->_privileges();
		$this->assign('username', session('username'));
		$this->assign('admin', session('admin'));
	}
	
	/**
	 * 判断登录状态，没登录则跳转到登录页面
	 */
	private function _login()
	{
		if(!session('admin_id') && MODULE_NAME != 'Public'){
			$this->redirect('Admin/Public/login');
		}
	}
	
	/**
	 * 检查是否有操作权限
	 */
	private function _privileges()
	{
		
	}
	
	protected function redirect($url, $params=array(), $delay=0, $msg=''){
		parent::redirect($url, $params=array(), $delay=0, $msg='');
	}
}