<?php
namespace Discuz\Controller;
use Think\Controller;
use Discuz\Common\Controller\CommonController;
use think\Session;
use Discuz\Common\Controller\Page;

class SkinController extends CommonController {
   
    public function blue(){

        $this->display('blue-skin');
       
    }
    
    
  
}