<?php

class HomeAction extends CommonAction
{
	/**
	 * 所有开通城市
	 * @var array
	 */
	protected $cities;
	/**
	 * 当前城市ID
	 * @var int
	 */
	protected $city_id;
	/**
	 * 当前城市别名
	 * @var string
	 */
	protected $city_alias;
	/**
	 * 当前城市
	 * @var array
	 */
	protected $city;
	/**
	 * 当前城市所有区域
	 * @var array
	 */
	protected $districts;
	
	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		parent::_initialize();
		$this->_detect_city();
		$this->cityBoxTree();
	}
	
	/**
	 * 判断当前城市
	 */
	private function _detect_city()
	{
		if(!count($_REQUEST['_URL_'])){
			redirect(__APP__.'/'.C('DEFAULT_CITY').'/');
		}
		$model = D('District');
		$this->cities = $model->where("`type`='city'")->getField('alias,id,title');
		$city_alias = strtolower($_REQUEST['_URL_'][0]);
		if (in_array($city_alias, array_keys($this->cities))) {
			$this->city_alias = $city_alias;
			$this->city = $this->cities[$city_alias];
			$this->city_id = $this->cities[$city_alias]['id'];
		}
		$this->districts = $model->where("`pid`={$this->city_id} AND `type` IN ('region','custom')")->order('sort_order ASC')->getField('alias,id,title,type');
		$this->assign('city', $this->city);
	}

	/**
	 * 登录检查
	 */
	protected  function _logincheck()
	{
		if(!$_SESSION['uid']){
			$this->error('您还没有登录会员!',U('User/login'));
		}else{
			$user=array();
			$user['username']=$_SESSION['username'];
			$user['uid']=$_SESSION['uid'];
			$user['role_id']=$_SESSION['role_id'];
			return $user;
		}
	}
	
	/**
	 * 省市选择框
	 */
	protected function cityBoxTree()
	{
		import('ORG.Util.Tree');
		$model = D('District');
		$rs = $model->order('pid ASC')->select();
		$tree = new Tree($rs);
		$this->assign('cityboxTree', $tree->leaf());
	}
	
	protected function friendlink()
	{
		
	}
}