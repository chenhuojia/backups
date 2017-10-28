<?php
namespace Think;
use Think\Controller;
class BaseController extends Controller
{
    public function _initialize(){
        $message=B('Behavior\AuthCheck','',array('Home:Admin:LoginUI'));
        if($message['error']==200){
            return;
        }
        if($message['error']==300){
            $this->redirect('Home/Admin/LoginUI');
        }
        if($message['error']==401){
            $this->SendDWZ(300, $message['message']);
        }
    }
}

