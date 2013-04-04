<?php

class IndexAction extends AdminAction
{
    public function index()
    {
    	$model = D('AdminMenus');
    	$this->assign('ctop', $model->find(1));
    	$this->assign('topmenus', $model->topMenus());
    	$this->assign('leftmenus', $model->leftMenus(1));
        $this->display();
    }
    
    public function main()
    {
    	$this->display();
    }
    
    public function menu()
    {
    	$id = intval($_GET['id']);
    	$model = D('AdminMenus');
    	$this->assign('ctop', $model->find($id));
    	$this->assign('leftmenus', $model->leftMenus($id));
    	$this->display();
    }

}
?>
