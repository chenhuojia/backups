<?php
namespace Admin2\Controller;
use Think\Controller;
use Admin2\Common\Controller\CommonController;
use think\Session;


class BlogController extends CommonController {
   
    public function index(){         
        
        $this->display('blog');
       
    }

    public function Det(){
    
        $this->display('article');
         
    }

}