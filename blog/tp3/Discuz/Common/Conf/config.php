<?php
return array(
	//'配置项'=>'配置值'
    //数据库设置
    'DB_TYPE'   => 'mysql', // 数据库类型
    //'DB_HOST'   => 'rm-uf671242s4g18rtxe.mysql.rds.aliyuncs.com', // 服务器地址
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'blog', // 数据库名 
    'DB_USER'   => 'root', // 用户名
    //'DB_PWD'    => 'ChenHuoJia@0907', // 密码
    'DB_PWD'    => '123456', // 密码 
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'bk_', // 数据库表前缀
    
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layout',
    'URL_HTML_SUFFIX'       => 'html',
    
    'BANNER_URL'            =>  '/bolg/tp3/Public/picture/',
    'SESSION_AUTO_START'  =>true,
    'TMPL_CACHE_ON' => false,
    'TMPL_CACHE_ON' => false,
    'URL_MODEL'             =>  1,
    'TMPL_CACHE_ON' => false,
    'TMPL_CACHE_ON' => false,
);