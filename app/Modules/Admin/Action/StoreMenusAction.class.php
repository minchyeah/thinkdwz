<?php

class StoreMenusAction extends AdminAction
{
    public function index()
    {
    	$store_id = intval($_REQUEST['store_id']);
    	$model = D('StoreMenus');
    	$where = array();
    	$where['store_id'] = $store_id;
    	$totalCount = $model->where($where)->count();
    	$currentPage = intval($_REQUEST['pageNum']);
    	$currentPage = $currentPage ? $currentPage : 1;
    	$numPerPage = 20;
    	$rowOffset = ($currentPage-1) * $numPerPage;
    	$list = $model->where($where)->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
    	
    	$this->assign('list', $list);
    	$this->assign('totalCount', $totalCount);
    	$this->assign('numPerPage', $numPerPage);
    	$this->assign('currentPage', $currentPage);
        $this->display();
    }
    
    public function add()
    {
    	$store_id = intval($_REQUEST['store_id']);
    	$this->display();
    }
    
    public function edit()
    {
    	$id =  intval($_GET['id']);
    	$model = D('StoreMenus');
    	$vo = $model->find($id);
    	$rs = D('Stores')->where(array('id'=>$vo['store_id']))->getField('name');
    	$vo['store_name'] = $rs;
    	$this->assign('vo', $vo);
    	$this->display('add');
    }
    
    public function save()
    {
    	$model = D('StoreMenus');
    	$_POST['store_id'] = intval($_POST['orgLookup_id']);
    	if ($_FILES['imgfile']['name']) {
    		$image = $this->saveImage($_FILES['imgfile']);
    		if ($image) {
    			$_POST['image'] = $image;
    		}
    	}
    	$data = $model->create();
    	if(!$data){
    		$this->error($model->getError());
    	}
    	if (!$data['id']) {
    		$rs = $model->add();
    	}else{
    		$rs = $model->save();
    	}
    	if(false !== $rs){
    		$this->success('保存成功！');
    	}else{
    		$this->error('保存失败！'.dump($data, false).$model->getDbError());
    	}
    }
}
?>
