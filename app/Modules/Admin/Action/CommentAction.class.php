<?php

class CommentAction extends AdminAction
{
	public function index()
	{
		$model = D('Msgboard');
		$where = '';
		$key = trim(strval($_REQUEST['search_key']));
		if($key){
			$where .= ' AND `product_name` LIKE "%'.$key.'%"';
		}
		$where = trim($where, ' AND');
		$totalCount = $model->where($where)->count();
		$currentPage = intval($_REQUEST['pageNum']);
		$currentPage = $currentPage ? $currentPage : 1;
		$numPerPage = 20;
		$rowOffset = ($currentPage-1) * $numPerPage;
		$list = $model->where($where)->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
		$this->display();
	}
	
	public function delete()
	{
		$id = intval($_REQUEST['id']);
		$model = D('Msgboard');
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
