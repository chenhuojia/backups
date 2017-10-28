<?php
namespace api\common\controller;
use think\Hook;
use think\Config;
use api\common\controller\ApiController;
use api\lib\exception\TokenException;
class BaseController extends ApiController
{
    public function __construct()
    {   
        parent::__construct();        
        $config=Config::load(APP_PATH.'extra/v1/nologin.php');
        $message = Hook::listen('run',$config['nologin']);       
        if($message[0]['error']==200){
              return true;
        }
        if($message[0]['error']==401){
            throw new TokenException();          
        }
        
    }
}
