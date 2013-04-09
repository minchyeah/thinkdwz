<?php

class ArticlePageAction extends AdminAction
{
    private function page($alias)
    {
    	$model = D('ArticlePage');
    	$where = array();
    	$where['page_code'] = $alias;
    	$this->assign('vo', $model->where($where)->find());
    	$this->display('Article:page');
    }
    
    public function contact()
    {
    	$this->page('contact');
    }
    
    public function aboutus()
    {
    	$this->page('aboutus');
    }
    
    public function terms()
    {
    	$this->page('terms');
    }
}
?>
