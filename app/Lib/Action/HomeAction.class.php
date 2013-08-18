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
	
	protected $user_id;
	protected $username;
	
	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		parent::_initialize();
		$this->user_id = session('user_id');
		$this->username = session('username');
		C('COOKIE_DOMAIN','.'.C('SITE_DOMAIN'));
		C('TMPL_PARSE_STRING', array_merge(C('TMPL_PARSE_STRING'), array(
			'__APP__' 	=> 'http://www.'.C('SITE_DOMAIN')
		)));
		
		$this->_detect_city();
		$this->_detect_district();
		$this->_detect_locations();
		$this->cityBoxTree();
		$settings = F('settings');
		if(!$settings){
			$settings = D('Settings')->getField('skey,svalue');
			F('settings', $settings);
		}
		$settings['site_name'] = str_replace('{city}', $this->city['title'], $settings['site_name']);
		$settings['seo_keywords'] = str_replace('{city}', $this->city['title'], $settings['seo_keywords']);
		$settings['seo_description'] = str_replace('{city}', $this->city['title'], $settings['seo_description']);
		$this->assign('settings', $settings);
	}
	
	protected function notfound($msg = '', $url = '')
	{
		$this->display('Public:404');
		exit;
	}
	
	/**
	 * 判断当前城市
	 */
	private function _detect_city()
	{
		$this->cities = F('cities');
		$model = D('District');
		if(!$this->cities){
			$this->cities = $model->where("`status`=1 AND `type`='city'")->getField('alias,id,title');
			F('cities', $this->cities);
		}
		if(!F('cities_id')){
			F('cities_id', $model->where("`status`=1 AND `type`='city'")->getField('id,alias,title'));
		}
		$host_city = str_replace('.'.C('site_domain'), '', $_SERVER['HTTP_HOST']);
		if(in_array($host_city, array_keys($this->cities))){
			$city_alias = $host_city;
		}else{
			$cookie_city = cookie('city_alias');
			if($cookie_city && in_array($cookie_city, array_keys($this->cities))){
				$city_alias = $cookie_city;
				if(!count($_REQUEST['_URL_'])){
					redirect('http://'.$city_alias.'.'.C('site_domain'));
				}
			}else{
				$city_alias = C('DEFAULT_CITY_ALIAS');
				redirect('http://'.$city_alias.'.'.C('site_domain'));
				//header('Location:'.'http://'.$city_alias.'.'.C('site_domain'), 302);
			}
		}
		$this->city_alias = $city_alias;
		$this->city = $this->cities[$city_alias];
		$this->city_id = $this->cities[$city_alias]['id'];
		cookie('city', $this->city);
		cookie('city_id', $this->city_id);
		cookie('city_alias', $this->city_alias);
		$this->districts = $model->where("`pid`={$this->city_id} AND `type` IN ('region','custom') AND `status`=1")->order('sort_order ASC')->getField('alias,id,title,type');
		$this->assign('city', $this->city);
	}
	
	private function _detect_district()
	{
		if(!F('district')){
			$model = D('District');
			$rs = $model->where("`status`=1 AND `type` IN ('custom','region')")->getField('id,alias,title');
			F('district', $rs);
		}
		if(!F('district_alias')){
			$model = D('District');
			$rs = $model->where("`status`=1 AND `type` IN ('custom','region')")->getField('alias,id,title');
			F('district_alias', $rs);
		}
	}
	
	private function _detect_locations()
	{
		if(!F('locations')){
			$model = D('Locations');
			$rs = $model->getField('id,alias,title');
			F('locations', $rs);
		}
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
		$rs = $model->where('`status`=1')->order('pid ASC')->select();
		$tree = new Tree($rs);
		$this->assign('cityboxTree', $tree->leaf());
	}
	
	/**
	 * 友情链接和商务合作链接
	 */
	protected function links()
	{
		$model = D('Links');
		$friendlinks = $model->where(array('category'=>'friendlink'))->order('sort_order ASC')->select();
		$this->assign('friendlinks', $friendlinks);
		$businesslinks = $model->where(array('category'=>'business'))->order('sort_order ASC')->select();
		$this->assign('businesslinks', $businesslinks);
	}
	
	/**
	 * 最新外卖店
	 */
	protected function latest_store()
	{
		$model = D('Stores');
		$where = array();
		$where['city_id'] = $this->city_id;
		$latest_store = $model->where($where)->limit(20)->order('id DESC')->select();
		$locations = F('locations');
		foreach ($latest_store as &$v){
			$locids = explode(',', $v['locations']);
			$v['location_name'] = $locations[array_shift($locids)]['title'];
		}
		$this->assign('latest_store', $latest_store);
	}
	
	protected function hot_foods()
	{
		$model = D('StoreMenuView');
		$where = array();
		$where['city_id'] = $this->city_id;
		$where['image'] = array('neq', '');
		$hot_foods = $model->where($where)->order('rand()')->limit(3)->select();
		$this->assign('hot_foods', $hot_foods);
	}
	
	/**
	 * 健康饮食
	 */
	protected function sidebar_healthy()
	{
		$model = D('Articles');
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['statue'] = 1;
		$where['cate_id'] = 1;
		$sidebar_healthy = $model->where($where)->order('id DESC')->limit(8)->getField('id,title,cate_id,create_time');
		$this->assign('sidebar_healthy', $sidebar_healthy);
	}
}