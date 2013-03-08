<?php
//error_reporting(0);
@set_time_limit(240);
//开启调试模式
define("APP_DEBUG", true);
//根目录
define('ROOT_PATH', getcwd());
//应用名称
define('APP_NAME', 'app');
//应用目录
define('APP_PATH', ROOT_PATH.'/app/');
//数据目录
define('DATA_PATH', ROOT_PATH.'/data/');
//缓存目录
define('RUNTIME_PATH', ROOT_PATH.'/temp/');

require('system/core.php');
?>