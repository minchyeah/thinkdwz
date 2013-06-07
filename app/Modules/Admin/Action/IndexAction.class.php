<?php

class IndexAction extends AdminAction
{
    public function index()
    {
    	$model = D('AdminMenus');
    	$this->assign('ctop', $model->find(1));
    	$topMenus = $model->topMenus();
    	if(1 != $this->admin_id){
    		$tmp = array();
    		foreach ($topMenus as $v){
    			if($v['id'] != 2){
    				continue;
    			}else{
    				$tmp[] = $v;
    			}
    		}
    		$topMenus = $tmp;
    		$this->assign('leftmenus', $model->leftMenus(2));
    	}else{
    		$this->assign('leftmenus', $model->leftMenus(1));
    	}
    	$this->assign('topmenus', $topMenus);
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
