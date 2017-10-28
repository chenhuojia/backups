<?php
namespace Home\Util;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
class Util
{
    /*
     * 生成定长22位的订单码
     * */
    public static function MyOrderNo22(){
        $code  =date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $code .= randCodeM(22-strlen($code),1);
        return $code;
    }

    public static function uploadImg(){
         require_once dirname(dirname(__FILE__)).'/Vendor/qiniu/autoload.php';
         // 引入鉴权类
         //require_once dirname(dirname(__FILE__)).'/Vendor/qiniu/src/Qiniu/Auth.php';   // 需要填写你的 Access Key 和 Secret Key
         $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
         $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
         // 构建鉴权对象
         $auth = new Auth($accessKey, $secretKey);
         // 要上传的空间
         $bucket = 'sjb-kts';
         // 生成上传 Token
         $token = $auth->uploadToken($bucket);
         if ($token){
             return $token;
         }else{
         	 return '';
         }
         
     }
     
     
     
     public static  function uploadShopImg(){
          require_once dirname(dirname(__FILE__)).'/Vendor/qiniu/autoload.php';
         // 引入鉴权类
        // require_once dirname(dirname(__FILE__)).'/Vendor/qiniu/src/Qiniu/Auth.php';  // 需要填写你的 Access Key 和 Secret Key
         $accessKey = 'zR87rxmyagB70rAy_AZHbDVlZlgS9aSseZZGLp6b';
         $secretKey = 'M_x29xDHy_A5z9pSZ2v5kYNkvtlz_bVqjqvCIH_0';
         // 构建鉴权对象
         $auth = new Auth($accessKey, $secretKey);
         // 要上传的空间
         $bucket = 'ktspic';
         // 生成上传 Token
         $token = $auth->uploadToken($bucket);
         if ($token){
             return $token;
         }else{
             return '';
         }
          
     }

}

