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
		$current_page = intval($_GET['page']);
    	$current_page = $current_page ? $current_page : 1;
		$total_page = ceil($total_count/6);
		$pre_page = '';
		$next_page = '';
		
		$volist = $products->where($where)->limit(($current_page-1)*6, 6)->select();
		
		$this->assign('current_page', $current_page);
		$this->assign('total_page', $total_page);
		$this->assign('total_count', $total_count);
		$this->assign('pre_page', $pre_page);
		$this->assign('next_page', $next_page);
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
}
?>
