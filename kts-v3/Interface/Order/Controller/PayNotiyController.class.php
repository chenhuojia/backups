<?php
namespace Order\Controller;

use Think\Controller;
use Order\Vendor\alipay\Alipayment;

class PayNotiyController extends Controller
{   
  
    /**
     * 商品支付宝回调
     **/
    public function BookAlipayNotify(){
        $config=include(dirname(dirname(__FILE__)).'/Vendor/alipay/config.php');
        $alipay=new Alipayment($config);
        $verify_result = $alipay->verifyCallback(TRUE);
        $verify_result=1;
        if($verify_result)
        {            
            $order_sn =trim($_POST['out_trade_no']); //商户订单号
            //$trade_no = $_POST['trade_no'];//支付宝交易号
             //$trade_status = $_POST['trade_status'];//交易状态 */
            $pay_sn=trim($_POST['trade_no']);
            $pay_time=strtotime(isset($_POST['gmt_payment'])?$_POST['gmt_payment']:0);
            if($_POST['trade_status'] == 'TRADE_FINISHED')
            {
                $b=update_pay_status($order_sn,$pay_time,$pay_sn,1); // 修改订单支付状态
                echo 'success';exit;
            }
            elseif ($_POST['trade_status'] == 'TRADE_SUCCESS')
            {
                update_pay_status($order_sn,$pay_time,$pay_sn,1); // 修改订单支付状态
                echo 'success';exit;
            } 
        }
        else
        {
            echo 'fail';exit; //验证失败
        }
    }
    
    
    /**
     * 保证金支付宝回调
     **/
    public function shopAlipayNotify(){
        $config=include(dirname(dirname(__FILE__)).'/Vendor/alipay/config.php');
        $config['notify_url'] = "http://120.77.214.101:8080/index.php/Order/PayNotiy/ShopAlipayNotify";
        $config['return_url'] = "http://120.77.214.101:8080/index.php/Order/PayNotiy/ShopAlipayNotify";
        $alipay=new Alipayment($config);
        $verify_result = $alipay->verifyCallback(TRUE);
        $verify_result=1;
        if($verify_result)
        {
            $order_sn =trim($_POST['out_trade_no']); //商户订单号
            /*  $trade_no = $_POST['trade_no'];//支付宝交易号
             //$trade_status = $_POST['trade_status'];//交易状态 */
            $pay_sn=trim($_POST['trade_no']);
            $pay_time=strtotime(isset($_POST['gmt_payment'])?$_POST['gmt_payment']:0);
            if($_POST['trade_status'] == 'TRADE_FINISHED')
            {   
               update_shop_pay_status($order_sn,$pay_time,$pay_sn,1);
                echo 'success';exit;
            }
            elseif ($_POST['trade_status'] == 'TRADE_SUCCESS')
            {
                update_shop_pay_status($order_sn,$pay_time,$pay_sn,1);
                echo 'success';exit;
            }
        }
        else
        {
            echo 'fail';exit; //验证失败
        }
    }
    
    
    public function sign(){ 
        $order_sn=I('post.order_sn');
        $order=M('order')->alias('o')->join('left join kts_order_goods g on o.order_id=g.order_id')
        ->where(array('o.order_sn'=>$order_sn,'o.pay_status'=>0))
        ->field('o.order_sn,o.order_amount,o.anum,g.book_name,g.book_desc')
        ->find();
        if ($order){
            $config=include (dirname(dirname(__FILE__)).'/Vendor/alipay/config.php');
            $config['sign_type'] = 'RSA'; 
            $alipay = new Alipayment($config,'app');
            $params = array(
                'out_trade_no' => $order_sn,
                'subject' => $order['book_name'],
                'total_fee' => $order['order_amount'],
                '_input_charset' => 'utf-8',
                'sign_type' => 'RSA'
            );
            $paramStr = $alipay->buildSignedParametersForApp($params); //此代码可以直接给APP端提交
            $this->myApiPrint(200,$paramStr['ordre'],$paramStr['sign']);
        }
        $this->myApiPrint(300,'订单不存在');
        $this->myApiPrint($a);
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

