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
		$ad = $model->where($where)->order('rand()')->find();
		$result = '';
		if($ad){
			if('district'==$data['type']){
				$result = '<div class="syggw">'.$ad['html'].'</div>';
			}else{
				$result = $ad['code'];
			}
		}
		return $result;
	}
}