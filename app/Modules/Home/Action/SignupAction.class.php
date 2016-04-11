<?php

class SignupAction extends HomeAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('current_nav', 'signup');
    }
    
	public function index()
	{
	    $this->assign('signup_data', $this->_build_signup_data());
	    $this->display('Signup:index');
	}
	
	private function _build_signup_data()
	{
	    $LevelModel = D('SignupLevel');
	    $level = $LevelModel->field('signup_id,level,first_major,second_major')->order('sort_order ASC,dateline DESC')->select();
	    if(!is_array($level)){
	        $level = array();
	    }
	    foreach ($level as $k=>$v){
	        $v['first_major'] = explode(',', trim(trim(str_replace('，', ',', $v['first_major']), ',')));
	        $v['second_major'] = explode(',', trim(trim(str_replace('，', ',', $v['second_major']), ',')));
	        $levels[$v['signup_id']][] = $v;
	    }
	    
	    $AreaModel = D('SignupArea');
	    $area = $AreaModel->field('signup_id,city,area')->order('sort_order ASC,dateline DESC')->select();
	    if(!is_array($area)){
	        $area = array();
	    }
	    foreach ($area as $ak=>$av){
	        $av['area'] = explode(',', trim(trim(str_replace('，', ',', $av['area']), ',')));
	        $areas[$av['signup_id']][] = $av;
	    }
	    
	    $SignupModel = D('Signup');
	    $signups = $SignupModel->field('id,name')->order('sort_order ASC,dateline DESC')->select();
	    if(!is_array($signups)){
	        $signups = array();
	    }
	    foreach ($signups as $key=>$val){
	        $val['levels'] = $levels[$val['id']];
	        $val['areas'] = $areas[$val['id']];
	        $signups[$key] = $val;
	    }
	    return $signups;
	}

	public function order()
	{
// 	    if($_POST['captcha'] == ''){
// 	        $this->error('请输入验证码');
// 	    }
// 	    if(!$this->checkCaptcha()){
// 	        $this->error('验证码错误');
// 	    }
	    $model = D('SignupOrders');
	    $_POST['is_buy'] = !isset($_POST['is_buy']) ? '否' : $_POST['is_buy'];
	    $_POST['ip'] = get_client_ip();
	    $data = $model->create();
	    if(!$data){
	        $this->error($model->getError());
	    }
	    $data['dateline'] = time();
	    $model->startTrans();
	    if (!$data['id']) {
	        $rs = $model->add($data);
	    }else{
	        $rs = $model->save($data);
	    }
	    if(false !== $rs){
	        $model->commit();
	        $data['dateline'] = date('Y-m-d H:i', $data['dateline']);
	        $this->mailorder($data);
	        $this->assign('signup', $data);
	        $this->success('报名成功！');
	    }else{
	        $model->rollback();
	        $this->error('报名失败！');
	    }
	}
	
	protected function success()
	{
	    $this->display('Signup:success');
	}

	private function mailorder($data)
	{
	    $tomail = C('notify_email');
	    $subject = '您有新的报名信息!';
	    $body = <<<BODY
	    <body>
	        <h2>您有新的报名信息</h2>
	        <table class="table" width="100%">
                <thead>
            	  <tr style="text-align:left;">
            		<th>学校</th>
            		<th>专业</th>
            		<th>考点</th>
            		<th>联系人</th>
            		<th>联系电话</th>
            		<th>联系QQ</th>
            		<th>报名时间</th>
            	  </tr>
                </thead>
                <tbody>
            	  <tr style="text-align:left;">
            		<td>{$data['school']}</td>
            		<td>{$data['level']} - {$data['first_major']}</td>
            		<td>{$data['city']} - {$data['area']}</td>
            		<td>{$data['contact']}</td>
            		<td>{$data['mobile']}</td>
            		<td>{$data['qq']}</td>
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
