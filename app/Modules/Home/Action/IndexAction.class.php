<?php

class IndexAction extends HomeAction
{
    public function index()
    {
    	$this->_index_slider();
    	$this->_index_product();
    	$this->_index_news();
        $this->display('Index:index');
    }
    
    private function _index_slider()
    {
    	
    }
    
    private function _index_product()
    {
    	$model = D('Products');
    	$where = array();
    	$where['status'] = 1;
    	$product = $model->where($where)->limit(10)->getField('id,product_name name,cate_id,thumb');
    	$this->assign('product', $product);
    }
    
    private function _index_news()
    {
    	$model = D('Articles');
    	$where = array();
    	$where['status'] = 1;
    	$news = $model->where($where)->limit(10)->getField('id,title,cate_id,create_time');
    	$this->assign('news', $news);
    	$this->assign('news_cate', D('ArticleCategory')->getField('id,pid'));
    }
}
?>
