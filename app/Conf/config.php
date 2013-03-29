<?php

$config = array(
    'APP_GROUP_LIST' 		=> 'Home,Admin,Api', // 项目分组设定,多个组之间用逗号分隔,例如'Home,Admin'
    'APP_GROUP_MODE' 		=> 1, // 分组模式 0 普通分组 1 独立分组，本项目不允许使用普通分组
    'APP_GROUP_PATH' 		=> 'Modules', // 分组目录 独立分组模式下面有效
    'TMPL_ACTION_ERROR'     => 'Public:error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => 'Public:success', // 默认成功跳转对应的模板文件
	'URL_CASE_INSENSITIVE'  => true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL'             => 2,       // URL访问模式,0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式，提供最好的用户体验和SEO支持
	'URL_HTML_SUFFIX'       => '.html',  // URL伪静态后缀设置
    'URL_404_REDIRECT'      =>  '', // 404 跳转页面 部署模式有效
);

$dbconfig = require ROOT_PATH.'/config.php';

return array_merge($config,$dbconfig);
?>