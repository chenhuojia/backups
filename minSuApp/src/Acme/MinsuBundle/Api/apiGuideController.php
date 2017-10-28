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
use Acme\MinsuBundle\Entity\Guide;
use Acme\MinsuBundle\Entity\GuideCalendar;
use Acme\MinsuBundle\Entity\GuideTravel;
use Acme\MinsuBundle\Entity\GuideAlbum;
use Acme\MinsuBundle\Entity\GuideCertification;
use Acme\MinsuBundle\Entity\GuideOrder;
use Acme\MinsuBundle\Entity\GuideOrderAction;
use Acme\MinsuBundle\Entity\GuideOrderGoods;
use Acme\MinsuBundle\Entity\GuideComment;

use Acme\MinsuBundle\Common\CommonController;
class apiGuideController extends CommonController
{

    
    public function __construct(){
    
    }
    
    /**
     * 导游列表
     * @Route("/apiSearchGuide", name="apiSearchGuide_")
     */
    public function apiSearchGuideAction(Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();
        $page =isset($_POST['page'])?$_POST['page']:0;
        // $startPage = ($page - 1) * $pageSize;
        $search =isset($_POST['search'])?$_POST['search']:'';
        $language =isset($_POST['language'])?$_POST['language']:'';
        $city =isset($_POST['city'])?$_POST['city']:'';
        $orderlist = "p.state = 2 and p.type=1 ";
        if(trim($search) !== '')
        {
            $orderlist.= 'and a.travel_title like '.'"%'.$search.'%"'.' ';
        }
        if(trim($language) !== '')
        {   
            $orderlist.= 'and p.language like '.'"%'.$language.'%"'.' ';
        }
        if(trim($city) !== '')
        {   
             $orderlist.= 'and a.city like '.'"%'.$city.'%"';
        }
        $conn = $em->getConnection();
        $data = $conn->createQueryBuilder()
        ->select(
                'p.guide_id', 'p.member_id', 'p.guide_price', 'p.experience', 'p.gender', 'p.age','p.language','p.introduction','p.introduction','p.introduction','p.service_quality','real_avatar','p.real_name','p.lead_time',
                'a.travel_title'
                )
                ->from('msk_guide', 'p')
                ->leftjoin('p', 'msk_guide_travel', 'a', 'a.guide_id = p.guide_id')
                ->where($orderlist)
                ->groupBy("p.guide_id") 
                ->setFirstResult($page)
                ->setMaxResults(10)   
                ->execute()
                ->fetchAll();    
        if(!empty($data)){
            return new JsonResponse($data);
        }else{
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found guide!';
            return new JsonResponse($massage);
        }
  
    }

    
    /**
     * 填写对导游的评论
     * @Route("apiGuideWriteComment",name="apiGuideWriteComment_")
     */
    public function apiGuideWriteComment()
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('guide_id','comment_user','username','imageurl','content','service_quality','kind');        
       /*  $data['content'] = '125862;49555266';
        $data['guide_id'] = 8;
        $data['comment_user'] = 4;
        $data['username'] = "陈伙佳";
        $data['imageurl'] = "http://qiniu.myfriday.cn/2_70078_15266794_1476158780528.jpg?imageView2/1/w/160/h/160";
        $data['service_quality'] = 4;
        $data['kind']=0; */
        $parBoo  =  $this->checkKeyForArr($par, $data);
        //$this->checkKeyForArr($par, $data)>0
        if($parBoo && $data!=""){
            if($data['content'] == ""){
                $this->Send(300,'评论内容必须填写才能提交');
            }
            $Guide  = new GuideComment();
            $Guide->setGuideId($data['guide_id']);
            $Guide->setCommentUser($data['comment_user']);
            $Guide->setUserName($data['username']);
            $Guide->setImageurl($data['imageurl']);
            $Guide->setContent($data['content']);
            $Guide->setServiceQuality($data['service_quality']);
            $Guide->setKind($data['kind']);
            $Guide->setAddTime(time());
            $manager->persist($Guide);
            $manager->flush();
            $bool  = $Guide->getCommentId();
            if(is_numeric($bool)){
                $a=$this->changeGuideTotalQuality($data['guide_id'],$data['service_quality']);              
                $this->Send(200,'success');
            }else{
                $this->Send(300,'fail');
            }
        }else{
            $this->Send(300,'Parameters Error!');
        }
    
    }
    
     /**
     * 导游详情
     * @Route("/apiGuideDetail", name="apiGuideDetail_")
     */
    public function apiGuideDetail(Request $request)
    {
        $guide_id =$request->get('guide_id',0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $query = $conn->createQueryBuilder()
                                 ->select(                                     
                                   'p.guide_id', 'p.member_id,e.nickname','m.avatar','p.guide_price', 'p.experience', 'p.gender', 'p.age','p.language','p.introduction','p.introduction','p.introduction','p.service_quality'
                                    )
                                ->from('msk_guide', 'p')
                                ->leftJoin('p','msk_member','m','p.member_id=m.id')
                                ->leftJoin('p','msk_member_info','e','p.member_id=e.member_id')
                                ->where("p.guide_id=".$guide_id)
                                ->andWhere("p.type=1") 
                                ->execute();
        $data = $query->fetch();
        $data['avatar']=$this->getParameter('app_qiniu_imgurl').$data['avatar'];
        // $year =date('Y');
        // $month =date('m');
        // $day =date('d');
        // //查询导游的忙碌日期
        // $data['calendar'] = $conn->createQueryBuilder()
        //                     ->select('k.*')
        //                     ->from('msk_guide_calendar', 'k')
        //                     ->where("k.guide_id=$guide_id")
        //                     ->andwhere("k.year =$year")
        //                     ->andwhere("k.month =$month")
        //                     // ->setParameter("year", $year)
        //                     // ->setParameter("aftertomorrow", $month)
        //                     ->execute()
        //                     ->fetch();
        if(!empty($data)){
            $this->Send(200,'success',$data);
        }else{
           
            $this->Send(300,'not found guide!');
        }       
  
    }

    /**
     * 导游服务的景点
     * @Route("/apiGuideTravel", name="apiGuideTravel_")
     */
    public function apiGuideTravel(Request $request)
    {
        $guide_id =isset($_GET['guide_id'])?$_GET['guide_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $query = $conn->createQueryBuilder()
                                 ->select(
                                    'p.travel_id', 'p.guide_id', 'p.travel_title', 'p.travel_title', 'p.service_price', 'p.city','p.travel_img'
                                    )
                                ->from('msk_guide_travel', 'p')
                                ->where("p.guide_id=$guide_id") 
                                ->execute();
        $data = $query->fetchAll();
        if(!empty($data)){
             return new JsonResponse($data);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found Travel!';
            return new JsonResponse($massage);
        }
  
    }

    /**
     * 导游的出行相册
     * @Route("/apiGuideAlbum", name="apiGuideAlbum")
     */
    public function apiGuideAlbum(Request $request)
    {
        $guide_id =isset($_GET['guide_id'])?$_GET['guide_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $query = $conn->createQueryBuilder()
                                 ->select(
                                    'p.album_id', 'p.guide_id', 'p.addtime', 'p.imageurl'
                                    )
                                ->from('msk_guide_album', 'p')
                                ->where("p.guide_id=$guide_id") 
                                ->execute();
        $data = $query->fetchAll();
        if(!empty($data)){
             return new JsonResponse($data);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found Album!';
            return new JsonResponse($massage);
        }
  
    }

    /**
     * 导游的相关评论
     * @Route("/apiGuideComment", name="apiGuideComment")
     */
    public function apiGuideComment(Request $request)
    {
        $guide_id =isset($_GET['guide_id'])?$_GET['guide_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $query = $conn->createQueryBuilder()
                                 ->select(
                                    'p.comment_id', 'p.guide_id', 'p.comment_user', 'p.username', 'p.imageurl', 'p.content', 'p.service_quality', 'p.kind','p.addtime'
                                    )
                                ->from('msk_guide_comment', 'p')
                                ->where("p.guide_id=$guide_id") 
                                ->execute();
        $data = $query->fetchAll();
        if(!empty($data)){
             return new JsonResponse($data);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found Comment!';
            return new JsonResponse($massage);
        }
  
    }

    /**
     * 导游的全部出行日期
     * @Route("/apiGuideCalendarAll", name="apiGuideCalendarAll")
     */
    public function apiGuideCalendarAll(Request $request)
    {
        $guide_id =isset($_GET['guide_id'])?$_GET['guide_id']:0;
        //$year =date('Y');
        //$month =date('m');
        // $day =date('d');
        //$orderlist = 'p.guide_id>='.$guide_id.' and p.year>='.$year.' and p.month>='.$month-1.' and p.month<='.$month+1;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                ->select('k.*')
                                ->from('msk_guide_calendar', 'k')
                                ->where("k.guide_id=$guide_id") 
                                ->execute()
                                ->fetchAll();
        if(!empty($data)){
             return new JsonResponse($data);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found Calendar!';
            return new JsonResponse($massage);
        }
  
    }

    /**
     * 导游全部空闲日期生成
     * @Route("apiGuideCalendarWrite",name="apiGuideCalendarWrite_")
     */
    public function apiGuideCalendarWrite() {
        
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        //var_dump($data);exit();
        $par =array('guide_id');
        //$data['the_day'] = '125862;49555266';
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){
            $conn = $this->getDoctrine()->getManager()->getConnection();
            $calendar = $conn->createQueryBuilder()
                                ->select('k.*')
                                ->from('msk_calendar', 'k')
                                ->execute()
                                ->fetchAll();
            foreach ($calendar as $key => $value) {
                $Guide  = new GuideCalendar();
                $Guide->setGuideId($data['guide_id']);
                $Guide->setYear($value['year']);
                $Guide->setMonth($value['month']);
                $Guide->setDays($value['days']);
                $manager->persist($Guide);
                $manager->flush();

                $bool  =$Guide->getCalendarId();
            }
            if(is_numeric($bool)){
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
     * 导游订单生成
     * @Route("apiOrderGuide",name="apiOrderGuide_")
     */
    public function apiOrderGuide() {
          
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        //var_dump($data);exit();
        $par =array('guide_id','member_id','goods_name','goods_number',
                'booking_place','consignee','member_name','member_avatar','guide_name',
                'guide_img','identity_card','mobile','emergency_contact','book_time');
        //$parBoo  =  $this->checkKeyForArr($par, $data); 
        $guide_id=$data['guide_id'];
        //根据guide_id查到价格，再乘以购买的数量，得到总价格
        $pricedata = $conn->getConnection()->createQueryBuilder()
                            ->select(
                                'p.guide_price'
                                )
                            ->from('msk_guide', 'p')
                            ->where("p.guide_id=$guide_id") 
                            ->execute()
                            ->fetch();
        $data['total_amount'] = $pricedata['guide_price']*$data['goods_number']; 
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){  
            $order_sn  = $this->_guide_order_sn(); 
            $order  = new GuideOrder();
            $order->setOrderSn($order_sn);
            $order->setGuideId($data['guide_id']);
            $order->setMemberId($data['member_id']);
            $order->setMemberName($data['member_name']);
            $order->setMemberAvatar($data['member_avatar']);
            $order->setGoodsName($data['goods_name']);
            $order->setBookingPlace($data['booking_place']);
            $order->setGuideName($data['guide_name']);
            $order->setGuideImg($data['guide_img']);
            $order->setConsignee($data['consignee']);
            $order->setIdentityCard($data['identity_card']);
            $order->setMobile($data['mobile']);
            $order->setEmergencyContact($data['emergency_contact']);
            $order->setTotalAmount($data['total_amount']);
            $order->setAddTime(time());
            $manager->persist($order);
            $manager->flush();
            
            $bool  =$order->getOrderId();
                
            if(is_numeric($bool)){
                //添加货单
                $var=explode(";",$data['book_time']);
                foreach ($var as $key => $value) {
                    $ordergoods  = new GuideOrderGoods();
                    $ordergoods->setOrderId($bool);
                    $ordergoods->setBookTime($value);
                    $manager->persist($ordergoods);
                    $manager->flush();
                }
                //添加订单记录
                $orderact  = new GuideOrderAction();
                $orderact->setOrderId($bool);
                $orderact->setActionUser($data['member_id']);
                $orderact->setStatusDesc("用户生成订单");
                $orderact->setLogTime(time());
                $manager->persist($orderact);
                $manager->flush();
                
                $message['status'] = 1;
                $message['error'] = 0;
                $message['order_sn'] = $order_sn;
                $message['msg'] ="Add Success!";
                $message['addTime'] =$order->getAddTime();
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
     * 团购订单号的生成
     */
    function _guide_order_sn()
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
                ->from ( 'msk_guide_order', 'm' )
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
     * @Route("/apiGuideOrderPreList", name="apiGuideOrderPreList_")
     */
    public function apiGuideOrderPreList(Request $request)
    {
        $order_id =isset($_GET['order_id'])?$_GET['order_id']:0;
        $member_id =isset($_GET['member_id'])?$_GET['member_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $query = $conn->createQueryBuilder()
                                ->select(
                                    'p.order_sn', 'p.guide_id', 'p.goods_name', 'p.booking_place', 'p.goods_price','p.consignee','p.mobile',
                                    'g.real_name','g.guide_price','g.gender','g.age','g.experience','g.language'
                                    )
                                ->from('msk_guide_order', 'p')
                                ->leftjoin('p', 'msk_guide', 'g', 'p.guide_id = g.guide_id')
                                ->where("p.order_id=$order_id") 
                                ->andwhere("p.member_id=$member_id")
                                ->execute();
        $data = $query->fetch();
        $data['book_time'] = $conn->createQueryBuilder()
                                ->select('k.*')
                                ->from('msk_guide_order_goods', 'k')
                                ->where("k.order_id=$order_id")
                                ->execute()
                                ->fetchAll();
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
     * 订单详情列表
     * @Route("/apiGuideOrderDetail", name="apiGuideOrderDetail")
     */
    public function apiGuideOrderDetail(Request $request)
    {
        $order_id =isset($_GET['order_id'])?$_GET['order_id']:0;
        $member_id =isset($_GET['member_id'])?$_GET['member_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $query = $conn->createQueryBuilder()
                                ->select(
                                    'p.order_sn', 'p.guide_id', 'p.goods_name', 'p.booking_place', 'p.goods_price','p.consignee','p.mobile','member_avatar','member_name','p.order_status','p.pay_status'
                                    )
                                ->from('msk_guide_order', 'p')
                                ->where("p.order_id=$order_id") 
                                ->andwhere("p.member_id=$member_id")
                                ->execute();
        $data = $query->fetch();
        $data['common_status'] = "";//订单app显示上的状态
        if($data['order_status']<=1){//订单未确认、已下单
           $data['common_status'] = "待支付";
        }elseif($data['order_status']==2) {//已取消订单
           $data['common_status'] = "取消订单";
        }elseif($data['order_status']==3) {//订单无效
           $data['common_status'] = "订单无效";
        }
        if($data['pay_status']==1) {//已付款
           $data['common_status'] = "已付款";
        }
        unset($data['order_status']);
        unset($data['pay_status']);
        $data['book_time'] = $conn->createQueryBuilder()
                                ->select('k.*')
                                ->from('msk_guide_order_goods', 'k')
                                ->where("k.order_id=$order_id")
                                ->execute()
                                ->fetchAll();
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
     * @Route("apiGuideOrderPaySuc",name="apiGuideOrderPaySuc")
     */
    public function apiGuideOrderPaySuc() {
        
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = isset($_GET)?$_GET:'';
        $par =array('order_sn','member_id','order_id');
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            $order_sn = $data['order_sn']; 
            $member_id = $data['member_id']; 
            $order_id = $data['order_id'];            
            $pay_status =1;
            $order_status =1; 
            $pay_time =time();
            $upb =  $conn->createQueryBuilder ()
            ->update ( 'msk_guide_order', 'm' )
            ->set ( 'm.pay_time', $pay_time )
            ->set ('m.pay_status',$pay_status)
            ->set ('m.order_status',"'$order_status'")
            ->where ( "m.order_sn =$order_sn" )
            ->andwhere ( "m.member_id =$member_id" )
            ->execute (); 
           
            if($upb){
                //添加订单操作记录
                $orderact  = new GuideOrderAction();
                $orderact->setOrderId($order_id);
                $orderact->setActionUser($member_id);
                $orderact->setOrderStatus(1);
                $orderact->setPayStatus(1);
                $orderact->setStatusDesc("用户支付成功");
                $orderact->setLogTime(time());
                $manager->persist($orderact);
                $manager->flush();
                
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
     * 取消导游订单
     * @Route("apiGuideOrderCancel",name="apiGuideOrderCancel")
     */
    public function apiGuideOrderCancel() {  
    
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = isset($_GET)?$_GET:'';
        $par =array('order_sn','member_id','order_id');
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            //$order_sn =$data['order_sn'];
            $order_sn =$data['order_sn'];
            $order_status =2; 
            $order_id =$data['order_id'];
            $member_id =$data['member_id'];
            $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_guide_order', 'm' )
            ->set ('m.order_status',$order_status )
            ->where ( "m.order_sn =$order_sn" )
            ->andwhere( "m.member_id =$member_id" )
            ->execute ();
            
            if($upb){
                //添加订单操作记录
                $orderact  = new GuideOrderAction();
                $orderact->setOrderId($order_id);
                $orderact->setActionUser($member_id);
                $orderact->setOrderStatus(2);
                // $orderact->setPayStatus(1);
                $orderact->setStatusDesc("用户取消了订单");
                $orderact->setLogTime(time());
                $manager->persist($orderact);
                $manager->flush();

                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] ="Cancel Success!";
            }else{
            
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] ="Cancel Error!";
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
     * @Route("apiGuideSellerOrderList",name="apiGuideSellerOrderList")
     */
    public function apiGuideSellerOrderList()
    {
       $conn = $this->getDoctrine()->getManager()->getConnection();
       $page =isset($_POST['page'])?$_POST['page']:0;
       $guide_id =isset($_POST['guide_id'])?$_POST['guide_id']:0;
       $type =isset($_POST['type'])?$_POST['type']:"";
       $orderlist = "p.order_status != 5 and p.guide_id="."$guide_id";
       //条件搜索
       //order_status(订单状态) 0，未确认；1，已确认；2，已取消；3，无效；4,删除订单;；
       //pay_status(支付状态) 0，未付款；1，已付款；
       //type 0，待支付；1，已付款；2，已完成；3，已取消
       if($type){
           switch ($type) {
             case '0':
               $orderlist.= " ".'and p.order_status<=1 and p.pay_status=0';
               break;
            case '1':
               $orderlist.= " ".'and p.pay_status =1';
               break;
            case '2':
               $orderlist.= " ".'and p.order_status =1 and p.pay_status=1';
               break;
            case '3':
              $orderlist.= " ".'and p.order_status =2';
               break;
             default:
               # code...
               break;
           }
       }
       $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.order_sn','p.order_id','p.guide_id','p.order_status','p.pay_status','p.goods_name', 'p.goods_price','p.member_name','member_avatar'
                                    )
                                ->from('msk_guide_order', 'p')
                                ->where($orderlist) 
                                ->execute()
                                ->fetchAll();
       foreach ($data as $key => $value) {
        $order_id =  $data[$key]['order_id'];    
        $data[$key]['goods'] = $conn->createQueryBuilder()
                                     ->select(
                                        'k.*'
                                        )
                                    ->from('msk_guide_order_goods', 'k')
                                    ->where("k.order_id=$order_id") 
                                    ->execute()
                                    ->fetchAll();
          $data[$key]['common_status'] = "";//订单app显示上的状态
          if($data[$key]['order_status']<=1){//订单未确认、已下单
               $data[$key]['common_status'] = "待支付";

          }elseif($data[$key]['order_status']==2) {//已取消订单
                $data[$key]['common_status'] = "取消订单";
          }elseif($data[$key]['order_status']==3) {//订单无效
               $data[$key]['common_status'] = "订单无效";
          }
          if($data[$key]['pay_status']==1) {//已付款
               $data[$key]['common_status'] = "已付款";
          }
          unset($data[$key]['order_status']);
          unset($data[$key]['pay_status']);
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

    /**
     * 买家订单列表
     * @Route("apiGuideBuyerOrderList",name="apiGuideBuyerOrderList")
     */
    public function apiGuideBuyerOrderList()
    {  
       $conn = $this->getDoctrine()->getManager()->getConnection();
       $page =isset($_POST['page'])?$_POST['page']:0;
       $member_id =isset($_POST['member_id'])?$_POST['member_id']:0;
       $type =isset($_POST['type'])?$_POST['type']:"";
       $orderlist = "p.order_status != 5 and p.member_id="."$member_id";
       //条件搜索
       //order_status(订单状态) 0，未确认；1，已确认；2，已取消；3，无效；4,删除订单;；
       //pay_status(支付状态) 0，未付款；1，已付款；
       //type 0，待支付；1，已付款；2，已完成；3，已取消
       if($type){
           switch ($type) {
             case '0':
               $orderlist.= " ".'and p.order_status<=1 and p.pay_status=0';
               break;
            case '1':
               $orderlist.= " ".'and p.pay_status =1';
               break;
            case '2':
               $orderlist.= " ".'and p.order_status =1 and p.pay_status=1';
               break;
            case '3':
              $orderlist.= " ".'and p.order_status =2';
               break;
             default:
               # code...
               break;
           }
       }
       $member_id =isset($_GET['member_id'])?$_GET['member_id']:0;
       $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.order_sn','p.order_id','p.guide_id','p.order_status','p.pay_status','p.goods_name', 'p.goods_price','p.guide_img','P.guide_name'
                                    )
                                ->from('msk_guide_order', 'p')
                                ->where($orderlist) 
                                ->execute()
                                ->fetchAll();
       foreach ($data as $key => $value) {
        $order_id =  $data[$key]['order_id'];    
        $data[$key]['goods'] = $conn->createQueryBuilder()
                                     ->select(
                                        'k.*'
                                        )
                                    ->from('msk_guide_order_goods', 'k')
                                    ->where("k.order_id=$order_id") 
                                    ->execute()
                                    ->fetchAll();
          $data[$key]['common_status'] = "";//订单app显示上的状态
          if($data[$key]['order_status']<=1){//订单未确认、已下单
               $data[$key]['common_status'] = "待支付";
               $data[$key]['btn']['pay_order'] = "支付订单";
               $data[$key]['btn']['remove_order'] = "取消订单";
               $data[$key]['btn']['re_order'] =""; //重新购买
               $data[$key]['btn']['del_order'] =""; //删除订单
               $data[$key]['btn']['connect_buyer'] =""; //联系商家
               $data[$key]['btn']['order_evaluate'] =""; //评价订单

          }elseif($data[$key]['order_status']==2) {//已取消订单
                $data[$key]['common_status'] = "取消订单";
                $data[$key]['btn']['pay_order'] = "支付订单";
                $data[$key]['btn']['re_order'] ="重新购买"; //重新购买
                $data[$key]['btn']['remove_order'] =""; //取消订单
                $data[$key]['btn']['del_order'] =""; //删除订单
                $data[$key]['btn']['connect_buyer'] =""; //联系商家
                $data[$key]['btn']['order_evaluate'] =""; //评价订单
          }elseif($data[$key]['order_status']==3) {//订单无效
               $data[$key]['common_status'] = "订单无效";
               $data[$key]['btn']['re_order'] = "重新购买";
               $data[$key]['btn']['pay_order'] ="";//确认订单
               $data[$key]['btn']['remove_order'] =""; //取消订单
               $data[$key]['btn']['del_order'] =""; //删除订单
               $data[$key]['btn']['connect_buyer'] =""; //联系商家
               $data[$key]['btn']['order_evaluate'] =""; //评价订单
          }
          if($data[$key]['pay_status']==1) {//已付款
               $data[$key]['common_status'] = "已付款";
               $data[$key]['btn']['connect_buyer'] = "联系商家";
               $data[$key]['btn']['pay_order'] ="";//确认订单
               $data[$key]['btn']['remove_order'] =""; //取消订单
               $data[$key]['btn']['re_order'] =""; //重新购买
               $data[$key]['btn']['del_order'] =""; //删除订单
               $data[$key]['btn']['order_evaluate'] =""; //评价订单
          }
          unset($data[$key]['order_status']);
          unset($data[$key]['pay_status']);
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

    //改变导游评论的平均质量
    protected function changeGuideTotalQuality($guide_id,$service_quality)
    {   
        
        if($guide_id >0){
            $conn = $this->getDoctrine()->getManager()->getConnection();
            $total = $conn->createQueryBuilder ()
            ->select("avg(service_quality) as quality_avg")
            ->from("msk_guide_comment" )
            ->where("guide_id =".$guide_id )
            //->andWhere("kind=1")
            ->execute()
            ->fetch();
            //return $total;
            $quality_avg = $total['quality_avg'];
            $upb = $conn->createQueryBuilder ()
                ->update ( 'msk_guide', 'm' )
                ->set ('m.service_quality',$quality_avg)
                ->where ( "m.guide_id =".$guide_id )
                ->execute ();
            if($upb) return true;
            return false;
        }
        return false;
    }
    



     /**
     * 导游设置是否接受其他景点
     * @Route("apiGuideChangeIsOther",name="apiGuideChangeIsOther_")
     */
    public function apiGuideChangeIsOther() 
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('guide_id','is_other');
        $parBoo  =  $this->checkKeyForArr($par, $data);
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){
            $is_other =isset($data['is_other'])?$data['is_other']:0;
            $guide_id =isset($data['guide_id'])?$data['guide_id']:0;
            $conn = $manager->getConnection();
            $upb = $conn->createQueryBuilder ()
                ->update ( 'msk_guide', 'm' )
                ->set ('m.is_other',$is_other )
                ->where ( "m.guide_id =$guide_id" )
                ->andWhere("m.type=1")
                ->execute ();
            if($upb){
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] ="change Success!";
            }else{
            
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] ="change Error!";
            }
            
        }else{
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
            
        }
        return new JsonResponse($message);
            
  }

   /**
     * 导游设置自我介绍
     * @Route("apiGuideSetIntroduct",name="apiGuideSetIntroduct_")
     */
    public function apiGuideSetIntroduct() 
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('guide_id','introduction');
        $parBoo  =  $this->checkKeyForArr($par, $data);
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){
            $introduction =$data['introduction'];
            if($introduction == ""){
                $message =array('status'=>0,'error'=>1,'msg'=>'介绍必须填写才能提交');
                return new JsonResponse($message);
            }
            $guide_id =isset($data['guide_id'])?$data['guide_id']:0;
            $conn = $manager->getConnection();
            $upb = $conn->createQueryBuilder ()
                ->update ( 'msk_guide', 'm' )
                ->set ('m.introduction',"'$introduction'")
                ->where ( "m.guide_id =$guide_id" )
                ->execute ();
            if($upb){
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] ="change Success!";
            }else{
            
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] ="change Error!";
            }    
        }else{
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
            
        }
        return new JsonResponse($message);
            
  }

   /**
     * 导游添加服务景点
     * @Route("apiGuideTravelAdd",name="apiGuideTravelAdd_")
     */
    public function apiGuideTravelAdd()
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('guide_id','travel_title','travel_img','service_price','city');
        $parBoo  =  $this->checkKeyForArr($par, $data);
        // $data['travel_title'] = '125862;49555266';
        // $data['guide_id'] = 4;
        // $data['travel_img'] = "http://qiniu.myfriday.cn/2_70078_15266794_1476158780528.jpg?imageView2/1/w/160/h/160";
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){
           $Guide  = new GuideTravel();
           $Guide->setGuideId($data['guide_id']);
           $Guide->setTravelTitle($data['travel_title']);
           $Guide->setTravelImg($data['travel_img']);
           $Guide->setServicePrice($data['service_price']);
           $Guide->setCity($data['city']);
           $manager->persist($Guide);
           $manager->flush();
           $bool  = $Guide->getTravelId();
            if(is_numeric($bool)){
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
     * 导游调整出行日历
     * @Route("apiGuideCalendarUpd",name="apiGuideCalendarUpd_")
     */
    public function apiGuideCalendarUpd()
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('calendar_id','guide_id','year','month','days');
        $parBoo  =  $this->checkKeyForArr($par, $data);
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){
            $manager = $this->getDoctrine()->getManager();
            // $calendar_id = 1;
            // $guide_id = 1;
            // $year = 2016;
            // $month =9;
            // $days = "0,0,1,1,1,1,1,1,0,0,1,1,1,1,1,1,0,0,1,1,1,1,1,1,0,0,1,1,1,0,0";
            $calendar_id = $data['calendar_id'];
            $guide_id = $data['guide_id'];
            $year = $data['year'];
            $month =$data['month'];
            $days = $data['days'];
            $sql =  "INSERT INTO msk_guide_calendar SET calendar_id = '{$calendar_id}',guide_id = '{$guide_id}', year = '{$year}', month = '{$month}', days = '{$days}' ON DUPLICATE KEY UPDATE days = '{$days}' ";
            $query = $manager->getConnection()->query($sql );
            if($query){
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
     * 导游添加出行相册
     * @Route("apiGuideAlbumAdd",name="apiGuideAlbumAdd_")
     */
    public function apiGuideAlbumAdd()
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('guide_id','imageurl');
        $parBoo  =  $this->checkKeyForArr($par, $data);
        // $data['guide_id'] = 4;
        // $data['imageurl'] = "http://qiniu.myfriday.cn/2_70078_15266794_1476158780528.jpg?imageView2/1/w/160/h/160";
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){
           $Guide  = new GuideAlbum();
           $Guide->setGuideId($data['guide_id']);
           $Guide->setaddtime(time());
           $Guide->setImageurl($data['imageurl']);
           $manager->persist($Guide);
           $manager->flush();
           $bool  = $Guide->getAlbumId();
            if(is_numeric($bool)){
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
     * 导游删除出行相册
     * @Route("/apiGuideAlbumDel",name="apiGuideAlbumDel_")
     */
    public function apiGuideAlbumDel() {
        $manager = $this->getDoctrine ()->getManager ();
        $data = isset($_POST)?$_POST:'';
        $par =array('guide_id','album_id');
        $parBoo  =  $this->checkKeyForArr($par, $data);
        // $data['album_id'] = 133;
        // $data['guide_id'] = 4;
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            $st = $manager->getRepository ( "AcmeMinsuBundle:GuideAlbum" );
            $st_data = $st->findOneBy( array('album_id'=>$data['album_id'],'guide_id'=>$data['guide_id']) );
            $manager->beginTransaction ();
            try {
                    $manager->remove ($st_data);
                    $manager->flush();
                    $manager->commit ();
                    $message['status'] = 1;
                    $message['error'] = 0;
                    $message['msg'] ="del Success!";
                    
                    // $commid =$post->getId();
                }catch (Exception $e) {
                    $message['status'] = 0;
                    $message['error'] = 1;
                    $message['msg'] ="del Error!";
                    return new JsonResponse($message);
                }

        }else{
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
            
        }
        return new JsonResponse($message);
    }

    /**
     * 申请开通导游
     * @Route("apiGuideApplyOpening",name="apiGuideApplyOpening_")
     */
    public function apiGuideApplyOpening()
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('member_id','real_name','gender','age','real_avatar');
        // $data['member_id'] = 1;
        // $data['real_name'] = "陈伙佳";
        // $data['real_avatar'] = "http://qiniu.myfriday.cn/2_70078_15266794_1476158780528.jpg?imageView2/1/w/160/h/160";
        // $data['gender'] = 0;
        //$data['age'] = 40;
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){
           $a=$manager->getConnection()->createQueryBuilder ()
                ->select("*")
                ->from('msk_guide')
                ->where('member_id='.$data['member_id'])
                ->execute()->fetch();
           if ($a) return new JsonResponse(array('code'=>300,'msg'=>'您已经提交了审核,勿重复提交','result'=>''));
           $Guide  = new Guide();
           $Guide->setMemberId($data['member_id']);
           $Guide->setRealName($data['real_name']);
           $Guide->setGender($data['gender']);
           $Guide->setAge($data['age']);
           $Guide->setRealAvatar($data['real_avatar']);
           $Guide->setaddtime(time());
           $Guide->setType(1);
           $manager->persist($Guide);
           $manager->flush();
           $bool  = $Guide->getGuideId();
            if(is_numeric($bool)){
                $GuideCer  = new GuideCertification();
                $GuideCer->setGuideId($bool);
                $manager->persist($GuideCer);
                $manager->flush();
                $message['guide_id'] =$bool ;
                return new JsonResponse(array('code'=>200,'msg'=>'认证成功','result'=>$message));
            }else{
                return new JsonResponse(array('code'=>300,'msg'=>'认证失败','result'=>''));
            }  
        }else{
             return new JsonResponse(array('code'=>300,'msg'=>'系统参数错误','result'=>''));
        }
       
            
    }


    /**
     * 导游认证
     * @Route("apiGuideAuthentication",name="apiGuideAuthentication_")
     */
    public function apiGuideAuthentication()
    {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = isset($_POST)?$_POST:'';
        $par =array('guide_id','member_id','real_name','real_avatar','gender','guide_price','lead_time','language','phone','identity_card','home_address','positive_identity','opposite_identity','guide_card','handheld_identity');
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){
            $guide_id =$data['guide_id'];
            $member_id =$data['member_id'];
            $real_name =$data['real_name'];
            $real_avatar =$data['real_avatar'];
            $gender = $data['gender']; 
            $guide_price =$data['guide_price'];
            $lead_time =$data['lead_time'];
            $language =$data['language'];
            $phone =$data['phone'];
            $identity_card =$data['identity_card'];
            $home_address =$data['home_address'];
            $guide_card =$data['guide_card'];
            $handheld_identity =$data['handheld_identity'];
            $positive_identity =$data['positive_identity'];
            $opposite_identity =$data['opposite_identity'];
            $upb = $conn->createQueryBuilder ()
                        ->update ( 'msk_guide', 'm' )
                        ->set ('m.real_name',"'$real_name'")
                        ->set ('m.real_avatar',"'$real_avatar'" )
                        ->set ('m.gender',$gender )
                        ->set ('m.guide_price',"'$guide_price'" )
                        ->set ('m.language',"'$language'")
                        ->set ('m.lead_time',$lead_time )
                        ->set ('m.home_address',"'$home_address'" )
                        ->set ('m.phone',"'$phone'")
                        ->set ('m.identity_card',"'$identity_card'")
                        ->set ('m.state',1)
                        ->where ( "m.guide_id =$guide_id" )
                        ->andwhere( "m.member_id =$member_id" )
                        ->andWhere("m.type=1")
                        ->execute ();
            if(is_numeric($upb)){
                //更新认证表
                $upb1=$conn->createQueryBuilder ()
                        ->update ( 'msk_guide_certification', 'm' )
                        ->set ('m.positive_identity',"'$positive_identity'")
                        ->set ('m.opposite_identity',"'$opposite_identity'")
                        ->set ('m.guide_card',"'$guide_card'" )
                        ->set ('m.handheld_identity',"'$handheld_identity'" )
                        ->where ( "m.guide_id =$guide_id" )
                        ->execute ();
                if(is_numeric($upb1)){
                     return new JsonResponse(array('code'=>200,'msg'=>'申请成功','result'=>''));
                }else{
                    return new JsonResponse(array('code'=>300,'msg'=>'申请失败','result'=>''));
                }
                
            }
        }else{
           return new JsonResponse(array('code'=>300,'msg'=>'系统参数错误','result'=>''));
        }
            
    }

    /**
     * 导游认证状态
     * @Route("/apiguideState", name="apiguideState_")
     */
    public function apiguideState(Request $request)
    {
        $member_id =isset($_GET['member_id'])?$_GET['member_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                ->select(
                                    'p.state','p.guide_id'
                                    )
                                ->from('msk_guide', 'p') 
                                ->where("p.member_id=$member_id")
                                ->execute()
                                ->fetch();
        //0未认证 1认证中 2认证通过 3认证失败 4注销 5禁用'
        if(!empty($data)){
             return new JsonResponse($data);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found data!';
            return new JsonResponse($massage);
        }  

    }

    //导游热门城市
    /**
     * @Route("/apiGuideHotCity",name="apiGuideHotCity")
     */
    public function apiGuideHotCityAction(){
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $data = $conn->createQueryBuilder ()
                     ->select ( "m.*,ma.area_name" )
                     ->from ( 'msk_guide_hot_city', 'm' )
                     ->leftjoin('m','msk_area','ma','m.area_id=ma.area_id')
                     ->orderBy("m.sort","DESC")
                     ->execute ()
                     ->fetchAll ();
        if(!empty($data)){
            return new JsonResponse($data);
        }else{
             $message['status'] ="0";
             $message['error'] = "1";
             $message['message'] ='no found data';
             return new JsonResponse($message);
        }
       
        
    }
    
     //导游全部城市
    /**
     * @Route("/apiGuideAllCity",name="apiGuideAllCity")
     */
    public function apiGuideAllCityAction(){
        $charactors = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        foreach ($charactors as $key => $value) {
              $a[$key]['Letter'] = $value;
              $a[$key]['data'] = $conn->createQueryBuilder ()
                        ->select ( "m.area_id,m.area_name" )
                        ->from ( 'msk_area', 'm' )
                        ->Where("area_region = '$value'")
                        ->andwhere('area_deep = 2')
                        ->execute ()
                        ->fetchAll ();
        }
    
        return new JsonResponse($a);
    
    }

     //测试下回滚哈
    /**
     * @Route("/apiGuideTest",name="apiGuideTest")
     */
    public function apiGuideTestAction(){
        $data['content'] = '125862;49555266';
        $data['guide_id'] = 4;
        $data['comment_user'] = 4;
        $data['username'] = "陈伙佳";
        $data['imageurl'] = "http://qiniu.myfriday.cn/2_70078_15266794_1476158780528.jpg?imageView2/1/w/160/h/160";
        $data['service_quality'] = 4;
        $data['kind'] =1;
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            //... do some work
            $Guide  = new GuideComment();
            $Guide->setGuideId($data['guide_id']);
            $Guide->setCommentUser($data['comment_user']);
            $Guide->setUserName($data['username']);
            $Guide->setImageurl($data['imageurl']);
            $Guide->setContent($data['content']);
            $Guide->setServiceQuality($data['service_quality']);
            $Guide->setKind($data['kind']);
            $Guide->setAddTime(time());
            $em->persist($Guide);
            $a=$em->flush();
            // var_dump($em->flush());exit; null
            
            $b=$em->getConnection()->createQueryBuilder ()
                        ->update ( 'msk_guide_comment', 'm' )
                        ->set("m.addtime",time())
                        ->where ( "m.comment_id =11" )
                        ->execute (); 
            if(!$a||!$b){
                 $em->getConnection()->commit();
            }
           
            return new JsonResponse(array('status'=>1,'error'=>0,'msg'=>'Success','result'=>''));
        } catch (Exception $e) {

            $em->getConnection()->rollback();
            $em->close();
            //throw $e;
            return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'error！'));
        }
    
    }

    
    /**
     * @Route("/apiLanguageList",name="apiLanguageList")
     */
   public function apiLanguageList(){
       $data=$this->conn()->createQueryBuilder()->select('id,name')->from('msk_language')->where('is_show=1')->execute()->fetchAll();
       $this->Send(200,'success',$data);
   }

    




   

   


    
    



  
  
}






























