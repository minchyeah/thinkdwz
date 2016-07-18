<?php
class CasesAction extends HomeAction
{

	public function _initialize()
	{
		parent::_initialize();
		$this->assign('current_nav', 'cases');
	}

	public function index()
	{
		$cate_id = intval($_GET['cate_id']);
		$model = M('Cases');
		$where = array('state'=>1);
		if($cate_id){
			$where['cate_id'] = $cate_id;
		}
		$style = trim($_GET['style']);
		if($style && $style != '全部'){
			$where_style = 'FIND_IN_SET("'.$style.'",`style`)';
		}
		$type = trim($_GET['type']);
		if($type && $type != '全部'){
			$where['type'] = $type;
		}
		$area = trim($_GET['area']);
		if($area && $area != '全部'){
			$wa = $this->parseArea($area);
			$wa && $where['area'] = $wa;
		}
		$budget = trim($_GET['budget']);
		if($budget && $budget != '全部'){
			$wb = $this->parseBudget($budget);
			$wb && $where['price'] = $wb;
		}
		
		$model->field('COUNT(1) count')->where($where);
		if($where_style){
			$model->where($where_style);
		}
		$total = $model->find();
		$total_count = intval($total['count']);
		$pager = $this->getPage($total_count, 12, __APP__ . '/cases/page-__PAGE__.html');
		
		$model->where($where);
		if($where_style){
			$model->where($where_style);
		}
		$volist = $model->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
		$this->assign('pager', $pager->show());
		$this->assign('volist', $volist);
		$this->display('Cases:cases_list');
	}

	public function detail($id = 0)
	{
		$cid = intval($id);
		$model = M('Cases');
		$where = array();
		$case = $model->field('*')->where($where)->find($id);
		$this->assign('vo', $case);
		$this->assign('case_category', M('CasesCategory')->find($case['cate_id']));
		$this->assign('designer', M('TeamMember')->find($case['designer']));
		$this->assign('engineer', M('TeamMember')->find($case['engineer']));
		$this->display('Cases:index');
	}

	public function _empty($name)
	{
		if(is_numeric($name)){
			return $this->detail($name);
		}
		if('page-' == substr($name, 0, 5)){
			$_GET['page'] = substr($name, 5);
		}elseif('cate-' == substr($name, 0, 5)){
			$args = explode('-', $name);
			$_GET['cate_id'] = intval($args[1]);
			$_GET['page'] = intval($args[3]);
		}
		$this->index();
	}
	
	private function parseArea($area)
	{
		if (strpos($area, '以上')) {
			return array('gt', intval($area));
		}
		if (strpos($area, '以下')) {
			return array('lt', intval($area));
		}
		$area = str_replace('～', '~', $area);
		if( strpos($area, '~') ){
			$tem = explode('~', $area);
			return array('between', array(intval($tem[0]), intval($tem[1])));
		}
	}
	
	private function parseBudget($budget)
	{
	
		if (strpos($budget, '以上')) {
			return array('gt', intval($budget));
		}
		if (strpos($budget, '以下')) {
			return array('lt', intval($budget));
		}
		$budget = str_replace('～', '~', $budget);
		if ( strpos($budget, '~') ){
			$tem = explode('~', $budget);
			return array('between', array(intval($tem[0]), intval($tem[1])));
		}
	}
}
?>
