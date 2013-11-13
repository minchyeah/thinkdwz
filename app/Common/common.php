<?php
function getStatus($status, $imageShow = true) {
	$str = '';
	switch ($status) {
		case 0 :
			$str = '<span style="color:red;">×</span>';
			break;
		case 1 :
		default :
			$str = '<span style="color:green;">√</span>';
	}
	return $str;
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
function city_domain($city)
{
	return 'http://'.$city.'.'.C('site_domain');
}
function getDistrict($idstr)
{
	$ids = explode(',', $idstr);
	$disarr = F('district');
	return $disarr[$ids[0]]['alias'];
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
/**
 * 显示星级
 */
function showStar($rate = 0)
{
	$rate = $rate ? $rate : 3;
	$str = '';
	for ($i=1; $i<=$rate; $i++){
		$str .= '★';
	}
	return $str;
}
