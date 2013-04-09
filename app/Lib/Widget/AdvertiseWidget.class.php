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
			$result = $ad['code'];
		}
		return $result;
	}
}