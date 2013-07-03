<?php

class HomeAction extends CommonAction
{
	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		parent::_initialize();
		$this->assign('sys_setting', $this->sysSetting);
		$this->initHeaderAbout();
		$this->initHeaderNews();
		$this->initHeaderProduct();
	}
	
	protected function initHeaderAbout()
	{
		$aModel = D('Articles');
		$where = array();
		$where['statue'] = 1;
		$where['cate_id'] = 2;
		$rs = $aModel->where($where)->getField('id,title,cate_id,create_time');
		$about = array();
		if (is_array($rs)) {
			foreach ($rs as $v){
				$v['catalog'] = 'about';
				$about[] = $v;
			}
		}
		//dump($about);
		$this->assign('header_about', $about);
	}
	
	protected function initHeaderNews()
	{
		$aModel = D('ArticleCategory');
		$cates = $aModel->order('pid ASC,sort_order ASC')->getField('id,cate_name,catalog,pid,sort_order');
		$about = array();
		$news = array();
		if (is_array($cates)) {
			foreach ($cates as $cate){
				if ($cate['pid']) {
					if($cate['pid'] == $about['id']){
						$about['subs'][] = $cate;
					}elseif ($cate['pid'] == $news['id']){
						$news['subs'][] = $cate;
					}
				}else{
					if('about' == $cate['catalog']){
						$about = $cate;
					}elseif ('news' == $cate['catalog']){
						$news = $cate;
					}
				}
			}
		}
		$this->assign('header_news', $news['subs']);
	}
	
	protected function initHeaderProduct()
	{
		$model = D('ProductCategory');
		$where = array();
		$where['pid'] = array('eq', 0);
		$cates = $model->where($where)->order('pid ASC,sort_order ASC')->getField('id,cate_name,pid');
		$this->assign('header_products', $cates);
	}
}