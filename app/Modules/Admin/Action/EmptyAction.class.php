<?php
/**
 * 空操作
 * @author Minch
 */
class EmptyAction extends AdminAction
{
	public function index()
	{		
		$this->redirect('Index/index');
    }
    
	public function _empty()
	{
		$this->redirect("/");
	}
}