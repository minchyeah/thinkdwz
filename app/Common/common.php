<?php
function getStatus($status, $imageShow = true) {
	switch ($status) {
		case 0 :
			$showText = '禁用';
			$showImg = '<IMG SRC="' . __PUBLIC__ . '/Images/error.png" WIDTH="20" HEIGHT="17" BORDER="0" ALT="禁用">';
			break;
		case 2 :
			$showText = '待审';
			$showImg = '<IMG SRC="' . __PUBLIC__ . '/Images/locked.png" WIDTH="20" HEIGHT="17" BORDER="0" ALT="待审">';
			break;
		case -1 :
			$showText = '删除';
			$showImg = '<IMG SRC="' . __PUBLIC__ . '/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
			break;
		case 1 :
		default :
			$showText = '正常';
			$showImg = '<IMG SRC="' . __PUBLIC__ . '/Images/ok.png" WIDTH="20" HEIGHT="17" BORDER="0" ALT="正常">';

	}
	return ($imageShow) ?  $showImg  : $showText;
}

function imgsrc($file, $width = '', $height = '')
{
	$width = intval($width);
	$height = intval($height);
	$realFile = DATA_PATH.$file;
	if (is_file($realFile)) {
		if($width>0 && $height>0){
			$arrtmp = explode('.', $file);
			$ext = end($arrtmp);
			$thumbFile = str_replace('.'.$ext, '_'.$width.'x'.$height.'.'.$ext, $realFile);
			if(is_file($thumbFile)){
				$imgfile = str_replace(DATA_PATH, '', $thumbFile);
			}elseif(is_file($realFile)){
				import('ORG.Util.Image');
				Image::thumb($realFile, $thumbFile, '', $width, $height);
				$imgfile = str_replace(DATA_PATH, '', $thumbFile);
			}
		}else{
			$imgfile = $file;
		}
	}
	return empty($imgfile) ? __ROOT__.'/static/images/noimg.jpg' : $imgfile;
}

function ids2str($ids, $data, $map = array(), $delimiter = ',')
{
	if (is_string($ids)) {
		$ids = explode(',', $ids);
	}
	$tmpdata = array();
	foreach ($data as $v){
		$tmpdata[$v[$map[0]]] = $v[$map[1]];
	}
	$str = '';
	foreach ($ids as $id){
		$str .= ','.$tmpdata[$id];
	}
	return trim($str, ',');
}