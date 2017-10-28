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
use Acme\MinsuBundle\Entity\Tour;
use Acme\MinsuBundle\Entity\TourRoute;
use Acme\MinsuBundle\Entity\TourDetail;
use Acme\MinsuBundle\Entity\TourRouteDetail;
use Acme\MinsuBundle\Entity\TravelAgency;
use Acme\MinsuBundle\Entity\TravelAgencyCertification;
use Acme\MinsuBundle\Entity\TourEnroll;
use Acme\MinsuBundle\Entity\TourTrip;
use Acme\MinsuBundle\Entity\TourRouteTrip;
use Acme\MinsuBundle\Entity\TourCalendar;

use Acme\MinsuBundle\Common\CommonController;
class apiTourController extends CommonController
{
    
    public function __construct(){
    
    }
    
    
    /**
     * 团购列表
     * @Route("/apiSearchTour", name="apiSearchTour_")
     */
    public function apiSearchTourAction(Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();
        $app_qiniu_imgurl = $this->getParameter('app_qiniu_imgurl');
        //         $startPage = ($page - 1) * $pageSize;
        $page =isset($_POST['page'])?$_POST['page']:0;
        $starting_place =isset($_POST['starting_place'])?$_POST['starting_place']:'';
        $period =isset($_POST['period'])?$_POST['period']:'';//期数
        $lowprice =isset($_POST['lowprice'])?$_POST['lowprice']:0;//最低价格
        $highprice =isset($_POST['highprice'])?$_POST['highprice']:10000000;//最高价格
        $priceRule =isset($_POST['priceRule'])?$_POST['priceRule']:0;//价格排序
        $popularRule =isset($_POST['popularRule'])?$_POST['popularRule']:0;//热度排序
        $destination =isset($_POST['destination'])?$_POST['destination']:'';//目的地
        $agency_id = isset($_POST['agency_id'])?$_POST['agency_id']:'';//旅行团
        $orderlist = "p.state = 1 ";
        // $orderlist = "a.the_date =".$now.' ';
        if($starting_place !==""){
            $orderlist.= 'and p.starting_place like '.'"%'.$starting_place.'%"'.' ';
        }
        if($destination !==""){
            $orderlist.= 'and p.destination like '.'"%'.$destination.'%"'.' ';
        }
        if(trim($period) !== '')
        {   
            $orderlist.= 'and p.period='.$period.' ';
        }
        if(trim($agency_id) !== '')
        {   
            $orderlist.= 'and p.agency_id='.$agency_id.' ';
        }
        $orderlist.= 'and p.default_adult_price>='.$lowprice.' and p.default_adult_price<='.$highprice;

        if($priceRule == 0){
           $orderRule = "p.default_adult_price, 'DESC'"; 
        }else{
            $orderRule = "p.default_adult_price, 'ASC'";
        }
        if($popularRule==0){
            $orderRule = "p.entered, 'DESC'";  
        }else{
            $orderRule = "p.entered, 'ASC'";  
        }
        $conn = $em->getConnection();
        $data = $conn->createQueryBuilder()
                                 ->select(
                                    'a.adult_price','a.child_price','p.tour_id', 'p.agency_id', 'p.tour_title', 'p.imgurl', 'p.period','p.addtime','p.starting_place','p.service_price','p.entered','a.chief_id','k.member_id as chief_member_id'
                                    )
                                ->from('msk_tour_calendar', 'a')
                                ->leftjoin('a','msk_tour', 'p','a.tour_id=p.tour_id')
                                ->leftjoin('a','msk_chief', 'k','a.chief_id=k.chief_id')
                                ->where($orderlist)
                                ->andWhere("a.state=1")
                                ->setFirstResult($page)
                                ->setMaxResults(10)
                                ->execute()
                                ->fetchAll();
        foreach ($data as $key => $value) {
            $data[$key]['imgurl'] = $this->getParameter('app_qiniu_imgurl').$value['imgurl'];
            //查询多少人报名的列表(最多获取3个)
            //$tour_id=$data[$key]['tour_id'];
            // $data[$key]['enroll'] = $conn->createQueryBuilder()
            //                     ->select('k.avatar,k.member_id')
            //                     ->from('msk_tour_order_goods', 'k')
            //                     ->where("k.tour_id=$tour_id")
            //                     ->setMaxResults(3) 
            //                     ->execute()
            //                     ->fetchAll();
           
         } 
        if(!empty($data)){
                return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else {
                return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }
  
    }

   /**
     * 热门团购列表
     * @Route("/apiHotTour", name="apiHotTour_")
     */
    public function apiHotTourAction(Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $now = date("Y-m-d");
        $data = $conn->createQueryBuilder()
        ->select(
                'p.tour_id', 'p.agency_id', 'p.tour_title', 'p.imgurl', 'p.default_adult_price as adult_price', 'p.period','p.addtime','p.starting_place','p.service_price','p.entered','p.chief_id'
                )
                ->from('msk_tour', 'p')
                ->where("p.state=1")
                ->orderBy('p.entered','DESC') 
                ->setFirstResult(0)
                ->setMaxResults(3) 
                ->execute()
                ->fetchAll();  
        foreach ($data as $key => $value) {
            $tour_id=$data[$key]['tour_id'];
            $data[$key]['imgurl'] = $this->getParameter('app_qiniu_imgurl').$value['imgurl'];
            //查询是否有导游设置的根据日期改变的价格，有的话就替换adult_price价格
            $price = $conn->createQueryBuilder()
                                ->select('a.adult_price','a.chief_id')
                                ->from('msk_tour_calendar', 'a')
                                ->where("a.tour_id=$tour_id")
                                ->andWhere("a.the_date ='$now'")
                                ->execute()
                                ->fetch();
            if($price){
                $data[$key]['adult_price'] = $price['adult_price'];
                $data[$key]['chief_id'] = $price['chief_id'];
            }
           
         } 
        if(!empty($data)){
            return new JsonResponse($data);
        }else{
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found tour!';
            return new JsonResponse($massage);
        }
  
    }


    /**
     * 团购详情
     * @Route("/apiTourDetail", name="apiTourDetail_")
     */
    public function apiTourDetail(Request $request)
    {
        $tour_id =isset($_GET['tour_id'])?$_GET['tour_id']:0;
        $now = date("Y-m-d");
        //$the_date=$request->get('the_date',$now);
        $the_date =isset($_GET['the_date'])?$_GET['the_date']:$now;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id', 'p.agency_id', 'p.tour_title', 'p.imgurl','p.period','p.default_adult_price','p.default_child_price as child_price','p.addtime','p.starting_place','p.service_price','p.state','p.entered','p.planned','p.agency_name','p.telphone','p.end_time','destination'
                                    )
                                ->from('msk_tour', 'p')
                                ->where("p.tour_id=".$tour_id)
                                ->execute()
                                ->fetch();
        if($data){
            $data['chief_id'] = 0;
            $data['chief_name'] = "";
            $data['chief_avatar'] = "";
            $data['imgurl'] = $this->getParameter('app_qiniu_imgurl').$data['imgurl'];
            //查询是否有导游设置的根据日期改变的价格，有的话就替换adult_price价格
            $price = $conn->createQueryBuilder()
                                ->select("a.adult_price,a.the_date,a.chief_id,a.chief_name,a.chief_avatar")
                                ->from('msk_tour_calendar', 'a')
                                ->where("a.tour_id=".$tour_id)
                                ->andWhere("a.the_date='$the_date'")
                                ->execute()
                                ->fetch();
            //$data['calendar'] =array();
            $data['calendar'] = $conn->createQueryBuilder()
                                ->select('a.*,k.member_id as chief_member_id')
                                ->from('msk_tour_calendar', 'a')
                                ->leftjoin('a','msk_chief', 'k','a.chief_id=k.chief_id')
                                ->where("a.tour_id=$tour_id")
                                ->andWhere("a.the_date >='$now'")
                                ->orderBy('a.the_date','ASC')
                                ->setMaxResults(8) 
                                ->execute()
                                ->fetchAll();
            //$data['adult_price']=$data['default_adult_price'];
            if(!empty($price)){
                $data['adult_price'] = $price['adult_price'];
                $data['chief_id'] = $price['chief_id'];
                $data['chief_name'] = $price['chief_name'];
                $data['chief_avatar'] = $price['chief_avatar'];
            }

            $message['code'] = 200;
            $message['msg'] = "Success!";
            $message['result'] =$data;
        }else{
        
            $message['code'] = 300;
            $message['msg'] = "Error!";
            $message['result'] ="";
        }  
        return new JsonResponse($message);     
  
    }

    /**
     * 旅程详情
     * @Route("/apiTourDetailList", name="apiTourDetailList_")
     */
    public function apiTourDetailList(Request $request)
    {
       
        $tour_id = $request->get('tour_id',0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data['detail'] = $conn->createQueryBuilder()
                                ->select('p.*')
                                ->from('msk_tour_detail', 'p')
                                ->where("p.tour_id=$tour_id") 
                                ->orderBy("p.site",'DESC')
                                ->execute()
                                ->fetchAll();
        $data['trip'] = $conn->createQueryBuilder()
                                ->select('p.*')
                                ->from('msk_tour_trip', 'p')
                                ->where("p.tour_id=$tour_id") 
                                ->orderBy("p.num",'DESC')
                                ->execute()
                                ->fetchAll();
        if(!empty($data)){
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }          
    }

    /**
     * 规定须知与费用说明
     * @Route("/apiTourExplainList", name="apiTourExplainList_")
     */
    public function apiTourExplainList(Request $request)
    {
       
        $tour_id =isset($_GET['tour_id'])?$_GET['tour_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                ->select(
                                    'p.tour_id', 'p.booking_notice'
                                    )
                                ->from('msk_tour', 'p')
                                ->where("p.tour_id=$tour_id") 
                                ->execute()
                                ->fetch();
        if(!empty($data)){
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }   
  
    }

    /**
     * 全部价格日期展示
     * @Route("/apiTourCalendarList", name="apiTourCalendarList_")
     */
    public function apiTourCalendarList(Request $request)
    {
       
        $tour_id =isset($_GET['tour_id'])?$_GET['tour_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                ->select(
                                    "p.*,date_format(p.the_date,'%Y-%c-%e') as the_date"
                                    )
                                ->from('msk_tour_calendar', 'p')
                                ->where("p.tour_id=$tour_id") 
                                ->orderBy('p.the_date','ASC')
                                ->execute()
                                ->fetchAll();
        if(!empty($data)){
             return new JsonResponse($data);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found tour data!';
            return new JsonResponse($massage);
        }       
  
    }


    /**
     * 报名人数列表
     * @Route("/apiTourEnrollList", name="apiTourEnrollList")
     */
    public function apiTourEnrollList(Request $request)
    {
       
        $calendar_id =isset($_GET['calendar_id'])?$_GET['calendar_id']:0;
        $page =isset($_GET['page'])?$_GET['page']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                ->select(
                                    'p.rec_id', 'p.order_sn', 'p.member_id', 'p.avatar', 'p.username', 'p.enroll_time'
                                    )
                                ->from('msk_tour_order_goods', 'p')
                                ->where("p.calendar_id=$calendar_id") 
                                ->andWhere("p.state in (1,3,4)") 
                                ->setFirstResult($page)
                                ->setMaxResults(10)
                                ->execute()
                                ->fetchAll();
        foreach ($data as $key => $value) {
            $data[$key]['avatar'] = $this->getParameter("app_qiniu_imgurl").$value['avatar'];
        }
        if(!empty($data)){
             return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }    
    }

   
    /**
     * 发布团游
     * @Route("apiTourAdd",name="apiTourAdd")
     */
    public function apiTourAdd()
    {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = isset($_POST)?$_POST:'';
        $par =array('agency_id','agency_name','imgurl','tour_title','period','starting_time','member_id','member_name','member_avatar','starting_place','service_price','booking_notice','detail','age_line','planned','route_id','end_time','telphone','default_adult_price','default_child_price','destination','proxy_id','trip','calendar','token');
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
           $memberid = $this->validationToken($data['token']);
           if(is_array($memberid)) return new JsonResponse($memberid);
           $detailarr=json_decode($data['detail'],true);
           $triparr=json_decode($data['trip'],true);
           $calendararr=json_decode($data['calendar'],true);
           $Tour  = new Tour();
           $Tour->setAgencyId($data['agency_id']);
           $Tour->setProxyId($data['proxy_id']);
           $Tour->setAgencyName($data['agency_name']);
           $Tour->setTourTitle($data['tour_title']);
           $Tour->setImgurl($data['imgurl']);
           $Tour->setPeriod($data['period']);
           $Tour->setChiefId($data['member_id']);
           $Tour->setChiefName($data['member_name']);
           $Tour->setChiefAvatar($data['member_avatar']);
           $Tour->setStartingTime($data['starting_time']);
           $Tour->setStartingPlace($data['starting_place']);
           $Tour->setServicePrice($data['service_price']);
           $Tour->setBookingNotice($data['booking_notice']);
           $Tour->setPlanned($data['planned']);
           $Tour->setRouteId($data['route_id']);
           $Tour->setDestination($data['destination']);
           $Tour->setDefaultAdultPrice($data['default_adult_price']);
           $Tour->setDefaultChildPrice($data['default_child_price']);
           $Tour->setTelphone($data['telphone']);
           $Tour->setaddtime(time());
           $Tour->setEndtime(time()+24*60*60*30);
           $manager->persist($Tour);
           $manager->flush();
           $bool  = $Tour->getTourId();
           
            if(is_numeric($bool)){
                if(is_array($detailarr)){
                    //循环插入旅游详情的值
                    foreach ($detailarr as $key => $value) {
                        $Detail  = new TourDetail();
                        $Detail->setTourId($bool);
                        $Detail->setLongitude($value['longitude']);
                        $Detail->setLatitude($value['latitude']);
                        $Detail->setSite($value['site']);
                        $manager->persist($Detail);
                        $manager->flush();
                    } 
                }
                if(is_array($triparr)){
                    //循环插入跟团行程的值
                    foreach ($triparr as $key => $value) {
                        $Detail  = new TourTrip();
                        $Detail->setTourId($bool);
                        $Detail->setTitle($value['title']);
                        $Detail->setLongitude($value['longitude']);
                        $Detail->setLatitude($value['latitude']);
                        $Detail->setNum($value['num']);
                        $manager->persist($Detail);
                        $manager->flush();
                    }
                }
                if(is_array($calendararr)){
                    foreach ($calendararr as $key => $value) {
                        $the_date = $value['the_date'];
                        $calendar_info = $manager->getConnection()->createQueryBuilder ()
                                    ->select('m.*')
                                    ->from('msk_tour_calendar', 'm' )
                                    ->where ( "m.tour_id =$bool" )
                                    ->andWhere ( "m.the_date ='$the_date'" )                 
                                    ->execute ()->fetch();
                        if(empty($calendar_info)){
                            $Calendar  = new TourCalendar();
                            $Calendar->setTourId($bool);
                            $Calendar->setTheDate($the_date);
                            $Calendar->setAdultPrice($value['adult_price']);
                            $Calendar->setChildPrice($value['child_price']);
                            $Calendar->setChiefId($value['chief_id']);
                            $Calendar->setChiefName($value['chief_name']);
                            $Calendar->setChiefAvatar($value['chief_avatar']);
                            $Calendar->setAddtime(time());
                            $Calendar->setState(1);
                            $Calendar->setGroupName("");
                            $Calendar->setChatRoom("");
                            $manager->persist($Calendar);
                            $manager->flush();
                            $calendar_id  = $Calendar->getCalendarId();
                            $groupName = md5($calendar_id);
                            $chatRoom = md5($calendar_id);
                            $chief_member_id=$value['chief_member_id'];
                            $upb = $conn->createQueryBuilder ()
                                        ->update ( 'msk_tour_calendar', 'm' )
                                        ->set ('m.group_name',"'$groupName'")
                                        ->set ('m.chat_room',"'$chatRoom'")
                                        ->where ( "m.calendar_id =$calendar_id" )
                                        ->execute ();
                            $tokenRes = $this->curl('/group/create', array('userId' => $chief_member_id, 'groupId' => $calendar_id, 'groupName' => $groupName));
                            $room_user="chatroom[$chief_member_id]";
                            $tokenRes = $this->curl('/chatroom/create', array($room_user => $chatRoom));
                            $tokenRes = $this->curl('/chatroom/user/whitelist/add', array('userId' => $chief_member_id, 'chatroomId' => $calendar_id));
                        }
                        
                    }
                }
                $proxy_id = isset($a['proxy_id'])?$a['proxy_id']:0;
                if($proxy_id>0){
                    //加1
                    $conn->createQueryBuilder ()
                         ->update ( 'msk_travel_agency_proxy', 'm' )
                         ->set ( 'm.tour_num', "m.tour_num+1" )
                         ->andWhere ( "m.proxy_id =$proxy_id" )
                         ->execute (); 
                }
                return new JsonResponse(array('code'=>200,'msg'=>'添加成功','result'=>''));
            }else{
                return new JsonResponse(array('code'=>300,'msg'=>'添加失败','result'=>''));
            }  
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
        }
            
    }

    /**
     * 更改操作团游上下架
     * @Route("apiTourSetState",name="apiTourSetState_")
     */
    public function apiTourSetState() 
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('tour_id','agency_id','state');
        //$parBoo  =  $this->checkKeyForArr($par, $data);
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            $state =trim($data['state']);
            $agency_id =isset($data['agency_id'])?$data['agency_id']:0;
            $tour_id =isset($data['tour_id'])?$data['tour_id']:0;
            $conn = $manager->getConnection();
            $upb = $conn->createQueryBuilder ()
                ->update ( 'msk_tour', 'm' )
                ->set ('m.state',"'$state'")
                ->where ( "m.agency_id =$agency_id" )
                ->andWhere ( "m.tour_id =$tour_id" )
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
     * 发布路线
     * @Route("apiTourRouteAdd",name="apiTourRouteAdd")
     */
    public function apiTourRouteAdd()
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('agency_id','proxy_id','tour_imgurl','tour_title','period','starting_time','starting_place','service_price','booking_notice','detail','age_line','planned','end_time','telphone','default_adult_price','default_child_price','destination','trip');
        $detailarr=json_decode($data['detail'],true);
        $triparr=json_decode($data['trip'],true);
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            $agency_id = $data['agency_id'];
            $agency_info=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_travel_agency')
            ->where("agency_id=".$agency_id)//客户端先判断后得到的id
            //->andWhere("member_id=$member_id")
            ->execute()->fetch();
            if (empty($agency_info)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'未找到匹配的旅行社'));
            if ($agency_info['state'] ==0) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'您的旅行团还未认证!'));
            if ($agency_info['state'] ==1) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'您的旅行团尚在认真中!'));
            if ($agency_info['state'] ==2) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'您的旅行团已经认证通过、但是未交保证金!'));
            if ($agency_info['state'] ==3) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'您的旅行团认证失败失败,请重新认证!'));
            if ($agency_info['state'] ==4) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'您的旅行团账号已经被注销，如有疑问可联系客服!'));
            if ($agency_info['state'] ==5) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'您的旅行团账号已经禁用，如有疑问可联系客服!'));
           $Tour  = new TourRoute();
           $Tour->setAgencyId($data['agency_id']);
           $Tour->setProxyId($data['proxy_id']);
           $Tour->setAgencyName($agency_info['agency_name']);
           $Tour->setTourTitle($data['tour_title']);
           $Tour->setTourImgurl($data['tour_imgurl']);
           $Tour->setPeriod($data['period']);
           $Tour->setStartingTime($data['starting_time']);
           $Tour->setStartingPlace($data['starting_place']);
           $Tour->setServicePrice($data['service_price']);
           $Tour->setBookingNotice($data['booking_notice']);
           $Tour->setDestination($data['destination']);
           $Tour->setDefaultAdultPrice($data['default_adult_price']);
           $Tour->setDefaultChildPrice($data['default_child_price']);
           $Tour->setTelphone($agency_info['agency_tel']);
           $Tour->setaddtime(time());
           $Tour->setEndtime(time()+24*60*60*30);
           $manager->persist($Tour);
           $manager->flush();
           $bool  = $Tour->getRouteId();
            if(is_numeric($bool)){
                if(is_array($detailarr)){
                    //循环插入旅游详情的值
                    foreach ($detailarr as $key => $value) {
                        $Detail  = new TourRouteDetail();
                        $Detail->setRouteId($bool);
                        $Detail->setLongitude($value['longitude']);
                        $Detail->setLatitude($value['latitude']);
                        $Detail->setSite($value['site']);
                        $manager->persist($Detail);
                        $manager->flush();
                    }
                }
                if(is_array($triparr)){
                    //循环插入跟团行程的值
                    foreach ($triparr as $key => $value) {
                        $Trip  = new TourRouteTrip();
                        $Trip->setTourId($bool);
                        $Trip->setTitle($value['title']);
                        $Trip->setLongitude($value['longitude']);
                        $Trip->setLatitude($value['latitude']);
                        $Trip->setNum($value['num']);
                        $manager->persist($Trip);
                        $manager->flush();
                    }
                }

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
     * 更改操作路线上下架
     * @Route("apiTourRouteSetState",name="apiTourRouteSetState_")
     */
    public function apiTourRouteSetState() 
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('route_id','agency_id','state');
        //$parBoo  =  $this->checkKeyForArr($par, $data);
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            $state =trim($data['state']);
            $agency_id =isset($data['agency_id'])?$data['agency_id']:0;
            $route_id =isset($data['route_id'])?$data['route_id']:0;
            $conn = $manager->getConnection();
            $upb = $conn->createQueryBuilder ()
                ->update ( 'msk_tour_route', 'm' )
                ->set ('m.state',"'$state'")
                ->where ( "m.agency_id =$agency_id" )
                ->andWhere ( "m.route_id =$route_id" )
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
     * 编辑路线
     * @Route("apiRouteInfoEdit",name="apiRouteInfoEdit")
     */
    public function apiRouteInfoEdit() {
        
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = isset($_POST)?$_POST:'';
        $par =array('route_id','agency_id','proxy_id','agency_name','tour_imgurl','tour_title','period','starting_time','starting_place','service_price','booking_notice','detail','age_line','end_time','telphone','default_adult_price','default_child_price','destination','trip');
        $detailarr=json_decode($data['detail'],true);
        $triparr=json_decode($data['trip'],true);
        //return new JsonResponse($detailarr);
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            $a=$manager->getConnection()->createQueryBuilder ()
                ->select("*")
                ->from('msk_tour_route')
                ->where('route_id='.$data['route_id'])
                ->andWhere('agency_id='.$data['agency_id'])
                ->orWhere('proxy_id='.$data['proxy_id'])
                ->andWhere('state != 0')
                ->execute()->fetch();
            if (empty($a)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限操作','result'=>''));
            $route_id =$data['route_id'];
            $tour_imgurl =$data['tour_imgurl'];
            $tour_title =$data['tour_title'];
            $period =$data['period'];
            $starting_time =$data['starting_time'];
            $starting_place =$data['starting_place'];
            $service_price =$data['service_price'];
            $booking_notice =$data['booking_notice'];
            $planned =$data['planned'];
            $end_time =$data['end_time'];
            $telphone =$data['telphone'];
            $default_adult_price =$data['default_adult_price'];
            $default_child_price =$data['default_child_price'];
            $destination =$data['destination'];
            //$detail = $data['detail'];
            $upb =  $conn->createQueryBuilder ()
            ->update ( 'msk_tour_route', 'm' )
            ->set ( 'm.tour_imgurl', "'$tour_imgurl'" )
            ->set ( 'm.tour_title', "'$tour_title'" )
            ->set ( 'm.period', $period )
            ->set ( 'm.starting_time', $starting_time )
            ->set ( 'm.starting_place', "'$starting_place'" )
            ->set ( 'm.service_price', $service_price )
            ->set ( 'm.booking_notice', "'$booking_notice'" )
            //->set ( 'm.planned', $planned )
            ->set ('m.end_time',$end_time)
            ->set ('m.default_adult_price',$default_adult_price)
            ->set ('m.default_child_price',$default_child_price)
            ->set ('m.destination',"'$destination'")
            ->set ('m.telphone',$telphone)
            ->where ( "m.route_id =$route_id" )
            ->execute (); 
            if($upb){
                if(is_array($detailarr)){
                    
                    foreach ($detailarr as $key => $value) {
                        $latitude = $value['latitude'];
                        $longitude = $value['longitude'];
                        $site = $value['site'];
                        $state =isset($value['state'])?$value['state']:1;
                        //循环插入旅游详情的值
                        $Detail  = new TourRouteDetail();
                        $Detail->setRouteId($route_id);
                        $Detail->setLongitude($longitude);
                        $Detail->setLatitude($latitude);
                        $Detail->setSite($site);
                        $manager->persist($Detail);
                        $manager->flush();
                        
                    }
                }
                //旅程
                if(is_array($triparr)){
                    foreach ($triparr as $key => $value) {
                        $trip_id =$value['trip_id'];
                        $title = $value['title'];
                        $latitude = $value['latitude'];
                        $longitude = $value['longitude'];
                        $num = $value['num'];
                        $state =isset($value['state'])?$value['state']:1;
                        if($trip_id ==0){
                                //循环插入旅游详情的值
                                $Trip  = new TourRouteTrip();
                                $Trip->setRouteId($route_id);
                                $Trip->setTitle($title);
                                $Trip->setLongitude($longitude);
                                $Trip->setLatitude($latitude);
                                $Trip->setNum($num);
                                $manager->persist($Trip);
                                $manager->flush();
                        }else{
                            //循环修改旅游详情的值
                            $upb =  $conn->createQueryBuilder ()
                                ->update ( 'msk_tour_route_trip', 'm' )
                                ->set ('m.title',$title)
                                ->set ('m.longitude',$longitude)
                                ->set ('m.latitude',$latitude)
                                ->set ('m.num',$num)
                                ->where ( "m.route_id =$route_id" )
                                ->andWhere ( "m.trip_id =$trip_id" )
                                ->execute (); 
                        }
                        
                    }
                }
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] ="Edit Success!";
            }else{
                $message['status'] = 0;
                $message['error'] = 1;
                $message['message'] = 'Edit Error!';
            }
          

        }else{
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        }
        return new JsonResponse($message);
    }


    /**
     * 团购列表状态
     * @Route("/apiSearchTourState", name="apiSearchTourState_")
     */
    public function apiSearchTourState(Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();
        $page =isset($_POST['page'])?$_POST['page']:0;
        $state =isset($_POST['state'])?$_POST['state']:1;
        $agency_id =isset($_POST['agency_id'])?$_POST['agency_id']:0;
        $conn = $em->getConnection();
        $data = $conn->createQueryBuilder()
        ->select(
                'p.tour_id', 'p.agency_id', 'p.tour_title', 'p.imgurl', 'p.default_adult_price as adult_price', 'p.period','p.addtime','p.starting_place','p.service_price','p.entered','total_amount'
                // 'a.the_date','a.adult_price'
                //,'p.check_in_time'
                )
                ->from('msk_tour', 'p')
                // ->leftjoin('p', 'msk_tour_calendar', 'a', 'a.tour_id = p.tour_id')
                ->where ( "p.agency_id =$agency_id" )
                ->andWhere( "p.state =$state" )
                ->setFirstResult($page)
                ->setMaxResults(10) 
                ->execute()
                ->fetchAll();  
        foreach ($data as $key => $value) {
             //查询多少人报名的列表(最多获取3个)
            $tour_id=$data[$key]['tour_id'];
            $data[$key]['enroll'] = $conn->createQueryBuilder()
                                ->select('k.avatar,k.member_id')
                                ->from('msk_tour_order_goods', 'k')
                                ->where("k.tour_id=$tour_id")
                                ->setMaxResults(3) 
                                ->execute()
                                ->fetchAll();
            //查询是否有导游设置的根据日期改变的价格，有的话就替换adult_price价格
            $now = date("Y-m-d");
            $price = $conn->createQueryBuilder()
                                ->select('a.adult_price')
                                ->from('msk_tour_calendar', 'a')
                                ->where("a.tour_id=$tour_id")
                                ->andWhere("a.the_date ='$now'")
                                ->execute()
                                ->fetch();
            if($price){
                $data[$key]['adult_price'] = $price['adult_price'];
            }
         } 
        if(!empty($data)){
            return new JsonResponse($data);
        }else{
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found tour!';
            return new JsonResponse($massage);
        }
  
    }

    

    /**
     * 更改儿童年龄限定
     * @Route("apiTourChildAgeLine",name="apiTourChildAgeLine_")
     */
    public function apiTourChildAgeLine() 
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('agency_id','age_line');
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){
            $age_line =isset($data['age_line'])?$data['age_line']:14;
            $agency_id =isset($data['agency_id'])?$data['agency_id']:0;
            $conn = $manager->getConnection();
            $upb = $conn->createQueryBuilder ()
                ->update ( 'msk_travel_agency', 'm' )
                ->set ('m.age_line',"'$age_line'")
                ->where ( "m.agency_id =$agency_id" )
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
     * 申请开通旅行社
     * @Route("apiTravelAgencyApplyOpening",name="apiTravelAgencyApplyOpening_")
     */
    public function apiTravelAgencyApplyOpening()
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('member_id','agency_image');
        // $data['member_id'] = 1;
        // $data['agency_image'] = "http://qiniu.myfriday.cn/2_70078_15266794_1476158780528.jpg?imageView2/1/w/160/h/160";
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
           $a=$manager->getConnection()->createQueryBuilder ()
                ->select("*")
                ->from('msk_travel_agency')
                ->where('member_id='.$data['member_id'])
                ->execute()->fetch();
           $message['agency_id'] =$a['agency_id'] ;
           if ($a)return new JsonResponse(array('code'=>300,'msg'=>'您已经提交过了审核','result'=>$message)); 
           $order_sn =$this->_travel_agency_order_sn();
           $deposit = 1000;
           $TravelAgency  = new TravelAgency();
           $TravelAgency->setMemberId($data['member_id']);
           $TravelAgency->setAgencyImage($data['agency_image']);
           $TravelAgency->setaddtime(time());
           $manager->persist($TravelAgency);
           $manager->flush();
           $bool  = $TravelAgency->getAgencyId();
            if(is_numeric($bool)){
                $AgencyCer  = new TravelAgencyCertification();
                $AgencyCer->setAgencyId($bool);
                $AgencyCer->setOrderSn($order_sn);
                $AgencyCer->setDeposit($deposit);
                $manager->persist($AgencyCer);
                $manager->flush();
                $message['agency_id'] =$bool ;
                return new JsonResponse(array('code'=>200,'msg'=>'申请成功','result'=>$message));
            }else{
                return new JsonResponse(array('code'=>300,'msg'=>'申请失败','result'=>''));
            }  
        }else{
            
            return new JsonResponse(array('code'=>300,'msg'=>'系统参数错误','result'=>''));
        }
        return new JsonResponse($message);
            
    }

    /**
     * 团购订单号的生成
     */
    function _travel_agency_order_sn()
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
                ->from ( 'msk_travel_agency_certification', 'm' )
                ->where("m.order_sn=".$order_sn)
                ->execute ();
        $orders = $query->fetchAll ();
        if (empty($orders))
        {
            /* 否则就使用这个订单号 */
            return $order_sn;
        }
        /* 如果有重复的，则重新生成 */
        return $this->_travel_agency_order_sn();
    }



    /**
     * 旅行社认证
     * @Route("apiTravelAgencyAuthentication",name="apiTravelAgencyAuthentication_")
     */
    public function apiTravelAgencyAuthentication()
    {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = isset($_POST)?$_POST:'';
        $par =array('agency_id','member_id','agency_name','agency_image','agency_tel','agency_address','trading_license','business_license','business_license_num','trading_license_num','business_license_limit','trading_license_limit','Legal_representative');
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            $agency_id =$data['agency_id'];
            $member_id =$data['member_id'];
            $agency_name =$data['agency_name'];
            $agency_image =$data['agency_image'];
            $agency_tel =$data['agency_tel'];
            $agency_address =$data['agency_address'];
            $business_license =$data['business_license'];
            $trading_license =$data['trading_license'];
            $business_license_num =$data['business_license_num'];
            $trading_license_num =$data['trading_license_num'];
            $business_license_limit =$data['business_license_limit'];
            $trading_license_limit =$data['trading_license_limit'];
            $Legal_representative =$data['Legal_representative'];
            $upb = $conn->createQueryBuilder ()
                        ->update ( 'msk_travel_agency', 'm' )
                        ->set ('m.agency_name',"'$agency_name'")
                        ->set ('m.agency_image',"'$agency_image'" )
                        ->set ('m.agency_address',"'$agency_address'" )
                        ->set ('m.agency_tel',"'$agency_tel'")
                        ->set ('m.state',1)
                        ->where ( "m.agency_id =$agency_id" )
                        ->andWhere( "m.member_id =$member_id" )
                        ->execute ();
            if(is_numeric($upb)){
                //更新认证表
                $upb1=$conn->createQueryBuilder ()
                        ->update ( 'msk_travel_agency_certification', 'm' )
                        ->set ('m.business_license',"'$business_license'" )
                        ->set ('m.trading_license',"'$trading_license'")
                        ->set ('m.business_license_num',"'$business_license_num'")
                        ->set ('m.trading_license_num',"'$trading_license_num'")
                        ->set ('m.business_license_limit',"'$business_license_limit'")
                        ->set ('m.trading_license_limit',"'$trading_license_limit'")
                        ->set ('m.Legal_representative',"'$Legal_representative'")
                        ->where ( "m.agency_id =$agency_id" )
                        ->execute ();
                if(is_numeric($upb1)){
                   return new JsonResponse(array('code'=>200,'msg'=>'认证申请成功','result'=>''));
                }else{
                    return new JsonResponse(array('code'=>300,'msg'=>'认证申请失败','result'=>''));
                }
                
            }
        }else{
            
            return new JsonResponse(array('code'=>300,'msg'=>'系统参数错误','result'=>''));
        }
        return new JsonResponse($message);
            
    }

    /**
     * 经办人认证
     * @Route("apiTravelAgencyLeadAuthentication",name="apiTravelAgencyLeadAuthentication")
     */
    public function apiTravelAgencyLeadAuthentication()
    {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = isset($_POST)?$_POST:'';
        $par =array('agency_id','member_id','manage_name','manage_identity_card','manage_phone','positive_identity','opposite_identity','handheld_identity');
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            $agency_id =$data['agency_id'];
            $member_id =$data['member_id'];
            $manage_name =$data['manage_name'];
            $manage_identity_card =$data['manage_identity_card'];
            $manage_phone =$data['manage_phone'];
            $positive_identity =$data['positive_identity'];
            $opposite_identity =$data['opposite_identity'];
            $handheld_identity =$data['handheld_identity'];
            $upb = $conn->createQueryBuilder ()
                        ->update ( 'msk_travel_agency', 'm' )
                        ->set ('m.manage_name',"'$manage_name'")
                        ->set ('m.manage_identity_card',"'$manage_identity_card'" )
                        ->set ('m.manage_phone',"'$manage_phone'" )
                        ->set ('m.state',1)
                        ->where ( "m.agency_id =$agency_id" )
                        ->andWhere( "m.member_id =$member_id" )
                        ->execute ();
            if(is_numeric($upb)){
                //更新认证表
                $upb1=$conn->createQueryBuilder ()
                        ->update ( 'msk_travel_agency_certification', 'm' )
                        ->set ('m.positive_identity',"'$positive_identity'")
                        ->set ('m.opposite_identity',"'$opposite_identity'")
                        ->set ('m.handheld_identity',"'$handheld_identity'")
                        ->where ( "m.agency_id =$agency_id" )
                        ->execute ();
                if(is_numeric($upb1)){
                    return new JsonResponse(array('code'=>200,'msg'=>'经办人认证成功','result'=>''));
                }else{
                    return new JsonResponse(array('code'=>300,'msg'=>'经办人认证失败','result'=>''));
                }
                
            }
        }else{
           return new JsonResponse(array('code'=>300,'msg'=>'系统参数错误','result'=>''));
        }
        return new JsonResponse($message);
            
    }





    /**
     * 旅行社认证状态
     * @Route("/apiTravelAgencyState", name="apiTravelAgencyState_")
     */
    public function apiTravelAgencyState(Request $request)
    {
        $member_id =isset($_GET['member_id'])?$_GET['member_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                ->select(
                                    'p.state','p.agency_id'
                                    )
                                ->from('msk_travel_agency', 'p') 
                                ->where("p.member_id=$member_id")
                                ->execute()
                                ->fetch();
        //0未认证 1认证中 2认证通过 3认证失败 4注销 5禁用 6未交保证金'
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
     * 添加团游时判断该队员是否已经存在
     * @Route("/apiTourIsMember", name="apiTourIsMember_")
     */
    public function apiTourIsMember(Request $request)
    {
        $account =isset($_POST['phone'])?$_POST['phone']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                 ->select(
                                     'p.id,p.true_name,p.avatar,k.nickname'
                                    )
                                ->from('msk_member', 'p')
                                ->leftjoin('p','msk_member_info','k','p.id = k.member_id')
                                ->where("p.account=$account") 
                                ->execute()
                                ->fetch();
        if(!empty($data)){
             $data['avatar_url'] = $this->getParameter('app_qiniu_imgurl').$data['avatar'];
            return new JsonResponse(array('code'=>200,'msg'=>'账号已经注册','result'=>$data));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'该账号未注册!','result'=>''));
        }         
  
    }


    /**
     * 缴纳保证金
     * @Route("/apiTravelAgencyPayDeposit",name="apiTravelAgencyPayDeposit")
     */
    public function apiTravelAgencyPayDepositAction(Request $request){
        
            $token = $request->get('token');
            $member_id = $this->validationToken($token);
            if(is_array($member_id)) return new JsonResponse($member_id);
            $manager = $this->getDoctrine ()->getManager ();
            $conn = $manager->getConnection();
            $apply=$conn ->createQueryBuilder()
                ->select('*')
                ->from('msk_travel_agency')
                ->where('member_id='.$member_id)
                ->execute()->fetch();
            if ($apply){
                if ($apply['state']==0) return new JsonResponse(array('code'=>300,'msg'=>'该旅行社未认证,请认证通过再交款!','result'=>''));
                if ($apply['state']==1) return new JsonResponse(array('code'=>300,'msg'=>'该旅行社认证中,请认证通过再交款!','result'=>''));
                if ($apply['state']==3) return new JsonResponse(array('code'=>300,'msg'=>'该旅行社认证失败,请认证通过再交款!','result'=>''));
                if ($apply['state']==4) return new JsonResponse(array('code'=>300,'msg'=>'该旅行社已注销,请联系客服!','result'=>''));
                if ($apply['state']==5) return new JsonResponse(array('code'=>300,'msg'=>'该旅行社已禁用,请联系客服!','result'=>''));
                if ($apply['state']==6) return new JsonResponse(array('code'=>300,'msg'=>'该旅行社已交保证金，请不要重复交款!','result'=>''));
            }
            $agency_id = $apply['agency_id'];
            $data=$_POST;
            $sign=array_pop($data);
            array_pop($data);
            $data=self::argSort($data);
            $str=self::createLinkstring($data); 
            $a=self::rsaVerify($str,$sign); 
            $order_sn =trim($_POST['out_trade_no']); //商户订单号
            $pay_sn=trim($data['trade_no']);
            $pay_time=isset($_POST['gmt_payment'])?strtotime($_POST['gmt_payment']):0;
            $certify=$conn->createQueryBuilder ()
                    ->update ( 'msk_travel_agency_certification', 'm' )
                    ->set ('m.pay_sn',"'$pay_sn'" )
                    ->set ('m.pay_time',"$pay_time" )
                    ->where ( "m.agency_id =$agency_id" )
                    ->execute ();
            if(!empty($certify)){
                $agency=$conn->createQueryBuilder ()
                    ->update ( 'msk_travel_agency', 'm' )
                    ->set ('m.state',"6" )
                    ->where ( "m.agency_id =$agency_id" )
                    ->execute ();
                if(!empty($agency)){
                       $data=array(
                            'agency_id' => $agency_id,
                            'action_user' => $member_id,
                            'pay_status' => 1,
                            'action_note' => '',
                            'log_time' => time(),
                            'status_desc' => '付款成功'
                        );
                       $conn->insert('msk_agency_deposit_action',$data);
                     return new JsonResponse(array('code'=>200,'msg'=>'缴纳保证金成功','result'=>''));
                }else{
                    return new JsonResponse(array('code'=>300,'msg'=>'缴纳保证金失败','result'=>''));
                }
            }
            
    }

    /**
     * 团长管理列表
     * @Route("/apiChiefTourList", name="apiChiefTourList_")
     */
    public function apiChiefTourList(Request $request)
    {
       
        $chief_id = $request->get('chief_id',0);
        $now = date("Y-m-d");
        $type = $request->get('type',0);//type：0当前出团、1待出团、2历史出团
        $conn = $this->getDoctrine()->getManager()->getConnection();
        switch ($type) {
            case '0':
                    $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id', 'p.agency_id', 'p.tour_title', 'p.imgurl','p.period','a.adult_price ','a.child_price','a.addtime','a.chief_id','a.calendar_id','a.group_name','a.chat_room','a.the_date'
                                    )
                                ->from('msk_tour_calendar', 'a')
                                ->leftJoin('a','msk_tour','p','a.tour_id = p.tour_id')
                                ->where("a.chief_id=$chief_id")
                                ->andWhere("a.state=2")
                                ->andWhere("a.the_date >=".$now)
                                ->orderBy("a.the_date",'ASC')
                                ->execute()
                                ->fetch();
                    if(!empty($data)){
                           $data['imgurl'] =$this->getParameter("app_qiniu_imgurl").$data['imgurl'];
                           $calendar_id = $data['calendar_id'];
                           $data['enroll'] = $conn->createQueryBuilder()
                                         ->select(
                                            'p.*'
                                            )
                                        ->from('msk_tour_order_goods', 'p')
                                        ->where("p.calendar_id=$calendar_id")
                                        ->andWhere("p.state !=0 and p.state !=2")
                                        ->execute()
                                        ->fetchAll();
                    }
                break;
            case '1':
                    $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id', 'p.agency_id', 'p.tour_title', 'p.imgurl','p.period','a.adult_price ','a.child_price','a.addtime','a.chief_id','a.calendar_id','a.group_name','a.chat_room','a.the_date'
                                    )
                                ->from('msk_tour_calendar', 'a')
                                ->leftJoin('a','msk_tour','p','a.tour_id = p.tour_id')
                                ->where("a.chief_id=$chief_id")
                                ->andWhere("a.state=1")
                                ->orderBy("a.the_date",'ASC')
                                ->execute()
                                ->fetchAll();
                    foreach ($data as $key => $value) {
                        $calendar_id = $value['calendar_id'];
                        $data[$key]['imgurl'] =$this->getParameter("app_qiniu_imgurl").$value['imgurl'];
                        $data[$key]['enroll'] = $conn->createQueryBuilder()
                                     ->select(
                                        'p.*'
                                        )
                                    ->from('msk_tour_order_goods', 'p')
                                    ->where("p.calendar_id=$calendar_id")
                                    ->andWhere("p.state !=0 and p.state !=2")
                                    ->execute()
                                    ->fetchAll();
                    }
                    
                break;
            case '2':
                    $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id', 'p.agency_id', 'p.tour_title', 'p.imgurl','p.period','a.adult_price ','a.child_price','a.addtime','a.chief_id','a.calendar_id','a.group_name','a.chat_room','a.the_date'
                                    )
                                ->from('msk_tour_calendar', 'a')
                                ->leftJoin('a','msk_tour','p','a.tour_id = p.tour_id')
                                ->where("a.chief_id=$chief_id")
                                ->andWhere("a.state=3")
                                ->execute()
                                ->fetchAll();
                    foreach ($data as $key => $value) {
                        $data[$key]['imgurl'] =$this->getParameter("app_qiniu_imgurl").$value['imgurl'];   
                    }
                break;
            
            default:
                return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
                break;
        }
        if(!empty($data)){
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }       
  
    }


    /**
     * 编辑团游
     * @Route("apiTourInfoEdit",name="apiTourInfoEdit")
     */
    public function apiTourInfoEdit() {
        
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = isset($_POST)?$_POST:'';
        $par =array('tour_id','imgurl','tour_title','period','starting_time','chief_id','starting_place','service_price','booking_notice','detail','planned','end_time','telphone','default_adult_price','default_child_price','destination','proxy_id','trip');
        $detailarr=json_decode($data['detail'],true);
        $triparr=json_decode($data['trip'],true);
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
            $a=$manager->getConnection()->createQueryBuilder ()
                ->select("*")
                ->from('msk_tour')
                ->where('tour_id='.$data['tour_id'])
                ->andWhere('chief_id='.$data['chief_id'])
                ->orWhere('proxy_id='.$data['proxy_id'])
                ->andWhere('state != 0 and state != 3')
                ->execute()->fetch();
            if (!empty($a)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限操作','result'=>''));
            $tour_id =$data['tour_id'];
            $chief_id =$data['chief_id'];
            $imgurl =$data['imgurl'];
            $tour_title =$data['tour_title'];
            $period =$data['period'];
            $starting_time =$data['starting_time'];
            $starting_place =$data['starting_place'];
            $service_price =$data['service_price'];
            $booking_notice =$data['booking_notice'];
            $planned =$data['planned'];
            $end_time =$data['end_time'];
            $telphone =$data['telphone'];
            $default_adult_price =$data['default_adult_price'];
            $default_child_price =$data['default_child_price'];
            $destination =$data['destination'];
            $upb =  $conn->createQueryBuilder ()
            ->update ( 'msk_tour', 'm' )
            ->set ( 'm.imgurl', "'$imgurl'" )
            ->set ( 'm.tour_title', "'$tour_title'" )
            ->set ( 'm.period', $period )
            ->set ( 'm.starting_time', $starting_time )
            ->set ( 'm.starting_place', "'$starting_place'" )
            ->set ( 'm.service_price', $service_price )
            ->set ( 'm.booking_notice', "'$booking_notice'" )
            ->set ( 'm.planned', $planned )
            ->set ('m.end_time',$end_time)
            ->set ('m.default_adult_price',$default_adult_price)
            ->set ('m.default_child_price',$default_child_price)
            ->set ('m.destination',"'$destination'")
            ->set ('m.telphone',$telphone)
            ->where ( "m.tour_id =$tour_id" )
            ->andWhere ( "m.chief_id =$chief_id" )
            ->execute (); 
            if($upb){
                //路线
                if(is_array($detailarr)){
                    foreach ($detailarr as $key => $value) {
                        $latitude = $value['latitude'];
                        $longitude = $value['longitude'];
                        $site = $value['site'];
                        $state =isset($value['state'])?$value['state']:1;
                        //循环插入旅游详情的值
                        $Detail  = new TourDetail();
                        $Detail->setTourId($tour_id);
                        $Detail->setLongitude($longitude);
                        $Detail->setLatitude($latitude);
                        $Detail->setSite($site);
                        $manager->persist($Detail);
                        $manager->flush();
                        
                    }
                }
                //旅程
                if(is_array($triparr)){
                    foreach ($triparr as $key => $value) {
                        $trip_id =$value['trip_id'];
                        $title = $value['title'];
                        $latitude = $value['latitude'];
                        $longitude = $value['longitude'];
                        $num = $value['num'];
                        $state =isset($value['state'])?$value['state']:1;
                        if($trip_id ==0){
                                //循环插入旅游详情的值
                                $Trip  = new TourTrip();
                                $Trip->setTourId($tour_id);
                                $Trip->setTitle($title);
                                $Trip->setLongitude($longitude);
                                $Trip->setLatitude($latitude);
                                $Trip->setNum($num);
                                $manager->persist($Trip);
                                $manager->flush();
                        }else{
                            //循环修改旅游详情的值
                            $upb =  $conn->createQueryBuilder ()
                                ->update ( 'msk_tour_trip', 'm' )
                                ->set ('m.title',$title)
                                ->set ('m.longitude',$longitude)
                                ->set ('m.latitude',$latitude)
                                ->set ('m.num',$num)
                                ->where ( "m.tour_id =$tour_id" )
                                ->andWhere ( "m.trip_id =$trip_id" )
                                ->execute (); 
                        }
                        
                    }
                }
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] ="Edit Success!";
            }else{
                $message['status'] = 0;
                $message['error'] = 1;
                $message['message'] = 'Edit Error!';
            }
          

        }else{
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        }
        return new JsonResponse($message);
    }

    /**
     * 扫描二维码加入与取消界面
     * @Route("/apiTourScanCodeDetail", name="apiTourScanCodeDetail_")
     * 
     */
    public function apiTourScanCodeDetail(Request $request)
    {
        $calendar_id = $request->get('calendar_id',0);
        $type = $request->get('type',0);//0加入界面，1取消界面
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $query = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id', 'p.agency_id','p.tour_title', 'p.imgurl','p.starting_place','p.service_price','p.entered','p.agency_name','p.route_id','p.starting_time','m.the_date as end_time','m.addtime','m.adult_price ','m.child_price','m.chief_id,m.chief_name,m.chief_avatar','m.group_name','m.chat_room'
                                    )
                                ->from('msk_tour_calendar','m')
                                ->leftJoin('m','msk_tour', 'p','p.tour_id=m.tour_id')
                                ->where("m.calendar_id=$calendar_id")
                                ->execute();
        $data = $query->fetch();
        if(!empty($data)){
            if($type ==0){
                $cancel = $conn->createQueryBuilder()
                                ->select("count(a.rec_id) as cancel,a.order_sn")
                                ->from('msk_tour_order_goods', 'a')
                                ->where("a.calendar_id=$calendar_id")
                                ->andWhere("a.state =4")
                                ->execute()
                                ->fetch();
                $data['cancel'] = $cancel['cancel'];
                $data['order_sn'] = $cancel['order_sn'];
                $data['uncancel'] =$data['entered']-$cancel['cancel'];
                $data['points'] ="团员扫码后即成功提前退团";
            }else{
                
                $confirm = $conn->createQueryBuilder()
                                ->select("count(a.rec_id) as confirm,a.order_sn")
                                ->from('msk_tour_order_goods', 'a')
                                ->where("a.calendar_id=$calendar_id")
                                ->andWhere("a.state =3")
                                ->execute()
                                ->fetch();
                $data['order_sn'] = $confirm['order_sn'];
                $data['confirm'] = $confirm['confirm'];
                $data['unconfirm'] =$data['entered']-$data['confirm'];
                $data['points'] ="团员扫码后即报名成功,可实时共享位置";
            }
            //unset($data['planned']);
      
            $massage['status'] = '1';
            $massage['error'] = '0';
            $massage['massage'] = $data;
            return new JsonResponse($massage);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = '暂无数据!';
            return new JsonResponse($massage);
        }       
  
    }

        /**
     * 已经缴费团游成员取消
     * @Route("apiTourOrderManCancelEnroll",name="apiTourOrderManCancelEnroll_")
     */
    public function apiTourOrderManCancelEnroll(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $tour_id = $request->get('tour_id',0);
        $order_sn = $request->get('order_sn');
        $member_id = $request->get('member_id',0);
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("rec_id,order_sn,member_id,avatar,username,state,calendar_id")
            ->from('msk_tour_order_goods')
            ->where("order_sn=".$order_sn)
            ->andWhere("member_id=$member_id")
            //->andWhere('state = 1')
            ->execute()->fetch();
        if (empty($a)) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'订单不存在'));
        if ($a['state'] ==4) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'您已经取消过报道了！'));
        if ($a['state'] !=3) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'您还未报道！'));
        $rec_id = $a['rec_id'];
        //更新表
        $upb=$conn->createQueryBuilder ()
                ->update ( 'msk_tour_enroll', 'm' )
                ->set ('m.state',"0")
                ->where("order_sn=".$order_sn)
                ->andWhere("rec_id=$rec_id")
                ->andWhere("member_id=$member_id")
                ->execute ();
        if($upb){
            //更新订单货物表
            $upb1=$conn->createQueryBuilder ()
                    ->update ( 'msk_tour_order_goods', 'm' )
                    ->set ('m.state',"4")
                    ->set ('m.unreport_time',time())
                    ->where("order_sn=".$order_sn)
                    ->andWhere("member_id=$member_id")
                    ->execute ();
            //退出融云的群组
            $tokenRes = $this->curl('/group/quit', array('userId' => $member_id, 'groupId' => $a['calendar_id']));
             //聊天室封禁,时长43200分钟
            $tokenRes = $this->curl('/chatroom/user/block/add', array('userId' => $member_id, 'chatroomId' => $a['calendar_id'], 'minute'=>'43200'));
            //$tokenResArr = json_decode($tokenRes, true);
            return new JsonResponse(array('code'=>200,'msg'=>'取消成功','result'=>''));
        }else {
            return new JsonResponse(array('code'=>300,'msg'=>'取消失败','result'=>''));
        }
            
   }

   /**
     * 已经缴费团游成员进行报道
     * @Route("apiTourOrderManEnroll",name="apiTourOrderManEnroll_")
     */
   
    public function apiTourOrderManEnroll(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $tour_id = $request->get('tour_id',0);
        $order_sn = $request->get('order_sn');
        $member_id = $request->get('member_id',0);
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("rec_id,order_sn,member_id,avatar,username,state,calendar_id")
            ->from('msk_tour_order_goods')
            ->where("order_sn=".$order_sn)
            ->andWhere("member_id=$member_id")
            //->andWhere('state = 1')
            ->execute()->fetch();
        if (empty($a)) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'订单不存在'));
        if ($a['state'] ==0) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'您未付款该订单,付款后才能报到！'));
        if ($a['state'] ==2) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'该订单已经取消,请重新下单！'));
        if ($a['state'] ==3) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'您已经报道过了,不用再次报道！'));
        //$data['geohash'] =$this->addGeohash($data['longitude'],$data['latitude']);
        //添加
        $enroll  = new TourEnroll();
        $enroll->setOrderSn($order_sn);
        $enroll->setTourId($tour_id);
        $enroll->setRecId($a['rec_id']);
        $enroll->setCalendarId($a['calendar_id']);
        $enroll->setAvatar($a['avatar']);
        $enroll->setUsername($a['username']);
        $enroll->setMemberId($member_id );
        $enroll->setLongitude(0);
        $enroll->setLatitude(0);
        $enroll->setGeohash(0);
        $enroll->setAddTime(time());
        $manager->persist($enroll);
        $manager->flush();
        $bool  =$enroll->getEnrollId();
        
        if($bool){
            //更新订单货物表
            $upb1=$conn->createQueryBuilder ()
                    ->update ( 'msk_tour_order_goods', 'm' )
                    ->set ('m.state',"3")
                    ->set ('m.report_time',time())
                    ->where("order_sn=".$order_sn)
                    ->andWhere("member_id=$member_id")
                    ->execute ();
            //加入融云的群组
            $tokenRes = $this->curl('/group/join', array('userId' => $member_id, 'groupId' => $a['calendar_id'], 'groupName' => md5($a['calendar_id'])));
            $tokenRes = $this->curl('/chatroom/join', array('userId' => $member_id, 'chatroomId' => $a['calendar_id']));
            $tokenResArr = json_decode($tokenRes, true);
             return new JsonResponse(array('code'=>200,'msg'=>'报道成功','result'=>''));
        }else {
            return new JsonResponse(array('code'=>300,'msg'=>'报道失败','result'=>''));
        }
          
   }

    /**
     * 筛选路线列表
     * @Route("/apiSearchTourRoute", name="apiSearchTourRoute_")
     */
    public function apiSearchTourRouteAction(Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();
        $app_qiniu_imgurl = $this->getParameter('app_qiniu_imgurl');
        //         $startPage = ($page - 1) * $pageSize;
        $page =isset($_POST['page'])?$_POST['page']:0;
        $destination =isset($_POST['destination'])?$_POST['destination']:'';
        $period =isset($_POST['period'])?$_POST['period']:'';//期数
        $lowprice =isset($_POST['lowprice'])?$_POST['lowprice']:0;//最低价格
        $highprice =isset($_POST['highprice'])?$_POST['highprice']:10000000;//最高价格
        $priceRule =isset($_POST['priceRule'])?$_POST['priceRule']:0;//价格排序
        $orderlist = "p.state = 1 ";
        // $orderlist = "a.the_date =".$now.' ';
        if($destination !==""){
            $orderlist.= 'and p.destination like '.'"%'.$destination.'%"'.' ';
        }
        if(trim($period) !== '')
        {   
            $orderlist.= 'and p.period='.$period.' ';
        }
        $orderlist.= 'and p.default_adult_price>='.$lowprice.' and p.default_adult_price<='.$highprice;

        if($priceRule == 0){
           $orderRule = "p.default_adult_price, 'DESC'"; 
        }else{
            $orderRule = "p.default_adult_price, 'ASC'";
        }
        
        $conn = $em->getConnection();
        $data = $conn->createQueryBuilder()
        ->select(
                'p.route_id', 'p.agency_id', 'p.tour_title', 'p.proxy_id'
                // 'a.the_date','a.adult_price'
                //,'p.check_in_time'
                )
                ->from('msk_tour_route', 'p')
                // ->leftjoin('p', 'msk_tour_calendar', 'a', 'a.tour_id = p.tour_id')
                ->where($orderlist)
                ->orderBy($orderRule)  
                ->setFirstResult($page)
                ->setMaxResults(10) 
                ->execute()
                ->fetchAll();  
        foreach ($data as $key => $value) {
            $route_id=$data[$key]['route_id'];
            //查询是否有导游设置的根据日期改变的价格，有的话就替换adult_price价格
            $now =strtotime(date('Y-m-d 23:59:59',time()));//今天23:59:59秒时间戳
            $price = $conn->createQueryBuilder()
                                ->select('a.adult_price')
                                ->from('msk_tour_route_calendar', 'a')
                                ->where("a.route_id=$route_id")
                                ->andWhere("a.the_date =$now")
                                ->execute()
                                ->fetch();
            if($price){
                $data[$key]['adult_price'] = $price['adult_price'];
            }
            
           
         } 
        if(!empty($data)){
            return new JsonResponse($data);
        }else{
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found tour!';
            return new JsonResponse($massage);
        }
  
    }

    /**
     * 置顶路线
     * @Route("apiTourRouteFirst",name="apiTourRouteFirst_")
     */
    public function apiTourRouteFirst(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $agency_id = $request->get('agency_id',0);
        $route_id = $request->get('route_id',0);
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_tour_route')
            ->where("route_id=$route_id")
            ->andWhere("agency_id=$agency_id")
            ->andWhere('state != 0')
            ->execute()->fetch();
        if (empty($a)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限操作或路线已删除','result'=>''));
        $conn = $manager->getConnection();
        //把该旅行射的路线取消置顶
        $conn->createQueryBuilder ()
            ->update ( 'msk_tour_route', 'm' )
            ->set ('m.sort',"0")
            ->where ( "m.agency_id =$agency_id" )
            ->execute ();
        //置顶
        $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_tour_route', 'm' )
            ->set ('m.sort',"1")
            ->where ( "m.route_id =$route_id" )
            ->andWhere("agency_id=$agency_id")
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
       
        return new JsonResponse($message);
            
  }

    /**
     * 删除路线
     * @Route("apiTourRouteDel",name="apiTourRouteDel_")
     */
    public function apiTourRouteDel(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $agency_id = $request->get('agency_id',0);
        $route_id = $request->get('route_id',0);
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_tour_route')
            ->where("route_id=$route_id")
            ->andWhere("agency_id=$agency_id")
            ->andWhere('state != 0')
            ->execute()->fetch();
        if (!empty($a)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限操作或路线已删除','result'=>''));
        $conn = $manager->getConnection();
        //删除
        $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_travel_agency', 'm' )
            ->set ('m.state',"0")
            ->where ( "m.route_id =$route_id" )
            ->andWhere("agency_id=$agency_id")
            ->execute ();
        if($upb){
            $message['status'] = 1;
            $message['error'] = 0;
            $message['msg'] ="del Success!";
        }else{
        
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] ="del Error!";
        }    
        
        return new JsonResponse($message);
            
   }

   
    /**
     * 旅行团经办人设置代理
     * @Route("apiTravelAgencyProxySet",name="apiTravelAgencyProxySet_")
     */
    public function apiTravelAgencyProxySet(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $agency_id = $request->get('agency_id',0);
        $member_id = $request->get('member_id',0);
        $proxy_name = $request->get('proxy_name');
        $proxy_image = $request->get('proxy_image');
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_travel_agency')
            ->where("agency_id=$agency_id")//客户端先判断后得到的id
            ->andWhere("member_id=$member_id")
            ->execute()->fetch();
        if (empty($a)) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'未找到匹配的旅行社'));
        if ($a['state'] ==0) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'您的旅行团还未认证!'));
        if ($a['state'] ==1) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'您的旅行团尚在认真中!'));
        if ($a['state'] ==2) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'您的旅行团已经认证通过、但是未交保证金!'));
        if ($a['state'] ==3) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'您的旅行团认证失败失败,请重新认证!'));
        if ($a['state'] ==4) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'您的旅行团账号已经被注销，如有疑问可联系客服!'));
        if ($a['state'] ==5) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'您的旅行团账号已经禁用，如有疑问可联系客服!'));
        $b=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_travel_agency_proxy')
            ->where("member_id=$member_id")//客户端先判断后得到的id
            ->andWhere("agency_id=$agency_id")
            ->execute()->fetch();
        $conn = $manager->getConnection();
        if (empty($b)){
            $data=array(
                'agency_id' => $agency_id,
                'member_id' => $member_id,
                'proxy_name' => $proxy_name,
                'proxy_image' => $proxy_image,
                'add_time' => time()
            );
            $upb=$conn->insert('msk_travel_agency_proxy',$data);
        }elseif($b['state']==0) {
            $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_travel_agency_proxy', 'm' )
            ->set ('m.state',"1")
            ->where ( "m.member_id =$member_id" )
            ->andWhere("agency_id=$agency_id")
            ->execute ();
        }else{
            return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'该用户已经添加!','result'=>''));
        }
        //加1
        $conn->createQueryBuilder ()
             ->update ( 'msk_travel_agency', 'm' )
             ->set ( 'm.proxy_num', "m.proxy_num+1" )
             ->andWhere ( "m.agency_id =$agency_id" )
             ->execute (); 
        if($upb){
             return new JsonResponse(array('code'=>200,'msg'=>'添加成功','result'=>''));
        }else {
                return new JsonResponse(array('code'=>300,'msg'=>'添加失败','result'=>''));
        }
        return new JsonResponse($message);
             
  }
  
  /**
     * 旅行团经办人删除代理
     * @Route("apiTravelAgencyProxyDel",name="apiTravelAgencyProxyDel_")
     */
    public function apiTravelAgencyProxyDel(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $agency_id = $request->get('agency_id',0);
        $proxy_id = $request->get('proxy_id',0);
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_travel_agency_proxy')
            ->where("proxy_id=$proxy_id")
            ->andWhere("agency_id=$agency_id")
            ->andWhere('state != 0')
            ->execute()->fetch();
        if (!empty($a)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限操作或代理已删除','result'=>''));
        $conn = $manager->getConnection();
        //删除
        $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_travel_agency_proxy', 'm' )
            ->set ('m.state',"0")
            ->where ( "m.proxy_id =$proxy_id" )
            ->andWhere("agency_id=$agency_id")
            ->execute ();
        //加1
        $conn->createQueryBuilder ()
             ->update ( 'msk_travel_agency', 'm' )
             ->set ( 'm.proxy_num', "m.proxy_num-1" )
             ->andWhere ( "m.agency_id =$agency_id" )
             ->execute (); 
        if($upb){
            $message['status'] = 1;
            $message['error'] = 0;
            $message['msg'] ="del Success!";
        }else{
        
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] ="del Error!";
        }    
        
        return new JsonResponse($message);
            
   }

   /**
     * 某一团长更改价格日期
     * @Route("apiChiefUpdCalendar",name="apiChiefUpdCalendar_")
     */
    public function apiChiefUpdCalendar(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $chief_id = $request->get('chief_id',0);
        $tour_id = $request->get('tour_id',0);
        $the_date = $request->get('the_date');
        $adult_price = $request->get('adult_price');
        // echo $the_date;die;
        //$the_date = "1478361599";
        if (empty($the_date)||!preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/",$the_date)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'设置的日期格式错误'));
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_tour')
            ->where("chief_id=$chief_id")
            ->andWhere("tour_id=$tour_id")
            ->execute()->fetch();
        if (empty($a)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'您不是该团游的团长！'));
        $b=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_tour_calendar')
            ->where("tour_id=$tour_id")
            ->andWhere("the_date ='$the_date'")
            ->execute()->fetch();
        $conn = $manager->getConnection();
        if (empty($b)){
            $data=array(
                'tour_id' => $tour_id,
                'the_date' => $the_date,
                'adult_price' => $adult_price
            );
            $upb=$conn->insert('msk_tour_calendar',$data);
        }else{
            $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_tour_calendar', 'm' )
            ->set ('m.adult_price', $adult_price )
            ->where ( "m.tour_id =$tour_id" )
            ->andWhere("m.the_date ='$the_date'")
            ->execute ();
        }
        if($upb){
            $message['status'] = 1;
            $message['error'] = 0;
            $message['msg'] ="Success!";
        }else{
        
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] ="Error!";
        }    
        
        return new JsonResponse($message);
        
            
  }

  /**
     * 旅行团经办人设置团长
     * @Route("apiTravelAgencyChiefSet",name="apiTravelAgencyChiefSet_")
     */
    public function apiTravelAgencyChiefSet(Request $request) 
    {
        
        $manager = $this->getDoctrine()->getManager();
        $agency_id = $request->get('agency_id',0);
        $member_id = $request->get('member_id',0);
        $chief_name = $request->get('chief_name');
        $chief_image = $request->get('chief_image');
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_member')
            ->where("id=$member_id")//客户端先判断后得到的id
            // ->andWhere("phone=$phone")
            ->andWhere("member_state=1")
            ->execute()->fetch();
        if (empty($a)) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'未找到匹配的用户'));
        $b=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_chief')
            ->where("member_id=$member_id")//客户端先判断后得到的id
            ->andWhere("agency_id=$agency_id")
            ->execute()->fetch();
        $conn = $manager->getConnection();
        if (empty($b)){
            $data=array(
                'agency_id' => $agency_id,
                'member_id' => $member_id,
                'chief_name' => $chief_name,
                'chief_image' => $chief_image,
                'add_time' => time()
            );
            $upb=$conn->insert('msk_chief',$data);
        }elseif($b['state']==0) {
            $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_chief', 'm' )
            ->set ('m.state',"1")
            ->where ( "m.member_id =$member_id" )
            ->andWhere("agency_id=$agency_id")
            ->execute ();
        }else{
            return new JsonResponse(array('code'=>300,'error'=>'','msg'=>'该用户已经添加!','result'=>''));
        }
        if($upb){
            return new JsonResponse(array('code'=>200,'msg'=>'添加成功','result'=>''));
        }else {
            return new JsonResponse(array('code'=>300,'msg'=>'添加失败','result'=>''));
        }
        

        
            
  }

   /**
     * 旅行团经办人删除团长
     * @Route("apiTravelAgencyChiefDel",name="apiTravelAgencyChiefDel_")
     */
    public function apiTravelAgencyChiefDel(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $agency_id = $request->get('agency_id',0);
        $chief_id = $request->get('chief_id',0);
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_chief')
            ->where("chief_id=$chief_id")
            ->andWhere("agency_id=$agency_id")
            ->andWhere('state != 0')
            ->execute()->fetch();
        if (!empty($a)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限操作或团长已删除','result'=>''));
        $conn = $manager->getConnection();
        //删除
        $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_chief', 'm' )
            ->set ('m.state',"0")
            ->where ( "m.chief_id =$chief_id" )
            ->andWhere("agency_id=$agency_id")
            ->execute ();
        if($upb){
            $message['status'] = 1;
            $message['error'] = 0;-
            $message['msg'] ="del Success!";
        }else{
        
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] ="del Error!";
        }    
        
        return new JsonResponse($message);
            
   }

   /**
     * 旅行社下的团长列表
     * @Route("/apiTravelAgencyChiefList", name="apiTravelAgencyChiefList_")
     */
    public function apiTravelAgencyChiefList(Request $request)
    {
         
        $agency_id = $request->get('agency_id',0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                     ->select(
                        'p.chief_id','p.member_id','p.chief_name ','p.chief_image','p.state','p.tour_num'
                        )
                    ->from('msk_chief', 'p')
                    ->where("p.agency_id=$agency_id")
                    ->andWhere("p.state !=0")
                    ->execute()
                    ->fetchAll();
        foreach ($data as $key => $value) {
            $data[$key]['chief_image'] = $this->getParameter('app_qiniu_imgurl').$value['chief_image'];
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
     * 旅行社下的代理列表
     * @Route("/apiTravelAgencyProxyList", name="apiTravelAgencyProxyList_")
     */
    public function apiTravelAgencyProxyList(Request $request)
    {
         
        $agency_id = $request->get('agency_id',0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                     ->select(
                        'p.proxy_id','p.member_id','p.proxy_name ','p.proxy_image','p.state','p.tour_num'
                        )
                    ->from('msk_travel_agency_proxy', 'p')
                    ->where("p.agency_id=$agency_id")
                    ->andWhere("p.state !=0")
                    ->execute()
                    ->fetchAll();
        foreach ($data as $key => $value) {
            $data[$key]['proxy_image'] = $this->getParameter('app_qiniu_imgurl').$value['proxy_image'];

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
     * 团长——团长信息
     * @Route("/apiChiefInfo", name="apiChiefInfo_")
     */
    public function apiChiefInfo(Request $request)
    {
         
        $chief_id = $request->get('chief_id',0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                     ->select(
                        'p.chief_id','p.member_id','p.chief_name ','p.chief_image','p.state'
                        )
                    ->from('msk_chief', 'p')
                    ->where("p.chief_id=$chief_id")
                    ->andWhere("p.state !=0")
                    ->execute()
                    ->fetch();
        $complete = $conn->createQueryBuilder()
                        ->select("count(a.tour_id) as complete")
                        ->from('msk_tour', 'a')
                        ->where("a.chief_id=$chief_id")
                        ->andWhere("a.state =3")
                        ->execute()
                        ->fetch();
        $data['complete'] = $complete['complete'];
        //unset($data['tour_num']);

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
     * 删除路线旅程详情
     * @Route("/apiTourRouteTripInfoDel",name="apiTourRouteTripInfoDel_")
     */
    public function apiTourRouteTripInfoDel(Request $request) {
        $manager = $this->getDoctrine ()->getManager ();
        $route_id = $request->get('route_id',0);
        $trip_id = $request->get('trip_id',0);
        // $agency_id = $request->get('agency_id',0);
        // $proxy_id = $request->get('proxy_id',0);
        $token =$request->get('token',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $tour = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_tour_route_trip', 'p')
                    ->where("p.tour_id=$tour_id")
                    ->execute()
                    ->fetchAll();
        if(empty($tour)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该数据不存在'));
        $agency_id = $tour['agency_id'];
        $proxy_id = $tour['proxy_id'];
        $agency = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_travel_agency', 'p')
                    ->where("p.agency_id=$agency_id")
                    ->andWhere("p.member_id=$member_id")
                    ->andWhere("p.state =6")
                    ->execute()
                    ->fetchAll();
        if(empty($agency)){
            $proxy = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_travel_agency_proxy', 'p')
                    ->where("p.proxy_id=$proxy_id")
                    ->andWhere("p.member_id=$member_id")
                    ->andWhere("p.state =0")
                    ->execute()
                    ->fetchAll();
            if(empty($proxy)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限删除'));
        }
        $query = $manager->createQuery(
                "delete from AcmeMinsuBundle:TourRouteTrip p where p.trip_id = :trip_id and p.route_id =:route_id"
        );
        $query->setParameter("trip_id",$trip_id);
        $query->setParameter("route_id",$route_id);
        if ($query->execute()) {
           return new JsonResponse(array('status'=>1,'error'=>0,'msg'=>'删除成功'));
        } else {
            return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'系统繁忙,请稍后再试'));
        }
        
    }

     /**
     * 删除旅程详情
     * @Route("/apiTourRouteDetailInfoDel",name="apiTourRouteDetailInfoDel_")
     */
    public function apiTourRouteDetailInfoDel(Request $request) {
        $manager = $this->getDoctrine ()->getManager ();
        $route_id = $request->get('route_id',0);
        $detail = $_POST['detail'];
        // $agency_id = $request->get('agency_id',0);
        // $proxy_id = $request->get('proxy_id',0);
        $token =$request->get('token',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $tour = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_tour_route_detail', 'p')
                    ->where("p.route_id=$route_id")
                    ->execute()
                    ->fetchAll();
        if(empty($tour)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该数据不存在'));
        $agency_id = $tour['agency_id'];
        $proxy_id = $tour['proxy_id'];
        $agency = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_travel_agency', 'p')
                    ->where("p.agency_id=$agency_id")
                    ->andWhere("p.member_id=$member_id")
                    ->andWhere("p.state =6")
                    ->execute()
                    ->fetchAll();
        if(empty($agency)){
            $proxy = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_travel_agency_proxy', 'p')
                    ->where("p.proxy_id=$proxy_id")
                    ->andWhere("p.member_id=$member_id")
                    ->andWhere("p.state =0")
                    ->execute()
                    ->fetchAll();
            if(empty($proxy)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限删除'));
        }
        $detail = json_decode($detail,true);
        if(is_array($detail)){
            foreach ($variable as $key => $value) {
              $detail_id = $value['detail_id'];
              $del=$manager->getConnection()->createQueryBuilder()
                    ->delete('msk_tour_route_detail')
                    ->where('detail_id='.$detail_id)
                    ->execute();
            }
        }
        if ($del) {
           return new JsonResponse(array('status'=>1,'error'=>0,'msg'=>'删除成功'));
        } else {
            return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'系统繁忙,请稍后再试'));
        }
        
    }


     /**
     * 删除旅程详情
     * @Route("/apiTourTripInfoDel",name="apiTourTripInfoDel_")
     */
    public function apiTourTripInfoDel(Request $request) {
        $manager = $this->getDoctrine ()->getManager ();
        $tour_id = $request->get('tour_id',0);
        $trip_id = $request->get('trip_id',0);
        // $agency_id = $request->get('agency_id',0);
        // $proxy_id = $request->get('proxy_id',0);
        $token =$request->get('token',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $tour = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_tour_trip', 'p')
                    ->where("p.tour_id=$tour_id")
                    ->execute()
                    ->fetchAll();
        if(empty($tour)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该数据不存在'));
        $agency_id = $tour['agency_id'];
        $proxy_id = $tour['proxy_id'];
        $agency = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_travel_agency', 'p')
                    ->where("p.agency_id=$agency_id")
                    ->andWhere("p.member_id=$member_id")
                    ->andWhere("p.state =6")
                    ->execute()
                    ->fetchAll();
        if(empty($agency)){
            $proxy = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_travel_agency_proxy', 'p')
                    ->where("p.proxy_id=$proxy_id")
                    ->andWhere("p.member_id=$member_id")
                    ->andWhere("p.state =0")
                    ->execute()
                    ->fetchAll();
            if(empty($proxy)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限删除'));
        }
        $query = $manager->createQuery(
                "delete from AcmeMinsuBundle:TourTrip p where p.trip_id = :trip_id and p.tour_id =:tour_id"
        );
        $query->setParameter("trip_id",$trip_id);
        $query->setParameter("tour_id",$tour_id);
        if ($query->execute()) {
           return new JsonResponse(array('status'=>1,'error'=>0,'msg'=>'删除成功'));
        } else {
            return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'系统繁忙,请稍后再试'));
        }
        
    }



    /**
     * 删除旅程详情
     * @Route("/apiTourDetailInfoDel",name="apiTourDetailInfoDel_")
     */
    public function apiTourDetailInfoDel(Request $request) {
        $manager = $this->getDoctrine ()->getManager ();
        $tour_id = $request->get('tour_id',0);
        $detail = $_POST['detail'];
        // $agency_id = $request->get('agency_id',0);
        // $proxy_id = $request->get('proxy_id',0);
        $token =$request->get('token',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $tour = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_tour_detail', 'p')
                    ->where("p.tour_id=$tour_id")
                    ->execute()
                    ->fetchAll();
        if(empty($tour)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该数据不存在'));
        $agency_id = $tour['agency_id'];
        $proxy_id = $tour['proxy_id'];
        $agency = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_travel_agency', 'p')
                    ->where("p.agency_id=$agency_id")
                    ->andWhere("p.member_id=$member_id")
                    ->andWhere("p.state =6")
                    ->execute()
                    ->fetchAll();
        if(empty($agency)){
            $proxy = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_travel_agency_proxy', 'p')
                    ->where("p.proxy_id=$proxy_id")
                    ->andWhere("p.member_id=$member_id")
                    ->andWhere("p.state =0")
                    ->execute()
                    ->fetchAll();
            if(empty($proxy)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限删除'));
        }
        $detail = json_decode($detail,true);
        if(is_array($detail)){
            foreach ($variable as $key => $value) {
              $detail_id = $value['detail_id'];
              $del=$manager->getConnection()->createQueryBuilder()
                    ->delete('msk_trour_detail')
                    ->where('detail_id='.$detail_id)
                    ->execute();
            }
        }
        if ($del) {
           return new JsonResponse(array('status'=>1,'error'=>0,'msg'=>'删除成功'));
        } else {
            return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'系统繁忙,请稍后再试'));
        }
        
    }

    /**
     * 申请退旅行社
     * @Route("/apiTravelAgencyRefund",name="apiTravelAgencyRefund")
     */
    public function apiTravelAgencyRefundAction(Request $request){
            $manager = $this->getDoctrine ()->getManager ();
            $conn = $manager->getConnection();
            $agency_id = $request->get('agency_id',0);
            $reason =$request->get('reason');
            $user_id =$request->get('user_id',0);
            $goods_return =$request->get('goods_return',1);
            $apply_price =$request->get('apply_price',0);
            $token =$request->get('token',0);
            $member_id = $this->validationToken($token);
            if(is_array($member_id)) return new JsonResponse($member_id);
            if (empty($reason)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'申请的原因不能为空!'));
            $apply=$conn ->createQueryBuilder()
                ->select('*')
                ->from('msk_travel_agency')
                ->where('agency_id='.$agency_id,'member_id='.$member_id)
                ->execute()->fetch();
            if ($apply){
                if ($apply['state']==0) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该旅行社未认证,需已交保证金通过才能注销!'));
                if ($apply['state']==1) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该旅行社认证中,需已交保证金通过才能注销!'));
                if ($apply['state']==2) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该旅行社审核通过，需已交保证金通过才能注销!'));
                if ($apply['state']==3) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该旅行社认证失败,需已交保证金通过才能注销!'));
                if ($apply['state']==4) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该旅行社已注销,不用重复注销!'));
                if ($apply['state']==5) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该旅行社已禁用,请联系客服!'));
               
            }
           
            $data=array(
                        'agency_id' => $agency_id,
                        'action_user' => $member_id,
                        'reason' => $reason,
                        'goods_return' => $goods_return,
                        'addtime' => time(),
                        'apply_price' => $apply_price
                    );
            $conn->insert('msk_travel_agency_refund',$data);
            if(!empty($s)){
                   $s=$conn->createQueryBuilder ()
                    ->update ( 'msk_travel_agency', 'm' )
                    ->set ('m.state',"6" )
                    ->where ( "m.agency_id =$agency_id" )
                    ->execute ();
                return new JsonResponse(array('status'=>1,'error'=>0,'msg'=>'申请退款成功!'));
            }else{
                return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'系统繁忙,请稍后再试!'));
            }
    }

    /**
     * 某一团游的详细信息
     * @Route("/apiTourRouteDetailInfo", name="apiTourRouteDetailInfo_")
     */
    public function apiTourRouteDetailInfo(Request $request)
    {
        
        $now = date("Y-m-d");
        $route_id = $request->get('route_id',0);
        $the_date = $request->get('the_date',$now);
        //return new JsonResponse($the_date);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data= $conn->createQueryBuilder()
                                ->select('p.*')
                                ->from('msk_tour_route', 'p')
                                ->where("p.route_id=$route_id")
                                ->andWhere("p.state =1") 
                                ->orderBy("p.sort",'DESC')
                                ->execute()
                                ->fetch();
        if(!empty($data)){
            $data['tour_imgurl'] = $this->getParameter('app_qiniu_imgurl').$data['tour_imgurl'];
            //查询是否有导游设置的根据日期改变的价格，有的话就替换adult_price价格
            $data['chief_info'] =array();
            $data['chief_info'] = $conn->createQueryBuilder()
                                ->select('a.chief_id,a.chief_name,a.chief_image,a.the_date,a.adult_price,a.adult_price')
                                ->from('msk_tour_route_calendar', 'a')
                                ->where("a.route_id=$route_id")
                                ->andWhere("a.the_date ='$now'")
                                ->execute()
                                ->fetch();
            $data['detail'] = $conn->createQueryBuilder()
                                    ->select('p.*')
                                    ->from('msk_tour_route_detail', 'p')
                                    ->where("p.route_id=$route_id") 
                                    ->orderBy("p.site",'DESC')
                                    ->execute()
                                    ->fetchAll();
            $data['trip'] = $conn->createQueryBuilder()
                                    ->select('p.*')
                                    ->from('msk_tour_route_trip', 'p')
                                    ->where("p.route_id=$route_id") 
                                    ->orderBy("p.num",'DESC')
                                    ->execute()
                                    ->fetchAll();
        
             return new JsonResponse($data);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found tour detail!';
            return new JsonResponse($massage);
        }       
  
    }

    /**
     * 某旅行社下全部价格日期展示
     * @Route("/apiAgencyCalendarList", name="apiAgencyCalendarList_")
     */
    public function apiAgencyCalendarList(Request $request)
    {
       
        $agency_id = $request->get('agency_id',0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                ->select("a.route_id,a.chief_id,a.chief_name,a.chief_image,date_format(a.the_date,'%Y-%c-%e') as the_date,a.adult_price,a.adult_price")
                                ->from('msk_tour_route_calendar', 'a')
                                ->where("a.agency_id=$agency_id") 
                                ->orderBy('a.the_date','ASC')
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
     * 添加旅行社路线下的日历
     * @Route("/apiTourRouteCalendarAdd",name="apiTourRouteCalendarAdd_")
     */
    public function apiTourRouteCalendarAdd(Request $request) {
        $manager = $this->getDoctrine ()->getManager ();
        $conn = $manager->getConnection();
        $agency_id = $request->get('agency_id',0);
        $route_id = $request->get('route_id',0);
        $chief_id = $request->get('chief_id',0);
        $chief_name = $request->get('chief_name');
        $chief_image = $request->get('chief_image');
        $the_date = $request->get('the_date');
        $adult_price = $request->get('adult_price',0);
        $token =$request->get('token',0);
        if (empty($the_date)||!preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/",$the_date)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'设置的日期格式错误'));
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $tour = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_tour_route_calendar', 'p')
                    ->where("p.route_id=$route_id")
                    ->andWhere("p.the_date ='$the_date'")
                    ->execute()
                    ->fetchAll();
        if(!empty($tour)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该数据已经存在,请直接修改数据即可'));
        $agency = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_travel_agency', 'p')
                    ->where("p.agency_id=$agency_id")
                    ->andWhere("p.member_id=$member_id")
                    ->andWhere("p.state =6")
                    ->execute()
                    ->fetchAll();
        if(empty($agency)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限添加'));
        $data=array(
                    'agency_id' => $agency_id,
                    'route_id' => $route_id,
                    'chief_id' => $chief_id,
                    'chief_name' => $chief_name,
                    'chief_image' => $chief_image,
                    'the_date' => $the_date,
                    'adult_price' => $adult_price,
                    'child_price' => 0
                );
        $bool=$conn->insert('msk_tour_route_calendar',$data);
        if ($bool) {
           return new JsonResponse(array('status'=>1,'error'=>0,'msg'=>'添加成功'));
        } else {
            return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'系统繁忙,请稍后再试'));
        }
        
    }

    /**
     * 修改旅行社路线下的日历
     * @Route("/apiTourRouteCalendarUpd",name="apiTourRouteCalendarUpd_")
     */
    public function apiTourRouteCalendarUpd(Request $request) {
        $manager = $this->getDoctrine ()->getManager ();
        $calendar_id = $request->get('calendar_id',0);
        $agency_id = $request->get('agency_id',0);
        $route_id = $request->get('route_id',0);
        $chief_id = $request->get('chief_id',0);
        $chief_name = $request->get('chief_name');
        $chief_image = $request->get('chief_image');
        $the_date = $request->get('the_date');
        $adult_price = $request->get('adult_price',0);
        $token =$request->get('token',0);
        if (empty($the_date)||!preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/",$the_date)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'设置的日期格式错误'));
        // $the_date = $the_date." 23:59:59";
        // $the_date=strtotime(date($the_date,time()));
        $member_id = $this->validationToken($token);
        $tour = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_tour_route_calendar', 'p')
                    ->where("p.route_id=$route_id")
                    ->andWhere("p.the_date ='$the_date'")
                    ->execute()
                    ->fetchAll();
        if(empty($tour)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该数据已经不存在,请先添加'));
        $agency = $manager->getConnection()->createQueryBuilder()
                     ->select(
                        'p.*'
                        )
                    ->from('msk_travel_agency', 'p')
                    ->where("p.agency_id=$agency_id")
                    ->andWhere("p.member_id=$member_id")
                    ->andWhere("p.state =6")
                    ->execute()
                    ->fetchAll();
        if(empty($agency)) return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'该您没有权限添加'));
        $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_tour_route_calendar', 'm' )
            ->set ('m.chief_id',"$chief_id")
            ->set ('m.chief_name',"'$chief_name'")
            ->set ('m.chief_image',"'$chief_image'")
            ->set ('m.the_date',"'$the_date'")
            ->set ('m.adult_price',"$adult_price")
            ->where ( "m.route_id =$route_id" )
            ->andWhere("agency_id=$agency_id")
            ->andWhere("calendar_id=$calendar_id")
            ->execute ();
        if ($upb) {
           return new JsonResponse(array('status'=>1,'error'=>0,'msg'=>'修改成功'));
        } else {
            return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'系统繁忙,请稍后再试'));
        }
        
    }

    /**
     * 所有旅行社集合
     * @Route("/apiTravelAgencyAllList", name="apiTravelAgencyAllList_")
     */
    public function apiTravelAgencyAllList(Request $request)
    {
       
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data= $conn->createQueryBuilder()
                                ->select('p.agency_name,p.agency_id,p.agency_image')
                                ->from('msk_travel_agency', 'p')
                                ->where("p.state=6") 
                                ->orderBy("p.proxy_num",'DESC')
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
     * 旅行社下的团游管理列表
     * @Route("/apiTravelAgencyTourStateList", name="apiTravelAgencyTourStateList_")
     */
    public function apiTravelAgencyTourStateList(Request $request)
    {
       
        $token = $request->get('token',0);
        $type = $request->get('type',2);//type：2出团中、3历史出团
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $agency_id = $conn->createQueryBuilder()
                                        ->select('a.agency_id')
                                        ->from('msk_travel_agency', 'a')
                                        ->where("a.member_id=$member_id")
                                        ->andWhere("a.state =6")
                                        ->execute()
                                        ->fetch();
        if(empty($agency_id)) return new JsonResponse(array('code'=>300,'msg'=>'您没有该权限','result'=>''));
        $agency_id = $agency_id['agency_id'];
        $data = $conn->createQueryBuilder()
                                 ->select(
                                    'a.adult_price','a.child_price','a.calendar_id','p.tour_id', 'p.agency_id', 'p.tour_title', 'p.imgurl','p.period','p.addtime','p.chief_id'
                                    )
                                ->from('msk_tour_calendar', 'a')
                                ->leftjoin('a','msk_tour', 'p','a.tour_id=p.tour_id')
                                ->where("p.agency_id=$agency_id")
                                ->andWhere("a.state=".$type)
                                ->execute()
                                ->fetchAll();
                
        if(!empty($data)){
                return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else {
                return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }
    }

    /**
     * 删除旅行社下的团游
     * @Route("/apiTravelAgencyTourStateDel",name="apiTravelAgencyTourStateDel_")
     */
    public function apiTravelAgencyTourStateDel(Request $request) {
        $token = $request->get('token',0);
        $calendar_id = $request->get('calendar_id',0);
        $tour_id = $request->get('tour_id',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $agency_id = $conn->createQueryBuilder()
                                        ->select('a.agency_id')
                                        ->from('msk_travel_agency', 'a')
                                        ->where("a.member_id=$member_id")
                                        ->andWhere("a.state =6")
                                        ->execute()
                                        ->fetch();
        if(empty($agency_id)) return new JsonResponse(array('code'=>300,'msg'=>'您没有该权限','result'=>''));
        $agency_id = $agency_id['agency_id'];
        $tour = $conn->createQueryBuilder()
                                        ->select('count(1)')
                                        ->from('msk_tour', 'a')
                                        ->where("a.tour_id=$tour_id")
                                        ->andWhere("a.state =1")
                                        ->andWhere("a.agency_id =".$agency_id)
                                        ->execute()
                                        ->fetch();
        if(empty($tour)) return new JsonResponse(array('code'=>300,'msg'=>'找不到该团游','result'=>''));
        $del=$manager->getConnection()->createQueryBuilder()
                               ->update ( 'msk_tour_calendar', 'm' )
                               ->set ('m.state',"0" )
                               ->where( "m.calendar_id =$calendar_id" )
                               ->execute ();
        if ($del) {
           return new JsonResponse(array('status'=>1,'error'=>0,'msg'=>'删除成功'));
        } else {
            return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'系统繁忙,请稍后再试'));
        }
        
    }

     /**
     * 团长下的团游详情
     * @Route("/apiTravelAgencyTourStateDetail", name="apiTravelAgencyTourStateDetail_")
     */
    public function apiTravelAgencyTourStateDetail(Request $request)
    {
        $token = $request->get('token',0);
        $calendar_id = $request->get('calendar_id',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $agency_id = $conn->createQueryBuilder()
                                        ->select('a.agency_id')
                                        ->from('msk_travel_agency', 'a')
                                        ->where("a.member_id=$member_id")
                                        ->andWhere("a.state =6")
                                        ->execute()
                                        ->fetch();
        if(empty($agency_id)) return new JsonResponse(array('code'=>300,'msg'=>'您没有该权限','result'=>''));
        $agency_id = $agency_id['agency_id'];
        $data = $conn->createQueryBuilder()
                     ->select(
                        'a.adult_price','a.child_price','a.calendar_id','p.tour_id', 'p.agency_id', 'p.tour_title', 'p.imgurl','p.period','p.addtime','a.chief_id'
                        )
                    ->from('msk_tour', 'p')
                    ->leftjoin('p','msk_tour_calendar', 'a','a.tour_id=p.tour_id')
                    ->where("p.agency_id=$agency_id")
                    ->andWhere("a.calendar_id=$calendar_id")
                    ->andWhere("a.state=2")
                    ->execute()
                    ->fetch();
        if(!empty($data)){
            $data['imgurl'] = $this->getParameter("app_qiniu_imgurl").$data['imgurl'];
            $calendar_id = $data['calendar_id'];
            $data['enroll'] = $conn->createQueryBuilder()
                         ->select(
                            'p.*'
                            )
                        ->from('msk_tour_order_goods', 'p')
                        ->where("p.calendar_id=$calendar_id")
                        ->andWhere("p.state !=0 and p.state !=2")
                        ->execute()
                        ->fetchAll();
            foreach ($data['enroll'] as $key1 => $value1) {
                   $data['enroll'][$key1]['avatar'] = $this->getParameter("app_qiniu_imgurl").$value1['avatar'];
            }

            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else {
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }
        
  
    }

    

    


   
  
}




























