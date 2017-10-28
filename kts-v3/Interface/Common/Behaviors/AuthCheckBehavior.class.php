<?php
namespace Common\Behaviors;

use Think\Behavior;

/**
 * 登陆检测类
 * @author Administrator
 *
 */
class AuthCheckBehavior extends Behavior
{

    public function run(&$params)
    {   
        $auth = MODULE_NAME . ':' . CONTROLLER_NAME . ':' . ACTION_NAME;
        if (C('USER_AUTH_ON')) {
            if (is_login()) {
                return message(200, '在线',null);
            }else{
               if (in_array_case($auth,$params)){
                   return message(200, '不用登陆',null);
               }else{
                return message(401, '没有登录',null);
               }
            }
        }else {
            return message(200, '没有开启', null);
        }
    }
}
?>