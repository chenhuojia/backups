<?php
#!  /usr/local/php56/bin -q
namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\UserAddress;
use Acme\MinsuBundle\Entity\OrderPay;
use Acme\MinsuBundle\Entity\Comment;
use Acme\MinsuBundle\Common\CommonController;

class checkPayStatusController extends CommonController {
    
/*     public function __construct(){
        self::CheckPayStatusAction();
        
    } */
    
    /**
     * @Route("/CheckPayStatus",name="CheckPayStatus_")
     */
   public function CheckPayStatusAction(){
      /*  $this->conn()->insert('msk_test',array('text'=>'我是谁'));
       return new JsonResponse(1); */
       $data=$this->conn()->createQueryBuilder()
               ->select("*")
               ->from('msk_mall_order')
               ->where('order_status=1','pay_status=1')
               ->execute()
               ->fetchAll();
         $now=time();
         foreach ($data as $k=>$v){
            $time=$v['add_time']+60*15;
            $inc=$now-$time;
            if ($inc>=0){
                $ret[]=self::changeOrderStatus($v['order_id']);
                 self::addorderlog($v['order_id'], $v['user_id'], '订单支付过时');
            }
        }
       return new JsonResponse($ret);  
    }
    
    function changeOrderStatus($order_id){
        return $this->conn()->createQueryBuilder()
                ->update('msk_mall_order')
                ->set('order_status',5)
                ->set('admin_note',"'订单支付过时'")
                ->where('order_id='.$order_id)
                ->execute();
    } 
    
}