<?php
/**
 * 招聘岗位
 * @author minch <yeah@minch.me>
 */
class JobsAction extends AdminAction
{
	public function index()
	{
		$model = D('Jobs');
		$where = array();
		$totalCount = $model->where($where)->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 10;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->where($where)->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
		$this->display();
	}
    
    public function add()
    {
    	$this->display();
    }
    
    public function edit()
    {
    	$id =  intval($_GET['id']);
    	$model = D('Jobs');
    	$this->assign('vo', $model->find($id));
    	$this->display('add');
    }
    
    public function save()
    {
    	$model = D('Jobs');
    	$data = $model->create();
    	if(!$data){
    		$this->error($model->getError());
    	}
    	if (!$data['id']) {
    		$rs = $model->add($data);
    	}else{
    		$rs = $model->save();
    	}
    	if(false !== $rs){
    		$this->success('保存成功！');
    	}else{
    		$this->error('保存失败！'.dump($data, false).$model->getDbError());
    	}
    }
    
    public function delete()
    {
    	$id =  intval($_GET['id']);
    	$model = D('Jobs');
    	$rs = $model->where(array('id'=>$id))->delete();
    	if(false !== $rs){
    		$this->success('保存成功！');
    	}else{
    		$this->error('保存失败！'.$model->getDbError());
    	}
    }

}
?>