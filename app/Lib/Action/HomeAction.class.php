<?php

class HomeAction extends CommonAction
{
	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		parent::_initialize();
		$this->initHeader();
	}
	
	protected function initHeader()
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
		$this->assign('header_about', $about['subs']);
		$this->assign('header_news', $news['subs']);
	}
}