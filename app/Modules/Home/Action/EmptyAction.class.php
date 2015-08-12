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
    	}elseif (in_array(strtolower(MODULE_NAME), array('about','news'))){
    		$_GET['catalog'] = strtolower(MODULE_NAME);
    		if(is_numeric($name)){
    		    $_GET['id'] = intval($name);
    		    return A('Article')->index();
    		}
    		if('page-' == substr($name, 0, 5)){
    		    $_GET['page'] = substr($name, 5);
    		}
    		return A('Article')->category();
    	}elseif (in_array(strtolower(MODULE_NAME), array('business','service'))){
    		$_GET['code'] = strtolower(MODULE_NAME);
    		A('Article')->page();
    	}else {
    		$this->notfound();
    	}
    }
    
    private function saveComment() {
        $name = trim(strip_tags($_POST['name']));
        $name = trim(strip_tags($_POST['phone']));
        $name = trim(strip_tags($_POST['email']));
        $name = trim(strip_tags($_POST['address']));
        $name = trim(strip_tags($_POST['content']));
        $name = trim(strip_tags($_POST['name']));
        $name = trim(strip_tags($_POST['name']));
    }

}
?>
