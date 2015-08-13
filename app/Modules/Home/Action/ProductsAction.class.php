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
	    $model = D('Order');
	    if($_POST['contact'] == '请输入您的姓名...'){
	        $_POST['contact'] = '';
	    }
	    if($_POST['phone'] == '请输入您的联系电话...'){
	        $_POST['phone'] = '';
	    }
	    if($_POST['address'] == '请输入您的联系地址...'){
	        $_POST['address'] = '';
	    }
	    if($_POST['captcha'] == ''){
	        $this->error('请输入验证码');
	    }
	    if(!$this->checkCaptcha()){
	        $this->error('验证码错误');
	    }
	    $_POST['ip'] = get_client_ip();
	    $data = $model->create();
	    if(!$data){
	        $this->error($model->getError());
	    }
	    $model->startTrans();
	    if (!$data['id']) {
	        $data['dateline'] = time();
	        $rs = $model->add($data);
	    }else{
	        $rs = $model->save($data);
	    }
	    if(false !== $rs){
	        $model->commit();
	        $this->success('预订成功！');
	    }else{
	        $model->rollback();
	        $this->error('预订失败！');
	    }
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
