<?php

class ProductsAction extends HomeAction
{
	public function index()
	{
		$id = intval($_GET['id']);
		if($id){
			$this->detail();
		}
		$products = M('Products');
		$total_count = $products->where(array('state'=>1))->select();
		$this->display('list');
	}
	
	public function detail()
	{
	    $id = intval($_GET['id']);
	    $products = M('Products');
	    $product = $products->where(array('state'=>1))
	                       ->where(array('id'=>$id))
	                       ->select();
	    $this->assign('product', $product);
		$this->display('detail');
	}
}
?>
