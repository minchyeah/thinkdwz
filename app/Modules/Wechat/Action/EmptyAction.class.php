<?php

class EmptyAction extends WechatAction
{
	public function _initialize()
	{
		parent::_initialize();
	}
	
    public function _empty($name)
    {
    	if (in_array(strtolower(MODULE_NAME), array_keys($this->districts))) {
    		switch (count($_REQUEST['_URL_'])){
    			case 1:
    				A('Index')->district($_REQUEST['_URL_'][0]);
    				break;
    			case 2:
    				A('Index')->location($_REQUEST['_URL_'][0],$_REQUEST['_URL_'][1]);
    				break;
    			default:
    		}
    	}elseif ('healthy' == strtolower(MODULE_NAME)){
    		if('index' == $name OR strpos($name, '-')){
    			$arr = explode('-', $name);
    			$_GET['cate_id'] = intval($arr[0]);
    			$_GET['page'] = intval($arr[1]);
    			A('Article')->category();
    		}else{
    			$_GET['id'] = intval($name);
    			A('Article')->index();
    		}
    	}elseif ('search' == strtolower(MODULE_NAME)){
    		A('Index')->search();
    	}elseif (in_array(strtolower(MODULE_NAME), array('about','contact','terms','fmeeting'))){
    		$_REQUEST['code'] = strtolower(MODULE_NAME);
    		A('Article')->page();
    	}else {
    		$this->notfound();
    	}
    }

}
?>
