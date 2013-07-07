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
		$this->assign('article', $article);
		$this->assign('current_category', D('ArticleCategory')->find($article['cate_id']));
		$this->latest_store();
		$this->hot_foods();
		$this->sidebar_healthy();
		$this->links();
		$this->display('Article:index');
	}
	
	public function category()
	{
		$model = D('ArticleCategory');
		$cate_id = intval($_GET['cate_id']);
		$current_category = $model->find($cate_id);
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
		$page = $this->getPage($count, 10, __APP__.'/healthy/'.$cate_id.'-__PAGE__.html');
		$articles = $article->where($where)->limit($page->firstRow,$page->listRows)->order('id DESC')->getField('id,title,cate_id,create_time');
		$this->assign('articles', $articles);
		$this->assign('current_category', $current_category);
		$this->assign('page', $page->show());
		$this->latest_store();
		$this->hot_foods();
		$this->sidebar_healthy();
		$this->links();
		$this->display('Article:category');
	}

	public function page()
	{
		$code = trim(strval($_REQUEST['code']));
		$model = D('ArticlePage');
		$page = $model->where(array('page_code'=>$code))->find();
		$model->where(array('id'=>$page['id']))->setInc('visit_count');
		$this->assign('page', $page);

		$model = D('Articles');
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['statue'] = 1;
		$where['cate_id'] = 2;
		$sidebar_list = $model->where($where)->getField('id,title,cate_id,create_time');
		$this->assign('sidebar_list', $sidebar_list);
		$this->display();
	}
}
?>
