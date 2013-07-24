<?php

class IndexAction extends HomeAction
{
	public function index($city = '')
	{
		$city_id = $city ? $this->cities[$city]['id'] : $this->city_id;
		$DistrictModel = D('District');
		$LocationModel = D('Locations');
		$districts = array();
		$district_ids = array();
		foreach ($this->districts as $k=>$v){
			$district_ids[] = $v['id'];
			$districts[$v['type']][$v['id']] = $v;
			$where = ' FIND_IN_SET('.$v['id'].',`district`)';
			$limit = 'custom'==$v['type']? 9 : 11;
			$locations = $LocationModel->where($where)->order('sort_order ASC')->limit($limit)->getField('id,title,alias,store_count,district');
			$districts[$v['type']][$v['id']]['locations'] = $locations;
		}
		$this->assign('districts', $districts);

		$this->latest_store();
		$this->hot_foods();
		$this->sidebar_healthy();
		$this->links();
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

		$this->latest_store();
		$this->hot_foods();
		$this->sidebar_healthy();
		$this->links();
		$this->display('Index:district');
	}
	
	public function location($district = '', $in_loc = '')
	{
		$StoreTypeModel = D('StoreType');
		$store_types = $StoreTypeModel->order('sort_order ASC,id DESC')->select();
		$this->assign('store_types', $store_types);
		$tmparr = explode('-', $in_loc);
		$location = $tmparr[0];
		$_GET['page'] = intval($tmparr[1]);
		$type_id = intval($_GET['type']);
		$district = $this->districts[$district];
		$district_id = $district['id'];
		$loc_alias = F('location_alias');
		$loc_id = $loc_alias[$location]['id'];
		
		$model = D('Stores');
		$where = '`city_id`='.$this->city_id;
		$where .= ' AND FIND_IN_SET('.$loc_id.',`locations`)';
		if($type_id){
			$where .= ' AND FIND_IN_SET('.$type_id.',`types`)';
		}
		$count = $model->where($where)->count();
		$req = $_GET;
		unset($req['page']);
		unset($req['_URL_']);
		$http_query = http_build_query($req);
		$http_query = $http_query ? '?'.$http_query : '';
		$page = $this->getPage($count, 10, __APP__.'/'.$this->city_alias.'/'.$district['alias'].'/'.$loc_alias[$location]['alias'].'-__PAGE__.html'.$http_query);
		$stores = $model->where($where)->limit($page->firstRow,$page->listRows)->order('id DESC')->getField('id,name,image,rating,sendup_prices');
		$this->assign('count', $count);
		$this->assign('page', $page->show());
		$this->assign('stores', $stores);
		$this->assign('current_district', $district);
		$this->assign('current_location', $loc_alias[$location]);
		$this->display('Index:location');
	}
	
	public function search()
	{
		$StoreTypeModel = D('StoreType');
		$store_types = $StoreTypeModel->order('sort_order ASC,id DESC')->select();
		$this->assign('store_types', $store_types);
		
		$type_id = intval($_GET['type']);
		$key = trim(strval($_GET['key']));
		$key = in_array($key, array('关键词...', '请输入餐馆名或地址...')) ? '' : $key;
		
		$model = D('Stores');
		$where = '`city_id`='.$this->city_id;
		if($type_id){
			$where .= ' AND FIND_IN_SET('.$type_id.',`types`)';
		}
		if($key){
			$where .= ' AND ( `name` LIKE "%'.$key.'%" OR `address` LIKE "%'.$key.'%" )';
		}
		$count = $model->where($where)->count();
		$req = $_GET;
		unset($req['page']);
		unset($req['_URL_']);
		$page = $this->getPage($count, 10, __APP__.'/search/?page=__PAGE__&'.http_build_query($req));
		$stores = $model->where($where)->limit($page->firstRow,$page->listRows)->order('id DESC')->getField('id,name,image,rating,sendup_prices');
		$this->assign('count', $count);
		$this->assign('page', $page->show());
		$this->assign('stores', $stores);
		$this->display('Index:search');
	}
}
?>
