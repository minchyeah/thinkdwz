<?php

class CourseAction extends AdminAction
{
	public function index()
	{
		$model = D('Courses');
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
	    $images = array_pad(array(), 8, '');
	    $this->assign('images', $images);
		$this->display();
	}

	public function delete()
	{
		$model = D('Courses');
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
		$model = D('Courses');
		$vo = $model->find($id);
		$this->assign('vo', $vo);
		$images = json_decode($vo['images'], true);
		$images = array_pad($images, 8, '');
		$this->assign('images', $images);
		$this->display('add');
	}
	
	public function save()
	{
		$model = D('Courses');
		$images = array();
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
	
	public function orders()
	{
		$model = D('CourseOrders');
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
		$model = D('CourseOrders');
		$where = array();
		$where['id'] = $id;
		$rs = $model->where($where)->delete();
		if($rs){
			$this->dwzSuccess('删除成功');
		}else{
			$this->dwzError('删除失败');
		}
	}
	
	public function cls()
	{
	    $model = D('CourseClassView');
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
	    $this->display('clslist');
	}
	
	public function addcls()
	{
	    $courses = D('Courses')->field('id,name')->select();
	    $this->assign('courses', $courses);
	    $this->display('clsform');
	}
	
	public function editcls()
	{
	    $courses = D('Courses')->field('id,name')->select();
	    $this->assign('courses', $courses);
	    
	    $id = intval($_GET['id']);
	    $cls = D('CoursesClass')->find($id);
	    $this->assign('vo', $cls);
	    $this->display('clsform');
	}
	
	public function savecls()
	{
	    $model = D('CoursesClass');
	    $data = $model->create();
	    if(!$data){
	        $this->error($model->getError());
	    }
	    $data['thumb'] = str_replace(__ROOT__.'/data/', '', getFirstImg($data['detail']));
	    $model->startTrans();
	    if (!$data['id']) {
	        $data['dateline'] = time();
	        $rs = $model->add($data);
	    }else{
	        $data['dateline'] = time();
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
	
	public function deletecls()
	{
	    $model = D('CoursesClass');
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
}
?>
