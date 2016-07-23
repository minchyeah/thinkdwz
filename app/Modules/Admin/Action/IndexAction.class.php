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
    		$leftid = 0;
    		foreach ($topMenus as $k=>$v){
    			if($v['id'] == 1){
    				continue;
    			}else{
    				$tmp[] = $v;
    				if(!$leftid){
    					$leftid = $v['id'];
    				}
    			}
    		}
    		$topMenus = $tmp;
    		$this->assign('leftmenus', $model->leftMenus($leftid));
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
