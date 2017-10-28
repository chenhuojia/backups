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
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\MinsuBundle\Entity\Tour;
use Acme\MinsuBundle\Entity\GroupTour;
use Acme\MinsuBundle\Entity\GroupTourForecast;
use Acme\MinsuBundle\Entity\GroupTourDetail;
use Acme\MinsuBundle\Entity\GroupTourTrip;
use Acme\MinsuBundle\Entity\GroupTourEnroll;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Geo\Geohash;

use Acme\MinsuBundle\Common\CommonController;
class GroupTourController extends CommonController
{   
    
    /**
     * 后台添加团游
     * @Route("/addGroupTour",name="addGroupTour_")
     * @Template("AcmeMinsuBundle:Default:addGroupTour.html.twig")
     */
    public function addGroupTourAction() {
       
      //读取会员信息
      $conn = $this->getDoctrine ()->getManager ()->getConnection ();
      $memberdata = $conn->createQueryBuilder ()
                      ->select ( "m.*,mmi.nickname" )
                      ->from ( 'msk_member', 'm' )
                      ->leftJoin('m','msk_member_info','mmi','m.id=mmi.member_id')
                      ->orderBy('m.creat_date','DESC')
                      ->execute ()
                      ->fetchAll ();
      $htypdata = $conn->createQueryBuilder()
                      ->select('m.*')
                      ->from('msk_homestay_type', 'm')
                      ->orderBy('m.sort','DESC')
                      ->execute()
                      ->fetchAll();
      return array('meb'=>$memberdata,'ht' =>$htypdata);
    }

     /**
     * 发布驴友团游
     * @Route("GroupTourAddData",name="GroupTourAddData_")
     */
    public function GroupTourAddData()
    {
         $manager = $this->getDoctrine()->getManager();
         $conn = $manager->getConnection();
         $data = isset($_POST)?$_POST:'';
         $memberinfo = explode(",", $data['memberinfo']);
         $member_id = $memberinfo[0];
         $data['member_name']=$memberinfo[1];
         $data["member_avatar"]=$memberinfo[2];
         //生成团游相片
         $filename =$_FILES['file0']['tmp_name'];// 要上传文件的本地路径
         $bucket = 'minsu2';// 要上传的空间
         $createName = 'group_tour'.time().mt_rand(1, 100).'.jpg';// 上传到七牛后保存的文件名
         $urlPrefix = "";//生成的url前缀
         $data['imgurl']=$this->upload_qiniu($filename,$bucket,$createName,$urlPrefix);
         $detailarr=isset($data['detail'])?$data['detail']:'';
         $triparr=isset($data['trip'])?$data['trip']:'';
         $data['longitude']=0;
         $data['latitude']=0;
         $data['address']="";
         $data['starting_time'] =strtotime($data['starting_time']);
         $data['end_time'] =strtotime($data['end_time']);
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
         $Tour->setServicePrice(0);
         $Tour->setBookingNotice($data['booking_notice']);
         $Tour->setPlanned(0);
         $Tour->setDestination($data['destination']);
         $Tour->setAdultPrice($data['adult_price']);
         $Tour->setTelphone($data['telphone']);
         $Tour->setaddtime(time());
         $Tour->setEndtime($data['end_time']);
         $Tour->setLatitude($data['latitude']);
         $Tour->setLongitude($data['longitude']);
         $Tour->setGeohash($data['geohash']);
         $Tour->setAddress($data['address']);
         $Tour->setVideo("");
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
                      $Detail->setSite($key);
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
              return $this->redirectToRoute('addGroupTour_');    
              //return new JsonResponse(array('code'=>200,'msg'=>'添加成功','result'=>''));
          }else{
              return $this->redirectToRoute('addGroupTour_');
              //return new JsonResponse(array('code'=>300,'msg'=>'添加失败','result'=>''));
          }  
           
    }

     /**
     * 添加图片
     * @Route("/groupTourImagAdd",name="groupTourImagAdd_")
     */
    public function groupTourImagAdd() {
        //$filename = $_POST['file0'];// 要上传文件的本地路径
        $filename =$_FILES['file0']['tmp_name'];
        //return new JsonResponse ($filename);
        $bucket = 'minsu2';// 要上传的空间
        $createName = 'group_tour'.'11'.'.png';// 上传到七牛后保存的文件名
        $urlPrefix = "";//生成的url前缀
        $avatar=$this->upload_qiniu($filename,$bucket,$createName,$urlPrefix);
        $imgurl = $this->getParameter('app_qiniu_imgurl').$avatar;
        return new JsonResponse (array('imgurl'=>$imgurl,'avatar'=>$avatar));

    }

     /**
    * 
    * @Route("/group_tour_list",name="_group_tour_list")
    * @Template("AcmeMinsuBundle:GroupTour:grouptourlist.html.twig")
    */
   function group_tour_list(){
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
           ->select('count(m.tour_id) as total')
           ->from('msk_group_tour', 'm' )
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
           ->from ( 'msk_group_tour', 'm' )
           ->orderBy("m.addtime","DESC")
           ->where($where)
           ->setFirstResult($startPage)
           ->setMaxResults($pageSize)
           ->execute ();
       $data=$query->fetchAll();
       foreach ($data as $k=>$v){
           $data[$k]['imgurl'] = $this->getParameter("app_qiniu_imgurl").$v['imgurl'];
           $data[$k]['member_avatar'] = $this->getParameter("app_qiniu_imgurl").$v['member_avatar'];
       }
      return array(
          'oList'=>$data,
          'page'=>self::pageHtml($totalPage,'group_tour_list',$page,$prePage,$nextPage),
      );
   }

   /**
     * 更改团游状态
     * @Route("/GroupTourStateChange", name="GroupTourStateChange_")
     */
    public function GroupTourStateChange(Request $request)
    {
     
       $tour_id = $request->get('tour_id');
       $state = $request->get('state');
       $conn = $this->getDoctrine ()->getManager ()->getConnection ();
       $query = $conn->createQueryBuilder ()
           ->update('msk_group_tour')
           ->set('state',$state)
           ->where('tour_id='.$tour_id)
           ->execute();
       if ($query){
           return new JsonResponse(1);
       }else{
           return new JsonResponse(0);
       }
    }

    /**
     * 团游的详情
     * @Route("/groupTourDetail",name="groupTourDetail_")
     * @Template("AcmeMinsuBundle:GroupTour:groupTourDetail.html.twig")
     */
    public function groupTourDetail()
    {
        $hid = isset($_GET['tour_id'])?$_GET['tour_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        //查询导游的基本信息
        $hstReturn = $conn->createQueryBuilder ()
                    ->select ( "m.*,FROM_UNIXTIME(m.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime,FROM_UNIXTIME(m.starting_time, '%Y-%m-%d') NewStartingTime" )
                    ->from ( 'msk_group_tour', 'm' )
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
        $longitude = isset($tourdetail[0]['longitude'])?$tourdetail[0]['longitude']:0;
        $latitude = isset($tourdetail[0]['longitude'])?$tourdetail[0]['latitude']:0;
        return  array(
                'v'=>$hstReturn,
                'detail' => $tourdetail,
                'trip' => $tripdetail,
                'public_longitude' => $longitude,
                'public_latitude' => $latitude
            );
    }

     /**
     * 团游的报名详情
     * @Route("/groupTourEnroll",name="groupTourEnroll_")
     * @Template("AcmeMinsuBundle:GroupTour:groupTourEnroll.html.twig")
     */
    public function groupTourEnroll()
    {
        $tour_id = isset($_GET['tour_id'])?$_GET['tour_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到团游的相关报名
        $data = $conn->createQueryBuilder()
            ->select("a.*,FROM_UNIXTIME(a.enroll_time, '%Y-%m-%d %H:%i:%s') NewAddTime")
            ->from('msk_group_tour_forecast', 'a')
            ->Where("a.tour_id = $tour_id")
            ->andWhere("a.state !=2")
            ->orderBy("a.enroll_time","DESC")
            ->execute()
            ->fetchAll();
        foreach ($data as $key => $value) {
          $data[$key]['avatar'] = $this->getParameter("app_qiniu_imgurl").$value['avatar'];
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





























