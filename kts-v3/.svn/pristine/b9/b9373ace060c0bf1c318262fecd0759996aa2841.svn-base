<?php
namespace Order\Org;
include 'Public/alipay/aop/AopClient.php';
final class Alipay
{
    
    /**
     * 支付宝签名 
     **/ 
    public function alipay_sign($order){
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2017010604881202';
        //$aop->rsaPrivateKeyFilePath = 'Public/alipay/aop/rsa_private_key.pem';
        $aop->alipayrsaPublicKey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
        $aop->rsaPrivateKey="MIICWwIBAAKBgQCze4FgiP7S1eXwCOd7iGQyIV6OBHTm+WuhVGVbE31DbNiT12cjomZxGyZgfauiopMI6VTm0OQVa98qcLZ/6SwMpboy/Mcgn0znaUnaBMWPdH8ZfzwYS54IvcYIITaHSy5FMV21vKj+LDURYSIJGH8uOumV9PbLaxupoyjXVqZy6QIDAQABAoGAe1tQUXv2wAuZLL/B8WqNitLafPtdKX6V1dz+iHk9p8DAneP3FEvC4swJNVpKQ84/BmnwG2/Iw1xPloi/NeyZg7Ue/83k+fPoJ2OFAISnF+OqzCLeK9CNTMpz8eAoB4qARNhchfDvpUzjpUpcSl1qoame+lc0gL4OmLoVkKat7YECQQDX+2UR/v7CvhRtSEds9eDbrHay7vYxhcNKHJEL3QUS2GwUO19csvG/S7XVg4SRP1NOAel0UGiyUQbw0w/+byH5AkEA1LzWm5QOPLuXUM0V7xsPAzOtKTCjXjo6XtbbabQ9ucb1VB6XHM1TQV1+Cz/TwG+4hxZ+j+ZI+Pv5kH0JLLkUcQJBAIfjwlmqSHwawBtqCJaDtTBBuIUoWHocIR02EASY0SJkTMwF9yAJ7pRffE6Uawo//5frGAl4tgbLeER5Z2y8W3ECPyVlDFRMsjP6xZ5ubmyZVQo7CzUyV4U4twn0upzaEf+V+YnP1sA5V2kmBgH0NkSdXtJgId8pm6oooLXFRNeXsQJASgC0z11P8xhLbbF9pgopYpify+QAu8sQiCsbPbu21c2NheO4e4AAGjp80Hj1SJK3nsyPAkjAcTs8xptFqxNG7A==";
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $sign=$aop->alRsaSign($order);
        //$sign=$aop->rsaSign(self:: getOrderInfo());
        return $sign;
    }
    
    /**
     * 支付宝签名
     **/
    public function alipay_sign2($order){
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2017010604881202';
        //$aop->rsaPrivateKeyFilePath = 'Public/alipay/aop/rsa_private_key.pem';
        $aop->alipayrsaPublicKey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
        $aop->rsaPrivateKey="MIICWwIBAAKBgQCze4FgiP7S1eXwCOd7iGQyIV6OBHTm+WuhVGVbE31DbNiT12cjomZxGyZgfauiopMI6VTm0OQVa98qcLZ/6SwMpboy/Mcgn0znaUnaBMWPdH8ZfzwYS54IvcYIITaHSy5FMV21vKj+LDURYSIJGH8uOumV9PbLaxupoyjXVqZy6QIDAQABAoGAe1tQUXv2wAuZLL/B8WqNitLafPtdKX6V1dz+iHk9p8DAneP3FEvC4swJNVpKQ84/BmnwG2/Iw1xPloi/NeyZg7Ue/83k+fPoJ2OFAISnF+OqzCLeK9CNTMpz8eAoB4qARNhchfDvpUzjpUpcSl1qoame+lc0gL4OmLoVkKat7YECQQDX+2UR/v7CvhRtSEds9eDbrHay7vYxhcNKHJEL3QUS2GwUO19csvG/S7XVg4SRP1NOAel0UGiyUQbw0w/+byH5AkEA1LzWm5QOPLuXUM0V7xsPAzOtKTCjXjo6XtbbabQ9ucb1VB6XHM1TQV1+Cz/TwG+4hxZ+j+ZI+Pv5kH0JLLkUcQJBAIfjwlmqSHwawBtqCJaDtTBBuIUoWHocIR02EASY0SJkTMwF9yAJ7pRffE6Uawo//5frGAl4tgbLeER5Z2y8W3ECPyVlDFRMsjP6xZ5ubmyZVQo7CzUyV4U4twn0upzaEf+V+YnP1sA5V2kmBgH0NkSdXtJgId8pm6oooLXFRNeXsQJASgC0z11P8xhLbbF9pgopYpify+QAu8sQiCsbPbu21c2NheO4e4AAGjp80Hj1SJK3nsyPAkjAcTs8xptFqxNG7A==";
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $orderInfo=self::getOrderInfo($order);
        $orderstr=$this->getOrder($orderInfo);
        $sign=$aop->alRsaSign($orderInfo);
        //$a=$aop->verify($unsing, $sign, $rsaPublicKeyFilePath);
        return array('sign'=>$sign,'order'=>$orderstr);
    }
    
    public function getOrder($params){
        ksort($params);
        reset($params);
        $paramStr = [];
        foreach ($params as $k => &$param) {
            $param = '"' . $param . '"';
            $paramStr[] = $k . '=' . $param;
        }
        
        //$ordre=implode('&', $paramStr);
        return $paramStr;
    }
    
    public function getOrderInfo($order) {
        $orderInfo["partner"]='2088521047343485';
        //$orderInfo["seller_id"]='2088521047343485';
        $orderInfo["out_trade_no"]=$order['order_sn'];
        $orderInfo["subject"]=$order['book_name'];
        //$orderInfo["body"]=$order['book_desc'];
        $orderInfo["total_fee"]=$order['order_amount'];
        $orderInfo["notify_url"]='http://121.196.230.128:8384/index.php/Order/Payment/BookAlipayNotify';
        $orderInfo["return_url"]='http://121.196.230.128:8384/index.php/Order/Payment/BookAlipayNotify';
        $orderInfo["service"]='mobile.securitypay.pay';
        $orderInfo["payment_type"]=1;
        $orderInfo["_input_charset"]='utf-8';
        //$orderInfo["it_b_pay"]='15m';
        //$orderInfo["return_url"]='m.alipay.com';
        return $orderInfo;
    }
    
    
     public function rsaSign($data,$private_key) {
        $priKey = file_get_contents($private_key);
		$res = openssl_pkey_get_private($priKey);
        //return $priKey;
        if($res)
        {
            openssl_sign($data, $sign,$res);
        }
        else {
            echo "您的私钥格式不正确!"."<br/>"."The format of your private_key is incorrect!";
            exit();
        }
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }
    
    /**
     *支付宝退款 
     **/
   public function alipay_refund($pay_sn,$order_amount){
        require_once 'Public/alipay/aop/request/AlipayTradeRefundRequest.php';
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2017010604881202';
        $aop->rsaPrivateKeyFilePath = 'Public/alipay/aop/rsa_private_key.pem';
        $aop->alipayrsaPublicKey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new \AlipayTradeRefundRequest();
        $request->setBizContent("{" .
            "    \"trade_no\":\"".$pay_sn."\"," .
            "    \"refund_amount\":"."'{$order_amount}'"."," .
            "    \"refund_reason\":\"正常退款\"," .
            "    \"out_request_no\":\"HZ01RF001\"," .
            "    \"operator_id\":\"OP001\"," .
            "    \"store_id\":\"NJ_S_001\"," .
            "    \"terminal_id\":\"NJ_T_001\"" .
            "  }");
        //return $request->getBizContent();
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
        $public_key= file_get_contents('D:\UPUPW_NP7.0\htdocs\kts\Public\alipay\aop\alipay_public_key.pem');
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($public_key, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        $pkeyid = openssl_get_publickey($res);
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
    
    
    public function pay(){
        require_once '/Public/alipay/aop/request/AlipayTradePayRequest.php';
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2016052401434746';
        $aop->rsaPrivateKey = 'MIICWwIBAAKBgQCwfxnfdxP8puupuxOq19Es+c8FxqrEcA2MI++xmE0JPXJdCz6B96F0sU6MTTexX4XnxRwk0otEPXMSoPIwUedhUE0pkK33mBJXnMP08W4cMS+9VY6FTy3yeT/E9if1JtmfytJGhxeZcEqlr2wOFveqngutSZfux+IQK+n61+BJiwIDAQABAoGAYQxCacHMV6xhAN9BHTu2ZQincQGgfIswp9JKxlh8Y4xKCidYo5ZKTSQBkNwUt49SzfJwWz76HPe9Ao1FHpSqqMlCBFFd/cjWkogz7qNuxd5aqkAn7XSJzhp/jdWrf/QbV8P6WJd7mktdI6gWD3SOXT2S+y2F0NrMBPa6M6MkToECQQDmLOunbGKONjrF/Nt2+zctYBUpt9J/cQw5jnATQzIdoyVwye1Qfj68OQFpWiU6BqCKrhrA0SIhmHos1lbl/N/bAkEAxExq5epECGEwe6/VxHZor89BOnUbPLCwrrPNKaPZHjedkGCiDxlw7pwl+/Lh8HqWrbhFYZVzyc0gG3EcwqIEEQJAe/jBy+ECjBoFOdHg2oqfw162x0tsoptevDlNf/J3MaNHbSI1IV99pp8FdZrJ5iDfoxH28tcxDOs1EqU3FJRIdQJAchzCX9mppv1ow95Z9JWpEdr33lXJeaR1cXnQoI5BX1GRZSbnzsNJZUycwDRXqAZ7pY1jt/C7mOqH6av9vh+VIQJASQrdSm2OLWZy7krYs2zONHmNJS4YP4YV3TS8u2YWsW+kmrSuOK/06ZZYqFaua6ddTG8xxmEcc0apAGFDU8gwhQ==';
        $aop->alipayrsaPublicKey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new \AlipayTradePayRequest ();
        $request->setBizContent("{" .
            "    \"out_trade_no\":\"20150320010101001\"," .
            "    \"scene\":\"bar_code,wave_code\"," .
            "    \"auth_code\":\"28763443825664394\"," .
            "    \"product_code\":\"FACE_TO_FACE_PAYMENT\"," .
            "    \"subject\":\"Iphone6 16G\"," .
            "    \"buyer_id\":\"2088202954065786\"," .
            "    \"seller_id\":\"2088102146225135\"," .
            "    \"total_amount\":0.01," .
            "    \"discountable_amount\":8.88," .
            "    \"undiscountable_amount\":80.00," .
            "    \"body\":\"Iphone6 16G\"," .
            "      \"goods_detail\":[{" .
            "                \"goods_id\":\"apple-01\"," .
            "        \"alipay_goods_id\":\"20010001\"," .
            "        \"goods_name\":\"ipad\"," .
            "        \"quantity\":1," .
            "        \"price\":2000," .
            "        \"goods_category\":\"34543238\"," .
            "        \"body\":\"特价手机\"," .
            "        \"show_url\":\"http://www.alipay.com/xxx.jpg\"" .
            "        }]," .
            "    \"operator_id\":\"yx_001\"," .
            "    \"store_id\":\"NJ_001\"," .
            "    \"terminal_id\":\"NJ_T_001\"," .
            "    \"alipay_store_id\":\"2016041400077000000003314986\"," .
            "    \"extend_params\":{" .
            "      \"sys_service_provider_id\":\"2088511833207846\"," .
            "      \"hb_fq_num\":\"3\"," .
            "      \"hb_fq_seller_percent\":\"100\"" .
            "    }," .
            "    \"timeout_express\":\"90m\"," .
            "    \"royalty_info\":{" .
            "      \"royalty_type\":\"ROYALTY\"," .
            "        \"royalty_detail_infos\":[{" .
            "                    \"serial_no\":1," .
            "          \"trans_in_type\":\"userId\"," .
            "          \"batch_no\":\"123\"," .
            "          \"out_relation_id\":\"20131124001\"," .
            "          \"trans_out_type\":\"userId\"," .
            "          \"trans_out\":\"2088101126765726\"," .
            "          \"trans_in\":\"2088101126708402\"," .
            "          \"amount\":0.1," .
            "          \"desc\":\"分账测试1\"," .
            "          \"amount_percentage\":\"100\"" .
            "          }]" .
            "    }," .
            "    \"sub_merchant\":{" .
            "      \"merchant_id\":\"19023454\"" .
            "    }," .
            "    \"auth_no\":\"2016110310002001760201905725\"," .
            "    \"ext_user_info\":{" .
            "      \"name\":\"李明\"," .
            "      \"mobile\":\"16587658765\"," .
            "      \"cert_type\":\"01\"," .
            "      \"cert_no\":\"362334768769238881\"" .
            "    }" .
            "  }");
        $result = $aop->execute ( $request);
        return $result;
    }
    

    
    public function signs(){
        $ali = array(
            'service' => 'mobile.securitypay.pay',
            'partner' => '2088521047343485',//
            '_input_charset' => 'utf-8',
            'sign_type' => 'RSA',
            'sign' => '',
            'notify_url' => 'http://121.196.230.128:8384/index.php/Order/Payment/BookAlipayNotify',//回调地址
            'out_trade_no' => '2017010554975010554825',//商户网站唯一订单号
            'subject' => 'duoyuen',//商品名称
            'payment_type' => 1,//支付类型
            'seller_id' => '2088521047343485',//支付宝账号
            'total_fee' => '0.01',//总金额
            'body' => '夜空的流星',//商品详情
        );
        
        $ali = self::argSort($ali);
        
        $str = '';
        foreach($ali as $key=>$val){
            if($key == 'sign_type' || $key == 'sign'){
                continue;
            }else{
                if ($str == ''){
                    $str = $key.'='.'"'.$val.'"';
                }else{
                    $str = $str.'&'.$key.'='.'"'.$val.'"';
                }
            }
        }
        return $str;
    }
    

    
    //$sign = urlencode(sign($str));
    //$str = $str.'&sign='.'"'.$sign.'"'.'&sign_type='.'"'.$ali['sign_type'].'"';
    
    
    //RSA签名
    function sign($data) {
        $priKey = file_get_contents('Public/alipay/aop/rsa_private_key.pem');
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }
    
    
    
}
