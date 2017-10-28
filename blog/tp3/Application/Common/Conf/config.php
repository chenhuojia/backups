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

    'LOAD_EXT_CONFIG'=>'sessionConfig',
    
    'SESSION_EXPIRE'=>31104000,//过期时间 24*3600*30*12秒
    'redis_time_out'=>5,
    
    
    'BANNER_URL'            =>  'Public/picture/',
    'SESSION_AUTO_START'  =>true,
    'TMPL_CACHE_ON' => false,
    'TMPL_CACHE_ON' => false,
    'URL_MODEL'             =>  1,
    //邮件配置
    'SEND_EMAIL_HOST'=> 'smtp.163.com',
    'SEND_EMAIL_USERNAME'=> 'chjdwl@163.com',
    'SEND_EMAIL_PASSWORD'=> 'chjdwl@0907',
    'SEND_EMAIL_REPLYTO'=> 'chjdwl@163.com',
    
    'appID'     => 'wx8ea258c0833e4e0c',
    'appsecret' => '891e7d19d7649535c8626e2c207fb1c8',
);