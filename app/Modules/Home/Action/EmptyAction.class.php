<?php

class EmptyAction extends HomeAction
{
	public function _initialize()
	{
		parent::_initialize();
	}
	
    public function _empty($name)
    {
    	if ('healthy' == strtolower(MODULE_NAME)){
    		if('index' == $name OR strpos($name, '-')){
    			$arr = explode('-', $name);
    			$_GET['cate_id'] = intval($arr[0]);
    			$_GET['page'] = intval($arr[1]);
    			A('Article')->category();
    		}else{
    			$_GET['id'] = intval($name);
    			A('Article')->index();
    		}
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
