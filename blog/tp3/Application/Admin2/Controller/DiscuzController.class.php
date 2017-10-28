<?php
namespace Admin2\Controller;
use Think\Controller;
use Admin2\Common\Controller\CommonController;
use think\Session;


class DiscuzController extends CommonController {
   
    public function index(){         
        
        $this->display('list');
       
    }

    public function Det(){
    
        $this->display('article');
         
    }

}