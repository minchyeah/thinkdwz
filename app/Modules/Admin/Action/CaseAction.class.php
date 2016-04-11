<?php

class CaseAction extends AdminAction
{
	public function index()
	{
		$model = D('Cases');
		$where = "type='case'";
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
		$list = $model->where($where)->order('sort_order ASC, dateline DESC')->limit($rowOffset . ',' . $numPerPage)->select();

		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
		$this->assign('catalog', $model->where("type='catalog'")->order('sort_order ASC, dateline DESC')->getField('id,name'));
		$this->display();
	}
	
	public function add()
	{
	    $model = D('Cases');
	    $where = "type='catalog'";
	    $catalog = $model->where($where)->order('sort_order ASC, dateline DESC')->select();
	    
	    $this->assign('catalog', $catalog);
		$this->display();
	}

	public function delete()
	{
		$model = D('Cases');
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
		$model = D('Cases');
		$case = $model->find($id);
		$this->assign('vo', $case);

		$where = "type='catalog'";
		$catalog = $model->where($where)->order('sort_order ASC, dateline DESC')->select();
		$this->assign('catalog', $catalog);
		
		$this->display('add');
	}
	
	public function save()
	{
		$model = D('Cases');
		if ($_FILES['imgfile']['name']) {
			$image = $this->saveImage($_FILES['imgfile']);
			if ($image) {
				$_POST['image'] = $image;
			}
		}
		$_POST['sort_order'] = intval($_POST['sort_order']);
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
			$this->error('保存失败！'.dump($data, false).$model->getDbError());
		}
	}
	
	public function uploadIcon()
	{
	    if($_FILES['appIcon']['name']){
	        $icon = $this->saveImage($_FILES['appIcon']);
	        if(!$icon){
	            $this->error('图标上传失败');
	        }
	        $data = array();
	        $data['status'] = 1;
	        $data['icon'] = $icon;
	        $data['icon_src'] = str_replace('upload', '/data/upload', imgsrc($icon, 96, 96));
	        $this->ajaxReturn($data);
	    }
	    $this->error('请选择上传图标');
	}
	
	public function uploadImage()
	{
	    if($_FILES['appImage']['name']){
	        $img = $this->saveImage($_FILES['appImage']);
	        if(!$img){
	            $this->error('截图上传失败');
	        }
	        $data = array();
	        $data['status'] = 1;
	        $data['image'] = $img;
	        $data['image_src'] = str_replace('upload', '/data/upload', imgsrc($img, 96, 96));
	        $data['index'] = intval($_REQUEST['index']);
	        $this->ajaxReturn($data);
	    }
	    $this->error('请选择上传截图');
	}

	public function catalog()
	{
		$model = D('Cases');
		$where = "type='catalog'";
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
		$list = $model->where($where)->order('sort_order ASC, dateline DESC')->limit($rowOffset . ',' . $numPerPage)->select();
		
		$this->assign('list', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('numPerPage', $numPerPage);
		$this->assign('currentPage', $currentPage);
		$this->display();
	} 

	public function addcatalog()
	{
	    $this->display();
	}

	public function editcatalog()
	{
	    $id =  intval($_GET['id']);
	    $model = D('Cases');
	    $case = $model->find($id);
	    $this->assign('vo', $case);
	    $this->display('addcatalog');
	}
	
	public function savecatalog()
	{
		$model = D('Cases');
		if ($_FILES['imgfile']['name']) {
			$image = $this->saveImage($_FILES['imgfile']);
			if ($image) {
				$_POST['image'] = $image;
			}
		}
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
}
?>
