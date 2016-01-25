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
		
		$settings = F('settings');
		if(!$settings){
			$settings = D('Settings')->getField('skey,svalue');
			F('settings', $settings);
		}
		$settings['site_name'] = str_replace('{city}', $this->city['title'], $settings['site_name']);
		$settings['seo_keywords'] = str_replace('{city}', $this->city['title'], $settings['seo_keywords']);
		$settings['seo_description'] = str_replace('{city}', $this->city['title'], $settings['seo_description']);
		$this->assign('settings', $settings);
		$this->assign('user_id', session('user_id'));
		$this->assign('username', session('username'));
		$this->topnav();
		$this->slider();
		$this->sidebar_course();
		$this->sidebar_jinbang();
		$this->sidebar_tostu();
	}
	
	protected function notfound($msg = '', $url = '')
	{
		$this->display('Public:404');
		exit;
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
	
	protected function topnav()
	{
	    $model = D('Cases');
	    $where = "type='catalog'";
	    $catalog = $model->where($where)->order('sort_order DESC, dateline DESC')->select();
	    $this->assign('topnav_case_catalog', $catalog);
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
	 * 侧边栏课程
	 */
	protected function sidebar_course()
	{
	    $sidebar_course = M('Courses')->field('id,name,desc')->where(array('state'=>1))->order('dateline DESC')->select();
		$this->assign('sidebar_course', $sidebar_course);
	}
	
	/**
	 * 侧边栏致学生信
	 */
	protected function sidebar_tostu()
	{
	    $model = D('ArticlePage');
	    $page = $model->field('thumb,content')->where(array('page_code'=>'tostu'))->find();
		$this->assign('sidebar_tostu', $page);
	}
	
	/**
	 * 侧边栏金榜题名
	 */
	protected function sidebar_jinbang()
	{
		$this->assign('sidebar_jinbang', $this->getArticleList('jinbang', 20));
	}
	
	/**
	 * 查询分类文章
	 * @param string $catalog 分类目录
	 * @param number $limit
	 */
	protected function getArticleList($catalog, $limit = 10)
	{
	    $model = D('ArticleCategory');
	    $current_category = $model->where(array('catalog'=>$catalog))->find();
	    $cate_id = $current_category['id'];
	     
	    $sub_cates = $model->where(array('pid'=>$cate_id))->getField('id,cate_name');
	    $cate_ids = array();
	    if ($sub_cates) {
	        $cate_ids = array_keys($sub_cates);
	    }
	    $cate_ids[] = $cate_id;
	    $where = array();
	    $where['status'] = 1;
	    $where['cate_id'] = array('in', $cate_ids);
	    return D('Articles')->where($where)->limit($limit)->order('id DESC')->getField('id,title,cate_id');
	}
	
	/**
	 * 幻灯片
	 */
	protected function slider()
	{
	    $model = D('Slider');
		$where = array();
		$where['position'] = 'index';
		$index_slider = $model->field('id,target,image')->where($where)->order('sort_order DESC')->select();
		$this->assign('index_slider', $index_slider);
		unset($model,$index_slider);;
	}

	/**
	 * 通用分页方法
	 * @param unknown $count
	 * @param number $perPage
	 * @param string $url
	 */
	protected function getPage($count, $perPage = 10, $url = '')
	{
	    import('Util.Page', LIB_PATH);
	    $page = new Page($count, $perPage, '', $url);
	    $page->setConfig('theme', '%upPage% %prePage% %linkPage% %nextPage% %downPage%');
	    return $page;
	}
}