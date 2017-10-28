<?php 
namespace Admin\Model;
use Think\Model;
use think\helper\hash\Md5;
use Org\Util\Page;
/**
 * 基础model
 */
class AdminModel extends Model{

    
     protected $_validate = array(
        array('name','require','用户名必须！',1),       
        array('name','','帐号名称已经存在！',0,'unique',2), // 在新增的时候验证name字段是否唯一
        array('password','require','密码必须！',1) ,
        //array('verify','require','验证码必须！') ,
        //array('checkpwd','checkPwd','密码格式不正确',1,'callback'), // 自定义函数验证密码格式
    ); 
    
    protected $_auto = array (       
        array('addtime','time',3,'function'), 
        array('encrypt_code','getCode',3,'callback') ,
    );
    
    protected function getCode(){
        return microtime();
    }
    
   
}