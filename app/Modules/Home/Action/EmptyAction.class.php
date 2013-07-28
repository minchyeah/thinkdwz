<?php

class EmptyAction extends HomeAction
{
	public function _initialize()
	{
		parent::_initialize();
	}
	
    public function _empty($name)
    {
    	if (in_array(strtolower(MODULE_NAME), array_keys($this->cities))) {
    		switch (count($_REQUEST['_URL_'])){
    			case 1:
    				$this->city($_REQUEST['_URL_'][0]);
    				break;
    			case 2:
    				$this->district($_REQUEST['_URL_'][1]);
    				break;
    			case 3:
    				$this->location($_REQUEST['_URL_'][1],$_REQUEST['_URL_'][2]);
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
    	}elseif (in_array(strtolower(MODULE_NAME), array('about','contact','terms'))){
    		$_REQUEST['code'] = strtolower(MODULE_NAME);
    		A('Article')->page();
    	}else {
    		$this->notfound();
    	}
    }
    
    /**
     * 城市页面
     * @param string $city 城市别名
     */
    private function city($city)
    {
    	A('Index')->index($city);
    }
    
    /**
     * 城区页面
     * @param string $district 城区别名
     */
    private function district($district)
    {
    	A('Index')->district($district);
    }
    
    /**
     * 商圈页面
     * @param string $district 城区别名
     * @param string $location 商圈别名
     */
    private function location($district, $location)
    {
    	A('Index')->location($district, $location);
    }

}
?>
