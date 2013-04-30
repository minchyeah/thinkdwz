<?php

class IndexAction extends HomeAction
{
    public function index($city = '')
    {
    	$city_id = $city ? $this->cities[$city]['id'] : $this->city_id;
    	$DistrictModel = D('District');
    	$districts = array();
    	$district_ids = array();
    	foreach ($this->districts as $k=>$v){
    		$district_ids[] = $v['id'];
    		$districts[$v['type']][$v['id']] = $v;
    	}
    	$LocationModel = D('Locations');
    	$where = '';
    	foreach ($district_ids as $v){
    		$v && $where .= ' FIND_IN_SET('.$v.',`district`) OR';
    	}
    	$where = trim($where, 'OR');
    	$locations = $LocationModel->where($where)->order('sort_order ASC')->getField('id,title,alias,store_count,district');
    	if(is_array($locations)){
	    	foreach ($locations as $v){
	    		$dis = explode(',', $v['district']);
	    		foreach ($dis as $did){
	    			$districts['region'][$did] && $districts['region'][$did]['locations'][] = $v;
	    			$districts['custom'][$did] && $districts['custom'][$did]['locations'][] = $v;
	    		}
	    	}
    	}
    	$this->assign('districts', $districts);
        $this->display('Index:index');
    }
    
    public function district($district = '')
    {
    	$district_id = $this->districts[$district]['id'];
    	$LocationModel = D('Locations');
    	$where = ' FIND_IN_SET('.$district_id.',`district`)';
    	$rs = $LocationModel->where($where)->order('letter ASC')->getField('id,title,alias,store_count,letter');
    	$locations = array();
    	if(is_array($rs)){
    		foreach ($rs as $k=>$v){
    			$locations[$v['letter']][] = $v;
    		}
    	}
    	$this->assign('locations', $locations);
    	$this->assign('district', $this->districts[$district]);
    	$this->display('Index:district');
    }
    
    public function location()
    {
    	$StoreTypeModel = D('StoreType');
    	$store_types = $StoreTypeModel->order('sort_order ASC,id DESC')->select();
    	$this->assign('store_types', $store_types);
    	$this->display('Index:location');
    }
    
    public function store()
    {
    	$this->display('Index:store');
    }
}
?>
