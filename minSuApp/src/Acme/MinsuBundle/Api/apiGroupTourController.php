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
use Acme\MinsuBundle\Entity\GroupTour;
use Acme\MinsuBundle\Entity\GroupTourForecast;
use Acme\MinsuBundle\Entity\GroupTourDetail;
use Acme\MinsuBundle\Entity\GroupTourTrip;
use Acme\MinsuBundle\Entity\GroupTourEnroll;

use Acme\MinsuBundle\Common\CommonController;

class apiGroupTourController extends CommonController
{
   
    
   /**
     * 驴友团游列表
     * @Route("/apiSearchGroupTour", name="apiSearchGroupTour_")
     */
    public function apiSearchGroupTourAction(Request $request)
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
        $member_id = isset($_POST['add_user'])?$_POST['add_user']:'';
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
        if(trim($member_id) !== '')
        {   
            $orderlist.= 'and p.member_id='.$member_id.' ';
        }
        $orderlist.= 'and p.adult_price>='.$lowprice.' and p.adult_price<='.$highprice;

        if($priceRule == 0){
           $orderRule = "p.adult_price, 'DESC'"; 
        }else{
            $orderRule = "p.adult_price, 'ASC'";
        }
        if($popularRule==0){
            $orderRule = "p.entered, 'DESC'";  
        }else{
            $orderRule = "p.entered, 'ASC'";  
        }
        $conn = $em->getConnection();
        $data = $conn->createQueryBuilder()
                ->select(
                        'p.tour_id', 'p.tour_title', 'p.imgurl', 'p.adult_price', 'p.period','p.addtime','p.starting_place','p.service_price','p.entered'
                        // 'a.the_date','a.adult_price'
                        //,'p.check_in_time'
                        )
                ->from('msk_group_tour', 'p')
                // ->leftjoin('p', 'msk_tour_calendar', 'a', 'a.tour_id = p.tour_id')
                ->where($orderlist)
                ->orderBy($orderRule)  
                ->setFirstResult($page)
                ->setMaxResults(10) 
                ->execute()
                ->fetchAll();  
        foreach ($data as $key => $value) {
            $data[$key]['imgurl'] = $this->getParameter("app_qiniu_imgurl").$value['imgurl'];
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
     * 发布驴友团游
     * @Route("apiGroupTourAdd",name="apiGroupTourAdd")
     */
    public function apiGroupTourAdd()
    {
     
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        $par =array('imgurl','tour_title','period','starting_time','token','member_name','member_avatar','starting_place','service_price','booking_notice','detail','age_line','planned','end_time','telphone','adult_price','destination','trip','latitude','longitude','address','video');
        $detailarr=json_decode($data['detail'],true);
        $triparr=json_decode($data['trip'],true);
        if( $this->checkKeyForArr($par, $data)>0 && $data!=""){
           $member_id = $this->validationToken($data['token']);
           if(is_array($member_id)) return new JsonResponse($member_id);
           $data['geohash'] =$this->addGeohash($data['longitude'],$data['latitude']);
           $Tour  = new GroupTour();
           $Tour->setTourTitle($data['tour_title']);
           $Tour->setImgurl($data['imgurl']);
           $Tour->setPeriod($data['period']);
           $Tour->setMemberId($member_id);
           $Tour->setMemberName($data['member_name']);
           $Tour->setMemberAvatar($data['member_avatar']);
           $Tour->setStartingTime($data['starting_time']);
           $Tour->setStartingPlace($data['starting_place']);
           $Tour->setServicePrice($data['service_price']);
           $Tour->setBookingNotice($data['booking_notice']);
           $Tour->setPlanned($data['planned']);
           $Tour->setDestination($data['destination']);
           $Tour->setAdultPrice($data['adult_price']);
           $Tour->setTelphone($data['telphone']);
           $Tour->setaddtime(time());
           $Tour->setEndtime($data['end_time']);
           $Tour->setLatitude($data['latitude']);
           $Tour->setLongitude($data['longitude']);
           $Tour->setGeohash($data['geohash']);
           $Tour->setAddress($data['address']);
           $Tour->setVideo($data['video']);
           $manager->persist($Tour);
           $manager->flush();
           $bool  = $Tour->getTourId();
            if(is_numeric($bool)){
                if(is_array($detailarr)){
                    //循环插入旅游详情的值
                    foreach ($detailarr as $key => $value) {
                        $Detail  = new GroupTourDetail();
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
                        $Detail  = new GroupTourTrip();
                        $Detail->setTourId($bool);
                        $Detail->setTitle($value['title']);
                        $Detail->setLongitude($value['longitude']);
                        $Detail->setLatitude($value['latitude']);
                        $Detail->setNum($value['num']);
                        $manager->persist($Detail);
                        $manager->flush();
                    }
                }
                //查找是否已经建立了改用户,有则tour_num+1，无则创建
                $b=$manager->getConnection()->createQueryBuilder ()
                    ->select("*")
                    ->from('msk_group_tour_chief')
                    ->where("member_id=$member_id")//客户端先判断后得到的id
                    ->execute()->fetch();
                $conn = $manager->getConnection();
                if (empty($b)){
                    $data=array(
                        'member_id' => $member_id,
                        'member_name' => $data['member_name'],
                        'member_image' => $data['member_avatar'],
                        'state' => 1,
                        'add_time' => time(),
                        'tour_num' => 1
                    );
                    $upb=$conn->insert('msk_group_tour_chief',$data);
                }else{
                    $upb = $conn->createQueryBuilder ()
                    ->update ( 'msk_group_tour_chief', 'm' )
                    ->set ('m.state',"1")
                    ->set ( 'm.tour_num', "m.tour_num+1" )
                    ->where ( "m.member_id =$member_id" )
                    ->execute ();
                }
                return new JsonResponse(array('code'=>200,'msg'=>'添加成功','result'=>''));
            }else{
                return new JsonResponse(array('code'=>300,'msg'=>'添加失败','result'=>''));
            }  
        }else{
           return new JsonResponse(array('code'=>300,'msg'=>'系统参数错误','result'=>'')); 
        }
            
    }

    /**
     * 删除驴友团
     * @Route("apiGroupTourDel",name="apiGroupTourDel_")
     */
    public function apiGroupTourDel(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $token = $request->get('token',0);
        $tour_id = $request->get('tour_id',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_group_tour')
            ->where("tour_id=$tour_id")
            ->andWhere("member_id=$member_id")
            ->andWhere('state != 0')
            ->execute()->fetch();
        if (empty($a)) return new JsonResponse(array('code'=>300,'msg'=>'该您没有权限操作或路线已删除','result'=>''));
        if ($a['state'] ==0) return new JsonResponse(array('code'=>300,'msg'=>'该旅游团已经删除,请勿重复删除','result'=>''));
        if ($a['state'] ==2) return new JsonResponse(array('code'=>300,'msg'=>'该旅游团正在出团,暂时还不能删除','result'=>''));
        $conn = $manager->getConnection();
        //删除
        $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_group_tour', 'm' )
            ->set ('m.state',"0")
            ->where ( "m.tour_id =$tour_id" )
            ->andWhere("member_id=$member_id")
            ->execute ();
        if($upb){
               $conn->createQueryBuilder ()
                    ->update ( 'msk_group_tour_chief', 'm' )
                    ->set ('m.state',"1")
                    ->set ( 'm.tour_num', "m.tour_num-1" )
                    ->where ( "m.member_id =$member_id" )
                    ->execute ();
            return new JsonResponse(array('code'=>200,'msg'=>'删除成功','result'=>''));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'删除失败','result'=>''));
        }    
        
        return new JsonResponse($message);
            
   }

   /**
     * 驴友团游订单生成
     * @Route("apiOrderGroupTour",name="apiOrderGroupTour_")
     */
    public function apiOrderGroupTour(Request $request) {
        
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $token = $request->get('token',0);
        $tour_id = $request->get('tour_id',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $tourifo = $conn->createQueryBuilder()
                            ->select("a.imgurl,a.tour_title,a.period,a.starting_time,a.starting_place,a.adult_price")
                            ->from('msk_group_tour', 'a')
                            ->where("a.tour_id=$tour_id")
                            ->andWhere("a.state !=0")
                            ->execute()
                            ->fetch();
        if (empty($tourifo)) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'该团游不存在'));
        $forecast=$manager->getConnection()->createQueryBuilder ()
            ->select("forecast_id,member_id,avatar,username,state")
            ->from('msk_group_tour_forecast')
            ->where("tour_id=$tour_id")
            ->andWhere("member_id=$member_id")
            //->andWhere('state = 1')
            ->execute()->fetch();
        if($forecast['state']==2){
            $conn->createQueryBuilder ()
                 ->update ( 'msk_group_tour_forecast', 'm' )
                 ->set ('m.state', "1")
                 ->where ("m.tour_id =$tour_id")
                 ->andWhere("m.member_id=$member_id")
                 ->execute (); 
            $dataInfo['forecast_id'] =$forecast['forecast_id'];
            return new JsonResponse(array('code'=>200,'result'=>$dataInfo,'msg'=>'添加成功'));
        }
        if (!empty($forecast)) return new JsonResponse(array('code'=>300,'msg'=>'您已经报名过了,请勿重复报名','result'=>''));
        $user = $this->getMemberInfo($member_id);
        $Forecast  = new GroupTourForecast();
        $Forecast->setTourId($tour_id);
        $Forecast->setMemberId($member_id);
        $Forecast->setAvatar($user['avatar']);
        $Forecast->setUsername($user['nickname']);
        $Forecast->setAccount($user['account']);
        $Forecast->setIdentityCard("");
        $Forecast->setEnrollTime(time());
        $Forecast->setState(1);
        $manager->persist($Forecast);
        $manager->flush();
        $bool  =$Forecast->getForecastId();
        if(is_numeric($bool)){
            //添加订单记录,用户团购后entered(报名人数)加1
            $conn->createQueryBuilder ()
                 ->update ( 'msk_group_tour', 'm' )
                 ->set ( 'm.entered', "m.entered+1" )
                 ->where ( "m.tour_id =$tour_id" )
                 ->execute (); 
            $dataInfo['forecast_id'] =$bool;
            return new JsonResponse(array('code'=>200,'result'=>$dataInfo,'msg'=>'添加成功'));
        }else{
           return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'添加失败'));
        }

            
    }


    /**
     * 我的驴友团游列表
     * @Route("/apiMyGroupTourList", name="apiMyGroupTourList_")
     */
    public function apiMyGroupTourList(Request $request)
    {
       
        $token = $request->get('token',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $type = $request->get('type',0);//type：0我发起的、1我入伙的、2我完成的
        $avatarPath = $this->getParameter('app_qiniu_imgurl');
        $conn = $this->getDoctrine()->getManager()->getConnection();
        switch ($type) {
            case '0':
                    $query = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id','p.tour_title', 'p.imgurl','p.period','p.adult_price','p.addtime','p.member_id','p.starting_place'
                                    )
                                ->from('msk_group_tour', 'p')
                                ->where("p.member_id=$member_id")
                                ->andWhere("p.state !=0")
                                ->execute();
                    $data = $query->fetchAll();
                    foreach ($data as $key => $value) {
                        $tour_id = $value['tour_id'];
                        $data[$key]['imgurl'] = $this->getParameter("app_qiniu_imgurl").$value['imgurl'];
                    }
                break;
            case '1':
                    $query = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id','p.tour_title', 'p.imgurl','p.period','p.adult_price','p.addtime','p.member_id','p.starting_place'
                                    )
                                ->from('msk_group_tour_forecast', 'm')
                                ->leftjoin('m','msk_group_tour','p','m.member_id=p.member_id')
                                ->where("p.member_id=$member_id")
                                ->andWhere("p.state !=0")
                                ->execute();
                    $data = $query->fetchAll();
                    
                break;
            case '2':
                $query = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id','p.tour_title', 'p.imgurl','p.period','p.adult_price','p.addtime','p.member_id','p.starting_place'
                                    )
                                ->from('msk_group_tour', 'p')
                                ->where("p.member_id=$member_id")
                                ->andWhere("p.state =3")
                                ->execute();
                    $data = $query->fetchAll();
                    foreach ($data as $key => $value) {
                        $tour_id = $value['tour_id'];
                        $data[$key]['imgurl'] = $this->getParameter("app_qiniu_imgurl").$value['imgurl'];
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
     * 发团管理列表
     * @Route("/apiChiefGroupTourList", name="apiChiefGroupTourList_")
     */
    public function apiChiefGroupTourList(Request $request)
    {
       
        $token = $request->get('token',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $type = $request->get('type',0);//type：0当前出团、1历史出团
        $conn = $this->getDoctrine()->getManager()->getConnection();
        switch ($type) {
            case '0':
                   $query = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id','p.tour_title', 'p.imgurl','p.period','p.adult_price','p.addtime','p.member_id','p.starting_place'
                                    )
                                ->from('msk_group_tour', 'p')
                                ->where("p.member_id=$member_id")
                                ->andWhere("p.state =2")
                                ->execute();
                    $data = $query->fetchAll();
                    foreach ($data as $key => $value) {
                        $tour_id = $value['tour_id'];
                        $data[$key]['imgurl'] = $this->getParameter("app_qiniu_imgurl").$value['imgurl'];
                    }
                break;
            case '1':
                    $query = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id','p.tour_title', 'p.imgurl','p.period','p.adult_price','p.addtime','p.member_id','p.starting_place'
                                    )
                                ->from('msk_group_tour', 'p')
                                ->where("p.member_id=$member_id")
                                ->andWhere("p.state =3")
                                ->execute();
                    $data = $query->fetchAll();
                    foreach ($data as $key => $value) {
                        $tour_id = $value['tour_id'];
                        $data[$key]['imgurl'] = $this->getParameter("app_qiniu_imgurl").$value['imgurl'];
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
     * 旅游团游报名列表
     * @Route("/apiGroupTourForecastList", name="apiGroupTourForecastList_")
     * 
     */
    public function apiGroupTourForecastList(Request $request)
    {
        $tour_id = $request->get('tour_id',0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.*'
                                    )
                                ->from('msk_group_tour_forecast', 'p')
                                ->where("p.tour_id=$tour_id")
                                ->andWhere("p.state!=2")
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
     * 旅游团详情
     * @Route("/apiGroupTourDetail", name="apiGroupTourDetail_")
     */
    public function apiGroupTourDetail(Request $request)
    {
        $tour_id = $request->get('tour_id',0);
        $token = $request->get('token',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id','p.tour_title', 'p.imgurl','p.period','p.adult_price ','p.addtime','p.starting_place','p.service_price','p.state','p.entered','p.planned','p.telphone','p.end_time','p.destination','p.member_id','p.booking_notice'
                                    )
                                ->from('msk_group_tour', 'p')
                                ->where("p.tour_id=$tour_id")
                                ->execute()
                                ->fetch();
        if(!empty($data)){
            //查询是否有入伙了
            $forecast = $conn->createQueryBuilder()
                                ->select('a.member_id')
                                ->from('msk_group_tour_forecast', 'a')
                                ->where("a.member_id=$member_id")
                                ->andwhere("a.tour_id  =$tour_id ")
                                ->execute()
                                ->fetch();
            $data['imgurl'] = $this->getParameter("app_qiniu_imgurl").$data['imgurl'];
            $data['is_join'] = 0;
            if(!empty($forecast)){
                $data['is_join'] = 1;
            }
             return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }      
  
    }


     /**
     * 旅程详情
     * @Route("/apiGroupTourDetailList", name="apiGroupTourDetailList_")
     */
    public function apiGroupTourDetailList(Request $request)
    {
       
        $tour_id = $request->get('tour_id',0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data['detail'] = $conn->createQueryBuilder()
                                ->select('p.*')
                                ->from('msk_group_tour_detail', 'p')
                                ->where("p.tour_id=$tour_id") 
                                ->orderBy("p.site",'DESC')
                                ->execute()
                                ->fetchAll();
        $data['trip'] = $conn->createQueryBuilder()
                                ->select('p.*')
                                ->from('msk_group_tour_trip', 'p')
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
     * @Route("/apiGroupTourExplainList", name="apiGroupTourExplainList_")
     */
    public function apiGroupTourExplainList(Request $request)
    {
       
        $tour_id =isset($_GET['tour_id'])?$_GET['tour_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                ->select(
                                    'p.tour_id', 'p.booking_notice'
                                    )
                                ->from('msk_group_tour', 'p')
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
     * 扫描二维码加入与取消界面
     * @Route("/apiGroupTourScanCodeDetail", name="apiGroupTourScanCodeDetail_")
     * 
     */
    public function apiGroupTourScanCodeDetail(Request $request)
    {
        $tour_id = $request->get('tour_id',0);
        $type = $request->get('type',0);//0加入界面，1取消界面
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                 ->select(
                                    'p.tour_id','p.tour_title', 'p.imgurl','p.adult_price ','p.addtime','p.starting_place','p.service_price','p.entered','p.member_id','p.member_name','p.member_avatar','starting_time','end_time'
                                    )
                                ->from('msk_group_tour', 'p')
                                ->where("p.tour_id=$tour_id")
                                ->where("p.state=2")
                                ->execute()
                                ->fetch();
        if(!empty($data)){
            //var_dump($data);exit;
            if($type ==0){
                $cancel = $conn->createQueryBuilder()
                                ->select("count(a.forecast_id) as cancel")
                                ->from('msk_group_tour_forecast', 'a')
                                ->where("a.tour_id=$tour_id")
                                ->andwhere("a.state =4")
                                ->execute()
                                ->fetch();
                $data['cancel'] = $cancel['cancel'];
                $data['uncancel'] =$data['entered']-$cancel['cancel'];
                $data['points'] ="团员扫码后即成功提前退团";
            }else{
                
                $confirm = $conn->createQueryBuilder()
                                ->select("count(a.forecast_id) as confirm")
                                ->from('msk_group_tour_forecast', 'a')
                                ->where("a.tour_id=$tour_id")
                                ->andwhere("a.state =3")
                                ->execute()
                                ->fetch();
                $data['confirm'] = $confirm['confirm'];
                $data['unconfirm'] =$data['entered']-$confirm['confirm'];
                $data['points'] ="团员扫码后即报名成功,可实时共享位置";
            }
            //unset($data['planned']);
          return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }             
  
    }


     /**
     * 驴友团成员取消
     * @Route("apiGroupTourManCancelEnroll",name="apiGroupTourManCancelEnroll_")
     */
    public function apiGroupTourManCancelEnroll(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $tour_id = $request->get('tour_id',0);
        $member_id = $request->get('member_id',0);
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("forecast_id,member_id,avatar,username,state")
            ->from('msk_group_tour_forecast')
            ->where("tour_id=$tour_id")
            ->andWhere("member_id=$member_id")
            //->andWhere('state = 1')
            ->execute()->fetch();
        if (empty($a)) return new JsonResponse(array('code'=>300,'msg'=>'报道不存在','result'=>''));
        if ($a['state'] ==4) return new JsonResponse(array('code'=>300,'msg'=>'您已经取消过报道了！','result'=>''));
        if ($a['state'] !=3) return new JsonResponse(array('code'=>300,'msg'=>'您还未报道！','result'=>''));
        $forecast_id = $a['forecast_id'];
        //更新表
        $upb=$conn->createQueryBuilder ()
                ->update ( 'msk_group_tour_enroll', 'm' )
                ->set ('m.state',"0")
                ->where("tour_id=$tour_id")
                ->andWhere("forecast_id=$forecast_id")
                ->andWhere("member_id=$member_id")
                ->execute ();
        if($upb){
            //更新订单货物表
            $upb1=$conn->createQueryBuilder ()
                    ->update ( 'msk_group_tour_forecast', 'm' )
                    ->set ('m.state',"4")
                    ->where("tour_id=$tour_id")
                    ->andWhere("member_id=$member_id")
                    ->execute ();
            return new JsonResponse(array('code'=>200,'msg'=>'取消成功','result'=>''));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'取消失败','result'=>''));
        }        
   }

   /**
     * 团游成员进行报道
     * @Route("apiGroupTourManEnroll",name="apiGroupTourManEnroll_")
     */
   
    public function apiGroupTourManEnroll(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $tour_id = $request->get('tour_id',0);
        $forecast_id = $request->get('forecast_id');
        $member_id = $request->get('member_id',0);
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("forecast_id,member_id,avatar,username,state")
            ->from('msk_group_tour_forecast')
            ->where("tour_id=$tour_id")
            ->andWhere("member_id=$member_id")
            //->andWhere('state = 1')
            ->execute()->fetch();
        if (empty($a)) return new JsonResponse(array('code'=>300,'msg'=>'订单不存在','result'=>''));
        if ($a['state'] ==2) return new JsonResponse(array('code'=>300,'msg'=>'该报名您已经取消了,请重新报名！','result'=>''));
        if ($a['state'] ==4) return new JsonResponse(array('code'=>300,'msg'=>'您已经取消过报道了！','result'=>''));
        if ($a['state'] ==3) return new JsonResponse(array('code'=>300,'msg'=>'您已经报道过了！','result'=>''));
        //var_dump($a);exit;
        //添加
        $enroll  = new GroupTourEnroll();
        $enroll->setTourId($tour_id);
        $enroll->setForecastId($a['forecast_id']);
        $enroll->setAvatar($a['avatar']);
        $enroll->setUsername($a['username']);
        $enroll->setMemberId($member_id );
        $enroll->setLongitude(0);
        $enroll->setLatitude(0);
        $enroll->setAddTime(time());
        $manager->persist($enroll);
        $manager->flush();
        $bool  =$enroll->getEnrollId();
        if($bool){
            //更新订单货物表
            $upb1=$conn->createQueryBuilder ()
                    ->update ( 'msk_group_tour_forecast', 'm' )
                    ->set ('m.state',"3")
                    ->where("order_sn=$order_sn")
                    ->andWhere("member_id=$member_id")
                    ->execute ();
            return new JsonResponse(array('code'=>200,'msg'=>'报道成功','result'=>''));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'报道失败','result'=>''));
        }        
   }

   /**
     * 所有发驴友团游的用户集合
     * @Route("/apiGroupTourChiefList", name="apiGroupTourChiefList_")
     */
    public function apiGroupTourChiefList(Request $request)
    {
       
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data= $conn->createQueryBuilder()
                                ->select('p.member_id,p.member_name,p.member_image,p.state,p.tour_num')
                                ->from('msk_group_tour_chief', 'p')
                                ->where("p.state !=0") 
                                ->orderBy("p.tour_num",'DESC')
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


    
    


}






























