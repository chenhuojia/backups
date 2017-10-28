<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-5-30
 * Time: 14:08
 */
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\TourOrder;
use Acme\MinsuBundle\Entity\TourOrderAction;
// use Acme\MinsuBundle\Entity\TourOrderGoods;
// use Acme\MinsuBundle\Entity\Calendar;

use Acme\MinsuBundle\Common\CommonController;
class apiTimeChangeController extends CommonController
{

    public function __construct(){
    
    }
    
    /**
      * 修改团游订单的状态
     * @Route("/apiModifyTourOrder",name="apiModifyTourOrder_")
     */
    public function apiModifyTourOrderAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $now =time();
        $limit_time=15*60;//15分钟后自动关闭订单
        $order = $conn->createQueryBuilder ()
                ->select('m.order_sn,m.order_id,m.add_time,m.adult_num,m.child_num,m.calendar_id')
                ->from('msk_tour_order', 'm' )
                ->where ( "m.order_status in (0,1)")
                ->andWhere("m.pay_status =0")            
                ->execute ()
                ->fetchAll();
        foreach ($order as $key => $value) {
            if($order[$key]['add_time']+$limit_time<=$now){
                $order_sn=$order[$key]['order_sn'];
                $order_id=$order[$key]['order_id'];
                $calendar_id=$order[$key]['calendar_id'];
                $upb =  $conn->createQueryBuilder ()
                ->update ( 'msk_tour_order', 'm' )
                ->set ('m.order_status',2)//取消该订单
                ->where ( "m.order_sn ='$order_sn'" )
                ->execute (); 
                if($upb){
                    //修改报名人员信息
                    $upb =  $conn->createQueryBuilder ()
                                  ->update ( 'msk_tour_order_goods', 'm' )
                                  ->set ( 'm.state', 2)
                                  ->where ( "m.order_sn ='$order_sn'" )
                                  ->execute (); 
                    //添加订单记录,用户团购后entered(报名人数)减1
                    $enroll = $order[$key]['adult_num']+$order[$key]['child_num'];
                    $conn = $manager->getConnection();
                    $conn->createQueryBuilder ()
                         ->update ( 'msk_tour_calendar', 'm' )
                         ->set ( 'm.entered', "m.entered-'$enroll'" )
                         ->andWhere ( "m.calendar_id =$calendar_id" )
                         ->execute (); 
                    //添加订单操作记录
                    $orderact  = new TourOrderAction();
                    $orderact->setOrderId($order_id);
                    $orderact->setActionUser(0);
                    $orderact->setOrderStatus(2);
                    $orderact->setPayStatus(0);
                    $orderact->setStatusDesc("订单过期,系统自动取消");
                    $orderact->setLogTime(time());
                    $manager->persist($orderact);
                    $manager->flush();
                    $bool  =$orderact->getActionId();
                    return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>''));
                }
               return new JsonResponse(array('code'=>300,'msg'=>'fail','result'=>''));

            } 
            
         }
        return new JsonResponse(array('code'=>300,'msg'=>'fail','result'=>''));
    }



  
  
}






























