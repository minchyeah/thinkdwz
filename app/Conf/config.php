<?php

$config = array(
    'APP_GROUP_LIST' 		=> 'Home,Admin,Api', // 项目分组设定,多个组之间用逗号分隔,例如'Home,Admin'
    'APP_GROUP_MODE' 		=> 1, // 分组模式 0 普通分组 1 独立分组，本项目不允许使用普通分组
    'APP_GROUP_PATH' 		=> 'Modules', // 分组目录 独立分组模式下面有效
    'DEFAULT_CITY'			=> 'nanning', //默认城市
    'FRIENDLINK_TYPE' 		=> array( // 友情链接类型
			'friendlink' 	=> '友情链接',
			'business' 		=> '商务合作',
		),
    'TMPL_ACTION_ERROR'     => 'Public:error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => 'Public:success', // 默认成功跳转对应的模板文件
	'TMPL_PARSE_STRING' 	=> array( // 模板内容替换
			' src="image' 	=> ' src="'.__ROOT__.'/static/image',
			' href="css/' 	=> ' href="'.__ROOT__.'/static/css/',
			' src="js/' 	=> ' src="'.__ROOT__.'/static/js/',
			' src="upload/' => ' src="'.__ROOT__.'/data/upload/',
		),
	'URL_CASE_INSENSITIVE'  => true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL'             => 2,       // URL访问模式,0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式，提供最好的用户体验和SEO支持
	'URL_HTML_SUFFIX'       => '.html',  // URL伪静态后缀设置
    'URL_404_REDIRECT'      =>  '', // 404 跳转页面 部署模式有效
	'URL_ROUTER_ON'			=> true, // 开启路由
	'URL_ROUTE_RULES' 		=> array( //定义路由规则
			'/^products$/'               			=> 'Product/category',
			'/^products\/cate-(\d+)-(\d+)-(\d+)/'   => 'Product/category?pid=:1&cate_id=:2&page=:3',
			'/^product\/(\d+)-(\d+)-(\d+)/'         => 'Product/index?cate_id=:1&id=:2',
			'/^about$/'          					=> 'Article/about?pid=0&cate_id=2&page=0',
			'/^about\/(\d+)-(\d+)-(\d+)/'           => 'Article/about?id=:1&cate_id=:2&pid=:3',
			'/^video$/'          					=> 'Article/video',
			'/^video\/page,(\d+)/'           		=> 'Article/video?page=:1',
			'/^video\/play,(\d+)/'           		=> 'Article/video?id=:1',
			'/^news$/'          					=> 'Article/category?pid=0&cate_id=1&page=0',
			'/^news\/cate-(\d+)-(\d+)-(\d+)/'       => 'Article/category?pid=:1&cate_id=:2&page=:3',
			'/^news\/(\d+)-(\d+)-(\d+)/'          	=> 'Article/index?pid=:1&cate_id=:2&id=:3',
			'contact'          						=> array('Article/page','code=contact'),
		),
	'VAR_PAGE'				=> 'page', // 分页变量
);

$dbconfig = require ROOT_PATH.'/config.php';

return array_merge($config,$dbconfig);
?>