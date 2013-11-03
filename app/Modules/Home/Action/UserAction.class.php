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
		$where = array();
		$where['username'] = trim($_POST['username']);
		$user = $model->where($where)->find();
		if($user['password'] == md5(md5($_POST['password']).$user['passwdkey'])){
			session('user_id', $user['id']);
			session('username', $user['username']);
			cookie('user_id', $user['id']);
			cookie('username', $user['username']);
			$this->success('登录成功');
		}else{
			$this->error('登录失败，用户名或密码错误');
		}
	}
	
	public function logout()
	{
		session('user_id', null);
		session('username', null);
		$url = $_SESSION['REFFER'];
		if(!$url OR strpos($url, 'logout')){
			$url = '/';
		}
		$this->redirect($url);
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
		$data = array();
		$data['username'] = trim($_POST['username']);
		$data['passwdkey'] = rand_string(8,3);
		$data['password'] = md5(md5($_POST['password']).$data['passwdkey']);
		$data['email'] = trim($_POST['email']);
		$data['nickname'] = trim($_POST['nickname']);
		$data['status'] = 1;
		$data['is_admin'] = 0;
		$data['create_time'] = time();
		$uname_test = $model->where(array('username'=>$data['username']))->select();
		if(count($uname_test)){
			$this->error('用户名已经存在');
		}
		$email_test = $model->where(array('email'=>$data['email']))->select();
		if(count($email_test)){
			$this->error('该邮箱已经注册过了');
		}
		$data = $model->create($data);
		if(!$data){
			$this->error($model->getError());
		}else{
			$rs = $model->add();
			if(!$rs){
				$this->error('注册失败');
			}else{
				$this->success('注册成功');
			}
		}
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