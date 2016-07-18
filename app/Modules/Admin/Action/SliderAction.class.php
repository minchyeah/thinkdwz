<?php
/**
 * 幻灯片模块
 * @author minch
 */
class SliderAction extends AdminAction
{
	protected $position_map = array(
			'index'=>'首页通栏',
			'index_engineer'=>'首页工程团队',
			'innert'=>'内页通栏',
		);
	
	public function _initialize()
	{
		parent::_initialize();
		$this->assign('position_map', $this->position_map);
	}
	
    public function index()
    {
    	$model = D('Slider');
    	$totalCount = $model->count();
    	$currentPage = intval($_REQUEST['pageNum']);
    	$currentPage = $currentPage ? $currentPage : 1;
    	$numPerPage = 20;
    	$rowOffset = ($currentPage-1) * $numPerPage;
    	$list = $model->order('position ASC, sort_order DESC')->limit($rowOffset . ',' . $numPerPage)->select();
    	
    	$this->assign('list', $list);
    	$this->assign('totalCount', $totalCount);
    	$this->assign('numPerPage', $numPerPage);
    	$this->assign('currentPage', $currentPage);
        $this->display();
    }
	
	public function add()
	{
		$this->display();
	}
	
	public function edit() {
		$model = D('Slider');
		$id = $_REQUEST['id'];
		$vo = $model->find ( $id );
		$this->assign ( 'vo', $vo );
		$this->display('add');
	}
	
	public function save()
	{
		$model = D ('Slider');
		$image = $this->saveImage($_FILES['img']);
		if($image){
			$_POST['image'] = $image;
		}
		if (false === $model->create()) {
			$this->error($model->getError());
		}
		
		//保存当前数据对象
		if(!intval($_POST['id'])){
			$rs = $model->add();
		}else{
			$rs = $model->save();
		}
		
		if ($rs !== false) {
			$this->success('保存成功');
			echo '<script type="text/javascript">
					var response = {
						"status":"1",
						"info":"\u64cd\u4f5c\u6210\u529f",
						"navTabId":"",
						"forwardUrl":"",
						"callbackType":"forward"
					};
					if(window.parent.donecallback) {
						window.parent.donecallback(response);
						window.parent.$.pdialog.closeCurrent();
					}
			    </script>';
		} else {
			//失败提示
			$this->error ('保存失败!'.$model->getDbError());
		}
	}
	
	public function delete()
	{
		$model = D('Slider');
		$id = $_REQUEST['id'];
		$rs = $model->where(array('id'=>$id))->delete();
		if ($rs !== false) {
			$this->dwzSuccess('保存成功');
		} else {
			$this->dwzError('保存失败!'.$model->getDbError());
		}
	}
}