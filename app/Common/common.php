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
	return empty($imgfile) ? __ROOT__.'/static/images/space.gif' : $imgfile;
}
function getFirstImg($content)
{
    $part = '/<[img|IMG].*?src=[\'|\"](.*?(?:[^\'|\"]))[\'|\"].*?[\/]?>/';
    preg_match($part, $content,$img);
    return array_pop($img);
}
function stripContent($content)
{
    $content = strip_tags($content);
    $content = preg_replace("/\s/", '', $content);
    $content = preg_replace("/&nbsp;/i", '', $content);
    return trim($content);
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
function showStar($score = 0)
{
	$rate = 0;
	switch (true){
		case $score >= 2000:
			$rate = 6;
			break;
		case $score >= 500:
			$rate = 5;
			break;
		case $score >= 200:
			$rate = 4;
			break;
		case $score >= 100:
			$rate = 3;
			break;
		case $score >= 10:
			$rate = 2;
			break;
		case $score >= 1:
			$rate = 1;
			break;
		default:
			$rate = 0;
			break;
	}
	$str = '';
	for ($i=1; $i<=$rate; $i++){
		$str .= '★';
	}
	return $str;
}
function hidename($name)
{
    $first = mb_substr($name, 0, 1, 'utf-8');
//     $str = str_pad($first, mb_strlen($name, 'utf-8'), '*', STR_PAD_RIGHT);
    return $first.'**';
}
function str2arr($str, $delimiter=',', $ext_delimiter = '，')
{
	$string = str_replace($ext_delimiter, $delimiter, $str);
	return explode($delimiter, $string);
}
