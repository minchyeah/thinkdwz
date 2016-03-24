<?php

class MsgboardAction extends AdminAction
{
    public function index(){}
    
	public function save()
	{
		$model = D('Msgboard');
		if($_POST['name'] == '标题...'){
		    $_POST['name'] = '';
		}
		if($_POST['mobile'] == '电话...'){
		    $_POST['mobile'] = '';
		}
		if($_POST['address'] == '地址...'){
		    $_POST['address'] = '';
		}
		if($_POST['email'] == 'QQ...'){
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
	        $data['dateline'] = date('Y-m-d H:i', $data['dateline']);
	        $this->mailorder($data);
			$this->success('留言保存成功！');
		}else{
			$model->rollback();
			$this->error('保存失败！'.dump($data, false).$model->getDbError());
		}
	}
	
	private function mailorder($data)
	{
	    $tomail = C('notify_email');
	    $subject = '您有新的留言!';
	    $body = <<<BODY
	    <body>
	        <h2>您有新的留言</h2>
	        <table class="table" width="100%">
                <thead>
            	  <tr style="text-align:left;">
            		<th>标题</th>
            		<th>电话</th>
            		<th>QQ</th>
            		<th>地址 <th>
            		<th>内容</th>
            		<th>时间</th>
            	  </tr>
                </thead>
                <tbody>
            	  <tr style="text-align:left;">
            		<td>{$data['name']}</td>
            		<td>{$data['mobile']}</td>
            		<td>{$data['email']}</td>
            		<td>{$data['address']}</td>
            		<td>{$data['content']}</td>
            		<td>{$data['dateline']}</td>
            	  </volist>
                </tbody>
              </table>
	    </body>
BODY;
	    $this->sendmail($tomail, $subject, $body);
	}

}
?>
