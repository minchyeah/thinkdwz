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
		
		$pager = $this->getPage($total_count, 18, __APP__.'/'.$team.'/page-__PAGE__.html');
		$model->where($where);
		if($where1){
			$model->where($where1);
		}
		$volist = $model->limit($pager->firstRow, $pager->listRows)->order('dateline DESC')->select();
		
		$this->assign('pager', $pager->show());
		$this->assign('volist', $volist);
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
		$cases = M('cases')->field('*')->where(array($_GET['catalog']=>$id))
							->order('dateline DESC')
							->select();
		$pager = '';
		$this->assign('vo', $teacher);
		$this->assign('pager', $pager);
		$this->assign('cases', $cases);
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
}
?>
