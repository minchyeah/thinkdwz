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
		$this->assign('article', $article);
		$this->assign('current_category', $current_category);
		$this->assign('current_nav', $current_category['catalog']);
		$this->display('Article:index');
	}
	
	public function category()
	{
		$model = D('ArticleCategory');
		$catalog = trim(strval($_GET['catalog']));
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
		$article = D('Articles');
		$count = $article->where($where)->count();
		$page = $this->getPage($count, 10, __APP__.'/'.$catalog.'/page-__PAGE__.html');
		$articles = $article->where($where)->limit($page->firstRow,$page->listRows)->order('id DESC')->getField('id,title,cate_id,content,thumb,create_time');
		$this->assign('articles', $articles);
		$this->assign('current_category', $current_category);
		$this->assign('current_nav', $current_category['catalog']);
		$this->assign('pager', $page->show());
		$this->display('Article:category');
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
