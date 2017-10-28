<?php
namespace app\admin\controller;
use think\controller;
use app\admin\common\controller\common;
use think\Config;

class Index extends common
{
    public function index()
    {  
       
      
        echo Config::get('__PUBLIC__');      
        return $this->fetch('index',['name'=>'thinkphp']);
        
    }
}
