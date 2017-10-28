<?php
namespace Admin2\Controller;
use Think\Controller;
use Admin2\Common\Controller\CommonController;
use think\Session;


class IndexController extends CommonController {
   
    public function index(){         
        
        $this->display();
       
    }

    public function indexV1(){
    
        $this->display('index_v1');
         
    }

}