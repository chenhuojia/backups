<?php
namespace api\v1\controller;
use think\Controller;
use think\Config;
use think\Loader;
class Pay extends Controller{
    
    public function alipay(){
        //dump($_SERVER);exit;
        $config=Config::load(APP_PATH.'extra/alipay.php');
        Loader::import('alipay.pagepay.service.AlipayTradeService',EXTEND_PATH);
        Loader::import('alipay.pagepay.buildermodel.AlipayTradePagePayContentBuilder',EXTEND_PATH);
        $config=$config['alipay'];
         //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = time();    
        //订单名称，必填
        $subject = 'testst';    
        //付款金额，必填
        $total_amount = 0.01;   
        //商品描述，可空
        $body = '测试测试';    
    	//构造参数
    	$payRequestBuilder = new \AlipayTradePagePayContentBuilder();
    	$payRequestBuilder->setBody($body);
    	$payRequestBuilder->setSubject($subject);
    	$payRequestBuilder->setTotalAmount($total_amount);
    	$payRequestBuilder->setOutTradeNo($out_trade_no);    
    	$aop = new \AlipayTradeService($config);
    	$response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);    
    	//输出表单
    	var_dump($response);
    }
    
    
}