<?php
namespace api\common\behavior;
use think\Config;
class UserToken {
    
    public function run(&$params)
    {   
        $auth = request()->module() . ':' . request()->controller() . ':' .request()->action();
        if (Config::get('USER_AUTH_ON')) {
            if (is_login()) {
                return message(200, '在线',null);
            }else{
               if (in_array($auth,$params)){
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