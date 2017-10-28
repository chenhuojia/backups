<?php
namespace api\v1\logic;
use think\Exception;
use api\lib\exception\WeChatException;
use think\Config;
class UserToken{
  
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;
    
    public function __construct($code){
        Config::load(APP_PATH.'extra/wx.php');
        $this->code=$code;
        $this->wxAppID=config('wx.app_id');
        $this->wxAppSecret=config('wx.app_secret');
        $this->wxLoginUrl=sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
    }
    
    public function get(){
       $result=http_curl($this->wxLoginUrl);
       $arrayEesult=json_decode($result,true);
       //ozx0M0Re0pZuf2yhvl9TZVJOuMKQ       
       if (empty($arrayEesult)){
           throw new Exception('获取微信session_key和微信openid错误');
       }else{
           if (array_key_exists('errcode',$arrayEesult)){
               $this->loginError($arrayEesult);
           }
           $this->grantToken($arrayEesult);
       } 
    }
    
    private function grantToken($result){
        return $result['openid'];
    }
    
    private function loginError($result){
        throw new WeChatException([
            'msg'=>$result['errmsg'],
            'errorCode'=>$result['errcode']
        ]);
    }
}