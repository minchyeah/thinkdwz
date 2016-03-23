<?php

class CourseAction extends HomeAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('current_nav', 'course');
    }
    
	public function index()
	{
	    $id = intval($_GET['id']);
	    if($id){
	        return $this->detail();
	    }
	    $products = M('Courses');
	    $where = array('state'=>1);
	    $total = $products->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);
	    
	    $pager = $this->getPage($total_count, 6, __APP__.'/course/page-__PAGE__.html');
	    $volist = $products->where($where)->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
	    
	    $this->assign('pager', $pager->show());
	    $this->assign('volist', $volist);
	    $this->display('Course:index');
	}
	
	public function detail($id=0)
	{
	    $id = $id ? $id : intval($_GET['id']);
	    $course = M('Courses')->where(array('state'=>1))
	                       ->where(array('id'=>$id))
	                       ->find();
	    $classes = M('CoursesClass')->where(array('course_id'=>$id))->order('dateline DESC')->select();
	    $this->assign('course', $course);
	    $this->assign('classes', $classes);
		$this->display('Course:detail');
	}
	
	public function clsdetail($id)
	{
	    $id = $id ? $id : intval($_GET['cls_id']);
	    $classes = M('CoursesClass')->where(array('id'=>$id))->find();
	    if(!is_array($classes) OR empty($classes)){
	        $this->notfound();
	    }

	    $course = M('Courses')->where(array('id'=>$classes['course_id']))->find();
	    
	    $this->assign('course', $course);
	    $this->assign('classes', $classes);
	    $this->display('Course:clsdetail');
	}

	public function _empty($name)
	{
	    if('page-' == substr($name, 0, 5)){
	        $_GET['page'] = substr($name, 5);
	        return $this->index();
	    }
	    if('cls-' == substr($name, 0, 4)){
	        $_GET['cls_id'] = substr($name, 4);
	        return $this->clsdetail();
	    }
	    if (is_numeric($name)) {
	        return $this->detail($name);
	    }
	}

	public function order()
	{
	    $model = D('Order');
	    if($_POST['contact'] == '请输入您的姓名...'){
	        $_POST['contact'] = '';
	    }
	    if($_POST['phone'] == '请输入您的联系电话...'){
	        $_POST['phone'] = '';
	    }
	    if($_POST['address'] == '请输入您的联系地址...'){
	        $_POST['address'] = '';
	    }
	    if($_POST['qq'] == '请输入您的联系QQ...'){
	        $_POST['qq'] = '';
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
	        $this->success('报名成功！');
	    }else{
	        $model->rollback();
	        $this->error('报名失败！');
	    }
	}

	private function mailorder($data)
	{
	    $tomail = C('notify_email');
	    $subject = '您有新的课程预订!';
	    $body = <<<BODY
	    <body>
	        <h2>您有新的课程预订</h2>
	        <table class="table" width="100%">
                <thead>
            	  <tr style="text-align:left;">
            		<th>报名课程</th>
            		<th>报名班级</th>
            		<th>联系人</th>
            		<th>联系电话</th>
            		<th>联系QQ</th>
            		<th>预订时间</th>
            	  </tr>
                </thead>
                <tbody>
            	  <tr style="text-align:left;">
            		<td>{$data['course_name']}</td>
            		<td>{$data['class_name']}</td>
            		<td>{$data['contact']}</td>
            		<td>{$data['phone']}</td>
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
