<?php

class ArticlePageAction extends AdminAction
{
    private function page($alias)
    {
    	$model = D('ArticlePage');
    	$where = array();
    	$where['page_code'] = $alias;
    	$vo = $model->where($where)->find();
    	if(!$vo){
    		$vo['page_code'] = $alias;
    	}
    	$this->assign('vo', $vo);
    	$this->display('Article:page');
    }
    
    public function save()
    {
    	$model = D('ArticlePage');
    	$_POST['create_time'] = time();
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

    public function _empty($name)
    {
        $this->page($name);
    }
   
}
?>
