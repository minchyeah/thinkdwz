<?php

class IndexAction extends HomeAction
{
	public function index()
	{
	    $cases = M('Cases')->where(array('state'=>1))->limit(6)->order('sort_order DESC, dateline DESC')->select();
	    $this->assign('cases', $cases);
	    
	    $this->links();
	    $page_feature = D('ArticlePage')->field('thumb,content')->where(array('page_code'=>'feature'))->find();
	    $this->assign('page_feature', $page_feature);
	    $this->assign('news_list', $this->getArticleList('news', 10));
		$this->display('Index:index');
	}
}
?>
