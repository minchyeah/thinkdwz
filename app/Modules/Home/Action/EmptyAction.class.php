<?php

class EmptyAction extends HomeAction
{
	public function _initialize()
	{
		parent::_initialize();
	}
	
    public function _empty($name)
    {
        if (in_array(strtolower(MODULE_NAME), array('major','webucation','news','material','guide','recruit','liniankaoshi','zsnews','chengrengaokao'))){
    		$_GET['catalog'] = strtolower(MODULE_NAME);
    		if(is_numeric($name)){
    		    $_GET['id'] = intval($name);
    		    return A('Article')->index();
    		}
    		if('page-' == substr($name, 0, 5)){
    		    $_GET['page'] = substr($name, 5);
    		}elseif('cate-' == substr($name, 0, 5)){
    			$args = explode('-', $name);
    			$_GET['cate_id'] = intval($args[1]);
    			$_GET['page'] = intval($args[3]);
    		}
    		return A('Article')->category();
    	}elseif (in_array(strtolower(MODULE_NAME), array('about','contact'))){
    		$_GET['code'] = strtolower(MODULE_NAME);
    		A('Article')->page();
    	}else {
    		$this->notfound();
    	}
    }

}
?>
