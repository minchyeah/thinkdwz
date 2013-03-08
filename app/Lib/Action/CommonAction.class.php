<?php

class CommonAction extends Action
{
	/**
	 * 系统设置项
	 * @var array
	 */
	protected $sysSetting = array();
	
	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		$this->assign('static_url', __ROOT__.'/data');
	    $settings=M('Settings');
	    $sys=$settings->where(array('category'=>'sys'))->select();
	    if(is_array($sys)){
	    	foreach ($sys as $k => $v){
	    		$this->sysSetting[$v['skey']] = $v['svalue'];
	    		C(strtoupper($v['skey']),$v['svalue']);
	    	}
	    }
	}
}