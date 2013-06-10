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
    	$this->assign('jumpUrl', U('Public/login'));
    	$this->assign('waitSecond', 1);
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
    
    public function chpasswd()
    {
    	if(IS_POST){
    		$orgpwd = strval($_POST['orgpassword']);
    		$pwd = strval($_POST['password']);
    		$repwd = strval($_POST['repassword']);
    		if(!$orgpwd){
    			$this->error('请输入当前密码');
    		}
    		if(!$pwd){
    			$this->error('请输入设置密码');
    		}
    		if(!$repwd){
    			$this->error('请输入确认密码');
    		}
    		if($pwd!=$repwd){
    			$this->error('两次设置的密码不一至，请重新输入');
    		}
    		$model = D('Admin');
    		$admin = $model->find(session('admin_id'));
    		if($admin['password'] != md5(md5($orgpwd).$admin['pwdkey'])){
    			$this->error('当前密码错误');
    		}
    		$data = array();
    		$data['pwdkey'] = rand_string(8);
    		$data['password'] = md5(md5($pwd).$data['pwdkey']);
    		if ($model->where(array('id'=>$admin['id']))->save($data)) {
    			$this->success('密码修改成功');
    		}else{
    			$this->error('密码修改失败');
    		}
    	}
    	$this->display();
    }
    
    public function profile()
    {
    	if(IS_POST){
    		$username = strval($_POST['username']);
    		$email = strval($_POST['email']);
    		$model = D('Admin');
    		$data = array();
    		$data['username'] = $username;
    		$data['email'] = $email;
    		if ($model->where(array('id'=>session('admin_id')))->save($data)) {
    			$this->success('资料保存成功');
    		}else{
    			$this->error('资料保存失败');
    		}
    	}else{
    		$model = D('Admin');
    		$admin = $model->find(session('admin_id'));
    		$this->assign('vo', $admin);
    		$this->display();
    	}
    }
    
    public function uploadify()
    {
    	$rs = $this->saveImage($_FILES['upfile']);
    	$data = array();
    	$data['uprs'] = $rs;
    	$data['file'] = $_FILES;
    	if ($rs){
    		$data['error'] = 0;
    		$data['thumb'] = __ROOT__.'/data/'.imgsrc($rs, 100, 100);
    		$data['src'] = $rs;
    	}else{
    		$data['error'] = 1;
    		$data['info'] = '上传失败';
    	}
    	exit(json_encode($data));
    }
    
    public function clear_cache()
    {
    	echo <<<JS
    	<script type="text/javascript">
    	$.pdialog.closeCurrent();
    	alertMsg.correct('缓存更新成功！');
    	</script>
JS;
    }
}
?>
