<?php

class DistrictAction extends AdminAction
{
    public function index()
    {
    	import('ORG.Util.Tree');
    	$model = D('District');
    	$where = array();
    	$list = $model->where($where)->order('pid ASC')->select();
    	$tree = new Tree($list);
    	if(1 != $this->admin_id){
    		$this->assign('city_id', $this->city_id);
    		$this->assign('list', $tree->leaf($this->city_id));
    	}else{
    		$this->assign('list', $tree->leaf());
    	}
        $this->display();
    }
    
    private function _assign_province()
    {
    	$model = D('District');
    	$where = array();
    	$where['pid'] = 0;
    	$provinces = $model->where($where)->select();
    	$this->assign('provinces', $provinces);
    }
    
    private function _assign_city($pid)
    {
    	$model = D('District');
    	$where = array();
    	$where['pid'] = $pid;
    	$cities = $model->where($where)->select();
    	$this->assign('cities', $cities);
    }
    
    public function add()
    {
    	$type = trim($_REQUEST['type']);
    	$pid = intval($_REQUEST['pid']);
    	if($type=='city' && $pid){
    		$this->assign('pid', $pid);
    		$this->_assign_city($pid);
    	}
    	$this->_assign_province();
    	$this->assign('type', $type);
    	$this->assign('city_id', $this->city_id);
    	$this->display();
    }
    
    public function edit()
    {
    	$type = trim($_REQUEST['type']);
    	$pid = intval($_REQUEST['pid']);
    	if($type=='city' && $pid){
    		$this->assign('pid', $pid);
    		$this->_assign_city($pid);
    	}
    	$this->_assign_province();
    	$this->assign('type', $type);
    	
    	$id =  intval($_GET['id']);
    	$model = D('District');
    	$pvo = $model->find($pid);
    	if('city' == $pvo['type']){
    		$this->_assign_city($pvo['pid']);
    	}
    	$vo = $model->find($id);
    	$this->assign('pvo', $pvo);
    	$this->assign('vo', $vo);
    	$this->assign('city_id', $this->city_id);
    	$this->display('add');
    }
    
    public function save()
    {
    	if(in_array(trim($_REQUEST['type']), array('city','province'))){
    		if(!$_POST['alias']){
	    		import('@.Util.Pinyin');
		    	$pinyin = new Pinyin();
		    	$_POST['alias'] = str_replace(' ', '', $pinyin->output($_REQUEST['title']));
    		}
    	}else{
    		$_POST['pid'] = intval($_REQUEST['city']);
    	}
    	$model = D('District');
    	$data = $model->create();
    	if(!$data){
    		$this->error($model->getError());
    	}
    	if (!$data['id']) {
    		$rs = $model->add();
    	}else{
    		$rs = $model->save();
    	}
    	if(false !== $rs){
    		$this->success('保存成功！');
    	}else{
    		$this->error('保存失败！'.dump($data, false).$model->getDbError());
    	}
    }
    
    public function multiselect()
    {
    	$model = D('District');
    	$city_id = intval($_REQUEST['city_id']);
    	$map = array();
    	$city_id && $map['pid'] = $city_id;
    	$tree = $model->tree($map,$_REQUEST['link'],$_REQUEST['selparent']);
    	$this->assign('tree',$tree);
    	$this->display('Public:multiselect');
    }
    
    public function lookup()
    {
    	$model = D('District');
    	$totalCount = $model->count();
    	$currentPage = intval($_REQUEST['pageNum']);
    	$currentPage = $currentPage ? $currentPage : 1;
    	$numPerPage = 20;
    	$rowOffset = ($currentPage-1) * $numPerPage;
    	$list = $model->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
    
    	$this->assign('list', $list);
    	$this->assign('totalCount', $totalCount);
    	$this->assign('numPerPage', $numPerPage);
    	$this->assign('currentPage', $currentPage);
    	$this->display();
    }
    
    public function lookupSuggest()
    {
    	$model = D('District');
    	$inputValue = trim(strval($_REQUEST['inputValue']));
    	$where = array();
    	$inputValue && $where['title'] = array('like', '%'.$inputValue.'%');
    	$list = $model->field('id,title')->where($where)->order('id DESC')->limit('0,10')->select();
    	echo json_encode($list);
    }
    
    public function combox()
    {
    	$model = D('District');
    	$pid = intval($_REQUEST['pid']);
    	$where = array();
    	$where['pid'] = $pid;
    	$list = $model->field('id,title')->where($where)->order('sort_order ASC')->select();
    	$res = array();
    	if (is_array($list)){
    		foreach ($list as $k=>$v){
    			$res[] = array($v['id'], $v['title']);
    		}
    	}
    	echo json_encode($res);
    }
    
    public function delete()
    {
    	$model = D('District');
    	$type = $_REQUEST['type'];
    	$id = intval($_REQUEST['id']);
    	if($type=='province'){
    		$cnt = $model->where(array('pid'=>$id,'type'=>'city'))->count();
    		if($cnt>0){
    			$this->dwzError('该省下还有城市不能删除'.$cnt);
    		}
    	}else if($type=='city'){
    		$cnt = $model->where(array('pid'=>$id,'type'=>array('in',array('custom','region'))))->count();
    		if($cnt>0){
    			$this->dwzError('该城市下还有分区不能删除'.$cnt);
    		}
    	}else if($type=='region'){
    		$locModel = D('Locations');
    		$cnt = $locModel->where('FIND_IN_SET('.$id.',`district`)')->count();
    		if($cnt>0){
    			$this->dwzError('该城区下还有商圈不能删除'.$cnt);
    		}
    	}
    	$where = array();
    	$where['id'] = $id;
    	$rs = $model->where($where)->delete();
    	if(false !== $rs){
    		$this->dwzSuccess('保存成功！', 'tab_8');
    	}else{
    		$this->dwzError('保存失败！');
    	}
    }
}
?>
