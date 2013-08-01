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
		$model = D('Sliders');
		$where = array();
		$where['position'] = $data['position'];
		$ad = $model->where($where)->order('`sort` ASC')->select();
		$result = '';
		if($ad){
			$data['sliders'] = $ad;
			$result = $this->renderFile($tpl, $data);
		}
		return $result;
	}
}