<?php

class IndexAction extends AdminAction
{
    public function index()
    {
        $this->display();
    }
    
    public function main()
    {
    	$this->display();
    }
    
    public function left()
    {
    	$_GET['id'] = $_GET['id'] ? $_GET['id'] : 'config';
    	$this->display();
    }
}
?>
