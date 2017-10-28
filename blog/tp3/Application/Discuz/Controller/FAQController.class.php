<?php
namespace Discuz\Controller;
use Think\Controller;
use Discuz\Common\Controller\CommonController;
use think\Session;
use Discuz\Common\Controller\Page;

class FAQController extends CommonController {
   
    public function index(){

        $this->display('faq');
       
    }
    
  
}