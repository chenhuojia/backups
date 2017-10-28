<?php
namespace Admin2\Controller;
use Think\Controller;
use Admin2\Common\Controller\CommonController;
use think\Session;


class UserController extends CommonController {
   
    public function userList(){         
        
        $this->display('contacts');
       
    }

    public function userDet(){
    
        $this->display('profile');
         
    }
    
    public function login(){
    
        $this->display('login_v2');
         
    }
    
    public function userPasswordEdit(){
    
        $this->display('user-password-edit');
         
    }


}