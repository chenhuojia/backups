<?php
namespace Think\Log\Driver;

use Think\Exception;

class Mysql{
    private $config;
    // 实例化并传入参数
    public function __construct($config=array()){
        $this->config   =   array_merge($this->config,$config);
    }
    /**
     * 日志写入接口
     * @access public
     * @param string $log 日志信息
     * @param string $destination  写入目标
     * @return void
     */
    public function write($level,$log,$destination='') {
        try {
        M('log_option')->add(
           array('ip'=>$_SERVER['REMOTE_ADDR'],'时间'=>date('Y-m-d G:i'),'客户端信息'=>$_SERVER['HTTP_USER_AGENT'],'请求路径'=>$_SERVER['REQUEST_URI'],'日志'=>$log,'请求参数'=>json_encode(array_diff($_REQUEST, $_COOKIE)),'类型'=>$level)
            );
        }catch (\Exception $e){
            
        }
    }
}

