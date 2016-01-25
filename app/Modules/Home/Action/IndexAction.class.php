<?php

class IndexAction extends HomeAction
{
	public function index()
	{
	    $courses = M('Courses')->field('id,name,desc')->where(array('state'=>1))->limit(6)->order('dateline DESC')->select();
	    $this->assign('courses', $courses);
	    
	    $teachers = M('Teacher')->field('id,name,image')->where(array('state'=>1))->limit(6)->order('sort_order DESC, dateline DESC')->select();
	    $this->assign('teachers', $teachers);
	    
	    $cases = M('Cases')->field('id,name,image')->where(array('state'=>1,'type'=>'case'))->limit(6)->order('sort_order DESC, dateline DESC')->select();
	    $this->assign('cases', $cases);

	    $envs = M('Env')->field('id,name,image')->where(array('state'=>1))->limit(6)->order('sort_order DESC, dateline DESC')->select();
	    $this->assign('envs', $envs);
	    
	    $this->links();
	    $page_feature = D('ArticlePage')->field('thumb,content')->where(array('page_code'=>'feature'))->find();
	    $this->assign('page_feature', $page_feature);
	    $this->assign('news_list', $this->getArticleList('news', 10));
		$this->display('Index:index');
	}
}
?>
