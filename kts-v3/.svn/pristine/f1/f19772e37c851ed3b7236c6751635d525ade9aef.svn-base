<?php
namespace User\Logic;
/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
use Think\Model;
use User\Util\Util;
class UserLogic  extends Model{

    /**
     * 根据ID 获取 用户 信息
     * return userId,头像，用户开通的信息,昵称
     */
    public function getUserInfo($userid){
        $user=M('user')->field('user_id,name,phone')->where(array('user_id'=>$userid))->find();
        $userMes = \User\Util\Util::GetUserAvatrAndNick($user['user_id']);
        $user['avatar'] = $userMes['avatar'];
        return $user;
    }
    //生成用户名
    public function addUserName()
    {    
        $name='kts_'.date('md').mt_rand();
        $user =M('user')->where(array('name'=>$name))->count(1);
        if($user){
            self::addUserName();
        }
        return $name;
    }
     /**
     * 用户登录
     */
    public function userLogin($phone,$password){
        $user=M('user')->field('user_id,name,phone,password,salt,appkey,is_show')->where(array('phone'=>$phone))->find();
        if(empty($user)) return message(300, '账号或者密码不存在');
        if($user['password'] != md5($password.$user['salt'])){
            return message(300, '账号或者密码不存在');
        }
        if($user['is_show'] == 0){
             return message(300, '该账号已被禁用,请联系客服');
        }
        import('User/Util/Util');
        $userMes = \User\Util\Util::GetUserAvatrAndNick($user['user_id']);
        $user['avatar'] = $userMes['avatar'];
        $user['is_shop'] = 1;
        unset($user['password']);
        unset($user['salt']);
        unset($user['is_show']);
        return message(200, '登录成功',$user);
    }

     /**
     * 更换保存用户的session信息
     */
    public function userSaveSession($user_id,$token){
        $user=M('user')->field('user_id,name,phone,appkey')->where(array('user_id'=>$user_id))->find();
        $user['token'] = $token;
        unset($user['appkey']);
        self::saveUserInfo($token,$user);
        return $user;
    }

     /**
     * 用户通过手机登录
     */
    public function userPhoneLogin($phone){
        $user=M('user')->field('user_id,name,phone,password,salt,appkey,is_show')->where(array('phone'=>$phone))->find();
        if(empty($user)) return message(300, '账号或者密码不存在');
        if($user['is_show'] == 0){
             return message(300, '该账号已被禁用,请联系客服');
        }
        import('User/Util/Util');
        $userMes = \User\Util\Util::GetUserAvatrAndNick($user['user_id']);
        $data['avatar'] = $userMes['avatar'];
        $user['is_shop'] = 1;
        unset($user['password']);
        unset($user['salt']);
        unset($user['is_show']);
        return message(200, '登录成功',$user);
    }


    /**
     * 用户登录信息
     */
    public function userLoginLog($userid,$ty,$op){
        $data = array(
            'userid'=>$userid,
            'login_time'=>time(),
            'login_ip'=>get_client_ip(), // 本次登录IP，时间，登录位置,
            'ty'=>$ty,
            'op'=>$op
        );
        $user=M('user_login_log')->add($data);
        if(empty($user)) return 1;
        else return 0;
    }

    /**
     * 更改用户的token
     */
    public function userChageToken($userid,$appkey){
         $strToken = md5($appkey).'-'.$userid.'-'.time();
         //$token = myEncode($strToken);
         return $strToken;
    }

    public function userAdd($name,$phone,$password,$op='1')
    {   
        $salt=mt_rand();
        $appkey=mt_rand();
        $password = md5($password.$salt);
        $data = array(
            'name'=>$name,
            'phone'=>$phone,
            'password'=>$password,
            'pay_pwd'=>0,
            'appkey'=>$appkey,
            'is_show'=>1,
            'salt'=>$salt
        );
        $user_id=M('user')->add($data);
        if (empty($user_id)){ 
             return message(300, '添加失败');
        }else{
             $ip = get_client_ip(); // 本次登录IP，时间，登录位置
             $Ip = new \Org\Net\IpLocation();
             $area = $Ip->getlocation($ip); // 获取某个IP地址所在的位置
             M('user_xq')->add(array('user_id'=>$user_id,'sex'=>'男','create_time'=>time(),'source'=>$op,'city'=>$area));
             //$avatar = \User\Util\Util::saveJpg($user_id, './Public/avatar/');
             return message(200, '添加成功',array('user_id'=>$user_id,'avatar'=>'123456.jpg','appkey'=>$appkey));
        }  
    }


    /**
     * 更改用户的密码
     */
    public function userChagePassword($user_id,$password,$salt){
         $password = md5($password.$salt);
         $user=M('user')->where(array('user_id'=> $user_id))->save(array('password'=>$password));
         if($user) return message(200, '修改成功');
         else return message(300, '修改失败');
    }

    
    /**
     * 根据订单状态获取用户订单数量
     */
    public function getOrderNum($user_id,$type)
    {
      
      if($type){
           switch ($type) {
             case '1': $where ="user_id=$user_id and order_status!=4 and order_status in (0,1) and shipping_status=0 and pay_status=0";
               break;//代付款
            case '2': $where ="user_id=$user_id and order_status!=4 and order_status=1 and shipping_status in (0,1) and pay_status=2";
               break;//待收货
            case '3': $where ="user_id=$user_id and order_status!=4 and  order_status=5 and shipping_status=2 and pay_status=2";
               break;//待评价
            case '4': $where ="user_id=$user_id and order_status!=4 and order_status=1 and shipping_status in (0,1,3) and pay_status in (3,4)";
               break;//待退款
             default: return 0;
               break;
           }
       }
       $data = M('order')
              ->field('order_id,order_status,shipping_status,pay_status,order_amount,order_sn')
              ->where($where)->count();
       return $data;
    }

    //融云
    public function rongYunCurl($action, $params, $contentType = 'urlencoded', $module = 'im', $httpMethod = 'POST')
    {
        switch ($module) {
            case 'im':
                $action = 'http://api.cn.ronghub.com' . $action . '.' . 'json';
                break;
            case 'sms':
                $action = 'http://api.sms.ronghub.com' . $action . '.json';
                break;
            default:
                $action = 'http://api.cn.ronghub.com' . $action . '.' . 'json';
        }
        $nonce = mt_rand();
        $timeStamp = time();
        $appSecret = 'KPQRVIj4HE';
        $appKey = 'n19jmcy5ni2x9';
        $sign = sha1($appSecret . $nonce . $timeStamp);
        $httpHeader = array(
            'RC-App-Key:'.$appKey,
            'RC-Nonce:'.$nonce,
            'RC-Timestamp:'.$timeStamp,
            'RC-Signature:'.$sign,
        );;
        $ch = curl_init();
        if ($httpMethod == 'POST' && $contentType == 'urlencoded') {
            $httpHeader[] = 'Content-Type:application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_query($params));
        }
        if ($httpMethod == 'POST' && $contentType == 'json') {
            $httpHeader[] = 'Content-Type:Application/json';
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        if ($httpMethod == 'GET' && $contentType == 'urlencoded') {
            $action .= strpos($action, '?') === false ? '?' : '&';
            $action .= $this->build_query($params);
        }
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_POST, $httpMethod == 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //处理http证书问题
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if (false === $ret) {
            $ret = curl_errno($ch);
        }
        curl_close($ch);
        return $ret;
    }

    public function build_query($formData, $numericPrefix = '', $argSeparator = '&', $prefixKey = '') {
        $str = '';
        foreach ($formData as $key => $val) {
            if (!is_array($val)) {
                $str .= $argSeparator;
                if ($prefixKey === '') {
                    if (is_int($key)) {
                        $str .= $numericPrefix;
                    }
                    $str .= urlencode($key) . '=' . urlencode($val);
                } else {
                    $str .= urlencode($prefixKey) . '=' . urlencode($val);
                }
            } else {
                if ($prefixKey == '') {
                    $prefixKey .= $key;
                }
                if (is_array($val[0])) {
                    $arr = array();
                    $arr[$key] = $val[0];
                    $str .= $argSeparator . http_build_query($arr);
                } else {
                    $str .= $argSeparator . $this->build_query($val, $numericPrefix, $argSeparator, $prefixKey);
                }
                $prefixKey = '';
            }
        }
        return substr($str, strlen($argSeparator));
    }
    /**
     * @param unknown $token
     * @param unknown $art  登录通过后，保存用户信息,仅在登录方法实用
     */
    private static function saveUserInfo($token,$art){
        start_session($token);
        $_SESSION=$art;
    }


    

     


    



}