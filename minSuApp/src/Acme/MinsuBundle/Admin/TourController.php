<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-7-22
 * Time: 14:36
 */
namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MinsuBundle\Entity\Tour;
use Acme\MinsuBundle\Entity\TourCalendar;

class TourController extends Controller
{

     //旅行社列表
    /**
     * @Route("/travelAgencyList",name="travelAgencyList_")
     */
    public function travelAgencyListAction(Request $request)
    {
        $stat=$request->get("isOwner","");
        if ($stat != "") $where = "m.state = $stat";
        else $where = 'm.state is not null';
        //搜索
        $searchText=$request->get("searchText","");
        if($searchText!=''){
          if($_POST['searchType']  =='nic'){
            $where  = $where ." and  m.agency_name LIKE '%$searchText%' "; 
          }elseif($_POST['searchType']  =='acc'){
            $where  = $where ." and  m.agency_tel LIKE '%$searchText%' "; 
          }
        }
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $memberList = $conn->createQueryBuilder ()
                    ->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,p.avatar, p.id,p.member_state" )
                    ->from ( 'msk_travel_agency', 'm' )
                    ->leftjoin('m', 'msk_member', 'p', 'm.member_id = p.id')
                    ->where ( "$where" )
                    ->orderBy("m.add_time","DESC")
                    ->execute ()
                    ->fetchAll ();
        foreach ($memberList as $key => $value) {
          $memberList[$key]['agency_image'] = $this->getParameter("app_qiniu_imgurl").$value['agency_image'];
          $memberList[$key]['avatar'] = $this->getParameter('app_qiniu_imgurl').$value['avatar'];
          $where1 = "o.agency_id =".$value['agency_id']; 
          $order = $conn->createQueryBuilder ()
                        ->select ( "count(o.agency_id) as sums" )
                        ->from ( 'msk_tour', 'o' )
                        ->where ( "$where1" )
                        ->execute ()
                        ->fetch();
           $memberList[$key]['sums'] =$order['sums'];
        }
        $totalPage = ceil(count($memberList) / 10);
        $page = $request->get('page');
        if ($page == '') {
            $page = 1;
        }
        $prePage = $page - 1;
        if ($prePage < 1) {
            $prePage = 1;
        }
        $nextPage = $page + 1;
        if ($nextPage > $totalPage) {
            $nextPage = $totalPage;
        }
        $offset = ($page - 1) * 10;
        $memberList = array_slice($memberList, $offset, 10);
         for  ($i =0 ; $i<count($memberList) ;$i++){
             $avatar =  $memberList[$i]['avatar'] ;
             $avatarPath=$this->container->getParameter('app_avater_path') .$memberList[$i]['id'].'/';
             $memberList[$i]['avatar_url'] =$avatarPath .$avatar;
         }
        return $this->render('AcmeMinsuBundle:Default:travelAgency.html.twig',
            array(
                'memberList'=>$memberList,
                'totalPage' => $totalPage,
                'prePage' => $prePage,
                'nextPage' => $nextPage,
                'page' => $page
            )
        );
    }

    /**
     * 更改旅行社的状态
     * @Route("/travelAgencyStateChange", name="travelAgencyStateChange_")
     */
    public function travelAgencyStateChangeAction(Request $request)
    {
        $id = $request->get('agency_id');
        $state = $request->get('state');
        $em = $this->getDoctrine()->getManager();
        $memberQry = $em->getRepository('AcmeMinsuBundle:TravelAgency')->findOneBy(
            array('agency_id' => $id)
        );
        $memberQry->setState($state);
        $em->flush();
        return new Response(1);
    }

    /**
     * 旅行社的详情
     * @Route("/travelAgencyDataDetail",name="travelAgencyDataDetail_")
     */
    public function travelAgencyDataDetailAction()
    {
        $hid = isset($_GET['agency_id'])?$_GET['agency_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        //查询旅行团的基本信息
        $hstReturn = $conn->createQueryBuilder ()
                    ->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,mm.true_name" )
                    ->from ( 'msk_travel_agency', 'm' )
                    ->leftjoin ( 'm', 'msk_member', 'mm', 'm.member_id=mm.id' )
                    ->where ( "m.agency_id = $hid" )
                    ->execute ()
                    ->fetch ();
        $chief_num = $conn->createQueryBuilder()
            ->select("count(c.chief_id) as chief_num")
            ->from('msk_chief', 'c')
            ->Where('c.agency_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetch();
        $certRes = $conn->createQueryBuilder()
            ->select('c.*')
            ->from('msk_travel_agency_certification', 'c')
            ->Where('c.agency_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetch();
        $noimg = $this->getParameter('app_none_imgurl');
        if($certRes['business_license'] ==null) $certRes['business_license'] =$noimg;
        else $certRes['business_license'] = $this->getParameter("qiniu_minsu_imgurl").$certRes['business_license'];
        if($certRes['trading_license'] ==null) $certRes['trading_license'] =$noimg; 
        else $certRes['trading_license'] = $this->getParameter("qiniu_minsu_imgurl").$certRes['trading_license']; 
        return $this->render('AcmeMinsuBundle:Default:travelAgencyDetail.html.twig',
            array(
                'v'=>$hstReturn,
                'cert' => $certRes,
                'chief_num'=>$chief_num['chief_num']
            ));
    }

    //团游列表
    /**
     * @Route("/tourDataList",name="tourDataList_")
     */
    public function tourDataListAction(Request $request)
    {   
        $stat=$request->get("isOwner","");
        $searchText=$request->get("searchText","");
        if ($stat != "") $where = "m.state = $stat";
        else $where = 'm.state is not null';
        if($searchText!=''){
          if($_POST['searchType']  =='nic'){
            $where  = $where ." and  p.agency_name LIKE '%$searchText%' "; 
          }elseif($_POST['searchType']  =='acc'){
            $where  = $where ." and  m.tour_title LIKE '%$searchText%' "; 
          }
        }
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $memberList = $conn->createQueryBuilder ()
                    ->select ( "m.*,FROM_UNIXTIME(m.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime,p.agency_name" )
                    ->from ( 'msk_tour', 'm' )
                    ->leftjoin('m', 'msk_travel_agency', 'p', 'm.agency_id = p.agency_id')
                    ->where ( "$where" )
                    ->orderBy("m.addtime","DESC")
                    ->execute ()
                    ->fetchAll ();
        foreach ($memberList as $key => $value) {
          $memberList[$key]['imgurl'] = $this->getParameter("app_qiniu_imgurl").$value['imgurl'];
          $where1 = "o.tour_id =".$value['tour_id']; 
          $calendar = $conn->createQueryBuilder ()
                        ->select ( "count(o.calendar_id) as sums" )
                        ->from ( 'msk_tour_calendar', 'o' )
                        ->where ( "$where1" )
                        ->execute ()
                        ->fetch();
           $memberList[$key]['calendar_sums'] =$calendar['sums'];
        }
        $totalPage = ceil(count($memberList) / 10);
        $page = $request->get('page');
        if ($page == '') {
            $page = 1;
        }
        $prePage = $page - 1;
        if ($prePage < 1) {
            $prePage = 1;
        }
        $nextPage = $page + 1;
        if ($nextPage > $totalPage) {
            $nextPage = $totalPage;
        }
        $offset = ($page - 1) * 10;
        $memberList = array_slice($memberList, $offset, 10);
        return $this->render('AcmeMinsuBundle:Default:tour.html.twig',
            array(
                'memberList'=>$memberList,
                'totalPage' => $totalPage,
                'prePage' => $prePage,
                'nextPage' => $nextPage,
                'page' => $page
            )
        );
    }

    /**
     * 更改团游的状态
     * @Route("/tourStateChange", name="tourStateChange_")
     */
    public function tourStateChangeAction(Request $request)
    {
        $id = $request->get('tour_id');
        $state = $request->get('state');
        $em = $this->getDoctrine()->getManager();
        $memberQry = $em->getRepository('AcmeMinsuBundle:Tour')->findOneBy(
            array('tour_id' => $id)
        );
        $memberQry->setState($state);
        $em->flush();
        return new Response(1);
    }

     /**
     * 团游的详情
     * @Route("/tourDataDetail",name="tourDataDetail_")
     */
    public function tourDataDetailAction()
    {
        $hid = isset($_GET['tour_id'])?$_GET['tour_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        //查询导游的基本信息
        $hstReturn = $conn->createQueryBuilder ()
                    ->select ( "m.*,FROM_UNIXTIME(m.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime,FROM_UNIXTIME(m.starting_time, '%Y-%m-%d') NewStartingTime,p.agency_name" )
                    ->from ( 'msk_tour', 'm' )
                    ->leftjoin('m', 'msk_travel_agency', 'p', 'm.agency_id = p.agency_id')
                    ->where ( "m.tour_id = $hid" )
                    ->execute ()
                    ->fetch();
        $tourdetail = $conn->createQueryBuilder()
                                ->select('p.*')
                                ->from('msk_tour_detail', 'p')
                                ->where("p.tour_id=$hid") 
                                ->execute()
                                ->fetchAll();
        $tripdetail = $conn->createQueryBuilder()
                                ->select('p.*')
                                ->from('msk_tour_trip', 'p')
                                ->where("p.tour_id=$hid") 
                                ->execute()
                                ->fetchAll();
        $tourcalendar = $conn->createQueryBuilder()
                                ->select('count(k.calendar_id) as calendar_count')
                                ->from('msk_tour_calendar', 'k')
                                ->where("k.tour_id=$hid") 
                                ->execute()
                                ->fetch();
        $calendar_count = $tourcalendar['calendar_count'];
        $longitude = isset($tourdetail[0]['longitude'])?$tourdetail[0]['longitude']:'113.412';
        $latitude = isset($tourdetail[0]['longitude'])?$tourdetail[0]['latitude']:'23.115';
        return $this->render('AcmeMinsuBundle:Default:tourDetail.html.twig',
            array(
                'v'=>$hstReturn,
                'detail' => $tourdetail,
                'trip' => $tripdetail,
                'calendar' => $calendar_count,
                'public_longitude' => $longitude,
                'public_latitude' => $latitude
            ));
    }

   
    /**
     * 团游的订单详情
     * @Route("/tourDataOrder",name="tourDataOrder_")
     */
    public function tourDataOrderAction()
    {
        $hid = isset($_GET['tour_id'])?$_GET['tour_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        //查到团游的相关报名
        $data = $conn->createQueryBuilder()
            ->select("p.*,FROM_UNIXTIME(p.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime")
            ->from('msk_tour_order','p')
            ->Where('p.tour_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->orderBy("p.add_time","DESC")
            ->execute()
            ->fetchAll();
        foreach ($data as $key => $value) {
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
        }
        return $this->render('AcmeMinsuBundle:Default:tourOrder.html.twig',
            array(
                'enroll' => $data
            ));
    }

    /**
     * 根据旅行社筛选路途
     */
    public function travelAgencySelectRoute() {
        //读取信息
         $agency_id = $_POST['agency_id'];
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $data = $conn->createQueryBuilder ()
                      ->select ( "m.*" )
                      ->from ( 'msk_tour_route', 'm' )
                      ->where("m.state=1")
                      ->orderBy('m.addtime','DESC')
                      ->execute ()
                      ->fetchAll();
        return new JsonResponse ($data);
    }

    /**
     * 根据旅行社筛选团游
     * @Route("/travelAgencySelectTour",name="travelAgencySelectTour_")
     */
    public function travelAgencySelectTour() {
        //读取信息
        $agency_id = $_POST['agency_id'];
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $data = $conn->createQueryBuilder ()
                      ->select ( "m.*" )
                      ->from ( 'msk_tour', 'm' )
                      ->where("m.state=1")
                      ->orderBy('m.addtime','DESC')
                      ->execute ()
                      ->fetchAll();
         return new JsonResponse ($data);
    }

     /**
     * 根据旅行社筛选团长
     * @Route("/travelAgencySelectChief",name="travelAgencySelectChief_")
     */
    public function travelAgencySelectChief() {
        //读取信息
        $agency_id = $_POST['agency_id'];
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $data = $conn->createQueryBuilder ()
                      ->select ( "m.*" )
                      ->from ( 'msk_chief', 'm' )
                      ->where("m.state=1")
                      ->orderBy('m.add_time','DESC')
                      ->execute ()
                      ->fetchAll();
         return new JsonResponse ($data);
    }




     /**
     * 后台添加团游
     * @Route("/addTourCalendar",name="addTourCalendar")
     * @Template("AcmeMinsuBundle:Default:addTourCalendar.html.twig")
     */
    public function addTourCalendarAction() {
        //读取旅行社
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $memberdata = $conn->createQueryBuilder ()
                      ->select ( "m.*" )
                      ->from ( 'msk_travel_agency', 'm' )
                      ->where('m.state=6')
                      ->orderBy('m.add_time','DESC')
                      ->execute ()
                      ->fetchAll ();
        return array('meb'=>$memberdata);
    }

     /**
     * @Route("/tourCalendarList",name="_tour_calendar_list")
     * @Template("AcmeMinsuBundle:Tour:tourCalendarList.html.twig")
     */
    public function tourCalendarList(){
        $tour_id = isset($_GET['tour_id'])?$_GET['tour_id']:0;
        $data=$this->getDoctrine ()->getManager ()->getConnection ()->createQueryBuilder()
                    ->select("p.*")
                    ->from("msk_tour_calendar",'p')
                    ->where("p.tour_id='$tour_id'")
                    ->execute()
                    ->fetchAll();
        return array('calendar'=>$data);
    }

    /**
     * 更改团游日历的状态
     * @Route("/calendarStateChange", name="calendarStateChange_")
     */
    public function calendarStateChangeAction(Request $request)
    {
     
       $calendar_id = $request->get('calendar_id');
       $state = $request->get('state');
       $conn = $this->getDoctrine ()->getManager ()->getConnection ();
       $query = $conn->createQueryBuilder ()
           ->update('msk_tour_calendar')
           ->set('state',$state)
           ->where('calendar_id='.$calendar_id)
           ->execute();
       if ($query){
           return new JsonResponse(1);
       }else{
           return new JsonResponse(0);
       }
    }

     /**
     * 团游的报名详情
     * @Route("/tourDataEnroll",name="tourDataEnroll")
     * @Template("AcmeMinsuBundle:Tour:tourEnroll.html.twig")
     */
    public function tourDataEnrollAction()
    {
        $calendar_id = isset($_GET['calendar_id'])?$_GET['calendar_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到团游的相关报名
        $data = $conn->createQueryBuilder()
            ->select("a.*,FROM_UNIXTIME(a.enroll_time, '%Y-%m-%d %H:%i:%s') NewAddTime")
            ->from('msk_tour_order_goods', 'a')
            ->Where("a.calendar_id = $calendar_id")
            ->andWhere("a.state !=2")
            ->orderBy("a.enroll_time","DESC")
            ->execute()
            ->fetchAll();
        foreach ($data as $key => $value) {
          $data[$key]['avatar'] = $this->getParameter("app_qiniu_imgurl").$value['avatar'];
        }
        return array('enroll'=>$data);
       
    }

     /**
     * 旅行社下的团长列表
     * @Route("/travelAgencyChiefList",name="travelAgencyChiefList_")
     * @Template("AcmeMinsuBundle:Tour:travelAgencyChiefList.html.twig")
     */
    public function travelAgencyChiefList()
    {
        $agency_id = isset($_GET['agency_id'])?$_GET['agency_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到团游的相关报名
        $data = $conn->createQueryBuilder()
            ->select("a.*,FROM_UNIXTIME(a.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime")
            ->from('msk_chief', 'a')
            ->Where("a.agency_id = $agency_id")
            ->orderBy("a.add_time","DESC")
            ->execute()
            ->fetchAll();
        foreach ($data as $key => $value) {
          $data[$key]['chief_image'] = $this->getParameter("app_qiniu_imgurl").$value['chief_image'];
        }
        return array('chief'=>$data);
       
    }

    /**
     * 旅行社下的团长列表
     * @Route("/travelAgencyProxyList",name="travelAgencyProxyList_")
     * @Template("AcmeMinsuBundle:Tour:travelAgencyProxyList.html.twig")
     */
    public function travelAgencyProxyList()
    {
        $agency_id = isset($_GET['agency_id'])?$_GET['agency_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到团游的相关报名
        $data = $conn->createQueryBuilder()
            ->select("a.*,FROM_UNIXTIME(a.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime")
            ->from('msk_travel_agency_proxy', 'a')
            ->Where("a.agency_id = $agency_id")
            ->orderBy("a.add_time","DESC")
            ->execute()
            ->fetchAll();
        foreach ($data as $key => $value) {
          $data[$key]['proxy_image'] = $this->getParameter("app_qiniu_imgurl").$value['proxy_image'];
        }
        return array('chief'=>$data);
       
    }

      /**
    * 
    * @Route("/tour_route_list",name="_tour_route_list")
    * @Template("AcmeMinsuBundle:Tour:tourRouteList.html.twig")
    */
   function tour_route_list(){
       $where='1=1';
         $searchType=isset($_GET['searchType'])?$_GET['searchType']:0;
         $searchText=isset($_GET['searchText'])?$_GET['searchText']:0;
         if ($searchType!=999 && ($searchText != '' ||  $searchText !=null || !empty($searchText))){
            $where .=" and m.$searchType like"."'%$searchText%'"; 
       }
       $conn = $this->getDoctrine ()->getManager ()->getConnection ();       
       $page  = isset($_GET['page'])?$_GET['page']:0;      
       if (empty($page)) {
           $page = 1;
       }
       $pageSize = $this->getParameter('pagesize');
       $totalNumRes= $conn->createQueryBuilder()
           ->select('count(m.route_id) as total')
           ->from('msk_tour_route', 'm' )
           ->where($where)
       ->execute()->fetch();
       $totalNum = $totalNumRes['total'];
        
       $totalPage = ceil($totalNum / $pageSize);
       if ($totalPage != 0 && $page > $totalPage) {
           $page = $totalPage;
       }
       $startPage = ($page - 1) * $pageSize;
        
       $prePage = $page - 1;
       $nextPage = $page + 1;
       
       $query = $conn->createQueryBuilder ()
           ->select ( "m.*" )
           ->from ( 'msk_tour_route', 'm' )
           ->orderBy("m.addtime","DESC")
           ->where($where)
           ->setFirstResult($startPage)
           ->setMaxResults($pageSize)
           ->execute ();
       $data=$query->fetchAll();
       foreach ($data as $k=>$v){
           $data[$k]['tour_imgurl'] = $this->getParameter("app_qiniu_imgurl").$v['tour_imgurl'];
           
       }
      return array(
          'oList'=>$data,
          'page'=>self::pageHtml($totalPage,'group_tour_list',$page,$prePage,$nextPage),
      );
   }
   
    /**
     * 更改线路状态
     * @Route("/TourRouteStateChange", name="TourRouteStateChange_")
     */
    public function TourRouteStateChange(Request $request)
    {
     
       $route_id = $request->get('route_id');
       $state = $request->get('state');
       $conn = $this->getDoctrine ()->getManager ()->getConnection ();
       $query = $conn->createQueryBuilder ()
           ->update('msk_tour_route')
           ->set('state',$state)
           ->where('route_id='.$route_id)
           ->execute();
       if ($query){
           return new JsonResponse(1);
       }else{
           return new JsonResponse(0);
       }
    }

     /**
     * 团游的详情
     * @Route("/tourRouteDetail",name="tourRouteDetail_")
     * @Template("AcmeMinsuBundle:Tour:tourRouteDetail.html.twig")
     */
    public function tourRouteDetail()
    {
        $hid = isset($_GET['route_id'])?$_GET['route_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        //查询导游的基本信息
        $hstReturn = $conn->createQueryBuilder ()
                    ->select ( "m.*,FROM_UNIXTIME(m.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime,FROM_UNIXTIME(m.starting_time, '%Y-%m-%d') NewStartingTime" )
                    ->from ( 'msk_tour_route', 'm' )
                    ->where ( "m.route_id = $hid" )
                    ->execute ()
                    ->fetch();
        $tourdetail = $conn->createQueryBuilder()
                                ->select('p.*')
                                ->from('msk_tour_route_detail', 'p')
                                ->where("p.route_id=$hid") 
                                ->execute()
                                ->fetchAll();
        $tripdetail = $conn->createQueryBuilder()
                                ->select('p.*')
                                ->from('msk_tour_route_trip', 'p')
                                ->where("p.route_id=$hid") 
                                ->execute()
                                ->fetchAll();
        $tour = $conn->createQueryBuilder ()
                        ->select ( "count(o.tour_id) as sums" )
                        ->from ( 'msk_tour', 'o' )
                        ->where ( "o.route_id =".$hid )
                        ->execute ()
                        ->fetch();
        $tour_sums =$tour['sums'];
        $longitude = isset($tourdetail[0]['longitude'])?$tourdetail[0]['longitude']:'113.412';
        $latitude = isset($tourdetail[0]['longitude'])?$tourdetail[0]['latitude']:'23.115';
        return  array(
                'v'=>$hstReturn,
                'detail' => $tourdetail,
                'trip' => $tripdetail,
                'tour_sums' => $tour_sums,
                'public_longitude' => $longitude,
                'public_latitude' => $latitude
            );
    }

     /**
     * 路线下的团游列表
     * @Route("/tourRouteTourList",name="tourRouteTourList_")
     * @Template("AcmeMinsuBundle:Tour:tourRouteTourList.html.twig")
     */
    public function tourRouteTourList()
    {
        $route_id = isset($_GET['route_id'])?$_GET['route_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到团游的相关报名
        $data = $conn->createQueryBuilder()
            ->select("a.*,FROM_UNIXTIME(a.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime")
            ->from('msk_tour', 'a')
            ->Where("a.route_id = $route_id")
            ->orderBy("a.addtime","DESC")
            ->execute()
            ->fetchAll();
        foreach ($data as $key => $value) {
          $data[$key]['imgurl'] = $this->getParameter("app_qiniu_imgurl").$value['imgurl'];
          $data[$key]['chief_avatar'] = $this->getParameter("app_qiniu_imgurl").$value['chief_avatar'];
          $where1 = "o.tour_id =".$value['tour_id']; 
          $calendar = $conn->createQueryBuilder ()
                        ->select ( "count(o.calendar_id) as sums" )
                        ->from ( 'msk_tour_calendar', 'o' )
                        ->where ( "$where1" )
                        ->execute ()
                        ->fetch();
           $data[$key]['calendar_sums'] =$calendar['sums'];
        }
        return array('enroll'=>$data);
       
    }
    

    protected function pageHtml($totalPage,$url,$page,$prePage,$nextPage,$type=1){
       $html ="<div class='page-dis'><div class='meneame'><a href=".$url."?page=1&type=".$type.">首页</a>";
       $html .="<a href=".$url."?page=$prePage&type=$type>< </a>";
       if($totalPage >= 7){
           if($page <= 4){
               for($i=1;$i<7;$i++){
                   $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
               }
           }elseif ($page > ($totalPage - 4)){
               for($i=$totalPage-7;$i<$totalPage;$i++){
                   $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
               }
           }else{
               for($i=($page-3);$i<($page+3);$i++){
                   $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
               }
           }
       }else{
           if ($totalPage!=0){
               for($i=1;$i<=$totalPage;$i++){
                   $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
               }
           }
       }
       $html .="<a href=".$url."?page=$nextPage&type=$type>></a>";
       $html .="<a href=".$url."?page=$totalPage&type=$type>尾页</a></div></div>";
       return $html;
   }






}





























