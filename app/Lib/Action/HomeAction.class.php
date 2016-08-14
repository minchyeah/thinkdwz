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
		$this->assign('first_kefuqq', substr($settings['kefuqq'], 0, strpos($settings['kefuqq'], '|')));
		$this->assign('sidebar_activity', $this->getArticleList('activity', 3));
		$this->topnav();
		$this->slider();
		$this->footerschool();
		$this->sidebar_cases();
		$this->sidebar_orders();
		$this->visit_count();
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
		$cases = D('CasesCategory')->field('id,cate_name,catalog')->where(array('pid'=>0))->order('sort_order ASC, id ASC')->limit(8)->select();
		$this->assign('topnav_cases', $cases);
		$designers = D('TeamCategory')->field('id,cate_name,catalog')->where(array('pid'=>22))->order('sort_order ASC, id ASC')->limit(8)->select();
		$this->assign('topnav_designers', $designers);
		$engineers = D('TeamCategory')->field('id,cate_name,catalog')->where(array('pid'=>23))->order('sort_order ASC, id ASC')->limit(8)->select();
		$this->assign('topnav_engineers', $engineers);
		$news = D('ArticleCategory')->field('id,cate_name,catalog')->where(array('pid'=>1))->order('sort_order ASC, id ASC')->limit(8)->select();
		$this->assign('topnav_news', $news);
		$activity = D('ArticleCategory')->field('id,cate_name,catalog')->where(array('pid'=>7))->order('sort_order ASC, id ASC')->limit(8)->select();
		$this->assign('topnav_activity', $activity);
		$welcome = D('ArticleCategory')->field('id,cate_name,catalog')->where(array('pid'=>23))->order('sort_order ASC, id ASC')->limit(8)->select();
		$this->assign('topnav_welcome', $welcome);
	}
	
	/**
	 * 友情链接和商务合作链接
	 */
	protected function links()
	{
		$model = D('Links');
		$friendlinks = $model->where(array('category'=>'friendlink'))->order('sort_order ASC')->select();
		$this->assign('friendlinks', $friendlinks);
// 		$businesslinks = $model->where(array('category'=>'business'))->order('sort_order ASC')->select();
// 		$this->assign('businesslinks', $businesslinks);
	}
	
	/**
	 * 侧边栏课程
	 */
	protected function sidebar_course()
	{
	    $ads = D('Advertise')->field('id,params,html')->order('start_time DESC')->limit(8)->select();
	    $this->assign('sidebar_course_ads', $ads);
// 	    $sidebar_course = M('Courses')->field('id,name')->order('dateline DESC')->limit(8)->select();
// 		$this->assign('sidebar_course', $sidebar_course);
	}
	
	/**
	 * 侧边栏课程
	 */
	protected function sidebar_orders()
	{
	    $orders = D('SignupOrders')->field('id,contact,school,first_major')->order('dateline DESC')->limit(18)->select();
	    $this->assign('sidebar_orders', $orders);
	}
	
	/**
	 * 侧边栏致学生信
	 */
	protected function sidebar_cases()
	{
	    $model = D('Cases');
	    $page = $model->field('id,name,image')->where(array('type'=>'case'))->order('sort_order ASC,dateline DESC')->limit(999)->select();
		$this->assign('sidebar_cases', $page);
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
	    return D('Articles')->where($where)->where('cate_id<>10')->limit($limit)->order('create_time DESC')->getField('id,title,cate_id,thumb,content');
	}
	
	protected function footerschool()
	{
	    $model = D('School');
	    $school = $model->field('id,name,image')->limit(8)->order('sort_order ASC, dateline DESC')->select();
	    $this->assign('footer_school', $school);
	}
	
	/**
	 * 幻灯片
	 */
	protected function slider()
	{
	    $model = D('Slider');
		$where = array();
		$where['position'] = 'innert';
		$innert_slider = $model->field('id,target,image,title')->where($where)->order('sort_order DESC')->select();
		$this->assign('innert_slider', $innert_slider);
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
	    $page->rollPage = 1;
	    $page->firstPageHtml = '<a href="__URL__">__TITLE__</a>';
	    $page->lastPageHtml = '<a href="__URL__">__TITLE__</a>';
	    $page->prevPageHtml = '<span><a href="__URL__" title="上一页" class="syy"><b></b><h5></h5></a></span>';
	    $page->nextPageHtml = '<span><a href="__URL__" title="上一页" class="xyy"><b></b><h5></h5></a></span>';
	    $page->currentPageHtml = '<a href="" class="xz">__TITLE__</a>';
	    $page->setConfig('theme', '%upPage% %first% %prePage% %linkPage% %nextPage% %end% %downPage%');
	    $page->setConfig('theme', '%upPage% %first% %prePage% %linkPage% %nextPage% %end% %downPage%');
	    $page->setConfig('theme', '%upPage% %first% %prePage% %linkPage% %nextPage% %end% %downPage%');
	    return $page;
	}
	
	protected function visit_count()
	{
	    $model = D('Settings');
	    $row = $model->where(array('skey'=>'visit_count'))->find();
	    if(!session('visit_count')){
            session('visit_count', $row['svalue'], 86400);
	        $model->where(array('skey'=>'visit_count'))->setInc('svalue');
	    }
		$this->assign('visit_count', $row['svalue']);
	}
}