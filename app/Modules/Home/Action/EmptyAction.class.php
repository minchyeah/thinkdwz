<?php

class EmptyAction extends HomeAction
{
	public function _initialize()
	{
		parent::_initialize();
	}
	
    public function _empty($name)
    {
    	if ('product' == strtolower(MODULE_NAME)){
    		$_GET['id'] = intval($name);
    		A('Products')->detail();
    	}elseif ('xueku' == strtolower(MODULE_NAME)){
    		if(is_numeric($name)){
    		    $_GET['id'] = intval($name);
    		    return A('Article')->index();
    		}
    		if('page-' == substr($name, 0, 5)){
    		    $_GET['page'] = substr($name, 5);
    		}
    		return A('Article')->category();
    	}elseif (in_array(strtolower(MODULE_NAME), array('vteach','news','jinbang'))){
    		$_GET['catalog'] = strtolower(MODULE_NAME);
    		if(is_numeric($name)){
    		    $_GET['id'] = intval($name);
    		    return A('Article')->index();
    		}
    		if('page-' == substr($name, 0, 5)){
    		    $_GET['page'] = substr($name, 5);
    		}
    		return A('Article')->category();
    	}elseif (in_array(strtolower(MODULE_NAME), array('about','topar','tostu','contact','feature'))){
    		$_GET['code'] = strtolower(MODULE_NAME);
    		A('Article')->page();
    	}else {
    		$this->notfound();
    	}
    }

}
?>
