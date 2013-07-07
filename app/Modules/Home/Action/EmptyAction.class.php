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
    			case 4:
    				$this->store($_REQUEST['_URL_'][3]);
    				break;
    			default:
    		}
    	}elseif ('healthy' == strtolower(MODULE_NAME)){
    		if('index' == $name OR strpos($name, '-')){
    			A('Article')->category($name);
    		}else{
    			A('Article')->index($name);
    		}
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
    
    /**
     * 店铺页面
     * @param int $id
     */
    private function store($id)
    {
    	A('Index')->store($id);
    }

}
?>
