<?php

class ArticleAction extends HomeAction
{
	public function index()
	{
		$id = intval($_GET['id']);
		$model = D('Articles');
		$where = array();
		$where['id'] = $id;
		$where['status'] = 1;
		$article = $model->where($where)->find();
		if(!$article){
			$this->notfound();
		}
		$model->where($where)->setInc('visit_count');
		$current_category = D('ArticleCategory')->find($article['cate_id']);

		$next_page = $model->field('id,title')->where(array('state'=>1))->where(array('cate_id'=>$article['cate_id']))
                		->where("id<{$article['id']}")
                		->order('id DESC')
                		->find();
		$prev_page = $model->field('id,title')->where(array('state'=>1))->where(array('cate_id'=>$article['cate_id']))
                		->where("id>{$article['id']}")
                		->order('id ASC')
                		->find();
		
		$this->assign('article', $article);
	    $this->assign('prev_page', $prev_page);
	    $this->assign('next_page', $next_page);
		$this->assign('current_category', $current_category);
		$this->assign('current_nav', $current_category['catalog']);
		$this->assign('current_position', $this->_build_current_position($current_category, $catalog));
		$this->display('Article:index');
	}
	
	public function category()
	{
		$model = D('ArticleCategory');
		$catalog = trim(strval($_GET['catalog']));
		$cate_id = intval($_GET['cate_id']);
		if($cate_id){
			$current_category = $model->find($cate_id);
		}else{
			$current_category = $model->where(array('catalog'=>$catalog))->find();
			$cate_id = $current_category['id'];
		}
		$sub_cates = $model->where(array('pid'=>$cate_id))->getField('id,cate_name');
		$cate_ids = array();
		if ($sub_cates) {
			$cate_ids = array_keys($sub_cates);
		}
		$cate_ids[] = $cate_id;
		$where = array();
		$where['status'] = 1;
		$where['cate_id'] = array('in', $cate_ids);
		$article = D('Articles');
		$count = $article->where($where)->count();
		if($catalog == $current_category['catalog']){
			$page = $this->getPage($count, 10, __APP__.'/'.$catalog.'/page-__PAGE__.html');
		}else{
			$page = $this->getPage($count, 10, __APP__.'/'.$catalog.'/cate-'.$current_category['id'].'-page-__PAGE__.html');
		}
		$articles = $article->where($where)->limit($page->firstRow,$page->listRows)->order('id DESC')->getField('id,title,cate_id,content,thumb,create_time');
		$this->assign('articles', $articles);
		$this->assign('current_category', $current_category);
		$this->assign('current_nav', $catalog);
		$this->assign('current_position', $this->_build_current_position($current_category, $catalog));
		$this->assign('sub_cates', $sub_cates);
		$this->assign('pager', $page->show());
		if(file_exists(THEME_PATH.'/Article/'.$catalog.'.html')){
			$this->display('Article:'.$catalog);
		}else{
			$this->display('Article:category');
		}
	}

	private function _build_current_position($cate)
	{
		if(!$cate['pids']){
			$str = '<a href="'.__APP__.'/'.$cate['catalog'].'/">'.$cate['cate_name'].'</a>>';
		}else{
			$pids = explode(',', $cate['pids']);
			$topcate = D('ArticleCategory')->field('id,cate_name,catalog,pid,pids')->find(end($pids));
			$cid = array_shift($pids);
			$str = '<a href="'.__APP__.'/'.$topcate['catalog'].'/cate-'.$cate['id'].'.html">'.$cate['cate_name'].'</a>>';
			$str = $this->_build_current_position(D('ArticleCategory')->field('id,cate_name,catalog,pid,pids')->find($cid)).$str;
		}
		return $str;
	}

	public function page()
	{
		$code = trim(strval($_GET['code']));
		$model = D('ArticlePage');
		$page = $model->where(array('page_code'=>$code))->find();
		$model->where(array('id'=>$page['id']))->setInc('visit_count');
		$this->assign('page', $page);
		$this->assign('current_nav', $code);
		if(file_exists(THEME_PATH.'/Article/'.$code.'.html')){
		    $this->display('Article:'.$code);
		}else{
			$this->display('Article:page');
		}
	}
}
?>
