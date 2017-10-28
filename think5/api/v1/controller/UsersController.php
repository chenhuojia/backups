<?php
namespace api\v1\controller;
use api\common\controller\BaseController;
class UsersController  extends BaseController
{  
    /***
     * 用户列表
     * @return unknown
     * ***/
     public function userList(){
         $user= \think\Loader::model('Users','logic');
         $data=$user->getUserList();
         $code=(empty($data))?201:200;        
         ajaxReturn($code,'success',$data);         
     }

}

