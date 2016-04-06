<?php

class IndexAction extends HomeAction
{
	public function index()
	{
	    $courses = M('Courses')->field('id,name,image,desc')->where(array('state'=>1))->limit(12)->order('sort_order ASC, dateline DESC')->select();
	    $this->assign('courses', $courses);
	    
	    $teachers = M('Teacher')->field('id,name,subject,image')->where(array('state'=>1))->limit(12)->order('sort_order ASC, dateline DESC')->select();
	    $this->assign('teachers', $teachers);
	    
	    $class = D('CourseClassView')->order('dateline DESC')->limit(3)->select();
	    $this->assign('classes', $class);

	    $this->assign('xuekus', $this->xuekus());
	    
	    $this->links();
// 	    $page_feature = D('ArticlePage')->field('thumb,content')->where(array('page_code'=>'feature'))->find();
// 	    $this->assign('page_feature', $page_feature);
// 	    $this->assign('news_list', $this->getArticleList('news', 10));
	    $this->assign('current_nav', 'index');
		$this->display('Index:index');
	}
	
	private function xuekus()
	{
	    $XkModel = D('ArticleCategory');
	    $xueku = $XkModel->field('id,cate_name,pid,pids')->where(array('pid'=>1))->order('sort_order ASC')->limit(8)->select();
	    if(is_array($xueku)){
	        foreach ($xueku as $k=>$xks){
	            $sub_xueku = $XkModel->field('id,cate_name,pid,pids')->where(array('pid'=>$xks['id']))->order('sort_order ASC')->limit(2)->select();
	            if(is_array($sub_xueku)){
	                foreach ($sub_xueku as $j=>$sxk){
	                    $sub_xueku[$j]['articles'] = $this->getXkArticles($sxk['id']);
	                }
	            }
	            $xueku[$k]['subs'] = $sub_xueku;
	        }
	    }
	    return $xueku;
	}
	
	private function getXkArticles($id)
	{
	    $cids = D('ArticleCategory')->where(array('pid'=>$id))->getField('id,cate_name');
	    if(is_array($cids)){
	        $ids = array_keys($cids);
	    }
	    if(is_array($ids)){
	        array_push($ids, $id);
	    }else{
	        $ids = array($id);
	    }
	    $idstr = implode(',', $ids);
	    $articles = D('Articles')->field('id,title,status')->where('cate_id IN ('.$idstr.')')->order('create_time DESC')->limit(7)->select();
	    return $articles;
	}
}
?>
