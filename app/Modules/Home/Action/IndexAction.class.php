<?php
class IndexAction extends HomeAction
{
	public function index()
	{
		$cases_category = M('CasesCategory')->field('id,cate_name')->order('sort_order ASC')->limit(2)->select();
		$this->assign('cases_category', $cases_category);
		
		$cases = M('Cases')->field('*')->order('dateline DESC')->limit(10)->select();
		$this->assign('cases', $cases);
		
		$cids = array(22);
		
		$sids = M('TeamCategory')->where(array('pid'=>22))->field('id')->select();
		if(is_array($sids)){
			foreach ($sids as $v){
				array_push($cids, intval($v['id']));
			}
		}
		
		$designers = M('TeamMember')->field('id,name,image,title')->where(array('cate_id'=>array('IN', $cids)))->limit(9)->order('sort_order ASC, dateline DESC')->select();
		$this->assign('designers', $designers);
		
		$index_slider = D('Slider')->field('id,target,image,title')->where(array('position'=>'index'))->order('sort_order DESC')->select();
		$this->assign('index_slider', $index_slider);
		
		$index_engineer_slider = D('Slider')->field('id,target,image,title')->where(array('position'=>'index_engineer'))->order('sort_order DESC')->select();
		$this->assign('index_engineer_slider', $index_engineer_slider);
		
		$this->assign('activity_list', $this->getArticleList('activity', 4));
		
		$this->assign('newslist', $this->xuekus());
		
		$this->assign('current_nav', 'index');
		$this->display('Index:index');
	}

	private function xuekus()
	{
		$XkModel = D('ArticleCategory');
		$xueku = $XkModel->field('id,cate_name,pid,pids')->where(array('pid'=>1))->order('sort_order ASC')->limit(3)->select();
		if(is_array($xueku)){
			foreach($xueku as $k=>$xks){
				$xueku[$k]['articles'] = $this->getArticles($xks['id']);
			}
		}
		
		return $xueku;
	}

	private function getArticles($id)
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
		$articles = D('Articles')->field('id,title,thumb')->where('cate_id IN (' . $idstr . ')')->order('create_time DESC')->limit(5)->select();
		return $articles;
	}
}
?>
