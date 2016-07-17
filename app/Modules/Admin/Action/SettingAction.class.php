<?php
/**
 * @name 系统配置模块
 */
class SettingAction extends AdminAction
{
	// 基本信息设置
    public function index(){
		$model = D('Settings');
		$oldSettings = $model->field('skey,svalue')->select();
		if (is_array($oldSettings)){
			foreach ($oldSettings as $k=>$v){
				$this->assign($v['skey'], $v['svalue']);
			}
		}
	    $this->display();
    }
    
	// 配置信息保存
    public function updateconfig($config){
		$model = D('Settings');
		$data = array();
		$i = 0;
		foreach ($config as $k=>$v){
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
		$keys = array('site_name', 'seo_keywords', 'seo_description', 'footer_content',
				'company_phone', 'notify_email', 'kefuqq', 'stat_code', 'visit_count', 
				'contact_name', 'contact_mobile', 'case_style', 'case_type', 'case_area', 'case_budget',
				'company_name', 'company_address', 'icpbeian'
			);
		foreach ($_POST as $k=>$v){
			if(in_array($k, $keys)){
				$config[$k] = trim($v);
			}
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