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
    	}elseif (in_array(strtolower(MODULE_NAME), array('about','contact','business','service'))){
    		$_REQUEST['code'] = strtolower(MODULE_NAME);
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
