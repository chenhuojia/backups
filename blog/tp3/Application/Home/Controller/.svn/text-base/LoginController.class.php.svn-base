<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\Controller\CommonController;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;



class LoginController extends CommonController {
    
    
    public function login(){
        $this->display('login_v2');  
    }
   
    public function ajaxlogin(){
        $User = D("User"); // 实例化User对象
        $User->create(); // 生成数据对象
        $user=$User->where(array('name'=>I('post.name'),'password'=>md5(I('post.password'))))->find();
        if ($user){
            $User->where(array('user_id'=>$user['user_id']))->save(array('login_time'=>$_SERVER['REQUEST_TIME']));
            session('userInfo',$user);
            $user=session('userInfo');
          $this->ajaxReturn(1);
        }
        $this->ajaxReturn(0);
    }
    
    public function logout(){
        $a=session('userInfo',null);
        if ($a) $this->ajaxReturn(1);
        $this->ajaxReturn(0);
    }
    
    public function register(){
        $this->display();
    }
    
    public function ajaxRegistered(){
        $User = D("User"); // 实例化User对象
        if ($user=$User->create()){
            $id=$User->add();
            $this->sendEmailTmpl($user['email'],$user['name'],'注册成功','欢迎注册本网站 本网站承诺不进行任何商业活动！');
            $user['user_id']=$id;
            session('userInfo',$user);
            $this->ajaxReturn(array('code'=>200,'msg'=>'success'));
        }
        $this->ajaxReturn(array('code'=>300,'msg'=>$User->getError()));
    }
    
    public function forGetPwd(){
       $name=I('post.name');
       $newpawd=I('post.password');
       if(empty($name) || empty($newpawd)) $this->ajaxReturn(0);
       $result=M('user')->where(array('name'=>$name))->getField('password');
       if($result){
           if ($result['password']==md5($newpawd)){
               $this->ajaxReturn(1);
           }else{
               M('user')->where(array('name'=>$name))->setField('password',md5($newpawd));
           } 
       }
       $this->ajaxReturn(2);
    }
    
    public function GetCode(){
        $name=I('post.phone',13725474374);  
        if (!$a=preg_match('/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/',$name)){
           $this->ajaxReturn(array('code'=>300,'msg'=>'手机号码错误'));
        }
        if (!$result=M()->query('select * from bk_user where phone = '.$name)){            
            $this->ajaxReturn(array('code'=>300,'msg'=>'账号不存在'));
        }
        //$this->ajaxReturn(array('code'=>$result[0]['user_id'],'msg'=>M()->_sql()));
        $appKey = 'e0x9wycfeaufq';
        $appSecret = 'y5QJ7mt6a4Ar';
        $jsonPath = "jsonsource/";
        include (dirname(dirname(__FILE__)).'/Util/RongCloud.class.php');
        $RongCloud = new \RongCloud($appKey,$appSecret);        
        $data = $RongCloud->SMS()->sendCode($name, '4vZnPLJvk7pbZHh9nc3ABh', '86');
        $data=json_decode($data,true);
        if ($data['code']==200){
            session($result[0]['user_id'].'_sessionId',$data['sessionId']);
            $this->ajaxReturn(array('code'=>200,'msg'=>'success'));
        }
        $this->ajaxReturn(array('code'=>300,'msg'=>'网络有误，请稍后再试'));
    }
    
    public function phoneLogin(){
        $this->display('login_v3');
    }
    
    public function ajaxPhoneLogin(){
        $code=I('post.code',324617);
        $phone=I('post.phone',13725474374);

        if (!$result=M()->query('select * from bk_user where phone = '.$phone)){
            $this->ajaxReturn(array('code'=>300,'msg'=>'账号不存在'));
        }
        $sessionid=session($result[0]['user_id'].'_sessionId');
        if (!$code || !$sessionid || !preg_match('/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/',$phone)){
            $this->ajaxReturn(array('code'=>300,'msg'=>'请检查数据是否有误'));
        }
        $appKey = 'e0x9wycfeaufq';
        $appSecret = 'y5QJ7mt6a4Ar';
        $jsonPath = "jsonsource/";
        include (dirname(dirname(__FILE__)).'/Util/RongCloud.class.php');
        $RongCloud = new \RongCloud($appKey,$appSecret);
        $data = $RongCloud->SMS()->verifyCode($sessionid, $code);
        $data=json_decode($data,true);        
        if ($data['code']==200){
            //session($result[0]['user_id'].'_sessionId',null);
            session('userInfo',$result[0]);
            $this->ajaxReturn(array('code'=>200,'msg'=>'success'));
        }
        $this->ajaxReturn(array('code'=>300,'msg'=>'网络有误，请稍后再试'));
    }
    
    public function test(){
        dump(session());
    }
}