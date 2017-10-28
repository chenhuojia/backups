<?php
namespace User\Controller;
use Think\Controller;
class LoginController extends Controller {
    
    
    public function login(){
        
        $User = D("User"); // 实例化User对象
        $User->create(); // 生成数据对象
        $user=$User->where(array('name'=>I('post.name'),'password'=>md5(I('post.password'))))->find();
        if ($user){
            $User->save();
            session('userInfo',$user);
            $user=session('userInfo');
            dump($user);
            //$this->success('登录成功');            
        }
        $this->error('登录失败');   
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
    
    public function ajaxRegistered(){
        $User = D("User"); // 实例化User对象
        $User->create(); // 生成数据对象
        $id=$User->add();
        $user=$User->where(array('user_id'=>$id))->find();
        if ($user){
            $User->save();
            session('userInfo',$user);
            $user=session('userInfo');
            $this->ajaxReturn(1);
        }
        $this->ajaxReturn(0);
    }
    
}