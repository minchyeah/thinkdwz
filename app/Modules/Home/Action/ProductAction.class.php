<?php

class ProductAction extends HomeAction
{
    public function index()
    {
        $this->display();
    }
    
    private function _sidebar_category()
    {
    	$model = D('ArticleCategory');
    	$where = array();
    	$where['pid'] = array('neq', 0);
    	$cates = $model->where($where)->getField('id,cate_name,catalog');
    	$this->assign('sidebar_category', $cates);
    }
    
    public function category()
    {
    	$this->display();
    }

}
?>