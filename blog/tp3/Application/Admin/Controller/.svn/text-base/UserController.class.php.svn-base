<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Common\Controller\CommonController;
use think\Session;


class UserController extends CommonController {
   
    public function userList(){         
        $data=D('user')->getUserList();
        $this->page=$data['page']->show();
        $this->list=$data['data'];
        $this->display('user-list');
       
    }

    public function userAdd(){
    
        if (IS_POST){
            $user=D('user');
            if (!$user->create()){
                $this->error($user->getError());
            }else{
                $user->add();
                $this->success('success');
            } 
        }
        $this->display('user-add');
         
    }
    
    public function userShow(){
    
        $this->display('user-show');
         
    }
    
    public function userPasswordEdit(){
    
        $this->display('user-password-edit');
         
    }
    

    
   
}