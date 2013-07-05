<?php

class ProductAction extends HomeAction
{
    public function index()
    {
    	$id = intval($_GET['id']);
    	$cate_id = intval($_GET['cate_id']);
    	$model = D('Products');
    	$where = array();
    	$where['cate_id'] = $cate_id;
    	$where['id'] = $id;
    	$where['status'] = 1;
    	$product = $model->where($where)->find();
    	$product['attrs'] = attrs_filter($product['product_attrs']);
    	$product['images'] = explode(',', $product['images']);
    	$this->assign('product', $product);
    	$category = D('ProductCategory')->find($product['cate_id']);
    	$this->assign('category', $category);
    	$this->assign('current_category', $category);
    	$this->assign('parent_category', D('ProductCategory')->find($category['pid']));
    	$this->_related_products($product['related_products']);
    	$this->_sidebar_category();
        $this->display();
    }
    
    private function _related_products($ids)
    {
    	$idarr = explode(',', str_replace('，', ',', $ids));
    	$model = D('Products');
    	$where = array();
    	$where['id'] = array('in', $idarr);
    	$where['status'] = 1;
    	$rs = $model->where($where)->limit(4)->getField('id,product_name name,thumb,cate_id');
    	$products = array();
    	$idarr = array_flip($idarr);
    	if (is_array($rs)) {
    		foreach ($rs as $k=>$v){
    			$products[$idarr[$k]] = $v;
    		}
    	}
    	ksort($products);
    	$this->assign('related_products', $products);
    }
    
    private function _sidebar_category()
    {
    	import('ORG.Util.Tree');
    	$model = D('ProductCategory');
    	$cates = $model->order('pid ASC,sort_order ASC')->select();
    	$tree = new Tree($cates, array('id','pid','subcates'));
    	$cates = $tree->leaf();
    	$this->assign('sidebar_category', $cates);
    }
    
    public function category()
    {
    	$model = D('ProductCategory');
    	$pid = intval($_GET['pid']);
    	$cate_id = intval($_GET['cate_id']);
    	$where = array();
    	$where['status'] = 1;
    	if ($cate_id) {
    		if(!$pid){
    			$pids = $model->where(array('pid'=>$cate_id))->getField('cate_name,id');
    			$where['cate_id'] = array('in', $pids);
    		}else{
    			$where['cate_id'] = $cate_id;
    			$this->assign('parent_category', $model->find($pid));
    		}
    	}
    	$this->assign('current_category', $model->find($cate_id));
    	$product = D('Products');
    	$count = $product->where($where)->count();
    	$page = $this->getPage($count, 15, __APP__.'/products/cate-'.$pid.'-'.$cate_id.'-__PAGE__.html');
    	$list = $product->where($where)->limit($page->firstRow,$page->listRows)->getField('id,product_name,thumb,cate_id,product_attrs,create_time');
    	$this->assign('list', $list);
    	$this->assign('cate_id', $cate_id);
    	$this->_sidebar_category();
    	$this->assign('page', $page->show());
    	$this->display();
    }

}
?>