<?php
namespace Acme\MinsuBundle\Common;
include '../vendor/alipay-sdk/aop/AopClient.php';
final class Payment
{
    
    /**
     * 支付宝签名 
     **/ 
    public function alipay_sign($order){
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2016052401434746';
        $aop->rsaPrivateKeyFilePath = '../vendor/alipay-sdk/aop/rsa_private_key.pem';
        $aop->alipayrsaPublicKey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0DDHd4jlNOWvFDP/JWLsRrabESO5p3iT3dxRQEYNN4eGNbTmaNJXzy1F2AFpzlVCuLCs1E2IyOs9VU8vgC3VdrRSTILaIPtdh28XKyC5eCsp9lSIXvrFAySb6wjtHAlfAuwg6/Ww9NJchKX0auv4AwZQJSl0v3tTCM6uelK0gwQIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $sign=$aop->alRsaSign($order);
        return $sign;
    }
    
    /**
     *支付宝退款 
     **/
   public function alipay_refund($order_sn,$order_amount){
        include '../vendor/alipay-sdk/aop/request/AlipayTradeRefundRequest.php';
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2016052401434746';
        $aop->rsaPrivateKeyFilePath = '../vendor/alipay-sdk/aop/rsa_private_key.pem';
        $aop->alipayrsaPublicKey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new \AlipayTradeRefundRequest();
        $request->setBizContent("{" .
            "    \"trade_no\":\"".$order_sn."\"," .
            "    \"refund_amount\":".$order_amount."," .
            "    \"refund_reason\":\"正常退款\"," .
            "    \"out_request_no\":\"HZ01RF001\"," .
            "    \"operator_id\":\"OP001\"," .
            "    \"store_id\":\"NJ_S_001\"," .
            "    \"terminal_id\":\"NJ_T_001\"" .
            "  }");
        $result = $aop->execute($request);
        return $result;
    }
    
    /**
     * 排序
     * **/
   public function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }
    
    /**
     * 组装字符串
     **/
   public function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);   
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
    
        return $arg;
    }
    
    /**
     *验证签名 
     **/
   public function rsaVerify($prestr, $sign) {
        $sign = base64_decode($sign);
        $dir=dirname(__FILE__);
        $public_key= file_get_contents($dir.'/alipay_public_key.pem');
        $pkeyid = openssl_get_publickey($public_key);
        if ($pkeyid) {
            $verify = openssl_verify($prestr, $sign, $pkeyid);
            openssl_free_key($pkeyid);
        }
        if($verify == 1){
            return  1;
        }else{
            return 0;
        }
    }
}
