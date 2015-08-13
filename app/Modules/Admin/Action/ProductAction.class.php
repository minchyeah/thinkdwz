<?php

class ProductAction extends AdminAction
{
	public function index()
	{
		$model = D('Products');
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
		$list = $model->where($where)->order('sort_order DESC, dateline DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
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
		$model = D('Products');
		$model->startTrans();
		$id = intval($_REQUEST['id']);
		$where = array();
		$where['id'] = $id;
		$orgStore = $model->find($id);
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
		$model = D('Products');
		$vo = $model->find($id);
		$this->assign('vo', $vo);
		$this->assign('images', json_decode($vo['images'], true));
		$this->display('add');
	}
	
	public function save()
	{
		$model = D('Products');
		$images = array();
		if ($_FILES['imgfile']['name']) {
			$image = $this->saveImage($_FILES['imgfile']);
			if ($image) {
				$images[0] = $image;
			}
		}
		$images[0] = $images[0] ? $images[0] : $_POST['img0'];
		for ($i=1; $i<=7; $i++){
		    $formName = 'imgfile'.$i;
		    if ($_FILES[$formName]['name']) {
		        $image = $this->saveImage($_FILES[$formName]);
		        if ($image) {
		            $images[$i] = $image;
		        }else{
		            $images[$i] = '';
		        }
		    }
		    $images[$i] = $images[$i] ? $images[$i] : $_POST['img'.$i];
		}
		$_POST['images'] = json_encode($images);
		$_POST['sort_order'] = intval($_POST['sort_order']);
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		$model->startTrans();
		if (!$data['id']) {
			$data['dateline'] = time();
			$rs = $model->add($data);
		}else{
			$rs = $model->save($data);
		}
		if(false !== $rs){
			$model->commit();
			$this->success('保存成功！');
		}else{
			$model->rollback();
			$this->error('保存失败！'.dump($data, false).$model->getDbError());
		}
	}
	
	public function orders()
	{
		$model = D('ProductOrders');
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
	
	public function deleteorder()
	{
		$id = intval($_REQUEST['id']);
		$model = D('ProductOrders');
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
