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
    	$this->assign('list', $tree->leaf());
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
    	$this->display();
    }
    
    public function edit()
    {
    	$id =  intval($_GET['id']);
    	$model = D('District');
    	$vo = $model->find($id);
    	$this->assign('vo', $vo);
    	$this->_assign_city();
    	$this->display('add');
    }
    
    public function save()
    {
    	if(in_array(trim($_REQUEST['type']), array('city','province'))){
    		import('@.Util.Pinyin');
	    	$pinyin = new Pinyin();
	    	$_POST['alias'] = str_replace(' ', '', $pinyin->output($_REQUEST['title']));
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
}
?>
