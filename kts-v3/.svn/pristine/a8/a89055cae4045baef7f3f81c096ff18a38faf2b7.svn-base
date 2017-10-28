<?php
$alipay_config = array();
$alipay_config['partner']       = '2088521047343485';
$alipay_config['key']           = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$alipay_config['seller_email']  = '952244519@qq.com';
$alipay_config['payment_type']  = "1";
$alipay_config['notify_url'] = "http://192.168.1.161:8080/index.php/Order/PayNotiy/BookAlipayNotify";
$alipay_config['return_url'] = "http://192.168.1.161:8080/index.php/Order/PayNotiy/BookAlipayNotify";
$alipay_config['sign_type']    = 'RSA';
$alipay_config['input_charset']= strtolower('utf-8');
$alipay_config['cacert']    = '../cacert.pem';
$alipay_config['transport']    = 'http';
$alipay_config['private_key_path']=dirname(__FILE__).'/rsa_private_key.pem';
// 证书路径
$alipay_config['cacert'] = dirname(__FILE__).'/cacert.pem';
//验签公钥地址
$alipay_config['public_key_path'] = dirname(__FILE__).'/alipay_public_key.pem';
return $alipay_config;
?>
