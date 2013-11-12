<?php

class LinkAction extends AdminAction
{
	public function _initialize()
	{
		parent::_initialize();
		$this->assign('linktype', C('FRIENDLINK_TYPE'));
	}
	
	public function index()
	{
		$model = D('Links');
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
	
	public function add()
	{
		$this->display();
	}
	
	public function edit()
	{
		$id =  intval($_GET['id']);
		$model = D('Links');
		$this->assign('vo', $model->find($id));
		$this->display('add');
	}
	
	public function save()
	{
		$model = D('Links');
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
	
	public function delete()
	{
		$id = intval($_REQUEST['id']);
		$model = D('Links');
		$where = array();
		$where['id'] = $id;
		$rs = $model->where($where)->delete();
		if($rs){
			$this->dwzSuccess('删除成功');
		}else{
			$this->dwzError('删除失败');
		}
	}
}
?>
