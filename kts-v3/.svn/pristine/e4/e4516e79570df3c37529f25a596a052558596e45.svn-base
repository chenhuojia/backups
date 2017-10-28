<?php

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */

/**
 * 检测验证码
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

 //防注入，字符串处理，禁止构造数组提交
  //字符过滤
  function safe_replace($string) {
    if(is_array($string)){ 
       $string=implode('，',$string);
         $string=htmlspecialchars(str_shuffle($string));
    } else{
        $string=htmlspecialchars($string);
    }
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string=str_replace("select","",$string);
    $string=str_replace("join","",$string);
    $string=str_replace("union","",$string);
    $string=str_replace("where","",$string);
    $string=str_replace("insert","",$string);
    $string=str_replace("delete","",$string);
    $string=str_replace("update","",$string);
    $string=str_replace("like","",$string);
    $string=str_replace("drop","",$string);
    $string=str_replace("create","",$string);
    $string=str_replace("modify","",$string);
    $string=str_replace("rename","",$string);
    $string=str_replace("alter","",$string);
    $string=str_replace("cas","",$string);
    $string = str_replace('"','&quot;',$string);
    $string = str_replace("'",'',$string);
    $string = str_replace('"','',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'',$string);
    $string = str_replace('}','',$string);

    return $string;
  }

/**
 * 获取用户信息
 * @param $user_id_or_name  用户id 邮箱 手机 第三方id
 * @param int $type  类型 0 user_id查找 1 邮箱查找 2 手机查找 3 第三方唯一标识查找
 * @param string $oauth  第三方来源
 * @return mixed
 */
function get_user_info($user_id_or_name,$type = 0,$oauth=''){
    $map = array();
    if($type == 0)
        $map['user_id'] = $user_id_or_name;
    if($type == 1)
        $map['email'] = $user_id_or_name;
    if($type == 2)
        $map['mobile'] = $user_id_or_name;
    if($type == 3){
        $map['openid'] = $user_id_or_name;
        $map['oauth'] = $oauth;
    }
    $user = M('users')->where($map)->find();
    return $user;
}
/**
 * 检查手机号码
 * @param $mobile 手机号码
 */
function check_mobile($mobile){
    if(preg_match('/1[34578]\d{9}$/',$mobile))
        return true;
    return false;
}



