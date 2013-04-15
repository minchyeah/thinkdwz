<?php

class LocationsModel extends Model
{
	public function getlocs()
	{
		$return = array();
		$locs = $this->select();
		if (is_array($locs)) {
			foreach ($locs as $k=>$v){
				if(strpos($v['district'], ',')){
					$ds = explode(',', $v['district']);
					foreach ($ds as $sk=>$sv){
						$key = intval($sv);
						$key && $return[$key][] = $v;
					}
				}else{
					$key = intval($v['district']);
					$key && $return[$key][] = $v;
				}
			}
		}
		return $return;
	}
	
	/**
	 * 输出栏目的树形菜单HTML
	 * @param $map
	 * @param $link  edit（修改栏目时）,info_addview（左侧菜单栏）,空表示默认选择栏目
	 * @param $selparent 是否允许选择父栏目
	 * @return string html <ul><li>....</li></ul>
	 */
	public function tree($map=array('pid'=>0),$link='',$selparent=''){
		$return = '';
		if($link==''){
			if($_REQUEST['cid']!='' || $_REQUEST['cname']!=''){ //附加“清空”按钮
				$return .= '<ul class="tree treeFolder">';
				$return .= '	<li><a href="javascript:;" style="color:red" onclick="if(\''.$_REQUEST['cid'].'\') $(\'#'.$_REQUEST['cid'].'\').val(\'\'); if(\''.$_REQUEST['cname'].'\') $(\'#'.$_REQUEST['cname'].'\', navTab.getCurrentPanel()).val(\'\');">清空重置</a></li>';
				$return .= '</ul>';
			}
		}
		$list = M('District')->field('id,title,pid,sort_order,type')->where($map)->select();
		$locs = $this->getlocs();
		if($list){
			if($link=='info_addview'){
				$return .= '<ul>';
			}else{
				$return .= '<ul class="tree treeFolder expand collapse">';
			}
			foreach($list as $rs){
				if($rs['pid']==0){
					$strlink = '<a href="javascript:;" onclick="return false;")>'.$rs['title'].'</a>';
					if($locs[$rs['id']]){
						$return .= '<li>'.$strlink;
						$return .= $this->_for_tree($locs[$rs['id']], $link, $selparent);
						$return .= '</li>';
					}
				}
			}
			$return .= '</ul>';
		}
		return $return;
	}
	
	/**
	 * 
	 * @param unknown $classmodule
	 * @param unknown $arrchildids
	 * @param string $link
	 * @param string $selparent
	 * @return void|string
	 */
	private function _for_tree($arrchildids,$link='',$selparent=''){
		if(empty($arrchildids)) return;
		$strchild = '';
		if(!is_array($arrchildids)) $arrchildids = unserialize($arrchildids);
		for($i=0,$n=count($arrchildids); $i<$n; $i++){
			if(is_array(($data = $arrchildids[$i]))){
				$strlink = '<a href="javascript:;" onclick="selectClass(\''.$data['id'].'\',\''.$data['title'].'\');")>'.$data['title'].'</a>';
				$strchild .= '<li>'.$strlink;
				$strchild .= '</li>';
			}
		}
		return ($strchild!='') ? '<ul>'.$strchild.'</ul>' : '';
	}
}

?>