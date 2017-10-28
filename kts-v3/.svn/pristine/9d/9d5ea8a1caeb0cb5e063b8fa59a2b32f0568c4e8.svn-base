<?php
namespace User\Logic;
use Think\Model;
class UserThirdLoginLogic extends Model{
    
    /**
     * @param unknown $openid
     * @param unknown $ty 1:QQ 2:微信
     */
    public function getUserFindOpenid($openid,$ty){
        
        $userid=M('user_third_login')->field('user_id')->where(array('ty'=>$ty,'openid'=>$openid))->find();
        if(!empty($userid)){
            return message(300, '该账号已经关联其他三方账号了,不能再关联',$userid);
        }
        return message(200, '恭喜该账号可以使用');
        
    }
    /**
     * 判断第三方登录登录
     * $ty:QQ:1 微信:2
     */
    public function userThirdLogin($openid,$ty){
         $login=M('user_third_login')->where(array('openid'=>$openid,'ty'=>$ty))->find();
         if(empty($login)) return message(300, '请进行联合登录,绑定手机号码');
         else return message(200, '已经绑定账号了',$login);
    }

     /**
     * 判断用户是否绑定第三方登录
     * $ty:QQ:1 微信:2
     */
    public function isUserThirdLogin($userid,$ty){
         $login=M('user_third_login')->where(array('user_id'=>$userid,'ty'=>$ty))->find();
         if(empty($login)) return message(200, '还未绑定账号');
         else return message(300, '已经绑定账号了',$login);
    }
     /**
     * 用户绑定第三方登录
     */
    public function userBindingThree($userid,$three_type,$openid){
        $data = array(
            'user_id'=>$userid,
            'ty'=>$three_type,
            'openid'=>$openid
        );
        $user=M('user_third_login')->add($data);
        if(empty($user)) return 1;
        else return 0;
    }

    public function getWeiXinOpenid($code)
    {   
           import('Common/Common/requests');
           $response=\requests::get('https://api.weixin.qq.com/sns/oauth2/access_token',array('appid'=>C('WEIXIN.appid'),'secret'=>C('WEIXIN.secret'),'code'=>$code,'grant_type'=>'authorization_code'));
           $data=json_decode($response,true);
           if(!$data['errcode']){
               $access_token=$data['access_token'];
               session(array('access_token'=>$access_token,'expire'=>7200));
               $openid=$data['openid'];
               return $openid;
           }  
          return "";   
    }

   public function WeiXinAccessTokenOpenid($access_token,$openid)
   {   
       import('Common/Common/requests');
       if(!session('?name')){
          $access_token= $this->isAccessTokenChange($access_token);
          if($access_token=="") return -1;
       }
       $response=\requests::get(' https://api.weixin.qq.com/sns/auth',array('access_token'=>$access_token,'openid'=>$openid));
       $data=json_decode($response,true);
       if($data['errcode']==0&&$data['errmsg']=='ok'){
          return 1;
       } 
       return 0;
   }

   public function isAccessTokenChange($access_token)
   {   
       import('Common/Common/requests');
       $response=\requests::get('  https://api.weixin.qq.com/sns/oauth2/refresh_token',array('appid'=>C('WEIXIN.appid'),'grant_type'=>'refresh_token','refresh_token'=>$access_token));
       $data=json_decode($response,true);
       if(!$data['errcode']){
           $access_token=$data['access_token'];
           session(array('access_token'=>$access_token,'expire'=>7200));
           return $access_token;
       }
       return "";     
   }

    public function getQq(){ 
     //public function getQqOpenid($code)
           $code=$_GET['code'];
           $code='FF26D07968D60B2E345C66FB6565A6A4';
           $appid='100330156';
           $appkey = 'e184b2f2d2a12bc8f24cc551b6e80bff';
           $url =urlencode('http://121.196.230.128:8384/index.php/User/UserLogin/getq');
           $url="https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=$appid&redirect_uri=$url&client_secret=$appkey&code=$code";
           $rs=file_get_contents($url);
           var_dump($rs);
           $arr=explode('&',$rs);
           //获取openid
           $url='https://graph.qq.com/oauth2.0/me?'.$arr[0];
           $rs=file_get_contents($url);
           return $rs;
           //http://www.w2bc.com/Article/44045
          
     }



    
}

