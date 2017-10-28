<?php 
namespace Admin\Model;
use Think\Model;
use think\helper\hash\Md5;
use Org\Util\Page;
/**
 * 基础model
 */
class UserModel extends Model{

    
     protected $_validate = array(
        array('name','require','用户名必须！',1),       
        array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        //array('checkpwd','checkPwd','密码格式不正确',1,'callback'), // 自定义函数验证密码格式
    ); 
    
    protected $_auto = array (
        array('avatar','/bolg/tp3/Public/picture/s1.jpg'), 
        array('password','checkPwd',3,'callback') ,
        array('profession','getJob',3,'callback') ,
        array('phone','getPhone',3,'callback') ,
        array('contact_address','getAddress',3,'callback') ,
        array('email','getEmail',3,'callback') , // 对password字段在新增和编辑的时候使md5函数处理
        array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
        array('addtime','time',3,'function'), 
        array('sex','getSex',3,'callback') ,
    );
    
    protected function checkPwd(){
        return I('post.password',md5(123456));
    }
    
    protected function getName(){
        return I('post.name');
    }
    
    protected function getJob(){
       return I('post.job','苦逼程序员');
       return '苦逼程序员';
    }
    
    protected function getPhone(){
        return I('post.phone',13413885166);
        return 13413885166;
    }
    protected function getEmail(){
        return I('post.email','shabi@163.com');
        return 'shabi@163.com';
    }
    protected function getAddress(){
        return I('post.address','帝都');
        return '帝都';
    }
    protected function getTime(){
        return $_SERVER['REQUEST_TIME'];
    }
    protected function getSex(){
        return I('post.sex','1');;
    }
    
    
    public function getUserList($take=10){
        $count=$this->where(array('is_show'=>1))->count();
        $Page=new Page($count,$take);
        $data=$this->query('select * from bk_user where is_show =1 limit '.$Page->firstRow.', '.$Page->listRows);
        return array('page'=>$Page,'data'=>$data);
    }
}