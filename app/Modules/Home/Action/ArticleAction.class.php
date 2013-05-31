<?php

class ArticleAction extends HomeAction
{
    public function index()
    {
    	$model = D('Articles');
        $this->display();
    }
    
    public function category()
    {
    	import('ORG.Util.Tree');
    	$model = D('ArticleCategory');
    	$cates = $model->order('pid ASC')->select();
    	$tree = new Tree($cates, array('id','pid','subcates'));
    	$category = $tree->leaf();
    	$this->assign('category', $this->buildCategoryTree($category, true));
    	$this->display();
    }
    
    public function page()
    {
    	$code = trim(strval($_REQUEST['code']));
    	$model = D('ArticlePage');
    	$page = $model->where(array('page_code'=>$code))->find();
    	$this->display();
    }
}
?>
