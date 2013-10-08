<?php

class UserAction extends HomeAction
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function login()
	{
		if($this->user_id){
			$this->error('你已经登录，请不要重复登录');
		}
		$this->display();
	}
	
	public function dologin()
	{
		$model = D('Users');
		$captcha = trim(strval($_POST['captcha']));
		if(!$this->checkCaptcha($captcha, 'login_captcha')){
			$this->error('验证码错误');
		}
		$this->success('登录成功');
	}
	
	public function signup()
	{
		$this->display();
	}
	
	public function dosignup()
	{
		$model = D('Users');
		$captcha = trim(strval($_POST['captcha']));
		if(!$this->checkCaptcha($captcha, 'signup_captcha')){
			$this->error('验证码错误');
		}
		$this->success('登录成功');
	}
	
	public function getpwd()
	{
		$this->display();
	}
	
	public function dogetpwd()
	{
		$model = D('Users');
	}
}
?>