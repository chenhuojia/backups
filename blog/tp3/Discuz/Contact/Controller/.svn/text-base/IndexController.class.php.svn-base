<?php
namespace Contact\Controller;
use Think\Controller;
use think\Session;

class IndexController extends Controller {
   
    public function index(){
        $this->topic=M('discuz_group')->where(array('parent_id'=>0))->select();
        $this->display('contact');
       
    }
    
    public function add(){
    
       $post=D('discuz_post');
       if (!$post->create()){
           $this->error($post->getError());
       }else{
           $post->add();
           $this->success('发表成功');
       } 
       //print_r($_POST);die;  
    }
    
}