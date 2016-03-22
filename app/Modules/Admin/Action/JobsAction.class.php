<?php

class JobsAction extends AdminAction
{
	public function index()
	{
		$model = D('Jobs');
		$where = '';
		$key = trim(strval($_REQUEST['search_key']));
		if($key){
			$where .= ' AND `name` LIKE "%'.$key.'%"';
		}
		$where = trim($where, ' AND');
		$totalCount = $model->where($where)->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->where($where)->order('dateline DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
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

	public function delete()
	{
		$model = D('Jobs');
		$model->startTrans();
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['id'] = $id;
		$rs = $model->where($where)->delete();
		if($rs){
			$model->commit();
			$this->dwzSuccess('删除成功');
		}else{
			$model->rollback();
			$this->dwzError('删除失败');
		}
	}
	
	public function edit()
	{
		$id =  intval($_GET['id']);
		$model = D('Jobs');
		$case = $model->find($id);
		$this->assign('vo', $case);
		$this->display('add');
	}
	
	public function save()
	{
		$model = D('Jobs');
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		$data['dateline'] = time();
		$model->startTrans();
		if (!$data['id']) {
			$rs = $model->add($data);
		}else{
			$rs = $model->save($data);
		}
		if(false !== $rs){
			$model->commit();
			$this->success('保存成功！');
		}else{
			$model->rollback();
			$this->error('保存失败！'.$model->getDbError());
		}
	}
}
?>
