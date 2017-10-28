<?php
namespace Discuz\Controller;
use Think\Controller;
use Discuz\Common\Controller\CommonController;
use think\Session;
use Discuz\Common\Controller\Page;

class MoreController extends CommonController {
   
    public function elements(){

        $this->display('elements');
       
    }
    
    public function full(){
    
        $this->display('full-width');
         
    }
    
    public function page(){
    
        $this->display('page');
         
    }
    
 
    
  
}