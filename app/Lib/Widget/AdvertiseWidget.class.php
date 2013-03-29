<?php
/**
 * 广告小部件
 * @author minch
 */
class AdvertiseWidget extends Widget
{
	public function render($data)
	{
		$model = D('Advertise');
		$where = array();
		$where['position'] = $data['position'];
		$ad = $model->where($where)->find();
		$result = '';
		if($ad){
			$width = $data['width'] ? ' width="'.$data['width'].'"' : '';
			$height = $data['height'] ? ' height="'.$data['height'].'"' : '';
			$result = '<a href="'. $ad['link'] .'" target="_blank"><img src="'. $ad['image'] .'" '.$width.$height.'/></a>';
		}
		return $result;
	}
}