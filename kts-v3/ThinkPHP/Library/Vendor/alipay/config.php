<?php

$alipay_config = array();
$alipay_config['partner']       = '2088521047343485';
$alipay_config['key']           = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$alipay_config['seller_email']  = '952244519@qq.com';
$alipay_config['payment_type']  = "1";
$alipay_config['notify_url'] = "http://121.196.230.128:8384/index.php/Order/Payment/BookAlipayNotify";
$alipay_config['return_url'] = "http://121.196.230.128:8384/index.php/Order/Payment/BookAlipayNotify";
$alipay_config['sign_type']    = 'RSA';
$alipay_config['input_charset']= strtolower('utf-8');
$alipay_config['cacert']    = '../cacert.pem';
$alipay_config['transport']    = 'http';
return $alipay_config;
?>
