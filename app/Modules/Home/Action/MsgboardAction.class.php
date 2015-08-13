<?php

class MsgboardAction extends AdminAction
{
    public function index(){}
    
	public function save()
	{
		$model = D('Msgboard');
		if($_POST['name'] == '请输入您的姓名...'){
		    $_POST['name'] = '';
		}
		if($_POST['mobile'] == '请输入您的电话...'){
		    $_POST['mobile'] = '';
		}
		if($_POST['address'] == '请输入您的地址...'){
		    $_POST['address'] = '';
		}
		if($_POST['email'] == '请输入您的Email...'){
		    $_POST['email'] = '';
		}
		if($_POST['captcha'] == ''){
		    $this->error('请输入验证码');
		}
		if(!$this->checkCaptcha()){
		    $this->error('验证码错误');
		}
		$_POST['ip'] = get_client_ip();
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		$model->startTrans();
		if (!$data['id']) {
			$data['dateline'] = time();
			$rs = $model->add($data);
		}else{
			$rs = $model->save($data);
		}
		if(false !== $rs){
			$model->commit();
			$this->success('留言保存成功！');
		}else{
			$model->rollback();
			$this->error('保存失败！'.dump($data, false).$model->getDbError());
		}
	}

}
?>
