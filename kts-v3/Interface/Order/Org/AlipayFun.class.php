<?php
/** AlipayFun.php 
  * 支付宝辅助类 用于后台生成 支付宝订单信息 */
namespace Order\Org;
require_once(dirname(__FILE__) . '/alipay/' . 'alipay.config.php');
class AlipayFun{
    /** 后台生成 支付宝订单 数据 */
    function getAlipayOrderString($number,$subject,$content,$fee){
        $alipay_config = require('alipay/alipay.config.php');
        $parter = $alipay_config['partner'];
        $seller = $alipay_config['sellid'];
        $notify = $alipay_config['notify_url'];

        $prestr = 'partner="'.$parter.'"&seller_id="'.$seller.'"&out_trade_no="'.$number.'"&subject="'.$subject.'"&body="'.$subject.'"&total_fee="'.$fee.'"&notify_url="'.$notify.'"';

        $orderInfo =$prestr.'&service="mobile.securitypay.pay"&payment_type="1"&_input_charset="utf-8"&it_b_pay="30m"';

        $key_path = $alipay_config['private_key_path'];
        $sign = $this->rsaSign($orderInfo,$key_path);  //1, 加密
        $sign = urlencode($sign);           //2. 编码 加密字符串
//        $return_str = $orderInfo.'&sign="'.$sign.'"&sign_type="RSA"';
/** 一开始想在后台一口气全部生成订单信息，发现签名错误，这里应该是urlencode的问题，php和java对urlencode（utf8）的处理可能不一样;但奇怪的是，这里需要php处理一遍urlencode，android再处理一遍urlencode，少了一方，都会报签名错误
*/
        $info = array();
        $info['order'] = $orderInfo;
        $info['rsa'] = $sign;

        return $info;
    }
    
    function rsaSign($data, $private_key) {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $private_key=str_replace("-----BEGIN RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("-----END RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("\n","",$private_key);
    
        $private_key="-----BEGIN RSA PRIVATE KEY-----".PHP_EOL .wordwrap($private_key, 64, "\n", true). PHP_EOL."-----END RSA PRIVATE KEY-----";
    
        $res=openssl_get_privatekey($private_key);
    
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
}