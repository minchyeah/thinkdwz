<?php

class LocationAction extends AdminAction
{
    public function index()
    {
    	$title = trim(strval($_REQUEST['title']));
    	$pid = intval($_REQUEST['pid']);
    	$model = D('Locations');
    	$where = 'FIND_IN_SET('.$pid.',`district`) ';
    	$title && $where .= "AND title LIKE '%".$title."%'";
    	$totalCount = $model->where($where)->count();
    	$currentPage = intval($_REQUEST['pageNum']);
    	$currentPage = $currentPage ? $currentPage : 1;
    	$numPerPage = 20;
    	$rowOffset = ($currentPage-1) * $numPerPage;
    	$list = $model->where($where)->order('id DESC')->limit($rowOffset . ',' . $numPerPage)->select();
    	$model = D('District');
    	$city_id = $model->where('id='.$pid)->getField('pid');
    	$rs = $model->select();
    	$district = array();
    	if (is_array($rs)) {
    		foreach ($rs as $v){
    			$district[$v['id']] = $v;
    		}
    	}
    	$this->assign('city_id', $city_id);
    	$this->assign('list', $list);
    	$this->assign('totalCount', $totalCount);
    	$this->assign('numPerPage', $numPerPage);
    	$this->assign('currentPage', $currentPage);
    	$this->assign('district', $district);
        $this->display();
    }
    
    public function add()
    {
    	$city_id = intval($_REQUEST['city_id']);
    	$this->assign('city_id', $city_id);
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
    	$city_id = intval($_REQUEST['city_id']);
    	$this->assign('city_id', $city_id);
    	$id =  intval($_REQUEST['id']);
    	$model = D('Locations');
    	$vo = $model->find($id);
    	$vo['district_name'] = $this->ids2str($vo['district'], D('District'), 'title');
    	$this->assign('vo', $vo);
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
    	$data = array();
    	if(false !== $rs){
    		$data['info'] = '保存成功！';
    		$data['status'] =   1;
    		$data['loadId'] =   'jbsxBox';
    		$data['url']    =   U('Location/index', array('pid'=>$_REQUEST['pid']));
    	}else{
    		$data['info'] = '保存失败！';
    		$data['status'] =   0;
    	}
    	$this->ajaxReturn($data);
    }
    
    public function multiselect()
    {
    	$city_id = intval($_GET['city_id']);
    	$model = D('Locations');
    	$map = array('pid'=>$city_id);
    	if( !empty($_REQUEST['mod']) ) $map['classmodule'] = $_REQUEST['mod'];
    	$tree = $model->tree($map,$_REQUEST['link'],$_REQUEST['selparent']);
    	$this->assign('tree',$tree);
    	$this->display();
    }
    
    public function delete()
    {
    	$id =  intval($_REQUEST['id']);
    	$model = D('Locations');
    	$rs = $model->where(array('id'=>$id))->delete();
    	if($rs){
    		$this->success('删除成功');
    	} else {
    		$this->success('删除失败');
    	}
    }
}
?>
