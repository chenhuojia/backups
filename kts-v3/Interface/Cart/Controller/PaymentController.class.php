<?php
namespace Cart\Controller;

use Think\Controller;
use Common\Controller\ApiController;

class PaymentController extends ApiController
{   
    
    /***
     * 支付
     ***/    
    public function doPayment(){
      $user_id=session('user_id');
      $order_sn=I('order_sn');
      $paytype=I('post.paytype',1);
      switch ($paytype){
          case 1:
              $data=self::use_money($user_id, $order_sn);
              break;          
      } 

      $this->myApiPrint($data);
      
    }
 
    private function use_money($user_id,$order_sn){
        $userModel=D('user');
        $user=$userModel->find($user_id);
        $order=M('order')->where(array('order_sn'=>$order_sn))->find();
        $ret=bccomp($user['money'],$order['order_amount'],2);
        if ($ret == 0 || $ret == 1){
            $userModel->startTrans();
            $uPuser=$userModel->where(array('user_id'=>$user_id))->setDec('money',$order['order_amount']);            
            $state=update_pay_status($user_id,$order_sn);
            $after=bcsub($user['money'],$order['order_amount'],2);
            $buyUser=account_log($user_id,$order['order_id'],$oreder_sn,$user['money'],$after,$order['order_amount'],0);
            if($order['payee']==0){
                $payee=$order['payee_shop'];
                $shop_user=M('user')->alias('u')->join('left join kts_shop s on u.user_id = s.user_id ')->getField('user_id');
                $SellUserMoney=$userModel->where(array('user_id'=>$shop_user))->setDec('money',$order['order_amount']);
            }else{
                $payee=$order['payee'];
                $SellUserMoney=$userModel->where(array('user_id'=>$payee))->setDec('money',$order['order_amount']);               
            } 
            $sellUser=account_log($payee,$order['order_id'],$oreder_sn,$user['money'],$after,$order['order_amount']);          
            if ($uPuser && $buyUser && $state && $SellUserMoney && $sellUser){
                $userModel->commit();
                return 1;
            }
            $userModel->rollback();
            return 0;
        }elseif ($ret == -1){
            $this->myApiPrint(300,'余额不足,请选择其他支付或先充值再支付');
        } 
        
    }
    
}

