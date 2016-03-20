<?php

class XuekuAction extends HomeAction
{
	public function index()
	{
		$id = intval($_GET['id']);
		$model = D('Articles');
		$where = array();
		$where['id'] = $id;
		$where['status'] = 1;
		$article = $model->where($where)->find();
		if(!$article){
			$this->category();
		}
		$model->where($where)->setInc('visit_count');
		$current_category = D('ArticleCategory')->find($article['cate_id']);

		$next_page = $model->field('id,title')->where(array('state'=>1))->where(array('cate_id'=>$article['cate_id']))
                		->where("id<{$article['id']}")
                		->order('id DESC')
                		->find();
		$prev_page = $model->field('id,title')->where(array('state'=>1))->where(array('cate_id'=>$article['cate_id']))
                		->where("id>{$article['id']}")
                		->order('id ASC')
                		->find();
		
		$this->assign('article', $article);
	    $this->assign('prev_page', $prev_page);
	    $this->assign('next_page', $next_page);
		$this->assign('current_category', $current_category);
		$this->assign('current_position', $this->_build_current_position($current_category));
		$this->display('Xueku:index');
	}
	
	private function _build_current_position($cate)
	{
	    $pstr = trim($cate['id'].','.$cate['pids'], ',');
	    $str = '<a href="'.__APP__.'/xueku/cate-'.$pstr.'.html">'.$cate['cate_name'].'</a>>';
	    if(!$cate['pids']){
	        return $str;
	    }else{
	        $pids = explode(',', $cate['pids']);
	        $cid = array_shift($pids);
	        $str = $this->_build_current_position(D('ArticleCategory')->find($cid)).$str;
	    }
	    return $str;
	}
	
	public function category()
	{
		$model = D('ArticleCategory');
		$cate_arr = $_GET['cates'];
		$topid = intval(array_pop($_GET['cates']));
		$sid = intval(array_pop($_GET['cates']));
		$ssid = intval(array_pop($_GET['cates']));
		
		$top_cates = $model->field('id,cate_name,catalog')->where(array('pid'=>0))->order('sort_order ASC')->select();
		if($topid){
		    $sub_cates = $model->where(array('pid'=>$topid))->getField('id,cate_name,pid,pids');
            $this->assign('sub_cates', $sub_cates);
		}
		if($sid){
		  $sub_sub_cates = $model->where(array('pid'=>$sid))->getField('id,cate_name,pid,pids');
		  $this->assign('sub_sub_cates', $sub_sub_cates);
		}
		
		$cate_ids = array();
		if ($ssid) {
		    $cate_ids[] = $ssid;
		}elseif($sid){
		    $cate_ids[] = $sid;
		    if(is_array($sub_sub_cates)){
		        foreach ($sub_sub_cates as $ssc){
		            $cate_ids[] = $ssc['id'];
		        }
		    }
		}elseif($topid){
		    $cate_ids[] = $topid;
		    if(is_array($sub_cates)){
		        foreach ($sub_cates as $ssc){
		            $cate_ids[] = $ssc['id'];
		        }
		    }
		}
		
		$where = array();
		$where['status'] = 1;
		if(!empty($cate_ids)) $where['cate_id'] = array('in', $cate_ids);
		$article = D('Articles');
		$count = $article->where($where)->count();
		
		$cates_str = implode(',', $cate_arr);
		$page = $this->getPage($count, 10, __APP__.'/xueku/cate-'.$cates_str.'-page-__PAGE__.html');
		$articles = $article->where($where)->limit($page->firstRow,$page->listRows)->order('id DESC')->getField('id,title,cate_id,content,thumb,create_time');
        $this->assign('cate_arr', $cate_arr);
		$this->assign('topcates', $top_cates);
		$this->assign('articles', $articles);
		$this->assign('pager', $page->show());
		$this->display('Xueku:category');
	}
	
	public function _empty($name)
	{
        if(is_numeric($name)){
            $_GET['id'] = intval($name);
            return $this->index();
        }
        if('cate-' == substr($name, 0, 5)){
            $arr = explode('-', $name);
            $_GET['cates'] = explode(',', $arr[1]);
            $_GET['page'] = $arr[3];
            return $this->category();
        }
        $this->notfound();
	}
}
?>
