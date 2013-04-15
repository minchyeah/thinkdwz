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
	    $sys=$settings->where(array('category'=>'sys'))->select();
	    if(is_array($sys)){
	    	foreach ($sys as $k => $v){
	    		$this->sysSetting[$v['skey']] = $v['svalue'];
	    		C(strtoupper($v['skey']),$v['svalue']);
	    	}
	    }
	    load('extend');
	}
	
	/**
	 * 验证码
	 */
	public function captcha()
	{
		import('ORG.Util.Image');
		$length = intval($_GET['length']) ? intval($_GET['length']) : 4;
		$mode = 2;
		$type = 'png';
		$width = intval($_GET['width']) ? intval($_GET['width']) : 50;
		$height = intval($_GET['height']) ? intval($_GET['height']) : 25;
		$sessame = 'captcha';
		Image::buildImageVerify($length, $mode, $type, $width, $height, $sessame);
	}
	
	/**
	 * 检查验证码是否正确
	 * @param string $captcha 验证码
	 * @return boolean
	 */
	protected function checkCaptcha($captcha = '')
	{
		$captcha = $captcha ? $captcha : strval($_REQUEST['captcha']);
		return md5(strtoupper($captcha)) == session('captcha');
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
			$upload->savePath =  DATA_PATH.'upload/'.substr(str_shuffle('abcdefghijklmnopqrstuvwxyz1234567890'), 20, 1).'/'.substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 6, 2).'/';;
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
}