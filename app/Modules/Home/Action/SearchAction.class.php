<?php

class SearchAction extends HomeAction
{
	public function index()
	{
	    $key = trim(strip_tags($_GET['key']));
	    $model = D('ArticleCategory');
	    $topid = 1;
	    $current_cate_id = 1;
	    $current_category = D('ArticleCategory')->find($current_cate_id);
	    
	    $top_cates = $model->field('id,cate_name,pid,pids')->where(array('pid'=>1))->order('sort_order ASC')->select();
	    if($topid){
	        $sub_cates = $model->where(array('pid'=>$topid))->order('sort_order ASC')->getField('id,cate_name,pid,pids');
	        $this->assign('sub_cates', $sub_cates);
	    }
	    $cids = $model->where("FIND_IN_SET({$current_cate_id},pids)")->getField('id,pids');
	    $cate_ids = array($current_cate_id);
	    if(is_array($cids)){
	        $cate_ids = array_merge($cate_ids, array_keys($cids));
	    }
	    $where = array();
	    $where['status'] = 1;
	    if(!empty($cate_ids)) $where['cate_id'] = array('in', $cate_ids);
	    $article = D('Articles');
	    $count = $article->where($where)->where("title LIKE '%{$key}%' OR content LIKE '%{$key}%'")->count();
	    $page = $this->getPage($count, 10, __APP__.'/search/?key='.$key.'&page=__PAGE__');
	    $articles = $article->where($where)->where("title LIKE '%{$key}%' OR content LIKE '%{$key}%'")->limit($page->firstRow,$page->listRows)->order('id DESC')->getField('id,title,cate_id,content,thumb,create_time');
	    $this->assign('articles', $articles);
	    $this->assign('pager', $page->show());
		$this->display('Index:search');
	}
}
?>
