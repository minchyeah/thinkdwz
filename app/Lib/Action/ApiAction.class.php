<?php

class ApiAction extends CommonAction
{
	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		parent::_initialize();
		C('TOKEN_ON', false);
	}

}