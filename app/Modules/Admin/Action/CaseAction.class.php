<?php

class CaseAction extends AdminAction
{
    public function index()
    {
    	$this->assign('treeCode', $this->cateTree(false));
        $this->display();
    }
    
    public function cateTree($echo = true)
    {
    	import('ORG.Util.Tree');
    	$model = D('CasesCategory');
    	$cates = $model->order('pid ASC,sort_order ASC')->select();
    	$tree = new Tree($cates, array('id','pid','subcates'));
    	if($echo){
    		echo $this->buildCateTree($tree->leaf(), true);
    	}else{
    		return $this->buildCateTree($tree->leaf(), true);
    	}
    }
    
    private function buildCateTree($tree, $root = false)
    {
    	$html = $root ? '<ul class="tree expand">' : '<ul>';
    	foreach ($tree as $k=>$v){
    		$aAttr = !$v['final'] && $v['id'] == 1 ? '' : ' href="'.U('alist','cate_id='.$v['id']).'" target="ajax" rel="alistBox"';
    		$html .= '<li><a'.$aAttr.'>'.$v['cate_name'].'</a>';
    		if(!empty($v['subcates'])){
    			$html .= $this->buildCateTree($v['subcates']);
    		}
    		$html .= '</li>';
    	}
    	$html .= '</ul>';
    	return $html;
    }
    
    public function alist()
    {
    	$cate_id = intval($_REQUEST['cate_id']);
    	$model = D('Cases');
    	$where = array();
    	$where['cate_id'] = $cate_id;
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
    	$this->_team();
    	$images = array_pad(array(), 12, '');
    	$this->assign('images', $images);
    	$vo['cate_id'] = intval($_REQUEST['cate_id']);
    	$this->assign('vo', $vo);
    	$this->display();
    }
    
    public function edit()
    {
    	$this->_team();
    	$id =  intval($_REQUEST['id']);
    	$model = D('Cases');
    	$vo = $model->find($id);
		$images = json_decode($vo['images'], true);
		$images = array_pad($images, 12, '');
		$this->assign('images', $images);
    	$this->assign('vo', $vo);
    	$this->display('add');
    }
    
    private function _team()
    {
    	$dcids = array(22);
    	$dsids = M('TeamCategory')->where(array('pid'=>22))->field('id')->select();
    	if(is_array($dsids)){
    		foreach ($dsids as $v){
    			array_push($dcids, intval($v['id']));
    		}
    	}
    	$where = array('cate_id'=>array('IN', $dcids));
    	$designers = M('TeamMember')->field('id,name')->where($where)->select();
    	
    	$ecids = array(23);
    	$esids = M('TeamCategory')->where(array('pid'=>23))->field('id')->select();
    	if(is_array($esids)){
    		foreach ($esids as $v){
    			array_push($ecids, intval($v['id']));
    		}
    	}
    	$where = array('cate_id'=>array('IN', $ecids));
    	$engineers = M('TeamMember')->field('id,name')->where($where)->select();
    	
    	$this->assign('designers', $designers);
    	$this->assign('engineers', $engineers);
    }
    
    public function save()
    {
    	$model = D('Cases');
    	$_POST['style'] = implode(',', $_POST['style']);
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
    	$saveimgs = array();
    	foreach ($images as $img){
    		if($img){
    			$saveimgs[]  = $img;
    		}
    	}
    	$_POST['images'] = json_encode($saveimgs);
    	$_POST['thumb'] = $saveimgs[0];
    	$data = $model->create();
    	if(!$data){
    		$this->error($model->getError());
    	}
    	$data['dateline'] = time();
    	if (!$data['id']) {
    		$rs = $model->add($data);
    	}else{
    		$rs = $model->save($data);
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
    	$model = D('CasesCategory');
    	$cates = $model->order('pid ASC,sort_order ASC')->select();
    	$tree = new Tree($cates, array('id','pid','subcates'));
    	$category = $tree->leaf();
    	$this->assign('category', $this->buildCategoryTree($category, true));
    	$this->display();
    }
    
    private function buildCategoryTree($tree, $root = false)
    {
    	$html = $root ? '<ul id="categoryTree" class="tree expand"><li><a href="javascript:;" onclick="return false" data-id="0" data-pic="0"><span class="cate_title">栏目管理</span><span class="cate_act disable">删除</span><span class="cate_act">|</span><span class="cate_act disable">编辑</span><span class="cate_act">|</span><span class="cate_act add_cate">添加子栏目</span></a></li>' : '<ul>';
    	foreach ($tree as $k=>$v){
    		$havesub = !empty($v['subcates']);
    		$delable = 'disable';
    		$addable = $v['final'] ? 'disable' : 'add_cate';
    		empty($v['subcates']) && $v['deletable'] && $delable = 'delete_cate';
    		$html .= '<li><a href="javascript:;" onclick="return false;" data-id="'.$v['id'].'" data-pid="'.$v['pid'].'"><span class="cate_title">'.$v['cate_name'].'</span>';
    		$html .= '<span class="cate_act '.$delable.'">删除</span><span class="cate_act">|</span><span class="cate_act edit_cate">编辑</span><span class="cate_act">|</span><span class="cate_act '.$addable.'">添加子栏目</span></a>';
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
    	$model = D('CasesCategory');
    	$cates = $model->order('pid ASC')->select();
    	$tree = new Tree($cates, array('id','pid','subcates'));
    	$pid = intval($_GET['id']);
    	$this->assign('cateOptions', $this->buildCateOptions($tree->leaf(), $pid, 0));
    	$this->display('category_add');
    }
    
    public function editCategory()
    {
    	import('ORG.Util.Tree');
    	$model = D('CasesCategory');
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
    	$model = D('CasesCategory');
    	$data = $model->create();
    	if(!$data){
    		$this->error($model->getError());
    	}
    	if($data['pid']){
    	    $ppids = $model->where(array('id'=>$data['pid']))->getField('pids');
    		$data['pids'] = $data['pid'].','.$ppids;
    	}else{
    	    $data['pids'] = '';
    	}
    	$data['pids'] = trim(trim($data['pids'], ','));
//     	if(strlen($data['pids'])>0 && count(explode(',', $data['pids'])) == 1){
    		$data['final'] = 1;
//     	}
    	if (!$data['id']) {
    		$rs = $model->add($data);
    	}else{
    		$rs = $model->save($data);
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
    		$model = D('CasesCategory');
    		if(!$model->where(array('pid'=>$id))->count() || !D('Case')->where(array('cate_id'=>$id))->count()){
    			$rs = $model->where(array('id'=>$id))->delete();
    			false !== $rs && $this->success('删除成功');
    		}else{
    			$this->error('该栏目下还有子栏目或文章不可删除！');
    		}
    	}
    	$this->error('操作失败');
    }
    
    public function delete()
    {
    	$id = intval($_REQUEST['id']);
		$model = D('Case');
		$where = array();
		$where['id'] = $id;
		$rs = $model->where($where)->delete();
		if($rs){
			$this->dwzSuccess('删除成功','tab_9');
		}else{
			$this->dwzError('删除失败');
		}
	}
}
?>
