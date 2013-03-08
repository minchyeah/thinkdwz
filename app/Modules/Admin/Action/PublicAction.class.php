<?php

class PublicAction extends AdminAction
{
	/**
	 * 登录页面
	 */
    public function login()
    {
    	if(session('admin_id')){
    		$this->redirect('Index/index');
    	}
        $this->display();
    }
    
    public function _before_checkLogin(){
    	$this->assign("jumpUrl", U('Public/login'));
    	if (empty($_POST['login_name'])) {
    		$this->error('管理员帐号必须填写！');
    	}
    	if (empty($_POST['login_pwd'])) {
    		$this->error('管理员密码必须填写！');
    	}
    }
    /**
     * 验证登录
     */
    public function checkLogin()
    {
    	$where = array();
    	$where['username'] = $_POST['login_name'];
    	$rs  = D("Users");
    	$admin = $rs->where($where)->find();
    	//使用用户名、密码和状态的方式进行认证
    	if (!$admin) {
    		$this->error('管理员帐号不存在！');
    	}
    	if ($admin['password'] != md5(trim($_POST['login_pwd']))) {
    		$this->error('用户密码错误,请重新输入！');
    	}
    	// 记录登录信息
    	session('admin_id', $admin['id']);
    	session('role_id', $admin['role_id']);
    	session('username', $admin['username']);
    	session('admin', $admin);
    	
    	redirect(U('Index/index'));
    }
    
    /**
     * 退出登录
     */
    public function logout()
    {
    	session('admin_id', null);
    	session('role_id', null);
    	session('username', null);
    	session('admin', null);
    	
    	redirect(U('Public/login'));
    }
    
    /**
     * 验证码
     */
    public function captcha()
    {
    	import("ORG.Util.Image");
    	Image::buildImageVerify();
    }
    
}
?>
