<?php

class IndexAction extends HomeAction
{
	public function index()
	{
	    $this->links();
		$this->display('Index:index');
	}
}
?>
