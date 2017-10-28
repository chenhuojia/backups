<?php
 define('APP_DEBUG',true);
define('APP_PATH','./Interface/');
define('APP_MODE','api');
// error_reporting(1);
// set_exception_handler('AppError');
// set_error_handler('AppError');
require 'StatisticClient.php';
require 'ThinkPHP/ThinkPHP.php';
function  AppError($errno='', $errstr='', $errfile='', $errline='',$errcontext=''){
   $msg= is_numeric($errno)?$errstr:$errno;
   StatisticClient::report(MODULE_NAME . ':' . CONTROLLER_NAME,ACTION_NAME, 0, 5502, "文件:{$errfile}行数{$errline},详细信息：".$msg);
    echo  '{"code":300,"message":403}';
    exit();
} 
StatisticClient::report(MODULE_NAME . ':' . CONTROLLER_NAME,ACTION_NAME, 1, 200, '')
?>