<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-5-30
 * Time: 14:08
 * http://www.xuebuyuan.com/2056662.html
 */
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\TourOrder;
use Acme\MinsuBundle\Entity\TourOrderAction;
use Acme\MinsuBundle\Entity\TourOrderGoods;
use Acme\MinsuBundle\Entity\TourEnroll;
use Acme\MinsuBundle\Entity\TourOrderRefund;
use Acme\MinsuBundle\Entity\TourCalendar;

use Acme\MinsuBundle\Common\CommonController;
class apiTourOrderController extends CommonController
{
    
    public function __construct(){
    
    }

     /**
     * 团购订单生成
     * @Route("apiOrderTour",name="apiOrderTour_")
     */
    public function apiOrderTour() {
        
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = isset($_POST)?$_POST:'';
        $par =array('tour_id','token','member_name','member_avatar',
                'consignee','the_date',
                'identity_card','mobile','emergency_contact',
                'adult_num','child_num','enrol_arr');
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            $member_id = $this->validationToken($data['token']);
            if(is_array($member_id)) return new JsonResponse($member_id);
            //找到相应的价格
            $tour_id = $data['tour_id'];
            $the_date = $data['the_date'];
            $now = date("Y-m-d");
            $diff_date=$this->diffBetweenTwoDays($now,$the_date);
            if($diff_date<3) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'为了团长有充足的时间准备,只能报三天后的日期团游!'));
            $tourifo = $conn->createQueryBuilder()
                                ->select("a.agency_id,a.imgurl,a.tour_title,a.period,a.starting_time,a.starting_place,a.default_adult_price as adult_price,a.default_child_price as child_price")
                                ->from('msk_tour', 'a')
                                ->where("a.tour_id=$tour_id")
                                ->execute()
                                ->fetch();
            $data['child_price'] = $tourifo['child_price'];
            if (empty($tourifo)) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'该团游不存在'));
            $price = $conn->createQueryBuilder()
                                ->select('a.adult_price,a.calendar_id,a.the_date,a.child_price')
                                ->from('msk_tour_calendar', 'a')
                                ->where("a.tour_id=$tour_id")
                                ->andWhere("a.the_date ='$the_date'")
                                ->execute()
                                ->fetch();
            if (empty($price)) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'该团游没有对应的团长和出团日期,请选择其他出行时间!'));
            $data['adult_price'] = $price['adult_price']; 
            $data['starting_time'] =strtotime($price['the_date']);
            $calendar_id = $price['calendar_id']; 
            $order_sn  = $this->_tour_order_sn();//订单的生成
            //总价格
            $data['total_amount'] = (float)$data['adult_price']*(int)$data['adult_num'] + (float)$data['child_price']*(int)$data['child_num']; 
            $order  = new TourOrder();
            $order->setOrderSn($order_sn);
            $order->setTourId($data['tour_id']);
            $order->setCalendarId($calendar_id);
            $order->setTourImg($tourifo['imgurl']);
            $order->setAgencyId($tourifo['agency_id']);
            $order->setMemberId($member_id);
            $order->setMemberName($data['member_name']);
            $order->setMemberAvatar($data['member_avatar']);
            $order->setGoodsName($tourifo['tour_title']);
            $order->setPeriod($tourifo['period']);
            $order->setStartingTime($data['starting_time']);
            $order->setStartingPlace($tourifo['starting_place']);
            $order->setConsignee($data['consignee']);
            $order->setIdentityCard($data['identity_card']);
            $order->setMobile($data['mobile']);
            $order->setEmergencyContact($data['emergency_contact']);
            $order->setTotalAmount($data['total_amount']);
            $order->setGoodsPrice($data['total_amount']);
            $order->setOrderAmount($data['total_amount']);
            $order->setAddTime(time());
            $order->setAdultPrice($data['adult_price']);
            $order->setChildPrice($price['child_price']);
            $order->setAdultNum($data['adult_num']);
            $order->setChildNum($data['child_num']);
            $manager->persist($order);
            $manager->flush();
            $bool  =$order->getOrderId();
            if(is_numeric($bool)){
                $enrol_arr=json_decode($data['enrol_arr'],true);
                foreach ($enrol_arr as $key => $value) {
                    //添加货单
                    $ordergoods  = new TourOrderGoods();
                    $ordergoods->setOrderSn($order_sn);
                    $ordergoods->setTourId($tour_id);
                    $ordergoods->setCalendarId($calendar_id);
                    $ordergoods->setMemberId($value['member_id']);
                    $ordergoods->setAvatar($value['avatar']);
                    $ordergoods->setUsername($value['username']);
                    $ordergoods->setAccount($value['account']);
                    $ordergoods->setIdentityCard($value['identity_card']);
                    $ordergoods->setEnrollTime(time());
                    $ordergoods->setType(0);
                    $ordergoods->setPrice(0);
                    $manager->persist($ordergoods);
                    $manager->flush();
                }

                //添加订单记录,用户团购后entered(报名人数)加1
                $enroll = $data['adult_num']+$data['child_num'];
                $tour_id = $data['tour_id'];
                $conn = $manager->getConnection();
                $conn->createQueryBuilder ()
                     ->update ( 'msk_tour_calendar', 'm' )
                     ->set ( 'm.entered', "m.entered+'$enroll'" )
                     ->andWhere ( "m.calendar_id =$calendar_id" )
                     ->execute (); 
                //添加订单提醒
                $this->addOrdersRemind($member_id,$bool,0);
                //添加订单记录
                $orderact  = new TourOrderAction();
                $orderact->setOrderId($bool);
                $orderact->setActionUser($member_id);
                $orderact->setStatusDesc("用户生成订单");
                $orderact->setLogTime(time());
                $manager->persist($orderact);
                $manager->flush();
                
                $dataInfo['addTime'] =$order->getAddTime();
                $dataInfo['goods_price'] =$data['total_amount'];
                $dataInfo['order_id'] =$bool;
                $dataInfo['order_sn'] = $order_sn;
                return new JsonResponse(array('code'=>200,'result'=>$dataInfo,'msg'=>'添加成功'));
            }else{
            
               return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'添加失败'));
            }

            
        }else{
           return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'参数错误'));
            
        }
            
    }


    /**
     * 团购订单号的生成
     */
    function _tour_order_sn()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        $timestamp = time();
        $y = date('y', $timestamp);
        $z = date('z', $timestamp);
        $order_sn = $y . str_pad($z, 3, '0', STR_PAD_LEFT) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $query = $conn->createQueryBuilder ()
        ->select (
                "m.*" )
                ->from ( 'msk_tour_order', 'm' )
                ->where("m.order_sn=".$order_sn)
                ->execute ();
        $orders = $query->fetchAll ();
    
        if (empty($orders))
        {
            /* 否则就使用这个订单号 */
            return $order_sn;
        }
    
        /* 如果有重复的，则重新生成 */
        return $this->_tour_order_sn();
    }

    /**
     * 订单预支付列表
     * @Route("/apiTourOrderPreList", name="apiTourOrderPreList_")
     */
    public function apiTourOrderPreList(Request $request)
    {
        $order_id =isset($_GET['order_id'])?$_GET['order_id']:0;
        $member_id =isset($_GET['member_id'])?$_GET['member_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $query = $conn->createQueryBuilder()
                                ->select(
                                    'p.order_sn', 'p.tour_id', 'p.goods_name', 'p.period', 'p.starting_time', 'p.goods_price','p.starting_place','p.adult_price','p.child_price','p.adult_num','p.child_num','p.total_amount','p.add_time','p.coupon_price'
                                    )
                                ->from('msk_tour_order', 'p')
                                ->where("p.order_id=$order_id") 
                                ->andWhere("p.member_id=$member_id")
                                ->execute();
        $data = $query->fetch();
        if(!empty($data['coupon_price'])){
            $data['coupon_price'] = "暂无可使用的代金券";
        }
        if(!empty($data)){
             return new JsonResponse($data);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found data!';
            return new JsonResponse($massage);
        }  

    }
     //order_status(订单状态) 0，未确认；1，已确认；2，已取消；3,已删除;    ；
       //pay_status(支付状态) 0，未付款；1，已付款,2退款中 3已退款；
       //type 0全部，1，待支付；2，已付款；3，退款
       //a.status:状态（0已删除、1在集合、2出团中、3完成） 
       //tour_status 进行中、已完成、已关闭

    /**
     * 订单详情列表
     * @Route("/apiTourOrderDetail", name="apiTourOrderDetail")
     */
    public function apiTourOrderDetail(Request $request)
    {
        $order_id = $request->get("order_id",0);
        $member_id = $request->get("member_id",0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.order_sn','p.order_id','p.order_sn','p.tour_id','p.order_status','p.pay_status','p.goods_name', 'p.starting_time', 'p.starting_place','p.consignee','p.mobile','p.emergency_contact','p.goods_price','p.tour_img','p.total_amount','p.calendar_id','p.adult_price','p.child_price','p.adult_num','p.child_num','p.add_time','p.period','a.state','a.group_name','a.chat_room','a.chief_id','a.chief_name','a.chief_avatar','k.member_id as chief_member_id'
                                    )
                                ->from('msk_tour_order', 'p')
                                ->leftjoin('p','msk_tour_order_goods','c','c.order_sn = c.order_sn')
                                ->leftjoin('p','msk_tour_calendar','a','a.calendar_id = p.calendar_id')
                                ->leftjoin('a','msk_chief', 'k','a.chief_id=k.chief_id')
                                ->where("p.member_id=$member_id or c.member_id=$member_id")
                                ->andWhere("p.order_id=".$order_id)
                                ->andWhere('a.state!=0')
                                ->execute()
                                ->fetch();
        if(!empty($data)){
            $data['tour_img'] = $this->getParameter("app_qiniu_imgurl").$data['tour_img'];
            $data['chief_avatar'] = $this->getParameter("app_qiniu_imgurl").$data['chief_avatar'];
            $data['backing_time'] = $data['starting_time']+86400*$data['period'];
            $data['common_status'] = "";//订单app显示上的状态
            if($data['order_status']<=1){//订单未确认、已下单
               $data['common_status'] = "待支付";
            }elseif($data['order_status']==2) {//已取消订单
               $data['common_status'] = "订单已取消";
            }elseif($data['order_status']==3) {//订单删除
               $data['common_status'] = "订单已删除";
            }
            if($data['pay_status']==1) {//已付款
               $data['common_status'] = "已付款";
            }elseif($data['pay_status']==2) {//退款中
               $data['common_status'] = "退款中";
            }elseif($data['pay_status']==3) {//已退款
               $data['common_status'] = "已退款";
            }
            if($data['pay_status']==1 && $data['state']==2) {//出游中
               $data['common_status'] = "出游中";
            }
            if($data['pay_status']==1 && $data['state']==3) {//已完成
               $data['common_status'] = "已完成";
            }
            unset($data['order_status']);
            unset($data['pay_status']);
            unset($data['state']);
            $order_sn=$data['order_sn'];
            $report = $conn->createQueryBuilder()
                           ->select('k.state')
                           ->from('msk_tour_order_goods', 'k')
                           ->where("k.order_sn=".$order_sn)
                           ->where("k.member_id=".$member_id)
                           ->execute()
                           ->fetch();
            $data['state'] =  isset($report['state'])?$report['state']:0;
            $data['enroll'] = $conn->createQueryBuilder()
                                    ->select('k.rec_id,k.identity_card,k.member_id,k.username,k.account')
                                    ->from('msk_tour_order_goods', 'k')
                                    ->where("k.order_sn=".$order_sn)
                                    ->execute()
                                    ->fetchAll();
        
            $message['code'] = 200;
            $message['msg'] = "Success!";
            $message['result'] =$data;
            return new JsonResponse($message);
        }else{
        
            $message['code'] = 300;
            $message['msg'] = "Error!";
            $message['result'] ="";
            return new JsonResponse($message);
        }  

    }

    /**
     * 订单详情列表(未支付)
     * @Route("/apiTourOrderDetailUnpay", name="apiTourOrderDetailUnpay")
     */
    public function apiTourOrderDetailUnpay(Request $request)
    {
        $order_id =isset($_GET['order_id'])?$_GET['order_id']:0;
        $member_id =isset($_GET['member_id'])?$_GET['member_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.order_sn','p.order_id','p.order_sn','p.tour_id','p.order_status','p.pay_status','p.goods_name', 'p.starting_time', 'p.goods_price','p.tour_img','p.total_amount','p.calendar_id','p.adult_price','p.child_price','p.adult_num','p.child_num','p.add_time','p.period'
                                    )
                                ->from('msk_tour_order', 'p')
                                ->leftjoin('p','msk_tour_calendar','a','a.calendar_id = p.calendar_id')
                                ->where("p.member_id=".$member_id)
                                ->andWhere("p.order_id=".$order_id)
                                ->andWhere('a.state!=0')
                                ->execute()
                                ->fetch();
        $data['backing_time'] = $data['starting_time']+86400*$data['period'];
        $data['common_status'] = "";//订单app显示上的状态
        if($data['order_status']<=1){//订单未确认、已下单
           $data['common_status'] = "待支付";
        }elseif($data['order_status']==2) {//已取消订单
           $data['common_status'] = "取消订单";
        }elseif($data['order_status']==3) {//订单删除
           $data['common_status'] = "订单删除";
        }
        if($data['pay_status']==1) {//已付款
           $data['common_status'] = "已付款";
        }
        if($data['pay_status']==2) {//退款中
           $data['common_status'] = "退款中";
        }
        if($data['pay_status']==3) {//退款中
           $data['common_status'] = "已退款";
        }
        unset($data['order_status']);
        unset($data['pay_status']);
        unset($data['state']);
        if(!empty($data)){
             return new JsonResponse($data);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found data!';
            return new JsonResponse($massage);
        }  

    }



     /**
     * 支付成功
     * @Route("apiTourOrderPaySuc",name="apiTourOrderPaySuc")
     */
    public function apiTourOrderPaySuc() {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();        
        $data=$_POST;
        $sign=array_pop($data);
        array_pop($data);
        $data=self::argSort($data);
        $str=self::createLinkstring($data); 
        $a=self::rsaVerify($str,$sign); 
        //$conn->insert('msk_test',array('text'=>$a));
        //$a=1;
        if($a)
        {    
            //$order_id =$data['order_id'];
            $order_sn =trim($_POST['out_trade_no']); //商户订单号
            $pay_sn=trim($data['trade_no']);
            $pay_status =1;
            $order_status =1; 
            $pay_time=isset($_POST['gmt_payment'])?strtotime($_POST['gmt_payment']):0;
            $order = $conn->createQueryBuilder ()
                ->select('*')
                ->from('msk_tour_order', 'm' )
                ->where ( "m.order_sn ='$order_sn'" )            
                ->execute ()->fetch();
            //var_dump($data);die;
            $member_id =$order['member_id'];
            $order_id = $order['order_id'];
            $upb =  $conn->createQueryBuilder ()
                ->update ( 'msk_tour_order', 'm' )
                ->set ( 'm.pay_time', $pay_time )
                ->set ('m.pay_status',$pay_status)
                ->set ('m.pay_sn',"'$pay_sn'")
                ->set ('m.order_status',$order_status)
                ->where ( "m.order_sn ='$order_sn'" )
                ->andWhere ( "m.member_id =$member_id" )
                ->execute (); 
            if($upb){
                //添加订单操作记录
                $orderact  = new TourOrderAction();
                $orderact->setOrderId($order_id);
                $orderact->setActionUser($member_id);
                $orderact->setOrderStatus(1);
                $orderact->setPayStatus(1);
                $orderact->setStatusDesc("用户支付成功");
                $orderact->setLogTime(time());
                $manager->persist($orderact);
                $manager->flush();
           
                //修改报名人员信息
                $upb =  $conn->createQueryBuilder ()
                              ->update ( 'msk_tour_order_goods', 'm' )
                              ->set ( 'm.state', 1)
                              ->where ( "m.order_sn ='$order_sn'" )
                              ->andWhere ( "m.member_id =$member_id" )
                              ->execute (); 
                
                // update msk_tour set guide_id=guide_id+1 where tour_id = 1;  
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] ="Add Success!";
            }else{
        
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] ="Add Error!";
            }

        }else{
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        }
        return new JsonResponse($message); 
    }


    
    /**
     * 卖家订单列表
     * @Route("apiTourSellerOrderList",name="apiTourSellerOrderList")
     */
    public function apiTourSellerOrderList()
    {
       $conn = $this->getDoctrine()->getManager()->getConnection();
       $page = $request->get("page",0);
       $token = $request->get("token",0);
       $tour_status = $request->get("tour_status",0);
       $type=$request->get("type",0);
       $member_id = $this->validationToken($token);
       if(is_array($member_id)) return new JsonResponse($member_id);
       $agency_id = $conn->createQueryBuilder()
                                        ->select('a.agency_id')
                                        ->from('msk_travel_agency', 'a')
                                        ->where("a.member_id=$member_id")
                                        ->andWhere("a.state =6")
                                        ->execute()
                                        ->fetch();
       if(empty($agency_id)) return new JsonResponse(array('code'=>300,'msg'=>'您没有该权限','result'=>''));
       $agency_id = $agency_id['agency_id'];
       $orderlist = "a.state != 0 and p.order_status !=3 and p.agency_id="."$agency_id";
       //条件搜索
       //order_status(订单状态) 0，未确认；1，已确认；2，已取消；3,已删除;    ；
       //pay_status(支付状态) 0，未付款；1，已付款,2退款中 3已退款；
       //type 0全部，1，待支付；2，已付款；3，退款
       //a.status:状态（0已删除、1在集合、2出团中、3完成） 
       //tour_status 进行中、已完成、已关闭
       if($tour_status==0){
            switch ($type) {

                case '0':
                   $orderlist.= " ".'and p.pay_status!=3 and p.order_status !=2 and (a.state=1) or (p.order_status =1 and p.pay_status =1 and a.state=2)';
                   break;
                case '1':
                   $orderlist.= " ".'and p.order_status<=1 and p.pay_status=0 and a.state in (1)';
                   break;
                case '2':
                   $orderlist.= " ".'and p.order_status =1 and p.pay_status =1 and a.state in (1,2)';
                   break;
                case '3':
                   $orderlist.= " ".'and p.order_status =1 and p.pay_status in (2) and a.state in (1)';
                   break;
                default:
                  return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
                  break;
        }
      }elseif ($tour_status==1) {
         $orderlist.= " ".'and (p.order_status =1 and p.pay_status=1 and a.state =3) or (p.order_status =1 and p.pay_status=3 and a.state =1)';
      }elseif ($tour_status==2) {
         $orderlist.= " ".'and p.order_status =2 and p.pay_status =0 and a.state in (1)';
      }else{
        return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
      }
       $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.order_sn','p.order_id','p.tour_id','p.order_status','p.pay_status','p.goods_name', 'p.starting_time', 'p.goods_price','p.tour_img','p.total_amount','p.calendar_id','p.adult_price','p.child_price','p.adult_num','p.child_num','a.state'
                                    )
                                ->from('msk_tour_order', 'p')
                                ->leftjoin('p','msk_tour_calendar','a','a.calendar_id = p.calendar_id')
                                ->where($orderlist) 
                                ->setFirstResult($page)
                                ->setMaxResults(10)
                                ->execute()
                                ->fetchAll();
       if(!empty($data)){
           foreach ($data as $key => $value) {
              $data[$key]['tour_img'] = $this->getParameter('app_qiniu_imgurl').$data[$key]['tour_img'];
              $data[$key]['common_status'] = "";//订单app显示上的状态
              if($tour_status==0 && $data[$key]['order_status']<=1 &&$data[$key]['pay_status']==0 && $data[$key]['state']==1){//订单未确认、已下单、未支付、集合中
                   $data[$key]['common_status'] = "待支付";
                   $data[$key]['btn']=array(
                            '取消订单',
                            '去支付',
                        );
              }
              if($tour_status==0 && $data[$key]['order_status']==1&&$data[$key]['pay_status']==1 && $data[$key]['state']==1) {//订单确认且支付、集合中
                    $data[$key]['common_status'] = "待出游";
                    $data[$key]['btn']=array(
                            '申请退款',
                            '团友列表',
                            '查看订单',
                        );
              }
              if($tour_status==0 && $data[$key]['order_status']==1&&$data[$key]['pay_status']==1&&$data[$key]['state']==2) {//订单确认且支付、出团中
                    $data[$key]['common_status'] = "出游中";
                    $data[$key]['btn']=array(
                            '团友列表',
                        );
              }
              if($tour_status==0 && $data[$key]['order_status']==1&&$data[$key]['pay_status']==2 && $data[$key]['state']==1) {//订单确认且正在退款、集合中
                   $data[$key]['common_status'] = "退款中";
                   $data[$key]['btn']=array(
                            '联系团长',
                        );
              }
              if($tour_status==1 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==1 && $data[$key]['state']==3) {//订单确认且正在退款、已完成
                   $data[$key]['common_status'] = "交易成功";
                   $data[$key]['btn']=array(
                            '删除订单',
                        );
              }
              if($tour_status==1 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==3 && $data[$key]['state']==1) {//订单确认且退款成功、集合中
                   $data[$key]['common_status'] = "退款成功";
                   $data[$key]['btn']=array(
                            '删除订单',
                        );
              }
              if($tour_status==2 && $data[$key]['order_status']==2 && $data[$key]['pay_status']==0 && $data[$key]['state']==1) {//订单取消且未付款、集合中
                   $data[$key]['common_status'] = "订单关闭";
                   $data[$key]['btn']=array(
                            '删除订单',
                        );
              }
              unset($data[$key]['order_status']);
              unset($data[$key]['pay_status']);
              unset($data[$key]['state']);
            }
       
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else {
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }
    }

     /**
     * 买家订单列表
     * @Route("apiTourBuyerOrderList",name="apiTourBuyerOrderList_")
     */
    public function apiTourBuyerOrderList(Request $request)
    {  
           $conn = $this->getDoctrine()->getManager()->getConnection();
           $page = $request->get("page",0);
           $token = $request->get("token",0);
           $tour_status = $request->get("tour_status",0);
           $type=$request->get("type",0);
           $member_id = $this->validationToken($token);
           if(is_array($member_id)) return new JsonResponse($member_id);
           $orderlist = "a.state != 0 and p.order_status !=3";
           //条件搜索
           //order_status(订单状态) 0，未确认；1，已确认；2，已取消；3,已删除;    ；
           //pay_status(支付状态) 0，未付款；1，已付款,2退款中 3已退款；
           //type 0全部，1，待支付；2，已付款；3，退款
           //a.status:状态（0已删除、1在集合、2出团中、3完成） 
           //tour_status 进行中、已完成、已关闭
           if($tour_status==0){
                switch ($type) {
                    case '0':
                       $orderlist.= " ".'and p.pay_status!=3 and p.order_status !=2 and (a.state=1) or (p.order_status =1 and p.pay_status =1 and a.state=2)';
                       break;
                    case '1':
                       $orderlist.= " ".'and p.order_status<=1 and p.pay_status=0 and a.state in (1)';
                       break;
                    case '2':
                       $orderlist.= " ".'and p.order_status =1 and p.pay_status =1 and a.state in (1,2)';
                       break;
                    case '3':
                       $orderlist.= " ".'and p.order_status =1 and p.pay_status in (2) and a.state in (1)';
                       break;
                    default:
                      return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
                      break;
                }
           }elseif ($tour_status==1) {
             $orderlist.= " ".'and (p.order_status =1 and p.pay_status=1 and a.state =3) or (p.order_status =1 and p.pay_status=3 and a.state =1)';
          }elseif ($tour_status==2) {
             $orderlist.= " ".'and p.order_status =2 and p.pay_status =0 and a.state in (1)';
          }else{
            return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
          }
         $payer = $this->payerOrderList($orderlist,$tour_status,$member_id);
         $participant = $this->participantOrderList($orderlist,$tour_status,$member_id);
         $arr = array_merge($payer,$participant);
         $data=$this->assoc_unique($arr, 'order_sn');
         $data=array_slice($data, $page, 10);
         if(!empty($data)){
            // foreach ($number as $key => $value) {
            //     $order_sn = $number[$key]['order_sn'];
            // }
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
         }else {
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
         }
    }

    //查询购买团游的人的订单列表
    function payerOrderList($orderlist,$tour_status,$member_id=0) {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.order_sn','p.order_id','p.tour_id','p.order_status','p.pay_status','p.goods_name', 'p.starting_time', 'p.goods_price','p.tour_img','p.total_amount','p.calendar_id','p.adult_price','p.child_price','p.adult_num','p.child_num','a.state'
                                    )
                                ->from('msk_tour_order', 'p')
                                ->leftjoin('p','msk_tour_calendar','a','a.calendar_id = p.calendar_id')
                                ->where($orderlist) 
                                ->andWhere("p.member_id=".$member_id)
                                ->execute()
                                ->fetchAll();
       foreach ($data as $key => $value) {
          $data[$key]['tour_img'] = $this->getParameter('app_qiniu_imgurl').$data[$key]['tour_img'];
          $data[$key]['common_status'] = "";//订单app显示上的状态
          if($tour_status==0 && $data[$key]['order_status']<=1 &&$data[$key]['pay_status']==0 && $data[$key]['state']==1){//订单未确认、已下单、未支付、集合中
               $data[$key]['common_status'] = "待支付";
               $data[$key]['btn']=array(
                        '取消订单',
                        '去支付',
                    );
          }
          if($tour_status==0 && $data[$key]['order_status']==1&&$data[$key]['pay_status']==1 && $data[$key]['state']==1) {//订单确认且支付、集合中
                $data[$key]['common_status'] = "待出游";
                $data[$key]['btn']=array(
                        '申请退款',
                        '团友列表',
                        '查看订单',
                    );
          }
          if($tour_status==0 && $data[$key]['order_status']==1&&$data[$key]['pay_status']==1&&$data[$key]['state']==2) {//订单确认且支付、出团中
                $data[$key]['common_status'] = "出游中";
                $data[$key]['btn']=array(
                        '团友列表',
                    );
          }
          if($tour_status==0 && $data[$key]['order_status']==1&&$data[$key]['pay_status']==2 && $data[$key]['state']==1) {//订单确认且正在退款、集合中
               $data[$key]['common_status'] = "退款中";
               $data[$key]['btn']=array(
                        '联系团长',
                    );
          }
          if($tour_status==1 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==1 && $data[$key]['state']==3) {//订单确认且正在退款、已完成
               $data[$key]['common_status'] = "交易成功";
               $data[$key]['btn']=array(
                        '删除订单',
                    );
          }
          if($tour_status==1 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==3 && $data[$key]['state']==1) {//订单确认且退款成功、集合中
               $data[$key]['common_status'] = "退款成功";
               $data[$key]['btn']=array(
                        '删除订单',
                    );
          }
          if($tour_status==2 && $data[$key]['order_status']==2 && $data[$key]['pay_status']==0 && $data[$key]['state']==1) {//订单取消且未付款、集合中
               $data[$key]['common_status'] = "订单关闭";
               $data[$key]['btn']=array(
                        '删除订单',
                    );
          }
          unset($data[$key]['order_status']);
          unset($data[$key]['pay_status']);
          //unset($data[$key]['state']);
       } 
       return $data;
    }

    //查询报名团游的人的订单列表
    function participantOrderList($orderlist,$tour_status,$member_id=0) {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.order_sn','p.order_id','p.tour_id','p.order_status','p.pay_status','p.goods_name', 'p.starting_time', 'p.goods_price','p.tour_img','p.total_amount','p.calendar_id','p.adult_price','p.child_price','p.adult_num','p.child_num','a.state'
                                    )
                                ->from('msk_tour_order_goods', 'k')
                                ->leftjoin('k','msk_tour_order','p','p.order_sn = k.order_sn')
                                ->leftjoin('p','msk_tour_calendar','a','a.calendar_id = p.calendar_id')
                                ->where($orderlist) 
                                ->andWhere("k.member_id=".$member_id)
                                ->andWhere("k.state!=2")
                                ->execute()
                                ->fetchAll();
       foreach ($data as $key => $value) {
          $data[$key]['tour_img'] = $this->getParameter('app_qiniu_imgurl').$data[$key]['tour_img'];
          $data[$key]['common_status'] = "";//订单app显示上的状态
          if($tour_status==0 && $data[$key]['order_status']<=1 &&$data[$key]['pay_status']==0 && $data[$key]['state']==1){//订单未确认、已下单、未支付、集合中
               $data[$key]['common_status'] = "待支付";
               $data[$key]['btn']=array(
                        '查看订单',
                    );
          }
          if($tour_status==0 && $data[$key]['order_status']==1&&$data[$key]['pay_status']==1 && $data[$key]['state']==1) {//订单确认且支付、集合中
                $data[$key]['common_status'] = "待出游";
                $data[$key]['btn']=array(
                        '查看订单',
                    );
          }
          if($tour_status==0 && $data[$key]['order_status']==1&&$data[$key]['pay_status']==1&&$data[$key]['state']==2) {//订单确认且支付、出团中
                $data[$key]['common_status'] = "出游中";
                $data[$key]['btn']=array(
                        '查看订单',
                    );
          }
          if($tour_status==0 && $data[$key]['order_status']==1&&$data[$key]['pay_status']==2 && $data[$key]['state']==1) {//订单确认且正在退款、集合中
               $data[$key]['common_status'] = "退款中";
               $data[$key]['btn']=array(
                        '查看订单',
                    );
          }
          if($tour_status==1 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==1 && $data[$key]['state']==3) {//订单确认且正在退款、已完成
               $data[$key]['common_status'] = "交易成功";
               $data[$key]['btn']=array(
                        '查看订单',
                    );
          }
          if($tour_status==1 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==3 && $data[$key]['state']==1) {//订单确认且退款成功、集合中
               $data[$key]['common_status'] = "退款成功";
               $data[$key]['btn']=array(
                        '查看订单',
                    );
          }
          if($tour_status==2 && $data[$key]['order_status']==2 && $data[$key]['pay_status']==0 && $data[$key]['state']==1) {//订单取消且未付款、集合中
               $data[$key]['common_status'] = "订单关闭";
               $data[$key]['btn']=array(
                        '查看订单',
                    );
          }
          unset($data[$key]['order_status']);
          unset($data[$key]['pay_status']);
          //unset($data[$key]['state']);
       } 
       return $data;
    }

    

    /**
     * 取消团购订单
     * @Route("apiTourOrderCancel",name="apiTourOrderCancel")
     */
    public function apiTourOrderCancel(Request $request) {  
    
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $order_sn = $request->get("order_sn",0);
        $order_id = $request->get("order_id",0);
        $token = $request->get("token",0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $order_info=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_tour_order')
            ->where('order_sn='.$order_sn)
            ->andWhere( "member_id =$member_id" )
            ->execute()->fetch();
        if (empty($order_info)) return new JsonResponse(array('code'=>300,'msg'=>'该订单不存在或您的权限不足','result'=>''));
        if ($order_info['order_status']==2) return new JsonResponse(array('code'=>300,'msg'=>'该订单已经取消了，请勿重复操作','result'=>''));
        if ($order_info['order_status']==3) return new JsonResponse(array('code'=>300,'msg'=>'该订单已经删除了！','result'=>''));
        if ($order_info['pay_status']==2) return new JsonResponse(array('code'=>300,'msg'=>'该正在退款,请确认退款后直接删除即可！','result'=>''));
        if ($order_info['pay_status']==3) return new JsonResponse(array('code'=>300,'msg'=>'该订单已退款,请直接删除即可！','result'=>''));
        if ($order_info['pay_status']==1) return new JsonResponse(array('code'=>300,'msg'=>'该订单已经付款，请先申请退款！','result'=>'')); 
        $upb = $conn->createQueryBuilder ()
        ->update ( 'msk_tour_order', 'm' )
        ->set ('m.order_status',2 )
        ->where ( "m.order_sn =".$order_sn )
        ->andWhere( "m.member_id =$member_id" )
        ->execute ();
        if($upb){
            //添加订单操作记录
            $orderact  = new TourOrderAction();
            $orderact->setOrderId($order_id);
            $orderact->setActionUser($member_id);
            $orderact->setOrderStatus(2);
            // $orderact->setPayStatus(1);
            $orderact->setStatusDesc("用户取消了订单");
            $orderact->setLogTime(time());
            $manager->persist($orderact);
            $manager->flush();
            //修改报名人员信息
            $upb1 =  $conn->createQueryBuilder ()
                          ->update ( 'msk_tour_order_goods', 'm' )
                          ->set ( 'm.state', 2)
                          ->where ( "m.order_sn =$order_sn" )
                          ->execute (); 
            //添加订单记录,用户团购后entered(报名人数)加1
            $enroll = $order_info['adult_num']+$order_info['child_num'];
            $tour_id = $order_info['tour_id'];
            $conn->createQueryBuilder ()
                 ->update ( 'msk_tour', 'm' )
                 ->set ( 'm.entered', "m.entered-'$enroll'" )
                 ->andWhere ( "m.tour_id =$tour_id" )
                 ->execute (); 
            return new JsonResponse(array('code'=>200,'msg'=>'取消订单成功','result'=>''));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'取消订单失败','result'=>''));
        }
    }

    /**
     * 删除团购订单
     * @Route("apiTourOrderDel",name="apiTourOrderDel")
     */
    public function apiTourOrderDel(Request $request) {  
    
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $order_sn = $request->get("order_sn",0);
        $token = $request->get("token",0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $orderlist ="a.state != 0 and p.order_status !=3 and (p.order_status =1 and p.pay_status=1 and a.state =3) or (p.order_status =1 and p.pay_status=3 and a.state =1) or (p.order_status =2 and p.pay_status =0 and a.state=1)";
        $calendar = $conn->createQueryBuilder()
                                 ->select(
                                    'p.order_sn','p.order_id','p.tour_id','p.order_status','p.pay_status','a.state'
                                    )
                                ->from('msk_tour_order', 'p')
                                ->leftjoin('p','msk_tour_calendar','a','a.calendar_id = p.calendar_id')
                                ->where($orderlist) 
                                ->andWhere("p.order_sn =".$order_sn)
                                ->andWhere( "p.member_id =$member_id" )
                                ->execute()
                                ->fetch();
        if (empty($calendar)) return new JsonResponse(array('code'=>300,'msg'=>'该订单暂时不能删除','result'=>''));
        /*if (empty($calendar)) return new JsonResponse(array('code'=>300,'msg'=>'该订单不存在','result'=>''));
        if($calendar['order_status']<=1 &&$calendar['pay_status']==0 && $calendar['state']==1){//订单未确认、已下单、未支付、集合中
              return new JsonResponse(array('code'=>300,'msg'=>'该订单待支付状态,请先取消','result'=>''));
        }
        if($calendar['order_status']==1&&$calendar['pay_status']==1 && $calendar['state']==1) {//订单确认且支付、集合中
            return new JsonResponse(array('code'=>300,'msg'=>'该订单待出游状态,尚不能删除','result'=>''));
        }
        if($calendar['order_status']==1&&$calendar['pay_status']==1&&$calendar['state']==2) {//订单确认且支付、出团中
            return new JsonResponse(array('code'=>300,'msg'=>'该订单出游中状态,尚不能删除','result'=>''));
        }
        if($calendar['order_status']==1&&$calendar['pay_status']==2 && $calendar['state']==1) {//订单确认且正在退款、集合中
           return new JsonResponse(array('code'=>300,'msg'=>'该订单退款中状态,尚不能删除','result'=>''));
        }*/
        $upb = $conn->createQueryBuilder ()
        ->update ( 'msk_tour_order', 'm' )
        ->set ('m.order_status',3 )
        ->where ( "m.order_sn =".$order_sn )
        ->andWhere( "m.member_id =$member_id" )
        ->execute ();
        if($upb){
            //添加订单操作记录
            $orderact  = new TourOrderAction();
            $orderact->setOrderId($calendar['order_id']);
            $orderact->setActionUser($member_id);
            $orderact->setOrderStatus(2);
            // $orderact->setPayStatus(1);
            $orderact->setStatusDesc("用户删除了订单");
            $orderact->setLogTime(time());
            $manager->persist($orderact);
            $manager->flush();
            return new JsonResponse(array('code'=>200,'msg'=>'删除订单成功','result'=>''));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'删除订单成功','result'=>''));

        }
    }

     /**
     * 申请退款页面列表展示
     * @Route("/apiTourOrderRefundList", name="apiTourOrderRefundList_")
     */
    public function apiTourOrderRefundList(Request $request)
    {
        $order_id = isset($_GET['order_id'])?$_GET['order_id']:0;
        $member_id =isset($_GET['member_id'])?$_GET['member_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                      ->select('p.order_sn', 'p.tour_id', 'p.goods_name', 'p.period', 'p.starting_time', 'p.goods_price','p.starting_place,p.adult_price,p.child_price,p.adult_num,p.child_num')
                      ->from('msk_tour_order','p')
                      ->where("p.order_id =$order_id")
                      ->andWhere("p.member_id =$member_id")
                      ->andWhere("p.pay_status =1")
                      ->execute()
                      ->fetch();
        $data['backing_time'] = $data['starting_time']+86400*$data['period'];
        if(!empty($data)){
            return new JsonResponse($data);
        }else{

            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found data!';
            return new JsonResponse($massage);
        }

    }

    /**
     * 申请退款
     * @Route("/apiTourOrderRefundApply", name="apiTourOrderRefundApply_")
     */
    public function apiTourOrderRefundApply(Request $request)
    {
       $manager = $this->getDoctrine()->getManager();
       $conn = $manager->getConnection();
       $token = $request->get("token",0);
       $reason = $request->get("reason");
       $order_sn = $request->get("order_sn");
       if (empty($reason)) return new JsonResponse(array('code'=>300,'msg'=>'申请内容不能为空!','result'=>''));
       $member_id = $this->validationToken($token);
       if(is_array($member_id)) return new JsonResponse($member_id);
       $refund=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_tour_order_refund')
            ->where('user_id='.$member_id)
            ->andWhere('order_sn='.$order_sn)
            ->execute()->fetch();
       if ($refund) return new JsonResponse(array('code'=>300,'msg'=>'该订单号正在处理，请勿重复提交!','result'=>''));
        $orderlist ="a.state != 0 and p.order_status !=3";
        $calendar = $conn->createQueryBuilder()
                                 ->select(
                                    'p.order_sn','p.order_id','p.tour_id','p.order_status','p.pay_status','a.state'
                                    )
                                ->from('msk_tour_order', 'p')
                                ->leftjoin('p','msk_tour_calendar','a','a.calendar_id = p.calendar_id')
                                ->where($orderlist) 
                                ->andWhere("p.order_sn =".$order_sn)
                                ->execute()
                                ->fetch();
        if (empty($calendar)) return new JsonResponse(array('code'=>300,'msg'=>'该订单不存在','result'=>''));
        if($calendar['order_status']<=1 &&$calendar['pay_status']==0 && $calendar['state']==1){//订单未确认、已下单、未支付、集合中
              return new JsonResponse(array('code'=>300,'msg'=>'该订单尚未支付','result'=>''));
        }
        if($calendar['state']==2) {//订单出团中
            return new JsonResponse(array('code'=>300,'msg'=>'该订单已经出游,已过退款期限','result'=>''));
        }
        if($calendar['state']==3) {//订单已完成出团
            return new JsonResponse(array('code'=>300,'msg'=>'该订单已经出游完成,不能进行退款了','result'=>''));
        }
       if ($calendar['pay_status']==2) return new JsonResponse(array('code'=>300,'msg'=>'该订单已经在退款中,请耐心等待!','result'=>''));
       if ($calendar['pay_status']==3) return new JsonResponse(array('code'=>300,'msg'=>'该订单已经退款,请不要重复申请!','result'=>''));
       $upb = $conn->createQueryBuilder ()
        ->update ( 'msk_tour_order', 'm' )
        ->set ('m.pay_status',2 )
        ->where ( "m.order_sn =".$order_sn )
        ->andWhere( "m.member_id =$member_id" )
        ->execute ();
       if (!$upb) return new JsonResponse(array('code'=>300,'msg'=>'提交失败,请重新提交!','result'=>''));
       $Refund  = new TourOrderRefund();
       $Refund->setUserId($member_id);
       $Refund->setAgencyId($calendar['agency_id']);
       $Refund->setReason($reason);
       $Refund->setOrderSn($order_sn);
       $Refund->setaddtime(time());
       $manager->persist($Refund);
       $manager->flush();
       $bool  = $Refund->getRefundId();
        if(is_numeric($bool)){
            //添加订单操作记录
            $orderact  = new TourOrderAction();
            $orderact->setOrderId($calendar['order_id']);
            $orderact->setActionUser($member_id);
            $orderact->setOrderStatus(1);
            $orderact->setPayStatus(2);
            $orderact->setStatusDesc("用户申请款");
            $orderact->setLogTime(time());
            $manager->persist($orderact);
            $manager->flush();
            return new JsonResponse(array('code'=>200,'msg'=>'您的申已经提交,请耐心等待!','result'=>''));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'系统繁忙,请稍后再试!','result'=>''));
        }  
        
    }




    

    

  
}




























