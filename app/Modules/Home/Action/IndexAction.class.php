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
			$limit = 'custom'==$v['type']? 14 : 10;
			$locations = $LocationModel->where($where)->order('sort_order ASC')->limit($limit)->getField('id,title,alias,store_count,district');
			$districts[$v['type']][$v['id']]['locations'] = $locations;
		}
		$total_store_count = D('Stores')->where(array('city_id'=>$this->city_id))->count();
		$this->assign('total_store_count', $total_store_count);
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
		if(!$district_id){
			$this->notfound();
		}
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
		
		$total_store_count = D('Stores')->where(array('city_id'=>$this->city_id))->count();
		$this->assign('total_store_count', $total_store_count);

		$this->latest_store();
		$this->hot_foods();
		$this->sidebar_healthy();
		$this->links();
		$this->display('Index:district');
	}
	
	public function location($district = '', $in_loc = '')
	{
		$model = D('Stores');
		$StoreTypeModel = D('StoreType');
		$store_types = $StoreTypeModel->order('sort_order ASC,id DESC')->select();
		$tmparr = explode('-', $in_loc);
		$_GET['page'] = intval($tmparr[1]);
		$location_id = $tmparr[0];
		$locations = F('locations');
		$location = $locations[$location_id];
		if(!$location){
			$this->notfound();
		}
		if (is_array($store_types)) {
			foreach ($store_types as $k=>$v){
				$where = '`city_id`='.$this->city_id;
				$where .= ' AND FIND_IN_SET('.$location_id.',`locations`)';
				$where .= ' AND FIND_IN_SET('.$v['id'].',`types`)';
				$store_types[$k]['store_count'] =  $model->where($where)->count();
			}
		}
		$type_id = intval($_GET['type']);
		$district = $this->districts[$district];
		$district_id = $district['id'];
		
		$where = '`city_id`='.$this->city_id;
		$where .= ' AND FIND_IN_SET('.$location_id.',`locations`)';
		$all_store_count = $model->where($where)->count();
		if($type_id){
			$where .= ' AND FIND_IN_SET('.$type_id.',`types`)';
		}
		$count = $model->where($where)->count();
		
		$sort = trim(strval($_REQUEST['sort']));
		switch ($sort){
			case 'score':
				$sort_order = 'rating DESC,id DESC';
				break;
			case 'recommend':
				$sort_order = 'recommend DESC,id DESC';
				break;
			default:
				$sort_order = 'id DESC';
				break;
		}

		$req = $_GET;
		unset($req['page']);
		unset($req['_URL_']);
		$http_query = http_build_query($req);
		$http_query = $http_query ? '?'.$http_query : '';
		$page = $this->getPage($count, 20, city_domain($this->city_alias).'/'.$district['alias'].'/'.$location_id.'-__PAGE__.html'.$http_query);
		$stores = $model->where($where)->limit($page->firstRow,$page->listRows)->order($sort_order)->getField('id,name,image,rating,sendup_prices');
		$this->assign('count', $count);
		$this->assign('page', $page->show());
		$this->assign('stores', $stores);
		$this->assign('store_types', $store_types);
		$this->assign('all_store_count', $all_store_count);
		$this->assign('current_district', $district);
		$this->assign('current_location', $location);

		$location_history = unserialize(cookie('location_history'));
		if(!$location_history){
			$location_history = array();
		}
		foreach ($location_history as $k=>$v){
			if($v['id'] == $location['id']){
				unset($location_history[$k]);
			}
		}
		if (count($location_history) >= 3){
			array_pop($location_history);
		}
		array_unshift($location_history, array('city'=>$this->city_alias,'alias'=>$district['alias'],'id'=>$location['id'],'title'=>$location['title']));
		array_unique($location_history);
		cookie('location_history', serialize($location_history), 30*24*3600);
		
		$this->display('Index:location');
	}
	
	public function search()
	{
		$model = D('Stores');
		$StoreTypeModel = D('StoreType');
		$store_types = $StoreTypeModel->order('sort_order ASC,id DESC')->select();
		
		$type_id = intval($_GET['type']);
		$key = trim(strval($_GET['key']));
		$key = in_array($key, array('关键词...', '请输入餐馆名或地址...')) ? '' : $key;

		if (is_array($store_types)) {
			foreach ($store_types as $k=>$v){
				$where = '`city_id`='.$this->city_id;
				$where .= ' AND FIND_IN_SET('.$v['id'].',`types`)';
				if($key){
					$where .= ' AND ( `name` LIKE "%'.$key.'%" OR `address` LIKE "%'.$key.'%" )';
				}
				$store_types[$k]['store_count'] =  $model->where($where)->count();
			}
		}
		
		$where = '`city_id`='.$this->city_id;
		if($key){
			$where .= ' AND ( `name` LIKE "%'.$key.'%" OR `address` LIKE "%'.$key.'%" )';
		}
		$all_store_count = $model->where($where)->count();
		if($type_id){
			$where .= ' AND FIND_IN_SET('.$type_id.',`types`)';
		}
		$count = $model->where($where)->count();
		
		$sort = trim(strval($_REQUEST['sort']));
		switch ($sort){
			case 'score':
				$sort_order = 'rating DESC,id DESC';
				break;
			case 'recommend':
				$sort_order = 'recommend DESC,id DESC';
				break;
			default:
				$sort_order = 'id DESC';
				break;
		}

		$req = $_GET;
		unset($req['page']);
		unset($req['_URL_']);
		$page = $this->getPage($count, 20, city_domain($this->city_alias).'/search/?page=__PAGE__&'.http_build_query($req));
		$stores = $model->where($where)->limit($page->firstRow,$page->listRows)->order($sort_order)->getField('id,name,image,rating,sendup_prices');
		$this->assign('count', $count);
		$this->assign('page', $page->show());
		$this->assign('stores', $stores);
		$this->assign('search_key', $key);
		$this->assign('store_types', $store_types);
		$this->assign('all_store_count', $all_store_count);
		$this->display('Index:search');
	}
}
?>
