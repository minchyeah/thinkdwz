<?php
class CasesAction extends HomeAction
{

	public function _initialize()
	{
		parent::_initialize();
		$this->assign('current_nav', 'cases');
		$sidebar_nav = M('CasesCategory')->where(array('pid'=>0))->field('id,cate_name,pid')->order('sort_order ASC')->select();
		$this->assign('sidebar_cases_nav', $sidebar_nav);
	}

	public function index()
	{
		$cate_id = intval($_GET['cate_id']);
		$model = M('Cases');
		$where = array('state'=>1);
		if($cate_id){
			$where['cate_id'] = $cate_id;
		}else{
			$where['cate_id'] = array('neq', 23);;
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
		$query_data = $_GET;
		unset($query_data['cate_id'], $query_data['page'], $query_data['_URL_']);
		$query_string = http_build_query($query_data);
		if($cate_id){
			$pager_url = __APP__ . '/cases/cate-'.$cate_id.'-page-__PAGE__.html';
		}else{
			$pager_url = __APP__ . '/cases/page-__PAGE__.html';
		}
		if($query_string){
			$pager_url = $pager_url.'?'.$query_string;
		}
		$pager = $this->getPage($total_count, 9, $pager_url);
		
		$model->where($where);
		if($where_style){
			$model->where($where_style);
		}
		$volist = $model->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
		$this->assign('pager', $pager->render());
		$this->assign('volist', $volist);
		$this->assign('current_category', M('CasesCategory')->find($cate_id));
		$this->assign('current_cases_id', $cate_id);
		if($cate_id == 23){
			$this->display('Cases:cases_list_23');
		}else{
			$this->display('Cases:cases_list');
		}
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
		$this->assign('current_cases_id', $case['cate_id']);
		if($case['cate_id'] == 23){
			$this->display('Cases:index_23');
		}else{
			$this->display('Cases:index');
		}
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
