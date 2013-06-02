<?php

class AdminAction extends CommonAction
{
	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		parent::_initialize();
		$this->_login();
		$this->_privileges();
		$this->assign('username', session('username'));
		$this->assign('admin', session('admin'));
	}
	
	/**
	 * 判断登录状态，没登录则跳转到登录页面
	 */
	private function _login()
	{
    	if (!session('admin_id') && 'Public' != MODULE_NAME) {
    		$this->redirect('Public/login');
    	}
	}
	
	/**
	 * 编辑器上传图片公共方法
	 */
	public function uploadEditorImage()
	{
		$upfile=@$_FILES['filedata'];
		if(!isset($upfile))
		{
			$err='文件域的name错误';
		}
		elseif(!empty($upfile['error']))
		{
			switch($upfile['error'])
			{
				case '1':
					$err = '文件大小超过了php.ini定义的upload_max_filesize值';
					break;
				case '2':
					$err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
					break;
				case '3':
					$err = '文件上传不完全';
					break;
				case '4':
					$err = '无文件上传';
					break;
				case '6':
					$err = '缺少临时文件夹';
					break;
				case '7':
					$err = '写文件失败';
					break;
				case '8':
					$err = '上传被其它扩展中断';
					break;
				case '999':
				default:
					$err = '无有效错误代码';
			}
		}
		elseif(empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none')
		{
			$err = '无文件上传';
		}
		else
		{
			$image = $this->saveImage($upfile);
			if ($image){
				$imgurl = __ROOT__.'/data/'.imgsrc($image, 600, 480);
				$localName = $upfile['name'];
			}else{
				$err = '上传失败';
			}
		}
		$return = array();
		$return['err'] = $err;
		$return['msg'] = array('url'=>$imgurl, 'localname'=>$localName, 'id'=>0);
		die(json_encode($return));
	}
	
	/**
	 * 检查是否有操作权限
	 */
	private function _privileges()
	{
		
	}
	
	protected function redirect($url, $params=array(), $delay=0, $msg=''){
		parent::redirect($url, $params=array(), $delay=0, $msg='');
	}
}