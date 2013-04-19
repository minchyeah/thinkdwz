<?php

class DistrictAction extends AdminAction
{
    public function index()
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
    	$this->assign('type', array('region'=>'行政城区', 'custom'=>'自定义'));
        $this->display();
    }
    
    private function _assign_city()
    {
    	$model = D('District');
    	$where = array();
    	$where['pid'] = 0;
    	$cities = $model->where($where)->select();
    	$this->assign('cities', $cities);
    }
    
    public function add()
    {
    	$this->_assign_city();
    	$this->assign('type', $_REQUEST['type']);
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
    	if('city'==trim($_REQUEST['type'])){
    		import('@.Util.Pinyin');
	    	$pinyin = new Pinyin();
	    	$_POST['alias'] = str_replace(' ', '', $pinyin->output($_REQUEST['title']));
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
    	$map = array();
    	$tree = $model->tree($map,$_REQUEST['link'],$_REQUEST['selparent']);
    	$this->assign('tree',$tree);
    	$this->display('Public:multiselect');
    }
}
?>
