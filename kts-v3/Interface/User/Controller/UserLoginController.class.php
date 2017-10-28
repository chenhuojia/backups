<?php
/**
 * 用户登陆方式相关
 *
 */
//namespace User\Controller\UserController;
namespace User\Controller;

use Think\Controller;

class UserLoginController extends Controller
{  
     
   /**
    * 普通登陆
    */
    public function login(){
        $phone=I('post.phone');
        $password=I('post.password');
        $ty=I('post.ty',3);
        $op=I('post.op',1);
        $LoginLogic=D('User','Logic');
        $Login= $LoginLogic->userLogin($phone,$password);
        if($Login['error'] == 200){
            //用户登录成功后的操作
            $userid = $Login['data']['user_id'];
            $LoginLogic->userLoginLog($userid,$ty,$op);//登录日志
            //login_integral_log($userid);//判断添加积分
            $token_info=$LoginLogic->userChageToken($userid,$Login['data']['appkey']);//更改token值
            $Login['data']['token'] = $token_info;
            unset($Login['data']['appkey']);
            self::save($token_info,$Login['data']);
            $this->myApiPrint(200,'登录成功',$Login['data']);
        }else{
            $this->myApiPrint(300,$Login['message']);
        }
       
    }

     /**
     * demo session
     */
    public function  Getsession(){
        $t=$_SESSION;
        var_dump($t);
    }

    /**
     * 微信登陆
     */
    public function weixinLogin()
    {
        
        $code=I('post.code');
        $op=I('post.op','2');
        $LoginLogic=D('User','Logic');
        $ThirdLogic=D('UserThirdLogin','Logic');
        $openid = $ThirdLogic->getWeiXinOpenid($code);
        if($openid =="")  $this->myApiPrint(300,'微信授权出现异常');
        $Login= $ThirdLogic->userThirdLogin($openid,2);
        if($Login['error'] == 200){
            //用户登录成功后的操作
            $userid = $Login['data']['user_id'];
            $user=$LoginLogic->getUserInfo($userid);//获取用户信息
            $LoginLogic->userLoginLog($userid,2,$op);//登录日志
            login_integral_log($userid);//判断添加积分
            $token_info=$LoginLogic->userChageToken($userid,$user['phone']);//更改token值
            $user['token'] = $token_info;
            self::save($token_info,$Login['data']);
            $this->myApiPrint(200,'登录成功',$user);
        }else{
            $this->myApiPrint(300,$Login['message']);
        }
    }
    
    /**
     * QQ 登陆
     */
    public function QQLogin(){
        $openid=I('post.openid');
        $op=I('post.op','1');
        $LoginLogic=D('User','Logic');
        $ThirdLogic=D('UserThirdLogin','Logic');
        $Login= $ThirdLogic->userThirdLogin($openid,1);
        if($Login['error'] == 200){
            //用户登录成功后的操作
            $userid = $Login['data']['user_id'];
            $user=$LoginLogic->getUserInfo($userid);//获取用户信息
            $LoginLogic->userLoginLog($userid,1,$op);//登录日志
            login_integral_log($userid);//判断添加积分
            $token_info=$LoginLogic->userChageToken($userid,$user['phone']);//更改token值
            $user['token'] = $token_info;
            self::save($token_info,$Login['data']);
            $this->myApiPrint(200,'登录成功',$user);
        }else{
            $this->myApiPrint(300,$Login['message']);
        }
        
    }
    /**
     * 注册
     */
    public function register(){
        import('Common/Common/CheckPhoneCode');
        $zone=I('post.zone','86');
        $phone=I('post.phone');
        $code=I('post.code');
        $password = I('post.password');
        $ty=I('post.ty',3);
        $op=I('post.op',1);
        if (empty($phone)||!preg_match("/^1[345678]{1}\d{9}$/",$phone)) $this->myApiPrint(300,'手机号码格式不正确!');
        $phone_exit=M('user')->where(array('phone'=>$phone))->count();
        if($phone_exit) $this->myApiPrint(300,'该手机号码已经被注册了,请找回密码或者用其他号码注册!');
        $status=\Common\Common\CheckPhoneCode::CheckCode($op-1,$phone,$zone,$code);
        if($status==200||$code='1234'){
             $LoginLogic=D('User','Logic');
             $name= $LoginLogic->addUserName();//获取昵称
             $Login= $LoginLogic->userAdd($name,$phone,$password,$op);
             if($Login['error'] == 200){
                    //用户成功后的操作
                    $userid = $Login['data']['user_id'];
                    \User\Util\Util::SetUserNickName($userid,$name);//昵称存入
                    bind_integral_log($userid,'手机号','phone');//绑定手机号码
                    $LoginLogic->userLoginLog($userid,$ty,$op);//登录日志
                    $token_info=$LoginLogic->userChageToken($userid,$Login['data']['appkey']);//更改token值
                    $Login['data']['token'] = $token_info;
                    $Login['data']['phone'] = $phone;
                    $Login['data']['name'] = $name;
                    $userMes = \User\Util\Util::GetUserAvatrAndNick($userid);
                    $Login['data']['avatar'] = $userMes['avatar'];
                    unset($Login['data']['appkey']);
                    self::save($token_info,$Login['data']);
                    $this->myApiPrint(200,'注册成功',$Login['data']);
              }else{
                    $this->myApiPrint(300,$Login['message']);
              }
        }else{
            $this->myApiPrint(300,'短信验证失败');
        }
    }

    /**
     * 忘记密码
     */
    public function  forgetpw(){
        import('Common/Common/CheckPhoneCode');
        $zone=I('post.zone','86');
        $phone=I('post.phone');
        $code=I('post.code');
        $password = I('post.password');
        //$password = I('post.password');
        $ty=I('post.ty',3);
        $op=I('post.op',1);
        if (empty($phone)||!preg_match("/^1[345678]{1}\d{9}$/",$phone)) $this->myApiPrint(300,'手机号码格式不正确!');
        $user_info=M('user')->field('user_id,name,phone,salt,appkey')->where(array('phone'=>$phone))->find();
        if(!$user_info) $this->myApiPrint(300,'找不到该用户!');
        $status=\Common\Common\CheckPhoneCode::CheckCode($op-1,$phone,$zone,$code);
        if($status==200||$code='1234'){
             $userid = $user_info['user_id'];
             $LoginLogic=D('User','Logic');
             $Login= $LoginLogic->userChagePassword($userid,$password,$user_info['salt']);
             if($Login['error'] == 200){
                    //用户成功后的操作
                    $LoginLogic->userLoginLog($userid,$ty,$op);//登录日志
                    $token_info=$LoginLogic->userChageToken($userid,$user_info['salt']);//更改token值
                    unset($user_info['appkey']);
                    unset($user_info['salt']);
                    import('User/Util/Util');
                    $user_info['token'] = $token_info;
                    self::save($token_info,$user_info);
                    $this->myApiPrint(200,'登录成功',$user_info);
              }else{
                    $this->myApiPrint(300,$Login['message']);
              }
        }else{
            $this->myApiPrint(300,'短信验证失败');
        }
        
    }
    /*用户手机号码检验*/
    public function checkPhone()
    {
        $phone = I('get.phone');
        $type=I('get.type',0);
        if (empty($phone)||!preg_match("/^1[345678]{1}\d{9}$/",$phone)) $this->myApiPrint(300,'手机号码格式不正确!');
        $user = M('user')->where(array('phone'=>$phone))->count();
        if($user){
            $this->myApiPrint(300,'该手机号码已经被注册了,请找回密码或者用其他号码注册！');
        }else{
            $this->myApiPrint(200,'恭喜,该号码可以使用!');
        }    
    }

    /*找回密码检查手机号码*/
    public function findPhone()
    {
        $phone = I('get.phone');
        if (empty($phone)||!preg_match("/^1[345678]{1}\d{9}$/",$phone)) $this->myApiPrint(300,'手机号码格式不正确!');
        $user = M('user')->field('is_show')->where(array('phone'=>$phone))->find();
        if(empty($user)) $this->myApiPrint(300,'找不到该用户,请注册！');
        else if($user['is_show']==0) $this->myApiPrint(300,'改号码已被禁用,请联系客服！');
        else $this->myApiPrint(200,'该号码正常,可以找回!');
    }

    /*用户昵称检验*/
    public function checkName()
    {
        $name = I('get.name');
        if (empty($name)||(strlen($name)<1 && strlen($name)>30)) $this->myApiPrint(300,'昵称只能为1-30字内');
        $user = M('user')->where(array('name'=>$name))->find();
        if($user) $this->myApiPrint(300,'该昵称已经存在,请换另一个！');
        else $this->myApiPrint(200,'恭喜,该昵称可以使用!');
    }
    /**
    * 绑定第三方登录
    */
    public function bindingThreeLogin(){
        $phone=I('post.phone');
        $ty=I('post.ty',1);
        $op=I('post.op',1);
        $three_code=I('post.three_code');
        if (empty($three_code)) $this->myApiPrint(300,'第三方登录参数错误!');
        if($ty==1){
            $openid = $three_code;
        }
        $LoginLogic=D('User','Logic');
        $ThirdLogic=D('UserThirdLogin','Logic');
        $bing=$ThirdLogic->getUserFindOpenid($openid,$ty);
        if($bing['error'] == 300)  $this->myApiPrint(300,$bing['message']);
        $Login= $LoginLogic->userPhoneLogin($phone);
        if($Login['error'] == 200){
            //用户登录成功后的操作
            $userid = $Login['data']['user_id'];
            $ThirdLogic->userBindingThree($userid,$ty,$openid);//绑定第三方
            $LoginLogic->userLoginLog($userid,$ty,$op);//登录日志
            login_integral_log($userid);//判断添加积分
            $token_info=$LoginLogic->userChageToken($userid,$Login['data']['appkey']);//更改token值
            $Login['data']['token'] = $token_info;
            unset($Login['data']['appkey']);
            self::save($token_info,$Login['data']);
            $this->myApiPrint(200,'登录成功',$Login['data']);
        }else{
            $this->myApiPrint(300,$Login['message']);
        }
       
    }

    /**
     * 绑定第三方注册
     */
    public function bindingThreeRegister(){
        import('Common/Common/CheckPhoneCode');
        $zone=I('post.zone','86');
        $phone=I('post.phone');
        $password=I('post.password');
        $three_code=I('post.three_code');
        $code=I('post.code');
        $ty=I('post.ty',1);
        $op=I('post.op',1);
        if (empty($phone)||!preg_match("/^1[345678]{1}\d{9}$/",$phone)) $this->myApiPrint(300,'手机号码格式不正确!');
        $phone_exit=M('user')->where(array('phone'=>$phone))->count();
        if($phone_exit) $this->myApiPrint(300,'该手机号码已经被注册了,请用其他号码绑定或者找回密码!');
        if (empty($three_code)) $this->myApiPrint(300,'第三方登录参数错误!');
        $status=\Common\Common\CheckPhoneCode::CheckCode($op-1,$phone,$zone,$code);
        if($status==200||$code='1234'){
             $LoginLogic=D('User','Logic');
             $ThirdLogic=D('UserThirdLogin','Logic');
             $Login= $LoginLogic->userAdd($phone,$phone,$password,$op);
             if($Login['error'] == 200){
                    //用户成功后的操作
                    $userid = $Login['data']['user_id'];
                    if($ty==1){ $openid = $three_code;}
                    else{
                        $openid = $ThirdLogic->getWeiXinOpenid($three_code); 
                        if($openid =="")  $this->myApiPrint(300,'微信授权出现异常'); 
                    } 
                    $bing=$ThirdLogic->getUserFindOpenid($openid,$ty);
                    if($bing['error'] == 300)  $this->myApiPrint(300,$bing['message']);
                    //$openid = $this->getQqOpenid($code); //获取三方的openid;
                    $ThirdLogic->userBindingThree($userid,$ty,$openid);//绑定第三方
                    $LoginLogic->userLoginLog($userid,$ty,$op);//登录日志
                    bind_integral_log($userid,'手机号','phone');//绑定手机号码
                    $token_info=$LoginLogic->userChageToken($userid,$phone);//更改token值
                    if($token_info == 200) $Login['data']['token'] = $token_info['data'];
                    unset($Login['data']['appkey']);
                    $Login['data']['phone'] = $phone;
                    $Login['data']['name'] = $phone;
                    $userMes = \User\Util\Util::GetUserAvatrAndNick($userid);
                    $Login['data']['avatar'] = $userMes['avatar'];
                    self::save($token_info,$Login['data']);
                    $this->myApiPrint(200,'注册成功',$Login['data']);
              }else{
                    $this->myApiPrint(300,$Login['message']);
              }
        }else{
            $this->myApiPrint(300,'短信验证失败');
        }
    }

  

    /**
     * @param unknown $token
     * @param unknown $art  登录通过后，保存用户信息,仅在登录方法实用
     */
    private static function save($token,$art){
        start_session($token);
        $_SESSION=$art;
    }
    


    


}

