<?php

class ArticleAction extends AdminAction
{
    public function index()
    {
    	import('ORG.Util.Tree');
    	$model = D('ArticleCategory');
    	$cates = $model->order('pid ASC')->select();
    	$tree = new Tree($cates, array('id','pid','subcates'));
    	$this->assign('treeCode', $this->buildCateTree($tree->leaf(), true));
        $this->display();
    }
    
    private function buildCateTree($tree, $root = false)
    {
    	$html = $root ? '<ul class="tree">' : '<ul>';
    	foreach ($tree as $k=>$v){
    		$html .= '<li><a href="'.U('alist','cate_id='.$v['id']).'" target="ajax" rel="alistBox">'.$v['cate_name'].'</a>';
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
    	$model = D('Articles');
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
    	$model = D('Users');
    	$this->assign('topmenus', $model->topMenus());
    	$this->display();
    }
    
    public function edit()
    {
    	$id =  intval($_GET['id']);
    	$model = D('Users');
    	$this->assign('topmenus', $model->topMenus());
    	$this->assign('vo', $model->find($id));
    	$this->display('add');
    }
    
    public function save()
    {
    	$model = D('Users');
    	$data = $model->create();
    	$data['params'] = '';
    	$data['group_name'] = '';
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
}
?>
