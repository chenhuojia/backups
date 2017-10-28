<?php
namespace User\Service;

class UserAPIService
{
    
   public function getOpenid($code,$ty){
       switch ($ty) {
           case 1:
           return WXgetOpenid($code);
           break;
           case 2:
           return QQgetOpenid($code);
           break;
           default:
             E('错误第三方')  ;
           break;
       }
   }
    /**
     * QQ 获取 openid
     */
    public function  QQgetOpenid($code){
        import('Common/Common/requests');
        $openid=\requests::post();
        
        return message(200, '成功获取', $openid);
    }
    /**
     * 微信获取openid
     */
    public  function WXgetOpenid($code){
        import('Common/Common/requests');
       $openid= \requests::post();
        return message(200, '成功获取', $openid);
    }

    
}

