<?php

class AdminMenusAction extends AdminAction
{
    public function index()
    {
    	$model = D('AdminMenus');
    	$menus = $model->order('pid ASC,sort_order ASC')->select();
    	$this->assign('list', $menus);
        $this->display();
    }
    
    public function add()
    {
    	$model = D('AdminMenus');
    	$this->assign('topmenus', $model->topMenus());
    	$this->display();
    }
    
    public function edit()
    {
    	$id =  intval($_GET['id']);
    	$model = D('AdminMenus');
    	$this->assign('topmenus', $model->topMenus());
    	$this->assign('vo', $model->find($id));
    	$this->display('add');
    }
    
    public function save()
    {
    	$model = D('AdminMenus');
    	if(!$model->create()){
    		$this->error($model->getError());
    	}
    	if ($model->id) {
    		$rs = $model->add();
    	}else{
    		$rs = $model->save();
    	}
    	if(false !== $rs){
    		$this->success('保存成功！');
    	}else{
    		$this->error('保存失败！');
    	}
    }
}
?>
