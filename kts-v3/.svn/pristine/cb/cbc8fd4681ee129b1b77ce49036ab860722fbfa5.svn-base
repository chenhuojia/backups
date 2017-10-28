<?php 
namespace Behavior;
use Think\Behavior;
class AuthCheckBehavior extends Behavior {
    
    public function run(&$params){
        $auth=SPACE_NAME.':'.CONTROLLER_NAME.':'.ACTION_NAME;
        if(C('USER_AUTH_ON')) {
            if(in_array_case($auth, $params)){
                return message(200, '不用登陆');
            }
           if(!is_login()){
             return message(300, '没有登录');
           }else{
               
//                if($_SESSION [SESSION_NAME]['role_id']==-1){//超级管理员
//                    return message(200, '超级管理员通过');
//                }
//                if(!strpos($_SESSION [SESSION_NAME]['auth'], $auth)){
//                    return message(401, '你没有权限');
//                }else {
//                    return message(200, '通过权限');
//                }
           } 
          }else{
               return message(200, '没有开启权限');
              
          }
           }
}
?>