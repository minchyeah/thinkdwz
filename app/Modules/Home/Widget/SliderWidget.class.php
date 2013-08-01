<?php
/**
 * 幻灯片小部件
 * @author minch
 */
class SliderWidget extends Widget
{
	public function render($data)
	{
		$tpl = $data['type'];
		$model = D('Slider');
		$where = array();
		$where['position'] = $data['position'];
		$ad = $model->where($where)->order('`sort_order` ASC')->select();
		$result = '';
		if($ad){
			$data['sliders'] = $ad;
			$result = $this->renderFile($tpl, $data);
		}
		return $result;
	}
}