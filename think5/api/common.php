<?php
use api\common\validate\TokenGet;
use think\Session;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//use think\Session;
/**
 * API返回函数
 * @param unknown $code
 * @param string $msg
 * @param array $data
 ****/
function ajaxReturn($code,$msg='',$data=[]){
    $result = [
        'code' => $code,
        'msg'  => $msg,
        'time' => date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']),
        'data' => $data,
    ];
    echo json_encode($result,JSON_UNESCAPED_UNICODE);exit;
}
/***
 * 返回数据格式
 * @param unknown $no
 * @param unknown $message
 * @param string $data
 * @return unknown[]|string[]
 * ***/
function message($no, $message, $data = "")
{
    return array(
        'error' => $no,
        'message' => $message,
        'data' => $data
    );
}
/***
 * 获取请求IP地址
 * @return string
 * ***/
function get_client_ip(){
    return !empty((request()->ip()))?request()->ip():'';
}
/***
 * redis测试
 * @param int $db
 * @return number|number|\Redis|int
 * ***/
function GetRedisConn(int $db=1)
{
    static $handle = array(
        'version' => - 1
    );
    if ($handle['version'] == $db) {
        return $handle['redis'];
    } else {
        if (! isset($handle['redis'])) {
            $handle['redis'] = new \Redis();
            $handle['redis']->pconnect(C('REDIS_HOST'), C('REDIS_PORT'), C('redis_time_out'));
        }
        $handle['version'] = $db;
        $handle['redis']->select($db);
    }
    return $handle['redis'];
}

/**
 * 用户登录时 开启 本次登录session,只有在没有token 还没有token时使用
 *
 * @param unknown $toekn
 */
function start_session($token)
{
   session_id($token);
}

/**
 * 检测用户是否已经登录
 */
function is_login()
{       
    $token=new TokenGet();
    $token->goCheck();
    start_session(request()->param('token'));
    $user = Session::get('user_id');
    if (empty($user)) {
        return false;
    } else {
        Session::set('user_id',$user);
        return true;
    }
}

//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function http_curl($url,$post='',$cookie='', $returnCookie=0){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    if($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));

    }
    if($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);

    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    if($returnCookie){
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $info['cookie']  = substr($matches[1][0], 1);
        $info['content'] = $body;
        return $info;
    }else{
        return $data;
    }
}
