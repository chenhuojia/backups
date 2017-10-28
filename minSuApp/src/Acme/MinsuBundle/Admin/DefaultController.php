<?php

namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\MinsuBundle\Entity\RoomServer;
use Acme\MinsuBundle\Entity\HomestayType;
use Acme\MinsuBundle\Entity\HotCity;
use Acme\MinsuBundle\Entity\Coupon;
use Acme\MinsuBundle\Entity\Group;
use Acme\MinsuBundle\Entity\Homestay;
use Acme\MinsuBundle\Entity\TravelNote;
use Acme\MinsuBundle\Entity\TravelNoteImage;
use Acme\MinsuBundle\Entity\Images;
use Acme\MinsuBundle\Entity\PointsGoods;
use Acme\MinsuBundle\Entity\MemberPoints;
use Acme\MinsuBundle\Entity\AppSuperAccount;
use Acme\MinsuBundle\Entity\IndexRec;
use Acme\MinsuBundle\Entity\GuideComment;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Geo\Geohash;
//use Symfony\Component\Translation\IdentityTranslator;

require '../vendor/php-sdk/autoload.php';
class DefaultController extends Controller
{
    private $qiniu='http://ogm9hltgr.bkt.clouddn.com/';
    
    private function QiniuToken($accessKey='exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT',$secretKey='lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC',$bucket='minsu2'){
            // 构建鉴权对象
           /*  $accessKey='qLnL64pg3KZ2JHWC2fXm-eaqS31wIt7fNKeQVviX';
            $secretKey='ehJe2v2kHDGpUHGNWYi7Otxy0X45d-ZAHbVu6UKI';
            $bucket='huojia'; */
            $auth = new Auth($accessKey, $secretKey);
            return $auth->uploadToken($bucket);
        }
    private function QiniuUpload($filename=0,$prefix=0){
        // 上传到七牛后保存的文件名
        $key = $prefix."_".time().mt_rand(1, 100).'.jpg';
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($this->QiniuToken(),$key,$filename);
        if ($err !== null) {
            return false;
        } else {
            return $ret['key'];
        }
    } 
    /**  骑牛二进制上传
     * @param unknown $data
     */
    private function QiniuloadBase64($data,$key){
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->put($this->QiniuToken(),$key,$data);
       return array(
            "state" =>"SUCCESS",
            "url" =>$key,
            "title" =>'',
            "original" =>'',
            "type" =>'',
            "size" =>'');
    }
    
    protected function addGeohash($longitude,$latitude)
    {
        require_once '../vendor/geohash.class.php';
        $geohash = new Geohash();
        //得到这点的hash值
        $hash = $geohash->encode($latitude, $longitude);
        return $hash;
    }
    
	/**
	 * @Route("/index",name="index_")
	 */
    public function indexAction()
    {
        return $this->render('AcmeMinsuBundle:Default:index.html.twig');
    }
    
    /**
     * @Route("/roomPicTest",name="roomPicTest_")
     * @Template("AcmeMinsuBundle:Default:RoomPic.html.twig")
     */
    public function roomPicTestAction(){
    	return array();
    }
    /**
     * @Route("/homestay",name="homestay_")
     */
public function homestayAction(Request $request)
    {
		$page = $request->get('page');
		if (empty($page)) {
			$page = 1;
		}
    	$pageSize = $this->getParameter('pagesize');
		$em = $this->getDoctrine ()->getManager ();

		$totalNumRes = $em->createQuery(
			"select count(p.id) from AcmeMinsuBundle:Homestay p"
		)
			->execute();
		$totalNum = $totalNumRes[0][1];

		$totalPage = ceil($totalNum / $pageSize);
		if ($totalPage != 0 && $page > $totalPage) {
			$page = $totalPage;
		}
		$startPage = ($page - 1) * $pageSize;

		$prePage = $page - 1;
		$nextPage = $page + 1;

    	if(isset($_POST['homestayState'])){
    		if($_POST['homestayState'] != ""){
    		$stat = $_POST['homestayState'];$where ="m.state = $stat";}else {$where ='m.state is not null';}
    	}else{
    		$where ='m.state is not null';
    	}
    	$conn = $em->getConnection ();
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*,mhy.homestay_type_name,mm.true_name,mm.is_owner,mifo.nickname,FROM_UNIXTIME(m.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime" )
    	->from ( 'msk_homestay', 'm' )
    	->leftjoin ( 'm', 'msk_homestay_type', 'mhy', 'm.homestay_type_id=mhy.id' )
    	->leftjoin ( 'm', 'msk_member', 'mm', 'm.member_id=mm.id' )
    	->leftjoin('m','msk_member_info','mifo','m.member_id=mifo.member_id')
    	->where ( "$where" )
    	->andWhere("m.is_manage=0") //        Ӫ
		->setFirstResult($startPage)
		->setMaxResults($pageSize)
		->orderBy("m.addtime","DESC")
    	->execute ();
    	$hsl = $a->fetchAll ();
    	
//     	echo print_r($hsl);exit();
    	return $this->render('AcmeMinsuBundle:Default:homestay.html.twig',
			array(
				'homestayList' => $hsl,
				'totalPage' => $totalPage,
				'page' => $page,
				'prePage' => $prePage,
				'nextPage' => $nextPage
			));
    }
    
    
    /**
     * @Route("/ownerApprove",name="ownerApprove_")
     */
    public function ownerApproveAction() {
    
    	if(isset($_POST['homestayState'])){
    		if($_POST['homestayState'] != ""){
    			$stat = $_POST['homestayState'];$where ="m.state = $stat";}else {$where ='m.state is not null';}
    	}else{
    		$where ='m.state is not null';
    	}
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*,mhy.homestay_type_name,mm.true_name,mm.is_owner,mifo.nickname,FROM_UNIXTIME(m.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime" )
    	->from ( 'msk_homestay', 'm' )
    	->leftjoin ( 'm', 'msk_homestay_type', 'mhy', 'm.homestay_type_id=mhy.id' )
    	->leftjoin ( 'm', 'msk_member', 'mm', 'm.member_id=mm.id' )
    	->leftjoin('m','msk_member_info','mifo','m.member_id=mifo.member_id')
    	->where ( "$where" )
    	->andWhere("m.is_manage=1") 
    	->orderBy("m.addtime","DESC")
    	->execute ();
    	$hsl = $a->fetchAll ();
    	 
    	// echo print_r($hsl);exit();
    	return $this->render('AcmeMinsuBundle:Default:ownerApprove.html.twig',array('homestayList'=>$hsl));
    }
    
    
    /**
     * @Route("/homestayDetail",name="homestayDetail_")
     */
    public function homestayDetailAction()
    {
        $hid = $_GET['hid'];
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	//��ȡ����
    	$hstReturn = $conn->createQueryBuilder ()
    	->select ( "m.*,mhy.homestay_type_name,mm.true_name" )
    	->from ( 'msk_homestay', 'm' )
    	->leftjoin ( 'm', 'msk_homestay_type', 'mhy', 'm.homestay_type_id=mhy.id' )
    	->leftjoin ( 'm', 'msk_member', 'mm', 'm.member_id=mm.id' )
    	->where ( "m.id = $hid" )
    	->execute ()->fetch();
        if($hstReturn['is_manage']==1){
            $yigong=$conn->createQueryBuilder()
                    ->select('*')
                    ->from('msk_community_post')
                    ->where('homestay_id='.$hid)
                    ->execute()->fetchAll();
            if (empty($yigong)){
                $yigong=0;
            }else{
                foreach ($yigong as $k=>$v){
                    $img=$conn->createQueryBuilder()
                    ->select('*')
                    ->from('msk_group_img')
                    ->where('groupId='.$v['id'])
                    ->execute()->fetch();
                    $imgs=explode(";",$img['imageName']);
                    foreach ($imgs as $kk=>$vv){
                        $imgs[$kk]=$this->qiniu.$vv;
                    }
                    $yigong[$k]['img']=$imgs;
                }
                
            }
        }
    	
    	//��ȡ����PIC
    $img=explode(';',$hstReturn['image_url']);
    foreach ($img as $k=>$v){
        $img[$k]=$this->qiniu.$v;
    }
   	$imgArray =$img;
   	$imgArray?	$hstReturn['image'] =$imgArray:$hstReturn['image']='';
   	
   	//��ȡ��֤�����ϴ�����
   	$ownerApproveImgArray =$this->getHRImg($hid,2);
   	$ownerApproveImgArray?	$hstReturn['ownerApproveImage'] =$ownerApproveImgArray:$hstReturn['ownerApproveImage']='';
   	
    	//��ȡ����
    	$b = $conn->createQueryBuilder ()
    	->select ( "m.*" )
    	->from ( 'msk_room', 'm' )
    	->where ( "m.homestay_id = $hid" )
    	->execute ();
    	$hroom = $b->fetchAll ();
    	
    	for ($i =0;$i<count($hroom);$i++){
    		$serid =$hroom[$i]['id'];
    		$c =$conn->createQueryBuilder ()
    			->select ( "mrs.server_name" )
    			->from( 'msk_room_server_relation','rsr' )
     			->leftjoin ( 'rsr','msk_room_server', 'mrs' ,'rsr.room_server_id=mrs.id' )
    			->where ( "rsr.room_id = $serid" )
    			->execute ();
    		$hroomser = $c->fetchAll ();
//     		print_r($hroomser);exit();
    		$hroom[$i]['room_server'] = $hroomser;
    		
    		//����ͼƬ����
    		$RimgArray =$this->getHRImg($serid,1);
    		$RimgArray?	$hroom[$i]['Rimage'] =$RimgArray:$hroom[$i]['Rimage']='';
    		
    	};

		$videoQry = $conn->createQueryBuilder()
			->select('c.goods_image')
			->from('msk_images', 'c')
			->where('c.img_type = :type')
			->andWhere('c.homestay_room_id = :hid')
			->setParameters(array('type' => 3, 'hid' => $hid))
			->execute();
		$videoRes = $videoQry->fetchAll();
//		$videoRes ? $video = $videoRes[0]['goods_image'] : '';
		if (!empty($videoRes)) {
			$memberPath = $this->getParameter('memberPath');
			$videoPath = $this->qiniu. $videoRes[0]['goods_image'];
		} else {
			$videoPath = '';
		}


    	return $this->render('AcmeMinsuBundle:Default:homestayDetail.html.twig',
			array(
				'v'=>$hstReturn,
				'room'=>$hroom,
				'video' => $videoPath,
			    'yigong'=>$yigong,
			));
    }
    
   //��ȡ����ͼƬ
    public function getHRImg($hid,$imgType){
    	if ($imgType==0){$FileName ='HomeStay';}elseif($imgType==1){$FileName='Room';}else {$FileName='Poster';}
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$query =$conn->createQueryBuilder ()
    	->select ( "m.*" )
    	->from ( 'msk_images', 'm' )
    	->where ( "m.homestay_room_id = $hid" )
    	->andWhere("m.img_type=$imgType")
    	->orderBy('m.is_default','DESC')
    	->execute ();
    	$data = $query->fetchAll ();
    	
    $url =	$this->container->getParameter('memberPath');
    	for ($i=0;$i<count($data);$i++){
    		$memberId =$data[$i]['member_id'];
    		$goodImg =$data[$i]['goods_image'];
    		$data[$i]['img_url'] = $url.$FileName.'/'.$memberId. '/'.$goodImg;	
    	}
    	
    	return $data ;
    }
    
    //��Ա
    /**
     * @Route("/member",name="member_")
     */
    public function memberAction(Request $request)
    {
    	if (isset($_POST['isOwner'])) {
    		if ($_POST['isOwner'] != "") {
    			$stat = $_POST['isOwner'];
				$where = "m.is_owner = $stat";
			} else {
				$where ='m.is_owner is not null';
			}
    	}else {
    		$where = 'm.member_state is not null';
    	}
    	
    	
    	//搜索
    	if (isset($_POST['searchText'])){
    	
    		$searchText  =$_POST['searchText'];
    		if($searchText!=''){
    		
    		  if($_POST['searchType']  =='nic'){
    		  	
    		  	$where  = $where ." and  p.nickname LIKE '%$searchText%' "; 
    		  	
    		  }elseif($_POST['searchType']  =='acc'){
    		  	
    		  		$where  = $where ." and  m.account LIKE '%$searchText%' "; 
    		  }
    		
    		}
    	}
    	
    	
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*,FROM_UNIXTIME(m.creat_date, '%Y-%m-%d %H:%i:%s') NewAddTime, p.nickname, c.state" )
    	->from ( 'msk_member', 'm' )
		->leftjoin('m', 'msk_member_info', 'p', 'm.id = p.member_id')
        ->leftJoin('m', 'msk_app_super_account', 'c', 'm.id = c.member_id')
    	->where ( "$where" )
    	->orderBy("m.creat_date","DESC")
    	->execute ();
    	$memberList = $a->fetchAll ();

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
    	 $avatar =	$memberList[$i]['avatar'] ;
    	 
    	 $avatarPath=$this->container->getParameter('app_qiniu_imgurl');
    	 $memberList[$i]['avatar_url'] =$avatarPath .$avatar;
    	 }

    	 
//     	 print_r($memberList);exit();
    	return $this->render('AcmeMinsuBundle:Default:user.html.twig',
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
	 * @Route("/memberStateChange", name="memberStateChange_")
	 */
	public function memberStateChangeAction (Request $request) {
		$state = $request->get('state');
		$account = $request->get('account');
		$addtime = $request->get('addtime');
		if (!$state && !$account) {
			return new Response(0);
		}

		$em = $this->getDoctrine()->getManager();
		$memberQry = $em->getRepository('AcmeMinsuBundle:Member')->findOneBy(
			array('account' => $account)
		);
		if (!$memberQry) {
			return new Response(0);
		}
		$memberQry->setMemberState($state);
		$em->flush();

		return new JsonResponse($state);
	}

    /**
     * @Route("/superStateChange", name="superStateChange_")
     */
    public function superStateChangeAction(Request $request)
    {
        $id = $request->get('id');
        $state = $request->get('state');

        $em = $this->getDoctrine()->getManager();
        $memberQry = $em->getRepository('AcmeMinsuBundle:AppSuperAccount')->findOneBy(
            array('member_id' => $id)
        );
        if (!$memberQry) {
            $mState = new AppSuperAccount();
            $mState->setAddTime(time());
            $mState->setMemberId($id);
            $mState->setState($state);
            $em->persist($mState);
            $em->flush();
            return new Response(1);
        }
        $memberQry->setState($state);
        $em->flush();
        return new Response(1);
    }
    
    /**
     * @Route("/roomserver",name="roomserver_")
     */
    public function roomserverAction()
    {
    
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*" )
    	->from ( 'msk_room_server', 'm' )
    	->orderBy("m.sort","DESC")
    	->execute ();
    	$rSerList = $a->fetchAll ();
    
    	return $this->render('AcmeMinsuBundle:Default:roomserver.html.twig',array('rSerList'=>$rSerList));
    }
    
    /**
     * @Route("/AddRoomServer",name="AddRoomServer_")
     */
    public function AddRoomServerAction()
    {
    	if (isset($_REQUEST['Serid'])) {
    		$serId =$_REQUEST['Serid'];
    		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    		$a = $conn->createQueryBuilder ()
    		->select ( "m.*" )
    		->from ( 'msk_room_server', 'm' )
    		->where("m.id=$serId")
    		->execute ();
    		$rSerList = $a->fetchAll ();
    		$serl =current($rSerList);
    	}else{
    		$serl ='';
    	}
    	
    	return $this->render('AcmeMinsuBundle:Default:RoomServerInfo.html.twig',array('v'=>$serl));
    }
    
    /**
     * @Route("/SaveRoomServer",name="SaveRoomServer_")
     */
    public function SaveRoomServerAction(){
    	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    	$manager = $this->getDoctrine()->getManager();
    	$rSData = new RoomServer();
    	$rSData->setServer_name($_POST['serName']);
    	$rSData->setSort($_POST['serSort']);
    	$rSData->setAddtime(time());
    	$manager->persist($rSData);
    	$manager->flush();    		
    		return $this->redirect($this->generateUrl('roomserver_'));
    		}
    	else{
    			return $this->redirect($this->generateUrl('AddRoomServer_'));
    	}
    }
    /**
     * @Route("/EditSaveRSer",name="EditSaveRSer_")
     */
    public function EditSaveRSerAction(){
    	$Id =$_POST['serId'];
    	$serName=$_POST['serName'];
    	$sort=$_POST['serSort'];
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$conn->createQueryBuilder ()
		->update ( 'msk_room_server', 'm' )
		->set ( 'm.server_name', "'$serName'" )
		->set ( 'm.sort', $sort )
		->where ( "m.id ='$Id'" )
		->execute ();
		return $this->redirect($this->generateUrl('roomserver_'));
    }
    
    
    /**
     * @Route("/deleteRoomServer",name="deleteRoomServer_")
     */
    public function deleteRoomServerAction() {
    	$manager = $this->getDoctrine ()->getManager ();
    	$biId=$_POST['RoomSerId'];
    	$st = $manager->getRepository ( "AcmeMinsuBundle:RoomServer" );
    	$st_data = $st->findOneBy( array('id'=>$biId) );
    
    	$manager->beginTransaction ();
    	$manager->remove ( $st_data );
    	$manager->flush ();
    	$manager->commit ();
    	
    	$sql="DELETE FROM msk_room_server_relation WHERE msk_room_server_relation.room_server_id =:id";
    	$params = array('id' => $biId);
    	$stmt = $manager->getConnection()->prepare($sql);
    	$stmt->execute($params);
    	
    	$msg='1';
    	return new JsonResponse ( $msg );
    }
    
    /*��������*/
    /**
     * @Route("/homestayType",name="homestayType_")
     */
    public function homestayTypeAction()
    {
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*" )
    	->from ( 'msk_homestay_type', 'm' )
    	->orderBy("m.sort","DESC")
    	->execute ();
    	$rSerList = $a->fetchAll ();
    
    	return $this->render('AcmeMinsuBundle:Default:homestayType.html.twig',array('rSerList'=>$rSerList));
    }

    /**
     * @Route("/AddHomeStayType",name="AddHomeStayType_")
     */
    public function AddHomeStayTypeAction()
    {
    	if (isset($_REQUEST['Serid'])) {
    		$serId =$_REQUEST['Serid'];
    		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    		$a = $conn->createQueryBuilder ()
    		->select ( "m.*" )
    		->from ( 'msk_homestay_type', 'm' )
    		->where("m.id=$serId")
    		->execute ();
    		$rSerList = $a->fetchAll ();
    		$serl =current($rSerList);
    	}else{
    		$serl ='';
    	}
    	 
    	return $this->render('AcmeMinsuBundle:Default:homestayTypeInfo.html.twig',array('v'=>$serl));
    }
    
    /**
     * @Route("/SaveHomestayType",name="SaveHomestayType_")
     */
    public function SaveHomestayTypeAction(){
    	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    		$manager = $this->getDoctrine()->getManager();
    		$htData = new HomestayType();
    		$htData->setHomestay_type_name($_POST['hotypeName']);
    		$htData->setSort($_POST['hotypeSort']);
    		$htData->setAddtime(time());
    		$manager->persist($htData);
    		$manager->flush();
    		return $this->redirect($this->generateUrl('homestayType_'));
    	}
    	else{
    		return $this->redirect($this->generateUrl('AddHomeStayType_'));
    	}
    }
    
    /**
     * @Route("/EditSaveHTpe",name="EditSaveHTpe_")
     */
    public function EditSaveHTpeAction(){
    	$Id =$_POST['hotypeId'];
    	$serName=$_POST['hotypeName'];
    	$sort=$_POST['hotypeSort'];
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$conn->createQueryBuilder ()
    	->update ( 'msk_homestay_type', 'm' )
    	->set ( 'm.homestay_type_name', "'$serName'" )
    	->set ( 'm.sort', $sort )
    	->where ( "m.id ='$Id'" )
    	->execute ();
    	return $this->redirect($this->generateUrl('homestayType_'));
    }
    
    /**
     * @Route("/deleteHType",name="deleteHType_")
     */
    public function deletedeleteHTypeAction() {
    	$manager = $this->getDoctrine ()->getManager ();
    	$biId=$_POST['HtId'];
    	$st = $manager->getRepository ( "AcmeMinsuBundle:HomestayType" );
    	$st_data = $st->findOneBy( array('id'=>$biId) );
    
    	$manager->beginTransaction ();
    	$manager->remove ( $st_data );
    	$manager->flush ();
    	$manager->commit ();
    	 
    	$msg='1';
    	return new JsonResponse ( $msg );
    }
    
    //���ų���
    /**
     * @Route("/hotCity",name="hotCity_")
     */
    public function hotCityAction()
    {
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*,ma.area_name" )
    	->from ( 'msk_hot_city', 'm' )
    	->leftjoin('m','msk_area','ma','m.area_id=ma.area_id')
    	->orderBy("m.sort","DESC")
    	->execute ();
    	$HotCL = $a->fetchAll ();
    	
    	$accName ='adminPublic';
    	$fileSavePath=	$this->container->getParameter('userUploadImagePath');
    	
//     	$path = '/msk/web/'.$fileSavePath . $accName.'/';
    	$path = $fileSavePath . $accName.'/';
    
    	return $this->render('AcmeMinsuBundle:Default:hotCity.html.twig',array('HotCL'=>$HotCL,'path'=>$path));
    }
    
    /**
     * @Route("/AddHotCity",name="AddHotCity_")
     * @Template("AcmeMinsuBundle:Default:hotCityInfo.html.twig")
     */
    public function AddHotCityTypeAction()
    {
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	if (isset($_REQUEST['hotCityId'])) {
    		$hctiyId =$_REQUEST['hotCityId'];
    		$a = $conn->createQueryBuilder ()
    			->select ( "m.*,ma.area_name,ma.area_parent_id" )
    			->from ( 'msk_hot_city', 'm' )
    			->leftjoin('m','msk_area','ma','m.area_id=ma.area_id')
    			->where("m.id=$hctiyId")
    			->orderBy("m.sort","DESC")
    			->execute ();
    	$HotCL = $a->fetchAll ();
    	$hotlist =current($HotCL);
    	$accName ="adminPublic";
 		$hotlist['img_url'] =$this->container->getParameter('adminUpLoadImgUI') .$accName."/" .$hotlist['img'];
 		$sondata =	$this->getParentId($hotlist['area_parent_id']);
 		$hotlist['SonData'] =  $sondata;
//    print_r($hotlist);exit();
    	}else{
    		$hotlist ='';
    	}
    	
    	//��ȡʡ����
    		$loc =$conn->createQueryBuilder ()
    		->select ( "m.*" )
    		->from ( 'msk_area', 'm' )
    		->where("m.area_parent_id=0")
    		->execute ();
    		$locSheng = $loc->fetchAll ();
    
    	return array('v'=>$hotlist,'locSheng'=>$locSheng);
    }
    
    /**
     * @Route("/SaveHotCity",name="SaveHotCity_")
     */
    public function SaveHotCityAction(){
    	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    		$manager = $this->getDoctrine()->getManager();
    		$htData = new HotCity();
    		$htData->setArea_id($_POST['hotCC']);
    		$htData->setImg($_POST['hiddenImgName']);
    		$htData->setSort($_POST['hotCSort']);
    		$htData->setAddtime(time());
    		$manager->persist($htData);
    		$manager->flush();
    		return $this->redirect($this->generateUrl('hotCity_'));
    	}
    	else{
    		return $this->redirect($this->generateUrl('AddHotCity_'));
    	}
    }
    /**
     * @Route("/EditSaveHotCity",name="EditSaveHotCity_")
     */
    public function EditSaveHotCityAction(){
    	$Id =$_POST['hcityId'];
    	$img=$_POST['hiddenImgName'];
    	$sort=$_POST['hotCSort'];
    	$arearId =$_POST['hotCC'];
    	
    	
    	//ɾ��ԭ��ͼƬ�ļ�
    	$this->deleteImgFile($Id,'msk_hot_city','img',$img,"adminPublic/");
    	
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$conn->createQueryBuilder ()
    	->update ( 'msk_hot_city', 'm' )
    	->set ( 'm.area_id', $arearId )
    	->set ( 'm.img', "'$img'" )
    	->set ( 'm.sort', $sort )
    	->where ( "m.id =$Id" )
    	->execute ();
    	return $this->redirect($this->generateUrl('hotCity_'));
    }
    
    /**
     * @Route("/deleteHotCity",name="deleteHotCity_")
     */
    public function deleteHotCityAction() {
    	$manager = $this->getDoctrine ()->getManager ();
    	$HId=$_POST['HotCityId'];
    	
    	//ɾ��ԭ��ͼƬ�ļ�
    	$this->deleteImgFile($HId,'msk_hot_city','*','','adminPublic/');
    	
    	$st = $manager->getRepository ( "AcmeMinsuBundle:HotCity" );
    	$st_data = $st->findOneBy( array('id'=>$HId) );
    
    	$manager->beginTransaction ();
    	$manager->remove ( $st_data );
    	$manager->flush ();
    	$manager->commit ();
    
    	$msg='1';
    	return new JsonResponse ( $msg );
    }
    
    //ȡ��ͬʡ����
    public function getParentId($parent_area_id){
    	
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*" )
    	->from ( 'msk_area', 'm' )
    	->where("m.area_parent_id=$parent_area_id")
    	->execute ();
    	$HotCL = $a->fetchAll ();
    	
    	return  $HotCL;
    	
    }
    
    //�Żݾ�
 	 /**
     * @Route("/coupon",name="coupon_")
     * @Template("AcmeMinsuBundle:Default:coupon.html.twig")
     */
    public function couponAction()
    {
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*" )
    	->from ( 'msk_coupon', 'm' )
    	->orderBy("m.sort","DESC")
    	->execute ();
    	$Coupon = $a->fetchAll ();
    
    	return array('couponList'=>$Coupon);
    }
    
    /**
     * @Route("/Addcoupon",name="Addcoupon_")
     */
    public function AddcouponAction()
    {
    	if (isset($_REQUEST['hotCityId'])) {
    		$Id =$_REQUEST['hotCityId'];
    		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    		$a = $conn->createQueryBuilder ()
    		->select ( "m.*" )
    		->from ( 'msk_coupon', 'm' )
    		->where("m.id=$Id")
    		->execute ();
    		$couponList = $a->fetchAll ();
    		$couponl =current($couponList);
    	}else{
    		$couponl ='';
    	}
    
    	return $this->render('AcmeMinsuBundle:Default:couponInfo.html.twig',array('v'=>$couponl));
    }
    
    /**
     * @Route("/SaveCoupon",name="SaveCoupon_")
     */
    public function SaveCouponAction(){
    	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    		$manager = $this->getDoctrine()->getManager();
    		$couponData = new Coupon();
    		$couponData->setCoupon_value($_POST['couponValue']);
    		$couponData->setConvert_points($_POST['couponPoints']);
    		$couponData->setMin_amount($_POST['minAmount']);
    		$couponData->setCoupon_dscp($_POST['couponDscp']);
    		$couponData->setSort($_POST['couponSort']);
    		$couponData->setAddtime(time());
    		$couponData->setQoutaDay($_POST['qouta_day']);
    		$couponData->setState($_POST['state']);
    		$manager->persist($couponData);
    		$manager->flush();
    		return $this->redirect($this->generateUrl('coupon_'));
    	}
    	else{
    		return $this->redirect($this->generateUrl('Addcoupon_'));
    	}
    }
    
    /**
     * @Route("/EditSaveCoupon",name="EditSaveCoupon_")
     */
    public function EditSaveCouponAction(){
    	$Id =$_POST['couponId'];
    	$couponValue=$_POST['couponValue'];
    	$sort=$_POST['couponSort'];
    	$couponPoints =$_POST['couponPoints'];
    	$minAmount =$_POST['minAmount'];
    	$couponDscp =$_POST['couponDscp'];
    	$qd  =$_POST['qouta_day'];
    	$sta  =$_POST['state'] ;
    	
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$conn->createQueryBuilder ()
    	->update ( 'msk_coupon', 'm' )
    	->set ( 'm.coupon_value', $couponValue )
    	->set ( 'm.convert_points',$couponPoints)
    	->set( 'm.min_amount', $minAmount)
    	->set( 'm.coupon_dscp',"'$couponDscp'")
    	->set( 'm.qouta_day' , $qd)
    	->set( 'm.state' ,$sta )
    	->set ( 'm.sort', $sort )
    	->where ( "m.id =$Id" )
    	->execute ();
    	return $this->redirect($this->generateUrl('coupon_'));
    }
    
    /**
     * @Route("/deleteCoupon",name="deleteCoupon_")
     */
    public function deleteCouponAction() {
    	$manager = $this->getDoctrine ()->getManager ();
    	$HId=$_POST['couponId'];
    	$st = $manager->getRepository ( "AcmeMinsuBundle:Coupon" );
    	$st_data = $st->findOneBy( array('id'=>$HId) );
    
    	$manager->beginTransaction ();
    	$manager->remove ( $st_data );
    	$manager->flush ();
    	$manager->commit ();
    
    	$msg='1';
    	return new JsonResponse ( $msg );
    }
    
  
    /**
     * 积分记录
     * @Route("/points",name="points_")
     * @Template("AcmeMinsuBundle:Default:points.html.twig")
     */
    public function pointsAction()
    {
//     echo date('Y-m-d H:i:s',"1461312499");
//     	if(isset($_POST['isOwner'])){
//     		if($_POST['isOwner'] != ""){
//     			$stat = $_POST['isOwner'];$where ="m.is_owner = $stat";}else {$where ='m.is_owner is not null';}
//     	}else{
//     		$where ='m.member_state is not null';
//     	}
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*,mmi.nickname,mm.account,mm.true_name, FROM_UNIXTIME(m.pl_addtime, '%Y-%m-%d %H:%i:%s') NewAddTime" )
    	->from ( 'msk_points', 'm' )
    	->leftJoin('m','msk_member','mm','m.pl_memberid=mm.id')
    	->leftJoin('m','msk_member_info','mmi','m.pl_memberid=mmi.member_id')
//     	->where ( "$where" )
    	->orderBy("m.pl_addtime","DESC")
    	->execute ();
    	$pointsList = $a->fetchAll ();
    
    	return array('pList'=>$pointsList);
    }
    
    /**
     * 积分记录
     * @Route("/pointsGoods",name="points_goods")
     * @Template("AcmeMinsuBundle:Default:pointsGoods.html.twig")
     */
    public function pointsGoodsAction() {
    	
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*" )
    	->from ( 'msk_points_goods', 'm' )
    	->orderBy("m.addtime","DESC")
    	->execute ();
    	$pointsList = $a->fetchAll ();
    	
    	
    	$accName ='goods';
    	$fileSavePath=	$this->container->getParameter('userUploadImagePath');
    	 
    	//     	$path = '/msk/web/'.$fileSavePath . $accName.'/';
    	$path = $fileSavePath . $accName.'/';
    
    	return array('pList'=>$pointsList,'path'=>$path);
    	
    }
    
    /**
     * 赠送积分页面
     * @Route("/addPoints",name="add_points_")
     * @Template("AcmeMinsuBundle:Default:pointsInfo.html.twig")
     */
    public function addPointsAction(){
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$query = $conn->createQueryBuilder ()
    	->select ( "m.*,mmi.nickname" )
    	->from ( 'msk_member', 'm' )
    	->leftJoin('m','msk_member_info','mmi','m.id=mmi.member_id')
    	->orderBy('m.creat_date','DESC')
    	 
    	->execute ();
    	$memberdata = $query->fetchAll ();
    	
    	return array('meb'=>$memberdata);
    }
    
    /**
     * 保存积分记录
     * @Route("/saveAddPoints",name="save_add_points_")
     */
    public function saveAddPointsAction(){

    	$manager = $this->getDoctrine()->getManager();
    	
    	$conn = $manager->getConnection ();
    	
    	$memberId = isset($_POST['memberId'])?$_POST['memberId']:"";
    	
    	$points = isset($_POST['points'])?$_POST['points']:"";
    	
    	$desc = isset($_POST['desc'])?$_POST['desc']:"";
    	
    	$eng = isset($_POST['eng'])?$_POST['eng']:0;
    	
    
    		$hsdata = new MemberPoints();
    		$hsdata->setPl_memberid($memberId);
    		$hsdata->setPl_points($points);
    		$hsdata->setPl_eng($eng);
    		$hsdata->setPl_desc("$desc");
    		$hsdata->setPl_addtime(time());
    	
    		$manager->persist($hsdata);
    		$manager->flush();
    		
    				
    			if($hsdata->getId()){
    	
    				$data  =$this->changeTotalPoints($memberId);
    				$total_points = $data['totalPoints'];
    	
    				if($total_points){
    						
    						
    					$conn->createQueryBuilder ()
    					->update ( 'msk_member', 'm' )
    					->set ( 'm.member_points', $total_points )
    					->where ( "m.id =$memberId" )
    					->execute ();
    				}
    	
    			}
    	
    	return $this->redirect($this->generateUrl('points_'));
    }
    
    
    /**
     * 添加新积分产品
     * @Route("/AddPointsGoods",name="AddPointsGoods_")
     * @Template("AcmeMinsuBundle:Default:pointsGoodsInfo.html.twig")
     */
    public function AddPointsGoodsAction()
    {
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	
    	if (isset($_REQUEST['goodsId'])) {
    		
    		$id =$_REQUEST['goodsId'];
    		$a = $conn->createQueryBuilder ()
    		->select ( "m.*" )
    		->from ( 'msk_points_goods', 'm' )
    		->where("m.id=$id")
    		->orderBy("m.sort","DESC")
    		->execute ();
    		$HotCL = $a->fetchAll ();
    		$hotlist =current($HotCL);
    		$accName ="goods";
    		$hotlist['img_url'] =$this->container->getParameter('adminUpLoadImgUI') .$accName."/" .$hotlist['goods_images'];
    		
    	}else{
    		$hotlist ='';
    	}

    	return array('v'=>$hotlist);
    }
    
    
    /**
     * @Route("/SavePointsGoods",name="SavePointsGoods_")
     */
    public function SavePointsGoodsAction(){
    	
    	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    		$manager = $this->getDoctrine()->getManager();
    		
    		$htData = new PointsGoods();
    		
    		$htData->setGoods_name($_POST['goods_name']);
    		
    		$htData->setGoods_points($_POST['goods_points']);
    		
    		$htData->setQuantity($_POST['quantity']);
    		
    		$htData->setGoods_images($_POST['hiddenImgName']);
    		
    		$htData->setState($_POST['state']);
    		
    		$htData->setSort($_POST['sort']);
    		
    		$htData->setAddtime(time());
    		
    		
    		$manager->persist($htData);
    		$manager->flush();
    		
    		return $this->redirect($this->generateUrl('points_goods'));
    	}
    	else{
    		return $this->redirect($this->generateUrl('AddPointsGoods_'));
    	}
    }
    /**
     * @Route("/EditSavePointsGoods",name="EditSavePointsGoods_")
     */
    public function EditSavePointsGoodsAction(){
    	$Id =$_POST['goodsId'];
    	
    	$img=$_POST['hiddenImgName'];
    	  	
    	$sort=$_POST['sort'];
    	
    	$goods_name=$_POST['goods_name'];
    	
    	$goods_points=$_POST['goods_points'];
    	
    	$quantity=$_POST['quantity'];
    	
    	$state=$_POST['state'];

    	
    	$this->deleteImgFile($Id,'msk_points_goods','goods_images',$img,"goods/");
    	 
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	
    	$conn->createQueryBuilder ()
    	->update ( 'msk_points_goods', 'm' )
    	->set ( 'm.goods_name', "'$goods_name'" )
    	->set ( 'm.goods_points', $goods_points )
    	->set ( 'm.quantity', $quantity )
    	->set ( 'm.goods_images', "'$img'" )
    	->set ( 'm.state', $state )
    	->set ( 'm.sort', $sort )
    	->where ( "m.id =$Id" )
    	->execute ();
    	return $this->redirect($this->generateUrl('points_goods'));
    }
    
    
    /**
     * @Route("/deletePointsGoods",name="deletePointsGoods_")
     */
    public function deletePointsGoodsAction() {
    	$manager = $this->getDoctrine ()->getManager ();
    	$HId=$_POST['goodsId'];
    	 
    	//删除文件
    	$this->deleteImgFile($HId,'msk_points_goods','goods_images','','goods/');
    	 
    	$st = $manager->getRepository ( "AcmeMinsuBundle:PointsGoods" );
    	$st_data = $st->findOneBy( array('id'=>$HId) );
    
    	$manager->beginTransaction ();
    	$manager->remove ( $st_data );
    	$manager->flush ();
    	$manager->commit ();
    
    	$msg='1';
    	return new JsonResponse ( $msg );
    }
    
    /**
     * 积分兑换记录
     * @Route("/pointsGoodsOrderList",name="points_goodsorder_list")
     * @Template("AcmeMinsuBundle:Default:pointsGoodsOrderList.html.twig")
     */
    public function pointsGoodsOrderListAction() {
    	
    	
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*,mmi.nickname,mm.account,mm.true_name, FROM_UNIXTIME(m.addtime, '%Y-%m-%d %H:%i:%s') newAddTime,mpg.goods_name,mpg.goods_points" )
    	->from ( 'msk_points_goods_order', 'm' )
    	->leftJoin('m','msk_member','mm','m.member_id=mm.id')
    	->leftJoin('m','msk_member_info','mmi','m.member_id=mmi.member_id')
    	->leftJoin('m','msk_points_goods','mpg','m.goods_id=mpg.id')
    	//     	->where ( "$where" )
    	->orderBy("m.addtime","DESC")
    	->execute ();
    	$pointsList = $a->fetchAll ();
    	
    	return array('plist'=>$pointsList);
    }
    

  /**
     * @Route("/travelManner",name="travelManner_")
     * @Template("AcmeMinsuBundle:Default:travelManner.html.twig")
     */
    public function travelAction(Request $request)
    {
    	//     echo date('Y-m-d H:i:s',"1461312499");
    	    	if(isset($_POST['logoState'])){
    	    		if($_POST['logoState'] != ""){
    	    			$stat = $_POST['logoState'];$where ="m.logo_state = $stat";}else {$where ='m.logo_state is not null';}
    	    	}else{
    	    		$where ='m.logo_state is not null';
    	    	}
    	    	
//     	    	echo  $where ;exit();
		$pageSize = $this->getParameter('pagesize');
		$page = $request->get('page');

		$em = $this->getDoctrine()->getManager();
		$totaleNumQry = $em->createQuery(
			"select count(p.id) from AcmeMinsuBundle:TravelNote p"
		);
		$totaleNum = $totaleNumQry->execute()[0][1];
		$totalPage = ceil($totaleNum / $pageSize);

		if ($page < 1) {
			$page = 1;
		}
		if ($totalPage != 0 && $page > $totalPage) {
			$page = $totalPage;
		}
		$prePage = $page - 1;
		if ($prePage < 1) {
			$prePage = 1;
		}
		$nextPage = $page + 1;
		if ($nextPage > $totalPage) {
			$nextPage = $totalPage;
		}

		$startPage = ($page - 1) * $pageSize;
		$pageData = array('prePage' => $prePage, 'nextPage' => $nextPage, 'totalPage' => $totalPage, 'page' => $page);

		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$a = $conn->createQueryBuilder ()
			->select ( "m.*,mm.account,mm.true_name, FROM_UNIXTIME(m.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime,mmi.nickname" )
			->from ( 'msk_travel_note', 'm' )
			->leftjoin('m','msk_member','mm','m.member_id=mm.id')
			->leftjoin('mm','msk_member_info','mmi','mm.id=mmi.member_id')
			->where($where)
			->setFirstResult($startPage)
			->setMaxResults($pageSize)
			->orderBy("m.addtime","DESC")
			->execute ();
		$trList = $a->fetchAll ();

		return array('trList' => $trList, 'pageData' => $pageData);
    }

	/**
	 * @Route("/travelNoteDetail", name="travelNoteDetail_")
	 * @Template("AcmeMinsuBundle:Default:travelNoteDetail.html.twig")
	 */
	public function travelNOteDetailAction(Request $request)
	{
		$travelNoteId = $request->get("id");
		$em = $this->getDoctrine()->getManager();
		$conn = $em->getConnection();
		$travelNoteQry = $conn->createQueryBuilder()
			->select(
				"p.travel_title", "p.travel_content", "p.addr", "p.recommend_homestay", "p.state", "p.addtime",
				"c.nickname", 'p.article_click', 'p.province', 'p.longitude', 'p.latitude'
			)
			->from("msk_travel_note", "p")
			->leftJoin("p", "msk_member_info", "c", "p.member_id = c.member_id")
			->where("p.id = :id")
			->setParameter('id', $travelNoteId)
			->execute();
		$travelNoteRes = $travelNoteQry->fetch();
		$travelNoteRes['travel_content']= htmlspecialchars_decode($travelNoteRes['travel_content']);
		$travelNoteImgQry = $conn->createQueryBuilder()
			->select(
				"p.travel_note_image", "p.is_default"
			)
			->from("msk_travel_note_images", "p")
			->where("p.travel_note_id = :id")
			->setParameter("id", $travelNoteId)
			->orderBy("p.travel_note_image_sort", "desc")
			->execute();
		$travelNoteImgRes = $travelNoteImgQry->fetchAll();

		$travelNoteImgArrLenth = count($travelNoteImgRes);
		for ($i = 0; $i < $travelNoteImgArrLenth; $i++) {
			$imgPath = $this->qiniu. $travelNoteImgRes[$i]['travel_note_image'];
			$travelNoteImgRes[$i]['travel_note_image'] = $imgPath;
		}

		return array('travelNote' => $travelNoteRes, 'img' => $travelNoteImgRes);
	}

	/**
	 * @Route("/shieldTravelNote", name="shieldTravelNote_")
	 */
	public function shieldTravelNotAction(Request $request)
	{
		$id = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getManager();
		$travelNote = $em->getRepository('AcmeMinsuBundle:TravelNote')->find($id);
		$travelNote->setState($state);
		$em->flush();

		return new Response(1);

	}

    
    /********S����С��******/
    
    /**
     * @Route("/group",name="group_")
     * @Template("AcmeMinsuBundle:Default:group.html.twig")
     */
    public function groupAction(Request $request) {
        $page = $request->get('page');
        if (empty($page)) {
            $page = 1;
        }
        $pageSize = $this->getParameter('pagesize');
        $em = $this->getDoctrine ()->getManager ();
        $conn = $em->getConnection ();

        $totalNumRes = $conn->createQueryBuilder()
            ->select("count(p.id)")
            ->from('msk_group', 'p')
            ->execute();
        $totalNum = $totalNumRes->fetchAll();

        $totalPage = ceil($totalNum[0]['count(p.id)'] / $pageSize);
        if ($totalPage != 0 && $page > $totalPage) {
            $page = $totalPage;
        }
        $startPage = ($page - 1) * $pageSize;

        $prePage = $page - 1;
        $nextPage = $page + 1;

    	$a = $conn->createQueryBuilder ()
    	->select ( "m.*" )
    	->from ( 'msk_group', 'm' )
        ->setFirstResult($startPage)
        ->setMaxResults($pageSize)
    	->orderBy("m.sort","DESC")
    	->execute ();
    	$gdata = $a->fetchAll ();
    	
    	$fileSavePath=	$this->container->getParameter('app_group_path');
    	 
    	
    	return array(
            'gdata'=>$gdata,
            'path'=>$fileSavePath,
            'prePage' => $prePage,
            'nextPage' => $nextPage,
            'totalPage' => $totalPage,
        );
    	
    }
    
    
    /**
     * @Route("/AddGroup",name="AddGroup_")
     * @Template("AcmeMinsuBundle:Default:groupInfo.html.twig")
     */
    public function AddGroupAction()
    {
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	if (isset($_REQUEST['groupId'])) {
    		$gId =$_REQUEST['groupId'];
    		$a = $conn->createQueryBuilder ()
    		->select ( "m.*" )
    		->from ( 'msk_group', 'm' )
    		->where("m.id=$gId")
    		->orderBy("m.sort","DESC")
    		->execute ();
    		$HotCL = $a->fetchAll ();
    	
    		$hotlist =current($HotCL);
    		$accName ="adminPublic";
    		
    		$fileSavePath=	$this->container->getParameter('app_group_path');
    		
    		$hotlist['img_url'] =$fileSavePath .$hotlist['img'];
    	}else{
    		$hotlist ='';
    	}
    	 
    	return array('v'=>$hotlist);
    }
    
    /**
     * @Route("/SaveGroup",name="SaveGroup_")
     */
    public function SaveGroupAction(){
    	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    		$manager = $this->getDoctrine()->getManager();
    		$htData = new Group();
    		$htData->setGroupName($_POST['groupName']);
    		$htData->setDscp($_POST['groupDscp']);
    		$htData->setImg($_POST['hiddenImgName']);
    		$htData->setFollowNum($_POST['followNum']);
    		$htData->setPostNum($_POST['postNum']);
    		$htData->setSort($_POST['groupSort']);
    		$htData->setAddtime(time());
    		$manager->persist($htData);
    		$manager->flush();
    		return $this->redirect($this->generateUrl('group_'));
    	}
    	else{
    		return $this->redirect($this->generateUrl('AddGroup_'));
    	}
    }
    
    /**
     * @Route("/EditSaveGroup",name="EditSaveGroup_")
     */
    public function EditSaveGroupAction(){
    	$Id =$_POST['gId'];
    	$img=$_POST['hiddenImgName'];
    	$sort=$_POST['groupSort'];
    	$dscp =$_POST['groupDscp'];
    	$followNum =$_POST['followNum'];
    	$postNum =$_POST['postNum'];
    	$groupName =$_POST['groupName'];
    	
    	//ɾ��ԭ��ͼƬ�ļ�
    	$this->deleteImgFile($Id,'msk_group','img',$img,'group/');
    	
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$conn->createQueryBuilder ()
    	->update ( 'msk_group', 'm' )
    	->set ( 'm.groupName', "'$groupName'" )
    	->set ( 'm.img', "'$img'" )
    	->set('m.dscp',"'$dscp'")
    	->set('m.followNum',$followNum)
    	->set('m.postNum',$postNum)
    	->set ( 'm.sort', $sort )
    	->where ( "m.id =$Id" )
    	->execute ();
    	
    	
    	return $this->redirect($this->generateUrl('group_'));
    }
    
    
    /**
     * @Route("/deleteGroup",name="deleteGroup_")
     */
    public function deleteGroupAction() {
    	$manager = $this->getDoctrine ()->getManager ();
    	$HId=$_POST['gId'];
    	
    	//ɾ��ԭ��ͼƬ�ļ�
    	$this->deleteImgFile($HId,'msk_group','*','','group/');
    	
    	
    	$st = $manager->getRepository ( "AcmeMinsuBundle:Group" );
    	$st_data = $st->findOneBy( array('id'=>$HId) );
    
    	$manager->beginTransaction ();
    	$manager->remove ( $st_data );
    	$manager->flush ();
    	$manager->commit ();

    	$msg='1';
    	return new JsonResponse ( $msg );
    }
    
    /********E����С��******/
    
    
    public function deleteImgFile($id,$table,$field,$oldImg,$fileName){
    	
    	
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$query = $conn->createQueryBuilder ()
    	->select ( "m.$field" )
    	->from ( $table, 'm' )
    	->where("m.id=$id")
    	->orderBy("m.sort","DESC")
    	->execute ();
    	$data = $query->fetchAll ();
    	$cdata =current($data);
    	$imgName =$cdata[$field];
    	$data_file_to_delete =$this->container->getParameter('delAdminPath').$fileName.$imgName;
    	
//     	echo $data_file_to_delete;exit();
    	
    	if ($oldImg != $imgName){
    	if(file_exists($data_file_to_delete))
    	{
    		unlink($data_file_to_delete);
    	}
    	}
    	
    	return true;
    }
    /**
     * @Route("/addTravelNote", name="addTravelNote_")
     */
    public function addTravelNoteAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$conn = $em->getConnection();
    	$memberQry = $conn->createQueryBuilder()
    	->select(
    			"p.id, p.account, c.nickname"
    			)
    			->from('msk_member', 'p')
    			->leftJoin('p', 'msk_member_info', 'c', 'p.id = c.member_id')
    			->where('p.member_state = :state')
    			->setParameter('state', 1)
    			->execute();
    			$memberQryRes = $memberQry->fetchAll();
    
    			$provinceQry = $conn->createQueryBuilder()
    			->select("p.area_name, p.area_id")
    			->from("msk_area", 'p')
    			->where("p.area_parent_id = :pid")
    			->setParameter('pid', 0)
    			->execute();
    			$provinceQryRes = $provinceQry->fetchAll();
    			//		var_dump($provinceQryRes);
    
    			return $this->render('AcmeMinsuBundle:Default:addTravelNote.html.twig',
    					array('v' => $memberQryRes, 'p' => $provinceQryRes));
    }
    
    /**
     * @Route("/accountLoad", name="accountLoad_")
     */
    public function accountLoadAction(Request $request)
    {
    	$id = $request->get('id');
    	$em = $this->getDoctrine()->getManager();
    	$conn = $em->getConnection();
    	$memberQry = $conn->createQueryBuilder()
    	->select(
    			"p.id, p.account, c.nickname"
    			)
    			->from('msk_member', 'p')
    			->leftJoin('p', 'msk_member_info', 'c', 'p.id = c.member_id')
    			->where('p.member_state = :state')
    			->andWhere('p.id = :id')
    			->setParameters(array('state' => 1, 'id' => $id))
    			->execute();
    			$memberQryRes = $memberQry->fetchAll();
    			return new Response($memberQryRes[0]['account']);
    }
    
    /**
     * @Route("/saveTravelNote", name="saveTravelNote_")
     */
    public function saveTravelNoteAction(Request $request)
    {   
    	$memberId = $request->get('member');
    	if (empty($_FILES['imageUpload']['tmp_name'])) $this->Send(300,'请插入封面');
    	$content = $request->get('editorValue');
		$title=$request->get('title');
    	$province = $request->get('province');
    	$city = $request->get('city');
    	$addr = $request->get('addr');
    	$district = $request->get('district');
    	$otherImage=$request->get('otherimage');
        $longitude=$request->get('longitude');
		$latitude=$request->get('latitude');
    	$ip = $_SERVER['REMOTE_ADDR'];    	
    	$em = $this->getDoctrine()->getManager();
    	$conn = $em->getConnection();
    	$travelNote = new TravelNote();
    	$travelNote->setMemberId($memberId);
    	$travelNote->setTravelTitle($title);
    	$travelNote->setTravelContent($content);
    	$travelNote->setAddr($addr);
    	$travelNote->setHomestay_id(0);    	
    	$travelNote->setRecommendHomestay(0);
    	$travelNote->setState(0);
    	$travelNote->setAddtime($_SERVER['REQUEST_TIME']);
    	$travelNote->setUploadIp("$ip");
    	$travelNote->setLongitude($longitude);
    	$travelNote->setLatitude($latitude);
    	$travelNote->setProvince($province);
    	$travelNote->setCity($city);
    	$travelNote->setDistrict($district);
    	$travelNote->setPay_money(0);
    	$travelNote->setMark_travel_id(0);
    	$em->persist($travelNote);
    	$em->flush();
    	$id = $travelNote->getId();
    	if (!empty($otherImage)) {   	
			foreach ($otherImage as $k=>$v){
				$travelNoteImage = new TravelNoteImage();
    			$travelNoteImage->setTravelNoteId($id);
    			$travelNoteImage->setTravelNoteImage($v);
    			$travelNoteImage->setIsDefault('0');
    			$travelNoteImage->setTravelNoteImageSort($k);
    			$travelNoteImage->setAddTime($_SERVER['REQUEST_TIME']);
    			$em->persist($travelNoteImage);
    			$em->flush();	
			}
    	}
    	if ($newImageName=self::QiniuUpload($_FILES['imageUpload']['tmp_name'], 'minsu')) {
    		$travelNoteImage = new TravelNoteImage();
    		$travelNoteImage->setTravelNoteId($id);
    		$travelNoteImage->setTravelNoteImage($newImageName);
    		$travelNoteImage->setIsDefault('1');
    		$travelNoteImage->setTravelNoteImageSort(0);
    		$travelNoteImage->setAddTime($_SERVER['REQUEST_TIME']);
    		$em->persist($travelNoteImage);
    		$em->flush();
    	}
    	return $this->redirectToRoute('addTravelNote_');      
    }
    
    /**
     * @Route("/sltCity", name="sltCity_")
     */
    public function sltCityAction(Request $request)
    {
    	$id = $request->get('id');
    	$em = $this->getDoctrine()->getManager();
    	$conn = $em->getConnection();
    	$cityQuery = $conn->createQueryBuilder()
    	->select(
    			"p.area_name, p.area_id"
    			)
    			->from('msk_area', 'p')
    			->where('p.area_parent_id = :id')
    			->setParameters(array('id' => $id))
    			->execute();
    			$cityQueryRes = $cityQuery->fetchAll();
    			return new JsonResponse($cityQueryRes);
    }
    
    /**
     * @Route("/baiduMapQry", name="baiduMapQry_")
     */
    public function baiduMapQryAction($addr, $city)
    {
    	try {
    		$url = "http://api.map.baidu.com/geocoder/v2/";
    		$val = array(
    				'address' => $addr,
    				'city' => $city,
    				'ak' => '2AFGg6DCpGbCg48f2mOFNnGLAh02ZQHU',
    				'output' => 'json'
    		);
    		$options = array(
    				'http' => array(
    						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
    						'method'  => 'POST',
    						'content' => http_build_query($val)
    				)
    		);
    		$context = stream_context_create($options);
    		$data = file_get_contents($url, false, $context);
    
    		$deJsonData = json_decode($data, true);
    		return $deJsonData;
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }
    
    /**
     * @Route("/imgupl", name="imgupl_")
     */
    public function imguplAction(Request $request)
    {
    	date_default_timezone_set("Asia/chongqing");
    	error_reporting(E_ERROR);
    	header("Content-Type: text/html; charset=utf-8");
    
    	$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("bundles/msk/public/js/php/config.json")), true);
    	$action = $_GET['action'];
    	switch ($action) {
    		case 'config':
    			$result =  json_encode($CONFIG);
    			break;
    
    			/* 上传图片 */
    		case 'uploadimage':
    			/* 上传涂鸦 */
    		case 'uploadscrawl':
    			/* 上传视频 */
    		case 'uploadvideo':
    			/* 上传文件 */
    		case 'uploadfile':
    			$data=file_get_contents($_FILES['upfile']['tmp_name']);
    		    $key='sense'.microtime().'.png';
    		    $result=$this->QiniuloadBase64($data,$key);
    		    $result['url']=$this->qiniu.$key;
    		   echo json_encode($result);exit;
    			break;
    
    			/* 列出图片 */
    		case 'listimage':
    			$result = include("action_list.php");
    			break;
    			/* 列出文件 */
    		case 'listfile':
    			$result = include("action_list.php");
    			break;
    
    			/* 抓取远程文件 */
    		case 'catchimage':
    			$result = include("action_crawler.php");
    			break;
    
    		default:
    			$result = json_encode(array(
    			'state'=> '请求地址出错'
    					));
    			break;
    	}
    
    	/* 输出结果 */
    	if (isset($_GET["callback"])) {
    		if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
    			return new Response( htmlspecialchars($_GET["callback"]) . '(' . $result . ')');
    		} else {
    			return new Response(json_encode(array(
    					'state'=> 'callback参数不合法'
    			)));
    		}
    	} else {
    		return new Response($result);
    	}
    }
    
    /**
     * 后台添加民宿
     * @Route("/addHomestay",name="a_d_d_h_o_m_e_s_t_a_y")
     * @Template("AcmeMinsuBundle:Default:addHomestay.html.twig")
     */
    public function addHomestayAction() {
    	//读取会员信息
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$query = $conn->createQueryBuilder ()
    	->select ( "m.*,mmi.nickname" )
    	->from ( 'msk_member', 'm' )
    	->leftJoin('m','msk_member_info','mmi','m.id=mmi.member_id')
    	->orderBy('m.creat_date','DESC')
    	
    	->execute ();
    	$memberdata = $query->fetchAll ();
    	
    	
    	
    	$queryhtype = $conn->createQueryBuilder()
    	->select('m.*')
    	->from('msk_homestay_type', 'm')
    	->orderBy('m.sort','DESC')
    	->execute();
    	$htypdata = $queryhtype->fetchAll();
    	
    	
    	
    	 
    	return array('meb'=>$memberdata,'ht' =>$htypdata);
    }

    
    /**
     * 后台添加民宿
     * @Route("/map",name="map_")
     * @Template("AcmeMinsuBundle:Default:map.html.twig")
     */
    public function mapAction() {
    
    	
    	if (isset($_GET['adr'])){
    		$adr  = $_GET['adr'];
    		
    	}else {
    		
    		$adr  = '';
    	}
    	return array('adr'=>$adr);
    }
    
    /**
     * 后台传图片
     * @Route("/imgupload",name="img_load")
     * @Template("AcmeMinsuBundle:Default:uploadImages.html.twig")
     */
 /*   public function imguploadAction() {
    
    	require '../vendor/JqueryFileUpload/UploadHandler.php';
	
    			
   		$upload_handler = new \UploadHandler();
    }*/
    
    
    
    
    /**
     * 后台保存民宿
     * @Route("/saveHomestayData",name="save_homestay_data")
     */
    public function saveHomestayDataAction() {  
	    $data = isset($_POST)?$_POST:'';
		$manager = $this->getDoctrine()->getManager();    	
		/* $hsdata = new Homestay();
		$hsdata->setMember_id($data['member_id']);
		$hsdata->setHomestay_name($data['homestay_name']);
		$hsdata->setHomestay_title($data['homestay_title']);
		$hsdata->setHomestay_type_id($data['homestay_type_id']);
		$hsdata->setBottom_price(isset($data['bottom_price'])?$data['bottom_price']:0);
		$hsdata->setHomestay_phone(isset($data['homestay_phone'])?$data['homestay_phone']:0);
		$hsdata->setHomestay_addr($data['homestay_addr']);
		$hsdata->setDscp(isset($data['dscp'])?$data['dscp']:0);
		$hsdata->setaddtime($_SERVER['REQUEST_TIME']);
		$hsdata->setRepast(isset($data['repast'])?$data['repast']:0);
		$hsdata->setInvoice(isset($data['invoice'])?$data['invoice']:0);
		$hsdata->setCheck_in_time(isset($data['check_in_time'])?$data['check_in_time']:0);		
		$hsdata->setReception_time(isset($data['reception_time'])?$data['reception_time']:null);
		$hsdata->setIs_manage(isset($data['is_manage'])?$data['is_manage']:0);
		$hsdata->setState(1);
		$hsdata->setUpload_ip($_SERVER['REMOTE_ADDR']);
		$hsdata->setUpload_terminal(3);
		$hsdata->setLongitude(isset($data['longitude'])?$data['longitude']:null);
		$hsdata->setLatitude(isset($data['latitude'])?$data['latitude']:null);
		$hsdata->setProvince(isset($data['province'])?$data['province']:null);
		$hsdata->setCity(isset($data['city'])?$data['city']:null);
		$hsdata->setDistrict(isset($data['district'])?$data['district']:null);
		$hsdata->setImageUrl(isset($data['district'])?$data['district']:null);
		$hsdata->setDistrict(isset($data['district'])?$data['district']:null);
		$hsdata->setDistrict(isset($data['district'])?$data['district']:null);
		$manager->persist($hsdata);
		$manager->flush();
		$homestay_room_id = $hsdata->getId(); */
		$img=$data['uploadimgName'];
		$count=count($img);
		for($i=0;$i<$count;$i++){
		    if ($i==($count-1)){
		        $images .=$img[$i];
		    }else{
		        $images .=$img[$i].';';
		    } 
		}
		$longitude=$data['longitude'];
		$laidtude=$data['latitude'];
		$geohash=self::addGeohash($longitude,$laidtude);;
	    $arr=array(
	        'member_id'=>$data['member_id'],
	        'homestay_name'=>$data['homestay_name'],
	        'homestay_title'=>$data['homestay_title'],
	        'homestay_type_id'=>$data['homestay_type_id'],
	        'bottom_price'=>isset($data['bottom_price'])?$data['bottom_price']:0,
	        'homestay_phone'=>isset($data['homestay_phone'])?$data['homestay_phone']:0,
	        'homestay_addr'=>$data['homestay_addr'],
	        'repast'=>isset($data['repast'])?$data['repast']:0,
	        'invoice'=>isset($data['invoice'])?$data['invoice']:0,
	        'reception_time'=>isset($data['reception_time'])?$data['reception_time']:0,
	        'least_day'=>isset($data['least_day'])?$data['least_day']:0,
	        'ban_event'=>isset($data['ban_event'])?$data['ban_event']:0,
	        'dscp'=>isset($data['dscp'])?$data['dscp']:0,
	        'state'=>0,
	        'is_manage'=>1,
	        'upload_ip'=>$_SERVER['REMOTE_ADDR'],
	        'upload_terminal'=>3,
	        'longitude'=>$longitude,
	        'latitude'=>$laidtude,
	        'geohash'=>$geohash,
	        'province'=>$data['province'],
	        'city'=>$data['city'],
	        'district'=>$data['district'],
	        'addtime'=>$_SERVER['REQUEST_TIME'],
	        'image_url'=>$images,
	    );	    
	    $a=$manager->getConnection()->insert('msk_homestay',$arr);
    	return $this->redirect($this->generateUrl('a_d_d_h_o_m_e_s_t_a_y'));
    	
    }

    
    function recurse_copy($src,$dst,$homestay_room_id,$member_id) {  // 原目录，复制到的目录
    	
    	$manager = $this->getDoctrine()->getManager();
    	$dir = opendir($src);
    	@mkdir($dst);
    	
    	$i = 1 ;
    	while(false !== ( $file = readdir($dir)) ) {
    		
    		
    		if (( $file != '.' ) && ( $file != '..' )) {
    			if ( is_dir($src . '/' . $file) ) {  //目录中目录
    				$this->recurse_copy($src . '/' . $file,$dst . '/' . $file,$homestay_room_id,$member_id);
     					
    			}
    			else {
    				
    				$fileArr  = explode(".", $file)  ;
    				
    				$fileName =uniqid() .'.'.$fileArr[1];
    				copy($src . '/' . $file,$dst . '/' . $fileName);
    
    				//	echo $file;
    				$i>0?$i=1:$i=0;
    					
    				
    					
    				$images =new Images();
    				$images->setHomestay_room_id($homestay_room_id);
    				$images->setMember_id($member_id);
    				$images->setImg_type(0); //0homestay1room2poster
    				$images->setGoods_image($fileName);
    				$images->setImg_dscp("");
    				$images->setGoods_image_sort(0);
    				$images->setIs_default($i);
    				$images->setAdd_time(time());
    				
    				$manager->persist($images);
    				$manager->flush();
    				
    				unlink($src . '/' . $file);
    				
    				$i--;
    
    
    			}
    		}
    	}
    	 
    	 
    	 
    	closedir($dir);
    }
    
    protected function get_user_id() {
    	@session_start();
    	return session_id();
    }
    
    /**
     * 首页推荐民宿
     * @Route("/reconmmend",name="reconmmend_")
     * @Template("AcmeMinsuBundle:Default:recommend.html.twig")
     */
    public function reconmmendAction() {
    	

    	
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$query = $conn->createQueryBuilder ()
    	->select ( "m.*,mh.homestay_name,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') newTime" )
    	->from ( 'msk_index_recommend', 'm' )
    	->leftJoin('m','msk_homestay','mh','m.rec_id=mh.id')
    	->where('m.type=0')
    	 ->orderBy('m.sort','DESC')
    	->execute ();
    	$data_ms = $query->fetchAll ();
    	
    	
    	$query_tra = $conn->createQueryBuilder ()
    	->select ( "m.*,mh.travel_title homestay_name,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') newTime" )
    	->from ( 'msk_index_recommend', 'm' )
    	->leftJoin('m','msk_travel_note','mh','m.rec_id=mh.id')
    	->where('m.type=1')
    	->orderBy('m.sort','DESC')
    	
    	->execute ();
    	$data_tra = $query_tra->fetchAll ();
    	
    	/* $yigong = $conn->createQueryBuilder ()
        	->select ( "m.*,mh.title as homestay_name,FROM_UNIXTIME(m.addTime, '%Y-%m-%d %H:%i:%s') newTime" )
        	->from ( 'msk_index_recommend', 'm' )
        	->leftJoin('m','msk_community_post','mh','m.rec_id=mh.id')
        	->where('m.type=3')
        	->orderBy('m.sort','DESC')
    	    ->execute ()->fetchAll (); */
    	
    	if(isset($_POST['State'])){
    		if($_POST['State'] != ""){
    			if($_POST['State'] == 0){
    				$data  =$data_ms;
    				
    			}elseif($_POST['State'] == 1){
    				$data  =$data_tra;
    			}
 
    		}else {$data  =array_merge($data_ms,$data_tra);}
    	}else{
    		$data  =array_merge($data_ms,$data_tra);
    	}

    	
    	return array('v'=>$data);
    	
    }
    
    /**
     * 添加首页推荐民宿
     * @Route("/rec_info",name="rec_info_")
     * @Template("AcmeMinsuBundle:Default:recommendInfo.html.twig")
     */
    public function rec_infoAction() {
    	
    	$conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	$query = $conn->createQueryBuilder ()
    	->select ( "m.*,mm.account,mifo.nickname" )
    	->from ( 'msk_homestay', 'm' )
    	->leftjoin ( 'm', 'msk_member', 'mm', 'm.member_id=mm.id' )
    	->leftjoin('m','msk_member_info','mifo','m.member_id=mifo.member_id')
    	->where('m.state = 1')
    	->orderBy('m.addtime','DESC')
    	->execute ();
    	$data = $query->fetchAll ();
    	
    	
    	$query_tra = $conn->createQueryBuilder ()
    	->select ( "m.*,mm.account,mifo.nickname" )
    	->from ( 'msk_travel_note', 'm' )
    	->leftjoin ( 'm', 'msk_member', 'mm', 'm.member_id=mm.id' )
    	->leftjoin('m','msk_member_info','mifo','m.member_id=mifo.member_id')
    	->where('m.state = 0')
    	->orderBy('m.addtime','DESC')
    	->execute ();
    	$data_trav = $query_tra->fetchAll ();
    	
    	 
    	return array('v'=>$data,'tra'=>$data_trav);
    	 
    }
    
    /**
     * 添加首页推荐民宿
     * @Route("/sav_rec_info",name="_sav_rec_info_")
     */
    public function sav_rec_infoAction() {
    	
    	$data  = $_POST;
    	$man =$this->getDoctrine()->getManager();
    	     $em = $man->getRepository('AcmeMinsuBundle:IndexRec');
    	     
    	     
    	     if($data['rec_type'] == 0) {
    	     	
    	     	$rec_id  =$data['rec_id'];
    	     }else{
    	     	
    	     	$rec_id =$data['tra_rec_id'];
    	     }
    	     $m_indx_rec = $em->findOneBy(array('rec_id' => $rec_id,'type'=>$data['rec_type']));
    	     
    	     if (!$m_indx_rec){
    	     	
    	     	
    	     	
    	     	$rec =new IndexRec();
    	    	$rec->setRecId( $rec_id);
    	    	$rec->setSort($data['Sort']);
    	   	  	$rec->setType($data['rec_type']);
    	    	$rec->setAddtime(time());
    	     	$man->persist($rec);
    	     	$man->flush();
  
    	     	return $this->redirect($this->generateUrl('reconmmend_'));
    	     }else{
    	     	
    	     	return $this->redirect($this->generateUrl('rec_info_'));
    	     	
    	     }
    	     
    	    
    }

    //导游列表
    /**
     * @Route("/guideList",name="guideList_")
     */
    public function guideListAction(Request $request)
    {
        if (isset($_POST['isOwner'])) {
            if ($_POST['isOwner'] != "") {
                $stat = $_POST['isOwner'];
                $where = "m.state = $stat";
            } else {
                $where ='m.state is not null';
            }
        }else {
            $where = 'm.state is not null';
        }
        
        
        //搜索
        if (isset($_POST['searchText'])){
        
            $searchText  =$_POST['searchText'];
            if($searchText!=''){
            
              if($_POST['searchType']  =='nic'){
                
                $where  = $where ." and  m.real_name LIKE '%$searchText%' "; 
                
              }elseif($_POST['searchType']  =='acc'){
                
                    $where  = $where ." and  m.phone LIKE '%$searchText%' "; 
              }
            
            }
        }
        
        
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $a = $conn->createQueryBuilder ()
        ->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,p.avatar, p.id,p.member_state" )
        ->from ( 'msk_guide', 'm' )
        ->leftjoin('m', 'msk_member', 'p', 'm.member_id = p.id')
        ->where ( "$where" )
        ->orderBy("m.add_time","DESC")
        ->execute ();
        $memberList = $a->fetchAll ();
        foreach ($memberList as $key => $value) {
          $where1 = "o.guide_id =".$value['guide_id']; 
          $order = $conn->createQueryBuilder ()
                        ->select ( "count(o.order_id) as sums" )
                        ->from ( 'msk_guide_order', 'o' )
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

        return $this->render('AcmeMinsuBundle:Default:guide.html.twig',
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
     * 更改导游的状态
     * @Route("/guideStateChange", name="guideStateChange_")
     */
    public function guideStateChangeAction(Request $request)
    {
        $id = $request->get('guide_id');
        $state = $request->get('state');
        $em = $this->getDoctrine()->getManager();
        $memberQry = $em->getRepository('AcmeMinsuBundle:Guide')->findOneBy(
            array('guide_id' => $id)
        );
        $memberQry->setState($state);
        $em->flush();
        return new Response(1);
    }


    /**
     * 导游的详情
     * @Route("/guideDataDetail",name="guideDataDetail_")
     */
    public function guideDataDetailAction()
    {
        $hid = isset($_GET['guide_id'])?$_GET['guide_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        //查询导游的基本信息
        $a = $conn->createQueryBuilder ()
        ->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,mm.true_name" )
        ->from ( 'msk_guide', 'm' )
        ->leftjoin ( 'm', 'msk_member', 'mm', 'm.member_id=mm.id' )
        ->where ( "m.guide_id = $hid" )
        ->execute ();
        $hsl = $a->fetchAll ();
        $hstReturn =    current($hsl) ;
        $certRes = $conn->createQueryBuilder()
            ->select('c.*')
            ->from('msk_guide_certification', 'c')
            ->Where('c.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetch();
        //查到评论数
        $comment = $conn->createQueryBuilder()
            ->select('count(c.comment_id) as comment_cout')
            ->from('msk_guide_comment', 'c')
            ->Where('c.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        $comment = $comment[0]['comment_cout'];
        //查到旅行相册数
        $album = $conn->createQueryBuilder()
            ->select('count(a.album_id) as album_cout')
            ->from('msk_guide_album', 'a')
            ->Where('a.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        $album = $album[0]['album_cout'];
        //查到评论数
        $travel = $conn->createQueryBuilder()
            ->select('count(a.travel_id) as travel_cout')
            ->from('msk_guide_travel', 'a')
            ->Where('a.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        $travel = $travel[0]['travel_cout'];
        $noimg = "http://img.hb.aicdn.com/d699d95653dfd7c17cafe914cda9fd739239c38ee23-xbKM4z_fw658";
        if($certRes['positive_identity'] ==null){$certRes['positive_identity'] =$noimg;}
        if($certRes['opposite_identity'] ==null){$certRes['opposite_identity'] =$noimg;}
        if($certRes['guide_card'] ==null){$certRes['guide_card'] =$noimg;}
        if($certRes['handheld_identity'] ==null){$certRes['handheld_identity'] =$noimg;}   
        return $this->render('AcmeMinsuBundle:Default:guideDetail.html.twig',
            array(
                'v'=>$hstReturn,
                'cert' => $certRes,
                'comment' => $comment,
                'album' => $album,
                'travel' =>$travel
               
            ));
    }

    /**
     * 导游带团景点的详情
     * @Route("/guideDataTravel",name="guideDataTravel_")
     */
    public function guideDataTravelAction()
    {
        $hid = isset($_GET['guide_id'])?$_GET['guide_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到旅行相册数
        $album = $conn->createQueryBuilder()
            ->select('a.*')
            ->from('msk_guide_travel', 'a')
            ->Where('a.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        return $this->render('AcmeMinsuBundle:Default:guideTravel.html.twig',
            array(
                'album' => $album
               
            ));
    }

    /**
     * 导游旅行图片的详情
     * @Route("/guideDataAlbum",name="guideDataAlbum_")
     */
    public function guideDataAlbumAction()
    {
        $hid = isset($_GET['guide_id'])?$_GET['guide_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到旅行相册数
        $album = $conn->createQueryBuilder()
            ->select("a.*,FROM_UNIXTIME(a.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime")
            ->from('msk_guide_album', 'a')
            ->Where('a.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        return $this->render('AcmeMinsuBundle:Default:guideAlbum.html.twig',
            array(
                'album' => $album
               
            ));
    }

    /**
     * 导游相关评论的详情
     * @Route("/guideDataComment",name="guideDataComment_")
     */
    public function guideDataCommentAction()
    {
        $hid = isset($_GET['guide_id'])?$_GET['guide_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到旅行相册数
        $comment = $conn->createQueryBuilder()
            ->select("a.*,FROM_UNIXTIME(a.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime")
            ->from('msk_guide_comment', 'a')
            ->Where('a.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        return $this->render('AcmeMinsuBundle:Default:guideComment.html.twig',
            array(
                'comment' => $comment
               
            ));
    }

    /**
     * 后台删除导游评论
     * @Route("/guideDataCommentDel",name="guideDataCommentDel_")
     */
    public function guideDataCommentDelAction() {
        $manager = $this->getDoctrine ()->getManager ();
        $guide_id = isset($_GET['guide_id'])?$_GET['guide_id']:0;
        $comment_id = isset($_GET['comment_id'])?$_GET['comment_id']:0;
       
        $st = $manager->getRepository ( "AcmeMinsuBundle:GuideComment" );
        $st_data = $st->findOneBy( array('comment_id'=>$comment_id,'guide_id'=>$guide_id) );
        $manager->beginTransaction ();
        $manager->remove ($st_data);
        $manager->flush();
        $manager->commit ();
        return $this->redirect($this->generateUrl('guideDataComment_'));
       
    }




    
}
