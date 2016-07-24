<?php

class TeamAction extends HomeAction
{
	protected $map_cid = array('designer'=>22, 'engineer'=>23);
	protected $map_title = array('designer'=>'设计团队', 'engineer'=>'施工团队');
	protected $map_want = array('designer'=>'找TA设计', 'engineer'=>'找TA施工');
	
	public function _initialize()
	{
		parent::_initialize();
	}
	
	public function index($team = '')
	{
		$this->assign('current_nav', $team);
		$this->assign('team_title', $this->map_title[$team]);
		$this->assign('team_want', $this->map_want[$team]);
		$team_nav = M('TeamCategory')->where(array('pid'=>$this->map_cid[$team]))->order()->select();
		$this->assign('sidebar_team_nav', $team_nav);
		$id = intval($_GET['id']);
		if($id){
			return $this->detail();
		}
		$cate_id = intval($_GET['cate_id']);
		if(!$cate_id){
			$cate_id = $this->map_cid[$team];
		}
		$cids = array($cate_id);
		
		$sids = M('TeamCategory')->where(array('pid'=>$cate_id))->field('id')->select();
		if(is_array($sids)){
			foreach ($sids as $v){
				array_push($cids, intval($v['id']));
			}
		}
		$where = array('state'=>1,'cate_id'=>array('IN', $cids));
		$style = trim($_GET['style']);
		$settings = F('settings');
		$where1 = '';
		if( in_array($style, str2arr($settings['case_style'])) ){
			$where1 = 'FIND_IN_SET("'.$style.'",`style`)';
		}
		$model = M('TeamMember');
		
		$model->field('COUNT(1) count')->where($where);
		if($where1){
			$model->where($where1);
		}
		$total = $model->find();
		$total_count = intval($total['count']);
		
		$pager = $this->getPage($total_count, 9, __APP__.'/'.$team.'/page-__PAGE__.html');
		$model->where($where);
		if($where1){
			$model->where($where1);
		}
		$volist = $model->limit($pager->firstRow, $pager->listRows)->order('dateline DESC')->select();
		
		$this->assign('pager', $pager->render());
		$this->assign('volist', $volist);
		$this->assign('current_team_cate_id', $cate_id);
		$this->display('Team:list');
	}
	
	public function detail($id=0)
	{
		$this->assign('team_title', $this->map_title[$_GET['catalog']]);
		$this->assign('team_want', $this->map_want[$_GET['catalog']]);
		$id = $id ? $id : intval($_GET['id']);
		$teacher = M('TeamMember')->where(array('state'=>1))
							->where(array('id'=>$id))
							->find();
		$total_case = M('Cases')->field('COUNT(1) count')->where(array($_GET['catalog']=>$id))->find();
		$total_order = M('TeamOrders')->field('COUNT(1) count')->where(array('member_id'=>$id))->find();
		$case_count = intval($total_case['count'])+$teacher['case_count'];
		$order_count = intval($total_order['count'])+$teacher['order_count'];
		$zhan_count = intval($teacher['zhan'])+$teacher['zhan_count'];
		$cases = M('Cases')->field('*')->where(array($_GET['catalog']=>$id))
							->order('dateline DESC')
							->select();
		$pager = '';
		$this->assign('vo', $teacher);
		$this->assign('pager', $pager);
		$this->assign('cases', $cases);
		$this->assign('current_nav', $_GET['catalog']);
		$this->assign('case_count', $case_count);
		$this->assign('order_count', $order_count);
		$this->assign('zhan_count', $zhan_count);
		$this->display('Team:detail');
	}

	public function _empty($name)
	{
		if('page-' == substr($name, 0, 5)){
			$_GET['page'] = substr($name, 5);
			return $this->index();
		}
		if (is_numeric($name)) {
			return $this->detail($name);
		}
	}

	public function order()
	{
		if(!IS_POST){
			$this->error('非法操作');
		}
		$contact = trim(strip_tags($_POST['contact']));
		if(!$contact OR $contact == '您的姓名？'){
			$this->error('请输入您姓名');
		}
		$phone = trim(strip_tags($_POST['phone']));
		if(!$phone OR $phone == '您的联系号码？'){
			$this->error('请输入您手机号码');
		}
		$model = D('TeamOrders');
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
			M('TeamMember')->where(array('id'=>$data['member_id']))->setInc('want_count');
			$data['dateline'] = date('Y-m-d H:i', $data['dateline']);
			$this->mailorder($data);
			$this->success('预约成功！');
		}else{
			$model->rollback();
			$this->error('预约失败！');
		}
	}

	public function zhan()
	{
		if(!IS_POST){
			$this->error('非法操作');
		}
		$member_id = intval($_POST['member_id']);
		$last_zhan_time = session('last_zhan_time_'.$member_id);
		if(time() - intval($last_zhan_time) < 600){
			$this->error('10分钟内只能点赞一次');
		}
		$rs = M('TeamMember')->where(array('id'=>$member_id))->setInc('zhan');
		if(false !== $rs){
			$tmb = M('TeamMember')->field('zhan,zhan_count')->where(array('id'=>$member_id))->find();
			session('last_zhan_time_'.$member_id, time());
			$this->success($tmb['zhan']+$tmb['zhan_count']);
		}else{
			$this->error('预约失败！');
		}
	}
	
	private function mailorder($data)
	{
		$tomail = C('notify_email');
		$subject = '您有新的消息!';
		$body = <<<BODY
	    <body>
	        <h2>您有新的课程预订</h2>
	        <table class="table" width="100%">
                <thead>
            	  <tr style="text-align:left;">
            		<th>联系人</th>
            		<th>手机号码</th>
            		<th>时间</th>
            	  </tr>
                </thead>
                <tbody>
            	  <tr style="text-align:left;">
            		<td>{$data['contact']}</td>
            		<td>{$data['phone']}</td>
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
