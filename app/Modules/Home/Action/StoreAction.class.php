<?php

class StoreAction extends HomeAction
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($id = 0)
	{
		$id = intval($id);
		$model = D('Stores');
		$store = $model->find($id);
		if(!$store){
			$this->notfound();
		}
		$lids = explode(',', $store['locations']);
		$locations = D('Locations')->where(array('id'=>array('in', $lids)))->select();
		$cates = explode(',', $store['menu_category']);
		$cates_rev = array_flip($cates);
		$where = array();
		$where['store_id'] = $id;
		$rs = D('StoreMenus')->where($where)->getField('id,name,price,image,remark,category,store_id');
		$menus = array();
		if (is_array($rs)){
			foreach ($rs as $k=>$v){
				$menus[$cates_rev[$v['category']]][] = $v;
			}
		}
		ksort($menus);
		$store['city_alias'] = $this->city_alias;
		$this->assign('store', $store);
		$this->assign('district', F('district'));
		$this->assign('menus', $menus);
		$this->assign('locations', $locations);
		$this->assign('cates', $cates);
		$this->enjoy_stores();
		
		$store_history = unserialize(cookie('store_history'));
		if(!$store_history){
			$store_history = array();
		}
		foreach ($store_history as $k=>$v){
			if($v['id'] == $store['id']){
				unset($store_history[$k]);
			}
		}
		if (count($store_history) >= 6){
			array_pop($store_history);
		}
		array_unshift($store_history, array('id'=>$store['id'],'name'=>$store['name']));
		cookie('store_history', serialize($store_history), 30*24*3600);
		$this->_sidebar_comment($store['id']);
		
		$this->display('Store:index');
	}
	
	public function _empty()
	{
		$id = intval($_REQUEST['_URL_'][1]);
		$this->index($id);
	}
	
	public function append()
	{
		$this->display();
	}
	
	public function saveAppend()
	{
		if(empty($_FILES['attachment']['tmp_name'])){
			$this->error('请选择上传菜单！');
		}
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize  = 1048576 * 4;
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','doc','docx');
		$letter = range('a', 'z');
		shuffle($letter);
		$upload->savePath =  DATA_PATH.'upload/'.rand(0, 9).$letter[rand(0, 25)].'/'.rand(0, 9).$letter[rand(0, 25)].'/';
		if(!is_dir($upload->savePath)) {
			mkdir($upload->savePath,0777,true);
		}
		$upload->saveRule = 'uniqid';
		$upload->uploadReplace = false;
		if($upload->upload()) {
			$imgs = $upload->getUploadFileInfo();
			$_POST['attachment'] = str_replace(DATA_PATH, '', $imgs[0]['savepath'].$imgs[0]['savename']);
		}else{
			$this->error('上传菜单失败！');
		}
		
		$model = D('StoreAppend');
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		if(!$data['store_owner']){
			$this->error('请输入店主信息');
		}
		if(!$data['store_phone']){
			$this->error('请输入外卖电话');
		}
		if(!$data['username']){
			$this->error('请输入您的姓名');
		}
		if(!$data['email']){
			$this->error('请输入您的邮箱');
		}
		if (!$data['id']) {
			$data['dateline'] = time();
			$data['ip'] = get_client_ip();
			$rs = $model->add($data);
		}else{
			$rs = $model->save($data);
		}
		if(false !== $rs){
			$this->success('保存成功,感谢您分享的外卖信息！');
		}else{
			$this->error('保存失败！'.dump($data, false).$model->getDbError());
		}
	}
	
	public function enjoy_stores()
	{
		$model = D('Stores');
		$where = array();
		$where['city_id'] = $this->city_id;
		$enjoy_stores = $model->where($where)->order('rand()')->limit(8)->select();
		$this->assign('enjoy_stores', $enjoy_stores);
	}
	
	public function comment()
	{
		$params = explode('-', $_REQUEST['_URL_'][2]);
		$id = intval($params[0]);
		$_GET['page'] = intval($params[1]);
		$model = D('Stores');
		$store = $model->find($id);
		if(!$store){
			$this->notfound();
		}
		$lids = explode(',', $store['locations']);
		$locations = D('Locations')->where(array('id'=>array('in', $lids)))->select();
		
		$where = array();
		//$where['status'] = 1;
		$where['store_id'] = $id;
		$cModel = D('StoreComment');
		$count = $cModel->where($where)->count();
		$page = $this->getPage($count, 10, __APP__.'/store/comment/'.$id.'-__PAGE__.html');
		$comments = $cModel->where($where)->limit($page->firstRow,$page->listRows)->order('id DESC')->select();
		$this->assign('comments', $comments);
		$this->assign('page', $page->show());
		$store['city_alias'] = $this->city_alias;
		$this->assign('store', $store);
		$this->assign('locations', $locations);
		
		$store_history = unserialize(cookie('store_history'));
		if(!$store_history){
			$store_history = array();
		}
		foreach ($store_history as $k=>$v){
			if($v['id'] == $store['id']){
				unset($store_history[$k]);
			}
		}
		if (count($store_history) >= 6){
			array_pop($store_history);
		}
		array_unshift($store_history, array('id'=>$store['id'],'name'=>$store['name']));
		cookie('store_history', serialize($store_history), 30*24*3600);
		$this->_sidebar_comment($store['id']);
		$this->enjoy_stores();
		
		$this->display();
	}
	
	public function saveComment()
	{
		$captcha = trim(strval($_POST['captcha']));
		if(!$this->checkCaptcha($captcha, 'comment_captcha')){
			$this->error('验证码错误');
		}
		$model = D('StoreComment');
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		if (!$data['id']) {
			$data['dateline'] = time();
			$data['user_id'] = intval(cookie('user_id'));
			$rs = $model->add($data);
		}else{
			$rs = $model->save($data);
		}
		if(false !== $rs){
			$store = D('Stores');
			$rs = $store->where(array('id'=>$data['store_id']))->setInc('rating', $store->stars[$data['rate']]);
			//$rs = $store->updateStar($data['store_id']);
			$this->success('保存成功！');
		}else{
			$this->error('保存失败！');
		}
	}
	
	private function _sidebar_comment($id)
	{
		$model = D('StoreComment');
		$where['store_id'] = $id;
		$comments = $model->where($where)->order('id DESC')->limit(3)->select();
		$this->assign('sidebar_comment', $comments);
	}
	
	public function saveError()
	{
		$model = D('StoreErrors');
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		if (!$data['id']) {
			$data['dateline'] = time();
			$data['user_id'] = intval(session('user_id'));
			$data['ip'] = get_client_ip();
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
	
	public function telphone()
	{
		$id = intval($_GET['id']);
		$model = D('Stores');
		$store = $model->find($id);
		import('ORG.Util.Image');
		Image::buildString($store['telphone'], array(255,0,0), '', 'png', 0, false);
	}
}
?>