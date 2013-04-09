<?php

class LocationAction extends AdminAction
{
    public function index()
    {
    	$model = D('Locations');
    	$totalCount = $model->count();
    	$currentPage = intval($_REQUEST['pageNum']);
    	$currentPage = $currentPage ? $currentPage : 1;
    	$numPerPage = 20;
    	$rowOffset = ($currentPage-1) * $numPerPage;
    	$list = $model->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
    	
    	$model = D('District');
    	$rs = $model->select();
    	$district = array();
    	if (is_array($rs)) {
    		foreach ($rs as $v){
    			$district[$v['id']] = $v;
    		}
    	}
    	
    	$this->assign('list', $list);
    	$this->assign('totalCount', $totalCount);
    	$this->assign('numPerPage', $numPerPage);
    	$this->assign('currentPage', $currentPage);
    	$this->assign('district', $district);
        $this->display();
    }
    
    public function add()
    {
    	$this->_assign_district();
    	$this->display();
    }
    
    private function _assign_district()
    {
    	$model = D('District');
    	$data = $model->order('sort_order ASC')->select();
    	$this->assign('district', $data);
    }
    
    public function edit()
    {
    	$id =  intval($_GET['id']);
    	$model = D('Locations');
    	$this->assign('vo', $model->find($id));
    	$this->_assign_district();
    	$this->display('add');
    }
    
    public function save()
    {
    	import('@.Util.Pinyin');
    	$pinyin = new Pinyin();
    	$alias = str_replace(' ', '', $pinyin->output($_REQUEST['title']));
    	$letter = strtoupper(substr($alias, 0, 1));
    	$_POST['alias'] = $alias;
    	$_POST['letter'] = $letter;
    	$model = D('Locations');
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
}
?>
