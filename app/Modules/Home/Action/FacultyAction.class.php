<?php

class FacultyAction extends HomeAction
{
	public function index()
	{
		$this->display('Faculty:index');
	}
	
	public function category()
	{
		$model = D('ArticleCategory');
		$catalog = trim(strval($_GET['catalog']));
		$current_category = $model->where(array('catalog'=>$catalog))->find();
		$cate_id = $current_category['id'];

		if('about' == $catalog){
		    $article = M('Articles')->where(array('cate_id'=>$cate_id,'state'=>1))->find();
		    $_GET['id'] = $article['id'];
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
		$page = $this->getPage($count, 8, __APP__.'/'.$catalog.'/page-__PAGE__.html');
		$articles = $article->where($where)->limit($page->firstRow,$page->listRows)->order('id DESC')->getField('id,title,cate_id,content,thumb,create_time');
		$this->assign('articles', $articles);
		$this->assign('current_category', $current_category);
		$page->show();
		$this->assign('pager', $page);
		$this->display('Faculty:list');
	}
}
?>
