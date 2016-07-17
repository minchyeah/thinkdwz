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
		$total = $model->field('COUNT(1) count')->where($where)->find();
		$total_count = intval($total['count']);
		$pager = $this->getPage($total_count, 12, __APP__ . '/cases/page-__PAGE__.html');
		
		$volist = $model->where($where)->limit($pager->firstRow, $pager->listRows)->order('sort_order DESC, dateline DESC')->select();
		
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
}
?>
