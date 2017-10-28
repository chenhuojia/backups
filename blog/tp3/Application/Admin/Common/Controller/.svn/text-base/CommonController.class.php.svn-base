<?php
namespace Admin\Common\Controller;
use Think\Controller;
class CommonController extends Controller {
    
    public function _initialize(){
        $action=strtolower(ACTION_NAME);
        if (empty(session('admin')) && $action != 'login'){
                header('Location:'.U('System/Login'));
                //$this->redirect('User/Login');
        }
        
    }

   

   
   
   
}