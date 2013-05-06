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
    	if (empty($_POST['username'])) {
    		$this->error('管理员帐号必须填写！');
    	}
    	if (empty($_POST['password'])) {
    		$this->error('管理员密码必须填写！');
    	}
    	if(!$this->checkCaptcha()){
    		$this->error('验证码错误');
    	}
    }
    /**
     * 验证登录
     */
    public function checkLogin()
    {
    	$where = array();
    	$where['account'] = $_POST['username'];
    	$model  = D('Admin');
    	$admin = $model->where($where)->find();
    	//使用用户名、密码和状态的方式进行认证
    	if (!$admin) {
    		$this->error('管理员帐号不存在！');
    	}
    	if ($admin['password'] != md5(md5($_POST['password']).$admin['pwdkey'])) {
    		$this->error('用户密码错误,请重新输入！');
    	}
    	// 记录登录信息
    	$model->setLogin($admin);
    	
    	redirect(U('Index/index'));
    }
    
    /**
     * 退出登录
     */
    public function logout()
    {
    	$model  = D('Admin');
    	$model->logout();
    	redirect(U('Public/login'));
    }
    
    public function clearCache()
    {
    	$this->success('d');
    }
}
?>
