<?php

class ArticleAction extends HomeAction
{
	public function index()
	{
		$id = intval($_REQUEST['id']);
		$model = D('Articles');
		$where = array();
		$where['id'] = $id;
		$where['status'] = 1;
		$article = $model->where($where)->find();
		if(!$article){
			$this->notfound();
		}
		$model->where($where)->setInc('visit_count');
		$this->assign('article', $article);
		$this->assign('current_category', D('ArticleCategory')->find($article['cate_id']));
		$this->display('Article:index');
	}
	
	public function category()
	{
		$model = D('ArticleCategory');
		$catalog = trim(strval($_REQUEST['catalog']));
		$current_category = $model->where(array('catalog'=>$catalog))->find();
		$cate_id = $current_category['id'];

		if('about' == $catalog){
		    $article = M('Articles')->where(array('cate_id'=>$cate_id,'state'=>1))->find();
		    $_REQUEST['id'] = $article['id'];
		    return $this->index();
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
		$page = $this->getPage($count, 10, __APP__.'/'.$catalog.'/page-__PAGE__.html');
		$articles = $article->where($where)->limit($page->firstRow,$page->listRows)->order('id DESC')->getField('id,title,cate_id,content,create_time');
		$this->assign('articles', $articles);
		$this->assign('current_category', $current_category);
		$this->assign('page', $page->show());
		$this->display('Article:category');
	}

	public function page()
	{
		$code = trim(strval($_REQUEST['code']));
		$model = D('ArticlePage');
		$page = $model->where(array('page_code'=>$code))->find();
		$model->where(array('id'=>$page['id']))->setInc('visit_count');
		$this->assign('article', $page);

		$this->latest_store();
		$this->hot_foods();
		$this->sidebar_healthy();
		$this->links();
		if($code=='service'){
		    $this->display('Article:service');
		}else{
		  $this->display('Article:page');
		}
	}
}
?>
