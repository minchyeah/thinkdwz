<?php
/**
 * 系统公共Action类
 * @author minch <yeah@minch.me>
 * @link http://www.minch.me
 */
class CommonAction extends Action
{
	/**
	 * 系统设置项
	 * @var array
	 */
	protected $sysSetting = array();
	
	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		$this->assign('static_url', __ROOT__.'/static');
	    $settings=M('Settings');
	    $this->sysSetting = $settings->where(array())->getField('skey,svalue');
	    load('extend');
	}
	
	/**
	 * 验证码
	 */
	public function captcha()
	{
		import('Util.Captcha', LIB_PATH);
		$config = new stdClass();
		intval($_REQUEST['length']) && $config->length = intval($_REQUEST['length']);
		intval($_REQUEST['width']) && $config->width = intval($_REQUEST['width']);
		intval($_REQUEST['height']) && $config->height = intval($_REQUEST['height']);
		strval($_REQUEST['background']) && $config->background = strval($_REQUEST['background']);
		strval($_REQUEST['font']) && $config->font = strval($_REQUEST['font']);
		strval($_REQUEST['font_color']) && $config->font_color = strval($_REQUEST['font_color']);
		strval($_REQUEST['font_size']) && $config->font_size = strval($_REQUEST['font_size']);
		strval($_REQUEST['charset']) && $config->charset = strval($_REQUEST['charset']);
		$captcha = new Captcha($config);
		$code = $captcha->rand_string();
		session('_captcha_', md5(strtoupper($code)));
		$captcha->show();
	}
	
	/**
	 * 检查验证码是否正确
	 * @param string $captcha 验证码
	 * @return boolean
	 */
	protected function checkCaptcha($captcha = '')
	{
		$captcha = $captcha ? $captcha : strval($_REQUEST['captcha']);
		return md5(strtoupper($captcha)) == session('_captcha_');
	}

	/**
	 * 保存图片方法
	 * @param array $file 上传的文件
	 * @return string|boolean
	 */
	protected function saveImage($file)
	{
		if(!empty($file['name'])){
			import("ORG.Net.UploadFile");
			$upload = new UploadFile();
			$upload->maxSize  = 1048576 * 4;
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
			$upload->savePath =  DATA_PATH.'upload/'.str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT).'/'.str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT).'/';;
			if(!is_dir($upload->savePath)) {
				mkdir($upload->savePath,0777,true);
			}
			$upload->saveRule = 'uniqid';
			$upload->uploadReplace = false;
			if($upload->upload()) {
				$imgs = $upload->getUploadFileInfo();
				return str_replace(DATA_PATH, '', $imgs[0]['savepath'].$imgs[0]['savename']);
			}
		}
		return false;
	}
	
	/**
	 * 把ID转换为对应的名称
	 * @param string $ids 主键ID
	 * @param Object $model 模型
	 * @param string $field 字段
	 * @param string $delimiter 分隔符
	 * @return string
	 */
	protected function ids2str($ids, $model, $field, $delimiter = ',', $pkfield = 'id')
	{
		$arrs = array();
		$rs = $model->field($field)->where($pkfield.' IN ('.$ids.')')->select();
		if (is_array($rs)) {
			foreach ($rs as $k=>$v){
				$arrs[] = $v[$field];
			}
		}
		return implode($delimiter, $arrs);
	}
	
	protected function getPage($count, $perPage = 10, $url = '')
	{
    	import('Util.Page', LIB_PATH);
		$page = new Page($count, $perPage, '', $url);
		$page->setConfig('theme', '%first% %prePage% %linkPage% %nextPage% %end%');
		return $page;
	}
}