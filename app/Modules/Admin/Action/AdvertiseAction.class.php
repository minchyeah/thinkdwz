<?php

class AdvertiseAction extends AdminAction
{
    public function index()
    {
    	$model = D('Advertise');
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
    	$this->display();
    }
    
    public function edit()
    {
    	$id =  intval($_GET['id']);
    	$model = D('Advertise');
    	$vo = $model->find ( $id );
		$this->assign ( 'vo', $vo );
		$this->assign ( 'params', json_decode($vo['params'], true));
    	$this->display('add');
    }

    public function save()
    {
    	$model = D ('Advertise');
    	if (false === $model->create()) {
    		$this->error($model->getError());
    	}
    	if(!empty($_FILES['img']['name'])){
    		import("ORG.Net.UploadFile");
    		$upload = new UploadFile();
    		$upload->maxSize  = 1048576 * 3; //3M
    		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg', 'swf');
    		$upload->savePath =  './Public/Upload/advertise/';
    		$upload->saveRule = 'uniqid';
    		$upload->thumb = true;
    		$upload->thumbMaxWidth = 100;
    		$upload->thumbMaxHeight = 100;
    		$upload->uploadReplace = false;
    		$upload->thumbPrefix = '100x100_';
    		if(!$upload->upload()) {
    			$this->error($upload->getErrorMsg());
    		}else{
    			$imgs = $upload->getUploadFileInfo();
    			$file = __ROOT__.'/Public/Upload/advertise/'.$imgs[0]['savename'];
    		}
    	}else{
    		if($_POST['imgurl']){
    			$file = trim(strval($_POST['imgurl']));
    		}else{
    			$this->error('请选择上传图片(Flash)或填写图片(Flash)地址');
    		}
    	}
    	$adtype = $this->getadtype($file);
    	if(!in_array($adtype, array('image', 'flash'))){
    		$this->error('请选择上传图片(Flash)或填写图片(Flash)地址');
    	}
    	$params = array();
    	$params['width'] = intval($_REQUEST['width']);
    	$params['height'] = intval($_REQUEST['height']);
    	$params['link'] = trim(strval($_REQUEST['link']));
    	$params['type'] = $adtype;
    	$params['file'] = $file;
    	$model->params = json_encode($params);
    	$model->code = $this->buildCode($params);
    	$model->start_time = time();
    	$model->end_time = 0;
    
    	//保存当前数据对象
    	if(!intval($_POST['id'])){
    		$rs = $model->add();
    	}else{
    		$rs = $model->save();
    	}
    
    	if ($rs !== false) {
    		echo '<script type="text/javascript">
					var response = {
						"status":"1",
						"info":"\u64cd\u4f5c\u6210\u529f",
						"navTabId":"",
						"forwardUrl":"",
						"callbackType":"forward"
					};
					if(window.parent.donecallback) {
						window.parent.donecallback(response);
						window.parent.$.pdialog.closeCurrent();
					}
			    </script>';
    	} else {
    		//失败提示
    		$this->error ('保存失败!'.$model->getDbError());
    	}
    }
    
    /**
     * 根据文件名判断广告类型
     * @param string $file
     */
    private function getadtype($file)
    {
    	$ext = strtolower(end(explode(".", $file)));
    	$type = '';
    	switch ($ext) {
    		case 'swf':
    			$type = 'flash';
    			break;
    
    		case 'jpg':
    		case 'jpeg':
    		case 'gif':
    		case 'png':
    			$type = 'image';
    			break;
    				
    		default:
    			;
    			break;
    	}
    	return $type;
    }
    
    /**
     * 构建广告代码
     * @param array $params
     */
    private function buildCode($params)
    {
    	$code = '';
    	if ('image'==$params['type']) {
    		$params['width'] && $width = ' width="'.$params['width'].'"';
    		$params['height'] && $height = ' height="'.$params['height'].'"';
    		$code = '<a href="'.$params['link'].'"><img src="'.$params['file'].'"'.$width.$height.' /></a>';
    	}elseif('flash'==$params['type']){
    		$width = $params['width'] ? $params['width'] : '100%';
    		$height = $params['height'] ? $params['height'] : '100%';
    		$code = <<<FLASH
			<object width="{$width}" height="{$height}" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">
					<param value="{$params['file']}" name="movie">
					<param value="high" name="quality">
					<param value="false" name="menu">
					<param value="opaque" name="wmode">
					<embed width="{$width}" height="{$height}" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" quality="high" wmode="opaque" src="{$params['file']}">
			</object>
FLASH;
    	}
    	return $code;
    }
}
?>
