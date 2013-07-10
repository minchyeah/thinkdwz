<?php

class ProductAction extends AdminAction
{
    public function index()
    {
    	$this->assign('treeCode', $this->cateTree(false));
        $this->display();
    }
    
    public function cateTree($echo = true)
    {
    	import('ORG.Util.Tree');
    	$model = D('ProductCategory');
    	$cates = $model->order('pid ASC')->select();
    	$tree = new Tree($cates, array('id','pid','subcates'));
    	if ($echo) {
    		echo $this->buildCateTree($tree->leaf(), true);
    	}else{
    		return $this->buildCateTree($tree->leaf(), true);
    	}
    }
    
    private function buildCateTree($tree, $root = false)
    {
    	$html = $root ? '<ul class="tree expand">' : '<ul>';
    	foreach ($tree as $k=>$v){
    		$aAttr = $root ? '' : ' href="'.U('itemlist','cate_id='.$v['id']).'" target="ajax" rel="alistBox"';
    		$html .= '<li><a'.$aAttr.'>'.$v['cate_name'].'</a>';
    		if(!empty($v['subcates'])){
    			$html .= $this->buildCateTree($v['subcates']);
    		}
    		$html .= '</li>';
    	}
    	$html .= '</ul>';
    	return $html;
    }
    
    public function itemlist()
    {
    	$cate_id = intval($_GET['cate_id']);
    	$model = D('Products');
    	$where = array();
    	$where['cate_id'] = $cate_id;
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
    
    public function add()
    {
    	$vo['cate_id'] = intval($_REQUEST['cate_id']);
    	$this->assign('vo', $vo);
    	$this->display();
    }
    
    public function edit()
    {
    	$id =  intval($_GET['id']);
    	$model = D('Products');
    	$vo = $model->find($id);
    	$vo['images'] = explode(',', $vo['images']);
    	$this->assign('vo', $vo);
    	$this->display('add');
    }
    
    public function save()
    {
    	$model = D('Products');
    	$thumb = $this->saveImage($_FILES['thumbfile']);
    	if($thumb){
    		$_POST['thumb'] = $thumb;
    	}
    	$_POST['images'] = implode(',', $_POST['images']);
    	$data = $model->create();
    	if(!$data){
    		$this->error($model->getError());
    	}
    	if (!$data['id']) {
    		$data['create_time'] = time();
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
    
    public function category()
    {
    	import('ORG.Util.Tree');
    	$model = D('ProductCategory');
    	$cates = $model->order('pid ASC')->select();
    	$tree = new Tree($cates, array('id','pid','subcates'));
    	$category = $tree->leaf();
    	$this->assign('category', $this->buildCategoryTree($category, true));
    	$this->display();
    }
    
    private function buildCategoryTree($tree, $root = false)
    {
    	$html = $root ? '<ul id="categoryTree" class="tree expand"><li><a href="javascript:;" onclick="return false" data-id="0" data-pic="0"><span class="cate_title">分类管理</span><span class="cate_act disable">删除</span><span class="cate_act">|</span><span class="cate_act disable">编辑</span><span class="cate_act">|</span><span class="cate_act add_cate">添加子分类</span></a></li>' : '<ul>';
    	foreach ($tree as $k=>$v){
    		$havesub = !empty($v['subcates']);
    		$addable = $v['pid'] ? 'disable' : 'add_cate';
    		$delable = $havesub ? 'disable' : 'delete_cate';
    		$html .= '<li><a href="javascript:;" onclick="return false;" data-id="'.$v['id'].'" data-pid="'.$v['pid'].'"><span class="cate_title">'.$v['cate_name'].'</span>';
    		$html .= '<span class="cate_act '.$delable.'">删除</span><span class="cate_act">|</span><span class="cate_act edit_cate">编辑</span><span class="cate_act">|</span><span class="cate_act '.$addable.'">添加子分类</span></a>';
    		if($havesub){
    			$html .= $this->buildCategoryTree($v['subcates']);
    		}
    		$html .= '</li>';
    	}
    	$html .= '</ul>';
    	return $html;
    }
    
    public function addCategory()
    {
    	import('ORG.Util.Tree');
    	$model = D('ProductCategory');
    	$cates = $model->where(array('pid'=>0))->order('pid ASC')->select();
    	$tree = new Tree($cates, array('id','pid','subcates'));
    	$this->assign('cateOptions', $this->buildCateOptions($tree->leaf(), intval($_GET['id']), 0));
    	$this->display('category_add');
    }
    
    public function editCategory()
    {
    	import('ORG.Util.Tree');
    	$model = D('ProductCategory');
    	$id = intval($_REQUEST['id']);
    	$vo = $model->find($id);
    	$cates = $model->order('pid ASC')->select();
    	$tree = new Tree($cates, array('id','pid','subcates'));
    	$this->assign('cateOptions', $this->buildCateOptions($tree->leaf(), $vo['pid'], 0));
    	$this->assign('vo', $vo);
    	$this->display('category_add');
    }
    
    private function buildCateOptions($tree, $selected = 0, $level = 0)
    {
    	$html = '';
    	for ($i=0; $i<$level; $i++){
    		$indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;|', $level).'--';
    	}
    	foreach ($tree as $k=>$v){
    		$havesub = !empty($v['subcates']);
    		$selstr = $selected == $v['id'] ? ' selected="selected"' : '';
    		$html .= '<option value="'.$v['id'].'"'.$selstr.'>'.$indent.$v['cate_name'].'</option>';
    		if($havesub){
    			$html .= $this->buildCateOptions($v['subcates'], $selected, $level+1);
    		}
    	}
    	return $html;
    }
    
    public function saveCategory()
    {
    	$model = D('ProductCategory');
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
    
    public function deleteCategory()
    {
    	$id = intval($_REQUEST['id']);
    	if($id){
    		$model = D('ProductCategory');
    		if(!$model->where(array('pid'=>$id))->count()){
    			$rs = $model->where(array('id'=>$id))->delete();
    			false !== $rs && $this->success('删除成功');
    		}else{
    			$this->error('该分类下还有子分类不可删除！');
    		}
    	}
    	$this->error('操作失败');
    }
}
?>