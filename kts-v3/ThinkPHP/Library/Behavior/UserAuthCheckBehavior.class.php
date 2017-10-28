<?php 
namespace Behavior;
use Think\Behavior;
class UserAuthCheckBehavior extends Behavior {
    
    public function run(&$params){
        $auth=SPACE_NAME.':'.CONTROLLER_NAME.':'.ACTION_NAME;
        if(C('USER_AUTH_ON')) {
            if(in_array_case($auth, $params)){
                return message(200, '不用登陆');
            }
           if(!Worker_is_login()){
             return message(300, '没有登录');
               }
        }
               return message(200, '通过权限');
    }
}
?>