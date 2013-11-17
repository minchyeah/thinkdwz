<?php

class StoresModel extends Model
{
	/**
	 * 星级评分
	 * @var unknown
	 */
	public $stars = array(
		1=>-1,
		2=>1,
		3=>3,
		4=>4,
		5=>5,
	);
	/**
	 * 更新店铺星级
	 * @param number $store_id 店铺ID
	 */
	public function updateStar($store_id = 0)
	{
		$Commemt = D('StoreComment');
		$rate = $Commemt->field('store_id,AVG(rate) star')->where(array('store_id'=>$store_id))->find();
		$this->where(array('id'=>$store_id))->setField('rating', intval($rate['star']));
		return $rate;
	}
}

?>