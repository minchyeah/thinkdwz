<?php

class DistrictModel extends Model
{
	/**
	 * 输出栏目的树形菜单HTML
	 * @param array $map 查询条件
	 * @return string html <ul><li>....</li></ul>
	 */
	public function tree($map=array('pid'=>0)){
		$return = '';
		$list = $this->field('id,title,pid,sort_order,type')->where($map)->order('sort_order ASC')->select();
		if($list){
			$return .= '<ul class="tree treeFolder expand collapse">';
			foreach($list as $rs){
				if($rs['pid']==$map['pid']){
					$strlink = '<a href="javascript:;" onclick="selectClass(\''.$rs['id'].'\',\''.$rs['title'].'\');")>'.$rs['title'].'</a>';
					$return .= '<li>'.$strlink.'</li>';
				}
			}
			$return .= '</ul>';
		}
		return $return;
	}
}

?>