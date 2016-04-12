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
		
		$this->assign('majors', $this->xuekus());
		
		$this->links();
		// $page_feature = D('ArticlePage')->field('thumb,content')->where(array('page_code'=>'feature'))->find();
		// $this->assign('page_feature', $page_feature);
		$this->assign('recruit_list', $this->getArticleList('recruit', 14));
		$this->assign('webucation_list', $this->getArticleList('webucation', 8));
		$this->assign('chengrengaokao_list', $this->getArticleList('chengrengaokao', 8));
		$this->assign('guide_list', $this->getArticleList('guide', 8));
		$this->assign('liniankaoshi_list', $this->getArticleList('liniankaoshi', 8));
		$this->assign('zsnews_list', $this->getArticleList('zsnews', 8));
		$this->assign('current_nav', 'index');
		$this->display('Index:index');
	}

	private function xuekus()
	{
		$XkModel = D('ArticleCategory');
		$xueku = $XkModel->field('id,cate_name,pid,pids')->where(array('pid'=>7))->order('sort_order ASC')->limit(5)->select();
		if(is_array($xueku)){
			foreach($xueku as $k=>$xks){
				$xueku[$k]['articles'] = $this->getXkArticles($xks['id']);
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
		$articles = D('Articles')->field('id,title,status')->where('cate_id IN (' . $idstr . ')')->order('id DESC')->limit(10)->select();
		return $articles;
	}
}
?>
