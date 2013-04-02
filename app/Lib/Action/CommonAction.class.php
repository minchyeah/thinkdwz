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
}