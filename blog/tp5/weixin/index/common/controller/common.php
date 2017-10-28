<?php
namespace weixin\index\common\controller;
use think\Controller;
use think\Db;
use think\Config;
class common extends Controller
{
  protected $test_access_token=null;
  protected $access_token=null;
  static private $appid='wx17e9c603ce6bbfd2';
  static private $secret='d7411f0ff2bf96fffc6a189bbc957f2f';
  static private $test_appid='wx8ea258c0833e4e0c';
  static private $test_secret='891e7d19d7649535c8626e2c207fb1c8';
    
  public function __construct(){
     //$access_token=self::check_access_token();
      $test_access_token=self::check_test_access_token();
      $this->test_access_token=$test_access_token;
      //$this->access_token=$access_token;
      
  }
  
    private static function check_test_access_token(){
       $data=Db::query("select * from bk_access_token where appid='wx8ea258c0833e4e0c'");
       if ($data){         
           if(($data[0]['addtime']+7200) <= time()){
              $result=self::getTestAccess_token();
              return $result['access_token'];
           }
          
             return $data[0]['access_token'];
       }else{
           $result=self::getTestAccess_token();
           $a=Db::name('access_token')
           ->insert(
           array('id'=>2,'appid'=>self::$test_appid,'appsecret'=>self::$test_secret,'access_token'=>$result['access_token'],'time' => $result['expires_in'],
            'addtime'=>$_SERVER['REQUEST_TIME'],));
           return $result['access_token'];
       }
        
    }
    
    private function check_access_token(){
        $data=Db::query("select * from bk_access_token where appid='wx17e9c603ce6bbfd2'");
        if ($data){
            if(($data[0]['addtime']+7200) <= time()){
                $result=self::getAccess_token();
                return $result['access_token'];
            }
            return $data[0]['access_token'];
        }else{
           $result=self::getTestAccess_token();
           $a=Db::name('access_token')
           ->insert(
           array('id'=>1,'appid'=>self::$appid,'appsecret'=>self::$secret,'access_token'=>$result['access_token'],'time' => $result['expires_in'],
            'addtime'=>$_SERVER['REQUEST_TIME'],));
           return $result['access_token'];
       } 
    }
    
    protected static function getAccess_token(){
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&';
        $appid=Config::get('weixin_AppID');
        $secret=Config::get('weixin_AppSecret');
        $url=$url.'appid='.$appid.'&secret='.$secret;
        $data=httpsRequest($url);
        $data=json_decode($data,true);
        $result=Db::name('access_token')
        ->where('id',1)
        ->insert([
            'access_token'  => $data['access_token'],
            'time' => $data['expires_in'],
            'addtime'=>$_SERVER['REQUEST_TIME'],
        ]);
        //return $result;
        return $data;
    }
    
     protected static function getTestAccess_token(){
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&';
        $appid=Config::get('weixinTest_AppID');
        $secret=Config::get('weixinTest_AppSecret');
        $url=$url.'appid='.$appid.'&secret='.$secret;
        $data=httpsRequest($url);
        $data=json_decode($data,true);
        $result=Db::name('access_token')
        ->where('id',2)
        ->update([
            'access_token'  => $data['access_token'],
            'time' => $data['expires_in'],
            'addtime'=>$_SERVER['REQUEST_TIME'],
        ]);
        return $data;
    }


    
    protected function checkSignature(){
        if (!defined("TOKEN")) {
            echo 'TOKEN is not defined!'; exit;
        }
        $request = request();
        $signature   = $request->get('signature');
        $timestamp   = $request->get('timestamp');
        $nonce       = $request->get('nonce');
        $token       = TOKEN;
        $tmpArr      = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    
}
