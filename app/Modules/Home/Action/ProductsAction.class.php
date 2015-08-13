<?php

class ProductsAction extends HomeAction
{
	public function index()
	{
		$id = intval($_GET['id']);
		if($id){
			return $this->detail();
		}
		$products = M('Products');
		$where = array('state'=>1);
	    $total = $products->field('COUNT(1) count')->where($where)->find();
	    $total_count = intval($total['count']);

	    $pager = $this->getPage($total_count, 6, __APP__.'/products/page-__PAGE__.html');
	    $pager->show();
		
		$volist = $products->where($where)->limit($pager->firstRow, $pager->listRows)->order('id DESC')->select();
		
		$this->assign('pager', $pager);
		$this->assign('volist', $volist);
		$this->display('list');
	}
	
	public function detail($id=0)
	{
	    $id = $id ? $id : intval($_GET['id']);
	    $products = M('Products');
	    $product = $products->where(array('state'=>1))
	                       ->where(array('id'=>$id))
	                       ->find();
	    $images = json_decode($product['images'], true);
	    $this->assign('product', $product);
	    $this->assign('images', $images);
		$this->display('Products:detail');
	}
	
	public function order()
	{
	    
	}
	
	public function _empty($name)
	{
	    if('page-' == substr($name, 0, 5)){
	        $_GET['page'] = substr($name, 5);
	        return $this->index();
	    }
	}
}
?>
