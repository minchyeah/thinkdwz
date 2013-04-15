<?php

class StoreTypeModel extends Model
{
	public function tree($map=array('pid'=>0))
	{
		$return = '';
		if($_REQUEST['cid']!='' || $_REQUEST['cname']!=''){ //附加“清空”按钮
			$return .= '<ul class="tree treeFolder">';
			$return .= '	<li><a href="javascript:;" style="color:red" onclick="if(\''.$_REQUEST['cid'].'\') $(\'#'.$_REQUEST['cid'].'\').val(\'\'); if(\''.$_REQUEST['cname'].'\') $(\'#'.$_REQUEST['cname'].'\', navTab.getCurrentPanel()).val(\'\');">清空重置</a></li>';
			$return .= '</ul>';
		}
		$list = $this->field('id,type_name')->where($map)->select();
		if($list){
			$return .= '<ul class="tree treeFolder expand collapse">';
			foreach($list as $rs){
				if($rs['pid']==0){
					$strlink = '<a href="javascript:;" onclick="selectClass(\''.$rs['id'].'\',\''.$rs['type_name'].'\');")>'.$rs['type_name'].'</a>';
					$return .= '<li>'.$strlink.'</li>';
				}
			}
			$return .= '</ul>';
		}
		return $return;
	}
}

?>