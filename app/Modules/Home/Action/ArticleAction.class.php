<?php

class ArticleAction extends HomeAction
{
    public function index()
    {
    	$id = intval($_GET['id']);
    	$cate_id = intval($_GET['cate_id']);
    	$pid = intval($_GET['pid']);
    	$model = D('Articles');
    	$where = array();
    	$where['cate_id'] = $cate_id;
    	$where['id'] = $id;
    	$where['status'] = 1;
    	$article = $model->where($where)->find();
    	$model->where($where)->setInc('visit_count');
    	$this->assign('article', $article);
    	$this->assign('current_category', D('ArticleCategory')->find($cate_id));
    	$this->assign('parent_category', D('ArticleCategory')->find($pid));
    	$this->_sidebar_category();
        $this->display();
    }
    
    private function _sidebar_category($pid)
    {
    	$pid = $pid ? $pid : intval($_GET['pid']);
    	$model = D('ArticleCategory');
    	$where = array();
    	$where['pid'] = $pid;
    	$cates = $model->where($where)->getField('id,cate_name,catalog,pid');
    	$this->assign('sidebar_category', $cates);
    }
    
    public function category()
    {
    	$model = D('ArticleCategory');
    	$pid = intval($_GET['pid']);
    	$cate_id = intval($_GET['cate_id']);
    	$pid = $pid ? $pid : $cate_id;
    	$current_category = $model->find($cate_id);
    	$parent_category = $model->find($pid);
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
    	$articles = $article->where($where)->getField('id,title,cate_id,create_time');
    	$this->assign('articles', $articles);
    	$this->assign('current_category', $current_category);
    	$this->assign('parent_category', $parent_category);
    	$this->_sidebar_category($pid);
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
