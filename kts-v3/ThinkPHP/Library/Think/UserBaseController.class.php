<?php
namespace Think;
use Think\Controller;
class UserBaseController extends Controller
{
    public function _initialize(){
        $message=B('Behavior\UserAuthCheck',null,array('Home:Index:loginUI'));
        if($message['error']==300){
            $this->redirect('Home/Index/LoginUI');
        }
        if($message['error']==401){
            $this->SendDWZ(300, $message['message']);
        }
        if($message['error']==200){
            return;
        }
        die('无权访问');
    }
}

