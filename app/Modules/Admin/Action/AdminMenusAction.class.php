<?php

class AdminMenusAction extends AdminAction
{
    public function index()
    {
    	$model = D('AdminMenus');
    	$totalCount = $model->count();
    	$currentPage = intval($_REQUEST['pageNum']);
    	$currentPage = $currentPage ? $currentPage : 1;
    	$numPerPage = 30;
    	$rowOffset = ($currentPage-1) * $numPerPage;
    	$menus = $model->order('pid ASC,sort_order ASC')->limit($rowOffset . ',' . $numPerPage)->select();
    	
    	$this->assign('list', $menus);
    	$this->assign('totalCount', $totalCount);
    	$this->assign('numPerPage', $numPerPage);
    	$this->assign('currentPage', $currentPage);
        $this->display();
    }
    
    public function add()
    {
    	$model = D('AdminMenus');
    	$this->assign('topmenus', $model->topMenus(true));
    	$this->display();
    }
    
    public function edit()
    {
    	$id =  intval($_GET['id']);
    	$model = D('AdminMenus');
    	$this->assign('topmenus', $model->topMenus(true));
    	$this->assign('vo', $model->find($id));
    	$this->display('add');
    }
    
    public function save()
    {
    	$model = D('AdminMenus');
    	$data = $model->create();
    	$data['params'] = '';
    	$data['group_name'] = '';
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
