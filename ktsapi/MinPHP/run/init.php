<?php
    //项目所有文件的入口文件
    //防跳墙常量
    define('API','can');
    //开启session
    session_start();
    //关错错误输出
    error_reporting(E_ALL ^ E_NOTICE);
    //设置页面字符编码
    header("Content-type: text/html; charset=utf-8");
    //设置时区
    date_default_timezone_set('Asia/Shanghai');
    //加载公用函数
    include './MinPHP/core/function.php';
    M();
    
