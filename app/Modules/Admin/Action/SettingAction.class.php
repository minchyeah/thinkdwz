<?php
/**
 * @name 系统配置模块
 */
class SettingAction extends AdminAction
{
	// 基本信息设置
    public function index(){
		$model = D('Settings');
		$where = array();
		//$where['category'] = '';
		$oldSettings = $model->where($where)->getField('skey,svalue');
		if (is_array($oldSettings)){
			foreach ($oldSettings as $k=>$v){
				$this->assign($k, $v);
			}
		}
	    $this->display();
    }
    
	// 配置信息保存
    public function updateconfig($config){
		$model = D('Settings');
		$where = array();
		$where['category'] = 'sys';
		$oldSettings = $model->field('id,skey')->where($where)->select();
		$fieldIndex = array();
		if (is_array($oldSettings)){
			foreach ($oldSettings as $k=>$v){
				$fieldIndex[$v['skey']] = $v['id'];
			}
		}
		$data = array();
		$i = 0;
		foreach ($config as $k=>$v){
			$data[$i]['id'] = $fieldIndex[$k];
			$data[$i]['skey'] = $k;
			$data[$i]['svalue'] = $v;
			$i += 1;
		}
		$model->addAll($data, '', true);
		$this->success('恭喜您，配置信息更新成功！');
	}
	
	/**
	 * 更新配置项
	 */
    public function save(){
    	$config = array();
    	foreach ($_POST as $k=>$v){
    		$config[$k] = trim($v);
    	}
		$this->updateconfig($config);
	}
	/**
	 * 系统日志列表
	 */
	public function loglist()
	{
		$rs=D("ConfigView");
		$page  = !empty($_GET['p'])?$_GET['p']:1;
		$count = $rs->count();
		import("ORG.Util.Page");
		$p = new Page($count, C('WEB_ADMIN_PAGENUM'));
		$page = $p->show();
		$logliset = $rs->order('id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('page',$page);
	    $this->assign('logliset',$logliset);
		$this->display();
	}
}
?>