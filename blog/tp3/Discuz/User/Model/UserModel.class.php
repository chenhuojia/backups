<?php 
namespace User\Model;
use Think\Model;
/**
 * 基础model
 */
class UserModel extends Model{

    
     protected $_validate = array(
        array('name','require','用户名必须！',1),
        array('password','require','密码必须！',1),
        array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
       //array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
    ); 
    
    protected $_auto = array (
        array('avatar','/bolg/tp3/Public/picture/s1.jpg'), 
        array('password','md5',3,'function') ,
        array('profession','getJob',3,'function') ,
        array('phone','getPhone',3,'function') ,
        array('contact_address','getAddress',3,'function') ,
        array('email','getEmail',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
        array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
        array('addtime','time',3,'function'), 
        array('login_time','getTime',3,'function') ,
    );
    
    protected function getName(){
        return I('post.name');
    }
    
    protected function getJob(){
        return I('post.job',0);
    }
    
    protected function getPhone(){
        return I('post.phone',0);
    }
    protected function getEmail(){
        return I('post.email',0);
    }
    protected function getAddress(){
        return I('post.address',0);
    }
    protected function getTime(){
        return $_SERVER['REQUEST_TIME'];
    }
}