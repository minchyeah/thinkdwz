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
		
	}
	
	public function signup()
	{
		$this->display();
	}
	
	public function dosignup()
	{
		
	}
	
	public function getpwd()
	{
		$this->display();
	}
	
	public function dogetpwd()
	{
		
	}
}
?>