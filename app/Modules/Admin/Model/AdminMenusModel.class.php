<?php

class AdminMenusModel extends Model
{
	public function topMenus()
	{
		$where = array();
		$where['pid'] = 0;
		$where['display'] = 1;
		return $this->where($where)->order('sort_order ASC')->select();
	}
	
	/**
	 * 左侧菜单列表
	 * @param int $id 主菜单ID
	 */
	public function leftMenus($id)
	{
		$where = array();
		$where['pid'] = $id;
		$where['display'] = 1;
		return $this->where($where)->order('pid ASC,sort_order ASC')->select();
	}
}

?>