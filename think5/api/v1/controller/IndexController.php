<?php
namespace api\v1\controller;
use api\common\controller\ApiController;
class IndexController extends ApiController
{
    public function index()
    {   return 1;
        /* $request = Request::instance();
        // 获取当前域名
        echo 'domain: ' . $request->domain() . '<br/>'; */     
        /* $params=array('5',25,3);
        $result = Hook::listen('run',$params); */
        
        
    }
}
