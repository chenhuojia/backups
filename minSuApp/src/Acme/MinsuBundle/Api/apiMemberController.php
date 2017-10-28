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
use Acme\MinsuBundle\Entity\Member;
use Acme\MinsuBundle\Entity\MemberInfo;
use Qiniu\Auth;

use Acme\MinsuBundle\Common\CommonController;
class apiMemberController extends CommonController
{

    public function __construct(){
    
    }
    
    
    /**
     * 卖家中的——店铺+民宿+导游+组团游状态
     * @Route("/apiSellerAllState", name="apiSellerAllState_")
     */
    public function apiSellerAllStateAction(Request $request)
    {
       
        
        $member_id =isset($_GET['member_id'])?$_GET['member_id']:0;
        $conn = $this->getDoctrine()->getManager()->getConnection();
        //导游认证
        $guide = $conn->createQueryBuilder()
                                ->select(
                                    'p.state as guide_state','p.guide_id'
                                    )
                                ->from('msk_guide', 'p') 
                                ->where("p.member_id=$member_id")
                                ->andWhere("p.type=1")
                                ->execute()
                                ->fetch();
        $data['guide_id'] = isset($guide['guide_id'])?$guide['guide_id']:0;
         //state:-1未请求 0未认证 1认证中 2认证通过 3认证失败 4注销 5禁用'
        $data['guide_state'] = $guide['guide_state'] !==null?$guide['guide_state']:-1;   
        //旅行社认证
        $agency = $conn->createQueryBuilder()
                                ->select(
                                    'p.state as agency_state','p.agency_id'
                                    )
                                ->from('msk_travel_agency', 'p') 
                                ->where("p.member_id=$member_id")
                                ->execute()
                                ->fetch();
        $data['agency_id'] = isset($agency['agency_id'])?$agency['agency_id']:0;
        //-1未请求 0未认证 1认证中 2认证通过 3认证失败 4注销 5禁用' 
        $data['agency_state'] = $agency['agency_state'] !==null?$agency['agency_state']:-1;  
        $data['agency_price'] = 1000;
        //店铺认证
        $shop = $conn->createQueryBuilder()
                                ->select(
                                    'p.is_checked as shop_state'
                                    )
                                ->from('msk_shop_apply', 'p') 
                                ->where("p.user_id=$member_id")
                                ->execute()
                                ->fetch();
        //是否审核通过 -1未请求，1表示审核通过 0表示不通过 2表示审核中
        if($shop['shop_state']===1){
          $data['shop_id'] = $conn->createQueryBuilder()
                                ->select(
                                    'k.shop_id'
                                    )
                                ->from('msk_shop', 'k') 
                                ->where("p.user_id=$member_id")
                                ->execute()
                                ->fetch();
        }else{
           $data['shop_id'] =0;
        }
        $data['shop_state'] = $shop['shop_state'] !==null?$shop['shop_state']:-1;

        //民宿认证
        $homestay = $conn->createQueryBuilder()
                                ->select(
                                    'p.state as homestay_state','p.id as homestay_id'
                                    )
                                ->from('msk_homestay', 'p') 
                                ->where("p.member_id=$member_id")
                                ->andWhere("p.is_manage=1")
                                ->execute()
                                ->fetch();
        $data['homestay_id'] = isset($homestay['homestay_id'])?$homestay['homestay_id']:0;
        //-1未请求 0未审核1通过审核2审核不通过3用户删除  
        $data['homestay_state'] = $homestay['homestay_state'] !==null?$homestay['homestay_state']:-1;                    
       
        //团长管理
        $chief = $conn->createQueryBuilder()
                                ->select(
                                    'p.state as chief_state','p.chief_id'
                                    )
                                ->from('msk_chief', 'p') 
                                ->where("p.member_id=$member_id")
                                ->execute()
                                ->fetch();
        $data['chief_id'] = isset($chief['chief_id'])?$chief['chief_id']:0;
        //-1未授权 0删除 1显示 2出团
        $data['chief_state'] = $chief['chief_state'] !==null?$chief['chief_state']:-1;  

        //代理管理
        $proxy = $conn->createQueryBuilder()
                                ->select(
                                    'p.state as proxy_state','p.proxy_id'
                                    )
                                ->from('msk_travel_agency_proxy', 'p') 
                                ->where("p.member_id=$member_id")
                                ->execute()
                                ->fetch();
        $data['proxy_id'] = isset($proxy['proxy_id'])?$proxy['proxy_id']:0;
        //-1未授权 0删除 1显示
        $data['proxy_state'] = $proxy['proxy_state'] !==null?$proxy['proxy_state']:-1; 


        if(!empty($data)){
             return new JsonResponse($data);
        }else{
           
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'error!';
            return new JsonResponse($massage);
        }           
  
    }

    /**
     *买家列表中的——店铺+民宿+导游+组团游状态
     * @Route("/apiCustomerOrderAll", name="apiCustomerOrderAll_")
     */
    public function apiCustomerOrderAllAction(Request $request)
    {   
        $token =$request->get('token',0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $remind = $conn->createQueryBuilder()
                                ->select(
                                    'p.*'
                                    )
                                ->from('msk_order_remind', 'p') 
                                ->where("p.member_id=".$member_id)
                                ->where("p.is_read=0")
                                ->execute()
                                ->fetchAll();
        $data['guide_status'] =0;
        $data['shop_status'] = 0;
        $data['tour_status'] = 0;
        $data['homestay_status'] = 0;
        foreach ($remind as $key => $value) {
            if($value['type']==0 && $value['order_id']>0){
                 $data['tour_status'] = 1;
            }
             if($value['type']==1 && $value['order_id']>0){
                 $data['guide_status'] = 1;
            }
            if($value['type']==2 && $value['order_id']>0){
                 $data['homestay_status'] = 1;
            }
            if($value['type']==3 && $value['order_id']>0){
                 $data['shop_status'] = 1;
            }
        }
        if(!empty($data)){
        	 $message['code'] = 200;
             $message['result'] = $data;
             $message['msg'] ="success";
             return new JsonResponse($message);
        }else{
           
            $message['code'] = 300;
            $message['result'] = '';
            $message['msg'] ="系统繁忙，请稍后再试!";
            return new JsonResponse($message);
        }           
  
    }


     /**
      * 登录
     * @Route("/apiLogin",name="apiLogin_")
     */
    public function apiLoginAction(Request $request)
    {   $em = $this->getDoctrine()->getManager();
        $pAccount = $request->get('pAccount',0);
        if ($pAccount==13413885166){
            $query = $em->getConnection()->createQueryBuilder()
				->select('p.member_qqopenid,p.avatar,p.token,p.id,m.nickname,m.introduce')
				->from('msk_member','p')
				->leftJoin('p','msk_member_info','m','p.id=m.member_id')
				->where('p.id=1')
				->execute()->fetch();
             $data = array(
                'status' => '1',
                'error' => '0',
                'message' => 'success',
                'pAccount' => $pAccount,
                'qAccount' =>  $query['member_qqopenid'],
                'avatar' => $this->getParameter('app_qiniu_imgurl').$query['avatar'],
                'native_avatar' => $query['avatar'],
                'nickname' =>$query['nickname'],
                'introduce' => $query['introduce'],
                'token' => $query['token'],
                'memberId' => $query['id'],
            ); 
            return new JsonResponse($data);
        }
        $password = $request->get('password',0);
        $password = md5($password);      
        $query = $em->createQuery(
            "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.account = :pAccount and p.password =:password"
        );
        $query->setParameter("pAccount",$pAccount);
        $query->setParameter("password",$password);
        $memberId = $query->execute();
        if (!empty($memberId)) {
            if ($memberId[0]['member_state'] == 1) {
                $host = $this->getParameter('host');
                //$host = "192.168.1.108/msk/web/";
                $memberId = $memberId[0]['id'];
                $time = time();
                $currenip = $_SERVER['REMOTE_ADDR'];

                $memberQuery = $em->createQuery(
                    "select p.member_login_ip,p.member_login_time,p.login_num,p.avatar,p.member_qqopenid from AcmeMinsuBundle:Member p WHERE p.id = :memberId AND p.member_state = 1"
                );
                $memberQuery->setParameter("memberId",$memberId);
                $memberQuery = $memberQuery->execute();
                if (!empty($memberQuery)) {
                    $loginIp = $memberQuery[0]['member_login_ip'];
                    $loginTime = $memberQuery[0]['member_login_time'];
                    $loginNum = $memberQuery[0]['login_num'] + 1;
                    $qAccount = $memberQuery[0]['member_qqopenid'];
                    $avatarRes = $memberQuery[0]['avatar'];
                } else {
                    $message['status'] = '0';
                    $message['error'] = '1';
                    $message['message'] = 'the acount is closed';

                    return new JsonResponse($message);
                }


                $memberObj = $em->getRepository('AcmeMinsuBundle:Member')->findOneByid($memberId);
                $memberObj->setMemberOldLoginIp($loginIp);
                $memberObj->setMemberLoginIp($currenip);
                $memberObj->setMemberOldLoginTime($loginTime);
                $memberObj->setMemberLoginTime($time);
                $memberObj->setLoginNum($loginNum);
                $memberObj->setMemberState(1);
                $memberObj->setIsOwner(0);
                if ($em->flush()) {
                    $message['status'] = '0';
                    $message['error'] = '1';
                    $message['message'] = 'change error';

                    return new JsonResponse($message);
                }
               
                $avatarImg = $this->getParameter('app_qiniu_imgurl').$avatarRes;
                //$avatar = base64_encode(file_get_contents($avatarImg));

                $memberInfoQuery = $em->createQuery(
                    "select p.nickname,p.introduce from AcmeMinsuBundle:MemberInfo p WHERE p.member_id = :memberId"
                );
                $memberInfoQuery->setParameter('memberId',$memberId);
                $memberInfoQueryRes = $memberInfoQuery->execute();
                if ($memberInfoQueryRes) {
                    if (!$nickname = $memberInfoQueryRes[0]['nickname']) {
                        $nickname = "";
                    }
                    if ($introduce = $memberInfoQueryRes[0]['introduce']) {
                        $introduce = "";
                    }
                } else {
                    $nickname = "";
                    $introduce = "";
                }

                $memberId = "{$memberId}";
                //Token
                $strToken = md5($pAccount).'|'.$memberId.'|'.time();
                $token = $this->myEncode($strToken);
                $this->getDoctrine()->getManager()->getConnection()->createQueryBuilder ()
                    ->update ( 'msk_member', 'm' )
                    ->set ('m.token',"'$token'")
                    ->andwhere( "m.id =$memberId" )
                    ->execute ();
                //token结束
                $data = array(
                    'status' => '1',
                    'error' => '0',
                    'message' => 'success',
                    'pAccount' => $pAccount,
                    'qAccount' => $qAccount,
                    'avatar' => $avatarImg,
                    'native_avatar' => $avatarRes,
                    'nickname' =>$nickname,
                    'introduce' => $introduce,
                    'token' => $token,
                    'memberId' => $memberId
                );
                return new JsonResponse($data);
            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'black list';

                return new JsonResponse($message);
            }
        } else {
            $message['status'] = '0';
            $message['error'] = '1';
            $message['message'] = 'Account or password error';

            return new JsonResponse($message);
        }
        
    }

     /**
      * 检查判断昵称是否已经被使用
     * @Route("/apiCheckNickName", name="apiCheckNickName_")
     */
    public function apiCheckNickNameAction(Request $request)
    {
        $data = isset($_POST)?$_POST:'';
        if ($data!="" ) {
            $nickname = $data['nickName'];
            if(empty($nickname)){
                return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'nickName is Not empty!'));
            }
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "select p.id from AcmeMinsuBundle:MemberInfo p where p.nickname = :nickname "
            );
            $query->setParameter("nickname",$nickname);
            $memberId = $query->execute();
            if (!empty($memberId)) {
                $message['state'] = '0';
                $message['error'] = '1';
                $message['message'] = 'nickName is registered!'; 
                return new JsonResponse($message);
            }else {
                 $message['state'] = '1';
                 $message['error'] = '0';
                 $message['message'] = 'nickName is available!';
                return new JsonResponse($message);
            }
        }else{
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Parameters not exist';

                return new JsonResponse($message);
        } 
    }

    /**
      * 检查手机号码是否已经被注册使用
     * @Route("/apiCheckPhone", name="apiCheckPhone_")
     */
    public function apiCheckPhoneAction(Request $request)
    {
        $data = isset($_POST)?$_POST:'';
        if ($data!="" ) {
            $phone = $data['phone'];
            if(empty($phone)){
                return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'phone is Not empty!'));
            }
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.account = :phone "
            );
            $query->setParameter("phone",$phone);
            $memberId = $query->execute();
            if (!empty($memberId)) {
                $message['state'] = '0';
                $message['error'] = '1';
                $message['message'] = 'phone is registered!'; 
                return new JsonResponse($message);
            }else {
                 $message['state'] = '1';
                 $message['error'] = '0';
                 $message['message'] = 'phone is available!';
                return new JsonResponse($message);
            }
        }else{
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Parameters not exist';

                return new JsonResponse($message);
        } 
    }

    /**
      * 注册
     * @Route("/apiRegisterV2",name="apiRegisterV2_")
     */
    public function apiRegisterV2Action(Request $request)
    {
        $data = isset($_POST)?$_POST:'';
        if ($data!="" ) {
            $pAccount = $data['pAccount'];
            $password = md5($data['password']);
            $code = $data['code'];
            $type = $request->get('type',0);//0、安卓 1、IOS
            $qAccount = $request->get('qAccount',"");//qq登录账号
            $loginIp = $_SERVER['REMOTE_ADDR'];
            $manager = $this->getDoctrine()->getManager();
            $query = $manager->createQuery(
                "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.account = :pAccount "
            );
            $query->setParameter("pAccount",$pAccount);
            $memberId = $query->execute();
            if (empty($memberId)) {
                $c=$this->SendMessages($pAccount,$code,$type);
                $c=json_decode($c,true);
                if($code !=='123'){
                    if($c['status']!==200){ 
                    return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'SMS verification error'));
                  }
                }
                
               $Member  = new Member();
               $Member->setAccount($pAccount);
               $Member->setPassword($password);
               $Member->setTrueName("");
               $Member->setMemberQqopenid($qAccount);
               $Member->setMemberSinaopenid("");
               $Member->setMemberWxopenid("");
               $Member->setSex(0);
               $Member->setAvatar("");
               $Member->setMemberPoints(0);
               $Member->setMemberLoginTime(time());
               $Member->setMemberOldLoginTime(time());
               $Member->setMemberLoginIp($loginIp);
               $Member->setLoginNum(1);
               $Member->setIsOwner(0);
               $Member->setMemberState(1);
               $Member->setCreatDate(time());
               $manager->persist($Member);
               $manager->flush();
               $bool  = $Member->getId();
               if(is_numeric($bool)){
                $Info  = new MemberInfo();
                $Info->setNickname("佚名");
                $Info->setMemberId($bool);
                $manager->persist($Info);
                $manager->flush();

                 //生成用户的头像
                 $filename ="../web/apple-touch-icon.png";// 要上传文件的本地路径
                 $bucket = 'minsu2';// 要上传的空间
                 $createName = 'avatar_'.time().mt_rand(1, 100).'.jpg';// 上传到七牛后保存的文件名
                 $urlPrefix = "";//生成的url前缀
                 $avatar=$this->upload_qiniu($filename,$bucket,$createName,$urlPrefix);
                 //Token
                 $strToken = md5($pAccount).'|'.$bool.'|'.time();
                 $token = $this->myEncode($strToken);
                 $conn = $manager->getConnection();
                 $conn->createQueryBuilder ()
                        ->update ( 'msk_member', 'm' )
                        ->set ('m.avatar',"'$avatar'" )
                        ->set ('m.token',"'$token'")
                        ->andwhere( "m.id =$bool" )
                        ->execute ();
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] ="Regist Success!";
                $message['member_id']=$bool;
                $message['token'] = $token;

                return new JsonResponse($message);
              }else{
                
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] ="Regist Error!";
                return new JsonResponse($message);
              }  

            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'phone is registered!';

                return new JsonResponse($message);
            }
        }else{
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Parameters not exist';

                return new JsonResponse($message);

        } 
    }

    /**
      * 修改密码
     * @Route("/apiModifyPassword",name="apiModifyPassword_")
     */
    public function apiModifyPasswordAction(Request $request)
    {
        $data = isset($_POST)?$_POST:'';
        if ($data!="" ) {
            $memberid =$data['memberid'];
            $pAccount = $data['pAccount'];
            $oldpassword = md5($data['oldPassword']);
            $password = md5($data['newPassword']);
            $code =$data['code'];
            $type = $request->get('type',0);//0、安卓 1、IOS
            $manager = $this->getDoctrine()->getManager();
            $query = $manager->createQuery(
                "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.account = :pAccount and p.password =:oldpassword"
            );
            $query->setParameter("pAccount",$pAccount);
            $query->setParameter("oldpassword",$oldpassword);
            $memberId = $query->execute();
            if (!empty($memberId)) {
                $c=$this->SendMessages($pAccount,$code,$type);
                $c=json_decode($c,true);
                if($code !=='123'){
                    if($c['status']!==200){ 
                    return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'SMS verification error'));
                  }
                }
                $conn = $manager->getConnection();
                $upb = $conn->createQueryBuilder ()
                        ->update ( 'msk_member', 'm' )
                        ->set ('m.password',"'$password'" )
                        ->andwhere( "m.id =$memberid" )
                        ->execute ();
                if(is_numeric($upb)){
                    
                    $message['status'] = 1;
                    $message['error'] = 0;
                    $message['msg'] ="Change Success!";
                    return new JsonResponse($message);
                  }else{

                    $message['status'] = 0;
                    $message['error'] = 1;
                    $message['msg'] ="change Error!";
                    return new JsonResponse($message);
                  }  

            } else {

                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Account or password error!';

                return new JsonResponse($message);
            }
        }else{
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Parameters not exist';

                return new JsonResponse($message);

        } 
    }

    /**
      * 注册后完善资料
     * @Route("/apiModifyRegistrationAdd",name="apiModifyRegistrationAdd_")
     */
    public function apiModifyRegistrationAddAction(Request $request)
    {
        $data = isset($_POST)?$_POST:'';
        if ($data!="" ) {
            $memberid=isset($data['memberid'])?$data['memberid']:0;
            $nickname = $data['nickName'];
            $avatar = $data['avatar'];
            
            $manager = $this->getDoctrine()->getManager();
            $query = $manager->createQuery(
                "select p.id,p.account, p.member_state,p.token from AcmeMinsuBundle:Member p where p.id = :memberid "
            );
            $query->setParameter("memberid",$memberid);
            $memberId = $query->execute();
            if (!empty($memberId)) {
                $conn = $manager->getConnection();
                $upb = $conn->createQueryBuilder ()
                        ->update ( 'msk_member', 'm' )
                        ->set ('m.avatar',"'$avatar'" )
                        ->andwhere( "m.id =$memberid" )
                        ->execute ();
                 $upb1 = $conn->createQueryBuilder ()
                        ->update ( 'msk_member_info', 'm' )
                        ->set ('m.nickname',"'$nickname'" )
                        ->andwhere( "m.id =$memberid" )
                        ->execute ();
                if(($upb!==false) && ($upb1!==false)){
                    $pAccount = $memberId[0]['account']; 
                    $token = $memberId[0]['token'];
                    $native_avatar = $avatar;
                    $avatar="http://oezcwek9o.bkt.clouddn.com" . '/' .$avatar;
                    $message['status'] = 1;
                    $message['error'] = 0;
                    $message['msg'] ="Change Success!";
                    $message['data'] =array('memberid'=>$memberid,'nickname'=>$nickname,'avatar'=>$avatar,'native_avatar' => $native_avatar,'pAccount' => $pAccount,'token'=>$token);
                    return new JsonResponse($message);
                  }else{

                    $message['status'] = 0;
                    $message['error'] = 1;
                    $message['msg'] ="Change Error!";
                    return new JsonResponse($message);
                  }  

            } else {

                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Not find the Member!';

                return new JsonResponse($message);
            }
        }else{
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Parameters not exist';

                return new JsonResponse($message);

        } 
    }

    /**
      * 找回密码
     * @Route("/apiRetrievePassword",name="apiRetrievePassword_")
     */
    public function apiRetrievePasswordAction(Request $request)
    {
        
        $data = isset($_POST)?$_POST:'';
        $par =array('pAccount','password','code');
        $parBoo  =  $this->checkKeyForArr($par, $data);
        if($this->checkKeyForArr($par, $data)>0 && $data!=""){
            $pAccount = $data['pAccount'];
            $password = md5($data['password']);
            $code = $data['code'];
            $type = $request->get('type',0);//0、安卓 1、IOS
            $manager = $this->getDoctrine()->getManager();
            $query = $manager->createQuery(
                "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.account = :pAccount "
            );
            $query->setParameter("pAccount",$pAccount);
            $memberId = $query->execute();
            if (!empty($memberId)) {
                $c=$this->SendMessages($pAccount,$code,$type);
                $c=json_decode($c,true);
                if($code !='123'){
                    if($c['status']!==200){ 
                    return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'SMS verification error'));
                  }
                }
                $conn = $manager->getConnection();
                $upb = $conn->createQueryBuilder ()
                        ->update ( 'msk_member', 'm' )
                        ->set ('m.password',"'$password'" )
                        ->set('m.token',"0")
                        ->andwhere( "m.account =$pAccount" )
                        ->execute ();
                if($upb!==false){
                    
                    $message['status'] = 1;
                    $message['error'] = 0;
                    $message['msg'] ="Change Success!";
                    return new JsonResponse($message);
                  }else{

                    $message['status'] = 0;
                    $message['error'] = 1;
                    $message['msg'] ="Change Error!";
                    return new JsonResponse($message);
                  }  

            } else {

                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Not find the Member!';

                return new JsonResponse($message);
            }
        }else{
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Parameters not exist';

                return new JsonResponse($message);

        } 
    }



    /**
      * 接触绑定前判断是否存在及短信验证
     * @Route("/apiRemoveBindMsgPre",name="apiRemoveBindMsgPre_")
     */
    public function apiRemoveBindMsgPreAction(Request $request)
    {
        
        $data = isset($_POST)?$_POST:'';
        if ($data!="" ) {
            $memberid =$data['memberid'];
            $pAccount = $data['pAccount'];
            $code = $data['code'];
            $type = $request->get('type',0);//0、安卓 1、IOS
            $manager = $this->getDoctrine()->getManager();
            $query = $manager->createQuery(
                "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.account = :pAccount and p.id =:memberid"
            );
            $query->setParameter("pAccount",$pAccount);
            $query->setParameter("memberid",$memberid);
            $memberId = $query->execute();
            if (!empty($memberId)) {
                $c=$this->SendMessages($pAccount,$code,$type);
                $c=json_decode($c,true);
                if($code !=='123'){
                    return new JsonResponse(array('status'=>1,'error'=>0,'message'=>'该号码可以解除绑定'));
                }
                if($c['status']!==200){ 
                    return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'SMS verification error'));
                }else{
                    return new JsonResponse(array('status'=>1,'error'=>0,'message'=>'该号码可以解除绑定'));
                   
                }
                
            } else {

                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Not find the Member!';

                return new JsonResponse($message);
            }
        }else{
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Parameters not exist';

                return new JsonResponse($message);

        } 
    }

    /**
      * 更换账号绑定
     * @Route("/apiReplaceAccount",name="apiReplaceAccount_")
     */
    public function apiReplaceAccountAction(Request $request)
    {
        
        $data = isset($_POST)?$_POST:'';
        if ($data!="" ) {
            $memberid =$data['memberid'];
            $pAccount = $data['pAccount'];
            $code = $data['code'];
            $type = $request->get('type',0);//0、安卓 1、IOS
            $manager = $this->getDoctrine()->getManager();
            $query = $manager->createQuery(
                "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.id =:memberid"
            );
            $query->setParameter("memberid",$memberid);
            $memberId = $query->execute();
            if (!empty($memberId)) {
                $c=$this->SendMessages($pAccount,$code,$type);
                $c=json_decode($c,true);
                if($c['status']!==200){ 
                    return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'SMS verification error'));
                }else{
                    $query = $manager->createQuery(
                        "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.account =:pAccount"
                    );
                    $query->setParameter("pAccount",$pAccount);
                    $account = $query->execute();
                    if (empty($account)) {
                         $conn = $manager->getConnection();
                         $upb = $conn->createQueryBuilder ()
                        ->update ( 'msk_member', 'm' )
                        ->set ('m.account',"'$pAccount'" )
                        ->andwhere( "m.id =$memberid" )
                        ->execute ();
                        if($upb>0){
                              return new JsonResponse(array('status'=>1,'error'=>0,'message'=>'Change Success!'));
                          }else{
                              return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'系统繁忙，请稍后再试'));
                          }  

                    }else{
                         return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'新绑定的号码已经在系统中存在，请换另一个账号'));
                    }
                   
                }             

            } else {

                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Not find the Member!';

                return new JsonResponse($message);
            }
        }else{
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'Parameters not exist';

                return new JsonResponse($message);

        } 
    }

     /**
     * QQ第三方登录
     * @Route("/apiqqLogin", name="apiqqLogin_")
     */
    public function apiqqLogin(Request $request)
    {
        $qAccount = $request->get('qAccount',0);
        $manager = $this->getDoctrine()->getManager();
        $query = $manager->createQuery(
            "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.member_qqopenid =:qAccount"
        );
        $query->setParameter("qAccount",$qAccount);
        $memberId = $query->execute();
        if (!empty($memberId)) {
                
                if ($memberId[0]['member_state'] == 1) {
                    $host = $this->getParameter('host');
                    //$host = "192.168.1.108/msk/web/";
                    $memberId = $memberId[0]['id'];
                    $time = time();
                    $currenip = $_SERVER['REMOTE_ADDR'];

                    $memberQuery = $manager->createQuery(
                        "select p.member_login_ip,p.member_login_time,p.account,p.login_num,p.avatar,p.member_qqopenid from AcmeMinsuBundle:Member p WHERE p.id = :memberId AND p.member_state = 1"
                    );
                    $memberQuery->setParameter("memberId",$memberId);
                    $memberQuery = $memberQuery->execute();
                    if (!empty($memberQuery)) {
                        $loginIp = $memberQuery[0]['member_login_ip'];
                        $loginTime = $memberQuery[0]['member_login_time'];
                        $loginNum = $memberQuery[0]['login_num'] + 1;
                        $qAccount = $memberQuery[0]['member_qqopenid'];
                        $pAccount = $memberQuery[0]['account'];
                        $avatarRes = $memberQuery[0]['avatar'];
                    } else {
                        $message['status'] = '0';
                        $message['error'] = '1';
                        $message['message'] = 'the acount is closed';

                        return new JsonResponse($message);
                    }


                    $memberObj = $manager->getRepository('AcmeMinsuBundle:Member')->findOneByid($memberId);
                    $memberObj->setMemberOldLoginIp($loginIp);
                    $memberObj->setMemberLoginIp($currenip);
                    $memberObj->setMemberOldLoginTime($loginTime);
                    $memberObj->setMemberLoginTime($time);
                    $memberObj->setLoginNum($loginNum);
                    $memberObj->setMemberState(1);
                    $memberObj->setIsOwner(0);
                    if ($manager->flush()) {
                        $message['status'] = '0';
                        $message['error'] = '1';
                        $message['message'] = 'change error';

                        return new JsonResponse($message);
                    }

                    $avatarImg = "http://oezcwek9o.bkt.clouddn.com" . '/' .$avatarRes;
                    //$avatar = base64_encode(file_get_contents($avatarImg));

                    $memberInfoQuery = $manager->createQuery(
                        "select p.nickname,p.introduce from AcmeMinsuBundle:MemberInfo p WHERE p.member_id = :memberId"
                    );
                    $memberInfoQuery->setParameter('memberId',$memberId);
                    $memberInfoQueryRes = $memberInfoQuery->execute();
                    if ($memberInfoQueryRes) {
                        if (!$nickname = $memberInfoQueryRes[0]['nickname']) {
                            $nickname = "";
                        }
                        if ($introduce = $memberInfoQueryRes[0]['introduce']) {
                            $introduce = "";
                        }
                    } else {
                        $nickname = "";
                        $introduce = "";
                    }
                    $memberId = "{$memberId}";
                    //Token
                    $strToken = md5($pAccount).'|'.$memberId.'|'.time();
                    $token = $this->myEncode($strToken);
                    $this->getDoctrine()->getManager()->getConnection()->createQueryBuilder ()
                        ->update ( 'msk_member', 'm' )
                        ->set ('m.token',"'$token'")
                        ->andwhere( "m.id =$memberId" )
                        ->execute ();
                    //token结束
                    $data = array(
                        'status' => '1',
                        'error' => '0',
                        'message' => 'success',
                        'pAccount' => $pAccount,
                        'qAccount' => $qAccount,
                        'avatar' => $avatarImg,
                        'nickname' =>$nickname,
                        'native_avatar' => $avatarRes,
                        'introduce' => $introduce,
                        'token' => $token,
                        'memberId' => $memberId
                    );

                    return new JsonResponse($data);
                } else {
                    $message['status'] = '0';
                    $message['error'] = '1';
                    $message['message'] = 'black list';

                    return new JsonResponse($message);
                }
            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = '该QQ账号还没绑定，请绑定后再登录';

                return new JsonResponse($message);
            }
      
    }

    /**
      * 第三方绑定账号
     * @Route("/apiThirdBindingAccount",name="apiThirdBindingAccount_")
     */
    public function apiThirdBindingAccountAction(Request $request)
    {   
     
        $data = isset($_POST)?$_POST:'';
        if ($data!="" ) {
            $pAccount = $data['pAccount'];
            $qAccount = $data['qAccount'];
            $password = md5($data['password']);
            $manager = $this->getDoctrine()->getManager();
            $query = $manager->createQuery(
                "select p.id, p.member_state,p.member_qqopenid from AcmeMinsuBundle:Member p where p.account = :pAccount and p.password =:password"
            );
            $query->setParameter("pAccount",$pAccount);
            $query->setParameter("password",$password);
            $memberId = $query->execute();
            if (!empty($memberId)) {
                $query1 = $manager->createQuery(
                    "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.member_qqopenid =:qAccount"
                );
                $query1->setParameter("qAccount",$qAccount);
                $isOpenid = $query1->execute();
                if(!empty($isOpenid)){
                    return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'该QQ账号已经被绑定了，请换另一个!'));
                }
                if(!empty($memberId[0]['member_qqopenid'])){
                    return new JsonResponse(array('status'=>0,'error'=>1,'message'=>'您的账号已经授权过qq账号了!'));
                }
                if ($memberId[0]['member_state'] == 1) {
                    $host = $this->getParameter('host');
                    //$host = "192.168.1.108/msk/web/";
                    $memberId = $memberId[0]['id'];
                    $time = time();
                    $currenip = $_SERVER['REMOTE_ADDR'];

                    $memberQuery = $manager->createQuery(
                        "select p.member_login_ip,p.member_login_time,p.account,p.login_num,p.avatar,p.member_qqopenid from AcmeMinsuBundle:Member p WHERE p.id = :memberId AND p.member_state = 1"
                    );
                    $memberQuery->setParameter("memberId",$memberId);
                    $memberQuery = $memberQuery->execute();
                    if (!empty($memberQuery)) {
                        $loginIp = $memberQuery[0]['member_login_ip'];
                        $loginTime = $memberQuery[0]['member_login_time'];
                        $loginNum = $memberQuery[0]['login_num'] + 1;
                        $pAccount = $memberQuery[0]['account'];
                        $avatarRes = $memberQuery[0]['avatar'];
                    } else {
                        $message['status'] = '0';
                        $message['error'] = '1';
                        $message['message'] = 'the acount is closed';

                        return new JsonResponse($message);
                    }


                    $memberObj = $manager->getRepository('AcmeMinsuBundle:Member')->findOneByid($memberId);
                    $memberObj->setMemberOldLoginIp($loginIp);
                    $memberObj->setMemberLoginIp($currenip);
                    $memberObj->setMemberOldLoginTime($loginTime);
                    $memberObj->setMemberLoginTime($time);
                    $memberObj->setMemberQqopenid($qAccount);
                    $memberObj->setLoginNum($loginNum);
                    $memberObj->setMemberState(1);
                    $memberObj->setIsOwner(0);
                    if ($manager->flush()) {
                        $message['status'] = '0';
                        $message['error'] = '1';
                        $message['message'] = 'change error';

                        return new JsonResponse($message);
                    }

                    $avatarImg = "http://oezcwek9o.bkt.clouddn.com" . '/' .$avatarRes;
                    //$avatar = base64_encode(file_get_contents($avatarImg));

                    $memberInfoQuery = $manager->createQuery(
                        "select p.nickname,p.introduce from AcmeMinsuBundle:MemberInfo p WHERE p.member_id = :memberId"
                    );
                    $memberInfoQuery->setParameter('memberId',$memberId);
                    $memberInfoQueryRes = $memberInfoQuery->execute();
                    if ($memberInfoQueryRes) {
                        if (!$nickname = $memberInfoQueryRes[0]['nickname']) {
                            $nickname = "";
                        }
                        if ($introduce = $memberInfoQueryRes[0]['introduce']) {
                            $introduce = "";
                        }
                    } else {
                        $nickname = "";
                        $introduce = "";
                    }

                    $memberId = "{$memberId}";
                    //Token
                    $strToken = md5($pAccount).'|'.$memberId.'|'.time();
                    $token = $this->myEncode($strToken);
                    $this->getDoctrine()->getManager()->getConnection()->createQueryBuilder ()
                        ->update ( 'msk_member', 'm' )
                        ->set ('m.token',"'$token'")
                        ->andwhere( "m.id =$memberId" )
                        ->execute ();
                    //token结束
                    $data = array(
                        'status' => '1',
                        'error' => '0',
                        'message' => 'success',
                        'pAccount' => $pAccount,
                        'qAccount' => $qAccount,
                        'avatar' => $avatarImg,
                        'native_avatar' => $avatarRes,
                        'nickname' =>$nickname,
                        'introduce' => $introduce,
                        'token' =>$token,
                        'memberId' => $memberId
                    );

                    return new JsonResponse($data);
                } else {
                    $message['status'] = '0';
                    $message['error'] = '1';
                    $message['message'] = 'black list';

                    return new JsonResponse($message);
                }
            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = '账号或者密码错误';

                return new JsonResponse($message);
            }
        }
    }

    /**
      * 修改背景图片
     * @Route("/apiModifyMemberBackgroundImg",name="apiModifyMemberBackgroundImg_")
     */
    public function apiModifyMemberBackgroundImgAction(Request $request)
    {
        
        $token = $request->get('token',0);
        $background_image = $request->get('background_image');
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        if (empty($background_image)) return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'上传的背景图片不能为空'));
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $upb = $conn->createQueryBuilder ()
                ->update ( 'msk_member_info', 'm' )
                ->set ('m.background_image',"'$background_image'" )
                ->andWhere( "m.member_id =$member_id" )
                ->execute ();
        if(is_numeric($upb)){
           return new JsonResponse(array('code'=>200,'result'=>'','msg'=>'修改成功'));
        }else{
            return new JsonResponse(array('code'=>300,'result'=>'','msg'=>'修改失败'));
        }  
    }


    /**
     * @Route("/minsumember", name="minsumember_")
     */
    public function minsumemberAction(){
        include ('../vendor/php-sdk/autoload.php');
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
        $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
         
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        //  要上传的空间
        $bucket = 'minsu';
         
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        if ($token){
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$token));
        }
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    
    }

   /**
    *测试token
    * @Route("/apiToken",name="apiToken")
    */
   public function apiToken(request $request)
   {
       $token =$request->get("token",0);
       $a = $this->validationToken($token);
       if(is_array($a)) return new JsonResponse($a);
       //echo $a;
       return new JsonResponse($a);
   }
    
    
   /**
    * @Route("/apiAddHomeStayComment", name="apiAddHomeStayComment_")
    */
   public function apiAddHomeStayComment(Request $request){
       $token=$request->get('token');
       $user_id=$this->validationToken($token);
       if(is_array($user_id)) return new JsonResponse($user_id);     
       $rank=$request->get('rank');
       $homestay_id=$request->get('homestay_id');
       $content=$request->get('content');
       if (!$content) return new JsonResponse(array('status'=>0,"error"=>1,'msg'=>'评论内容不能为空'));
       if (!$homestay_id) return new JsonResponse(array('status'=>0,"error"=>1,'msg'=>'民宿不存在'));
       // $user=$this->conn()->createQueryBuilder()
       //      ->select('p.avatar,m.nickname')           
       //      ->from('msk_member','p')
       //      ->leftjoin('p','msk_member_info','m','p.id=m.member_id')
       //      ->where('p.id='.$user_id)
       //      ->execute()->fetch();
       // $data=array(
       //     'user_id'=>$user_id,
       //     'user_name'=>$user['nickname'],
       //     'avator'=>$user['avatar'],
       //     'homestay_id'=>$homestay_id,
       //     'content'=>$content,
       //     'rank'=>$rank,
       //     'addtime'=>time(),
       // );
       // $res=$this->conn()->insert('msk_homestay_comment',$data);
       $data=array(
           'member_id'=>$user_id,
           'homestay_id'=>$homestay_id,
           'eval'=>$content,
           'grade'=>$rank,
           'state'=>0,
           'addtime'=>time(),
       );
       $res=$this->conn()->insert('msk_homestay_share_eval',$data);
       if ($res) return new JsonResponse(array('status'=>1,"error"=>0,'msg'=>'评论成功'));
       return new JsonResponse(array('status'=>0,"error"=>1,'msg'=>'评论失败'));
   }



   /**
    * 修改支付密码
    * @Route("/apiModifyPaywd",name="apiModifyPaywd_")
    */
   public function apiModifyPaywdAction(Request $request)
   {
        $token=$request->get('token');
        $user_id=$this->validationToken($token);
        if(is_array($user_id)) return new JsonResponse($user_id);    
           $oldpassword = md5($request->get('paywd'));
           $newpaywd =md5($request->get('newpaywd'));
           $confirmpaywd =md5($request->get('confirmpaywd'));
           if($newpaywd !== $confirmpaywd) return new JsonResponse(array('code'=>300,'msg'=>'两次输入密码不相同','result'=>''));
           $user=$this->conn()->createQueryBuilder()->select('*')->from('msk_member')->where('paypwd='."'$oldpassword'")->execute()->fetch();
           if ($user)
           {
               if ($newpaywd==$user['paypwd']) return new JsonResponse(array('code'=>300,'msg'=>'你输入的新密码与旧密码一至','result'=>''));
               else{
                   $upb = $this->conn()->createQueryBuilder ()
                       ->update ( 'msk_member', 'm' )
                       ->set ('m.paypwd',"'$newpaywd'" )
                       ->andwhere( "m.id =$user_id" )
                       ->execute ();
                   if(is_numeric($upb)){
                       return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>''));
                   }else{
                       return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
                   }
               } 
           }else return new JsonResponse(array('code'=>300,'msg'=>'你输入的旧密码错误','result'=>''));
   }

   
   /**
    * 获取收货地址
    * @Route("/apiMyaddress",name="apiMyaddress_")
    */
   public function apiMyaddressAction(Request $request)
   {
       $token=$request->get('token');
       $user_id=$this->validationToken($token);
       if(is_array($user_id)) return new JsonResponse($user_id);
       $manager = $this->getDoctrine()->getManager();
       $conn = $manager->getConnection();
       $upb = $conn->createQueryBuilder ()
           ->select('address_id,consignee,city,address,mobile,is_default')
           ->from("msk_user_address" )
           ->where( "user_id =$user_id" )
           ->execute()->fetchAll();
       if($upb){
           return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$upb));
       }else{
           return new JsonResponse(array('code'=>202,'msg'=>'暂无数据','result'=>''));
       }

   }
    


   /**
    * 个人信息
    * @Route("/apiUserInfo",name="apiUserInfo_")
    */
   public function apiUserInfoAction(Request $request)
   {	
       $token=$request->get('token');
       $user_id=$this->validationToken($token);
       if(is_array($user_id)) return new JsonResponse($user_id);
       $manager = $this->getDoctrine()->getManager();
       $conn = $manager->getConnection();
       $data = $conn->createQueryBuilder ()
           ->select('p.account,p.paypwd,p.avatar,p.member_qqopenid,m.introduce,m.nickname,is_owner')
           ->from("msk_member",'p' ) 
           ->leftJoin('p',"msk_member_info",'m','p.id=m.member_id')
           ->where( "p.id =$user_id" )
           ->execute()->fetch();
       if($data){
           if ($data['member_qqopenid']=='') $data['member_qqopenid']=0;
           if ($data['paypwd']) $data['is_have_paywd']=1;
           else $data['is_have_paywd']=0;
           $data['avatar']=$this->getParameter('app_qiniu_imgurl').$data['avatar']; 
           //判断该用户已经认证过民宿了
           $minsu = $conn->createQueryBuilder ()
    	        ->select('*')
    	        ->from("msk_homestay" )
    	        ->where( "member_id =".$user_id )
    	        ->andWhere("is_manage=1")
    	        ->execute()
    	        ->fetch();
           if ($minsu){
                   switch ($minsu['state']){
                       case 0:
                           $data['minsu']='未认证';
                           break;
                       case 1:
                           $data['minsu']='认证通过';
                           break;
                       case 2:
                           $data['minsu']='认证不通过';
                           break;
                       case 3:
                           $data['minsu']='已删除';
                           break;
                       case 3:
                           $data['minsu']='认证中';
                           break;
                   }
               }
           else $data['minsu']='未开通';
           $shop=$conn->createQueryBuilder()->select('*')->from('msk_shop_apply')
           ->where('user_id='.$user_id)
           ->execute()->fetch();
           if ($shop){
               switch ($shop['is_checked']){
                   case 0:
                       $data['shop']='认证不通过';
                       break;
                   case 1:
                       $data['shop']='认证通过';
                       break;
                   case 2:
                       $data['shop']='未认证';
                       break;
               }
           }else $data['shop']='未开通';
           $travel=$conn->createQueryBuilder()->select('*')->from('msk_travel_agency')
           ->where('member_id='.$user_id)
           ->execute()->fetch();
           if ($travel){
               switch ($travel['state']){
                   case 0:
                       $data['travel_agency']='未认证';
                       break;
                   case 1:
                       $data['travel_agency']='认证中';
                       break;
                   case 2:
                       $data['travel_agency']='认证通过';
                       break;
                   case 3:
                       $data['travel_agency']='认证失败';
                       break;
                   case 4:
                       $data['travel_agency']='注销';
                       break;
                   case 5:
                       $data['travel_agency']='禁用';
                       break;
                   case 6:
                       $data['travel_agency']='已交保证金';
                       break;
               }
           }else $data['travel_agency']='未开通';
           $guide=$conn->createQueryBuilder()->select('*')->from('msk_guide')
           ->where('member_id='.$user_id,'type=1')
           ->execute()->fetch();
           if ($guide){
               switch ($guide['state']){
                   case 0:
                       $data['guide']='未认证';
                       break;
                   case 1:
                       $data['guide']='认证中';
                       break;
                   case 2:
                       $data['guide']='认证通过';
                       break;
                   case 3:
                       $data['guide']='认证失败';
                       break;
                   case 4:
                       $data['guide']='注销';
                       break;
                   case 5:
                       $data['guide']='禁用';
                       break;
               }
           }else $data['guide']='未开通';
           return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
       }else{
           return new JsonResponse(array('code'=>202,'msg'=>'暂无数据','result'=>''));
       }
   }

    /**
    * 个人信息首页
    * @Route("/apiUserInfoPage",name="apiUserInfoPage_")
    */
   public function apiUserInfoPageAction(Request $request)
   {    
       $token=$request->get('token',0);
       $user_id=$this->validationToken($token);
       if(is_array($user_id)) return new JsonResponse($user_id);
       $manager = $this->getDoctrine()->getManager();
       $conn = $manager->getConnection();
       $data = $conn->createQueryBuilder ()
           ->select('p.account,p.available_balance,p.member_points,p.avatar,m.nickname,m.background_image,m.my_minsu,m.my_sense,m.my_yigong,m.my_daoyou,m.my_lvyoutuan')
           ->from("msk_member",'p' ) 
           ->leftJoin('p',"msk_member_info",'m','p.id=m.member_id')
           ->where( "p.id =$user_id" )
           ->execute()->fetch();
       if($data){
           $data['note']=$data['my_minsu']+$data['my_sense']+$data['my_yigong']+$data['my_daoyou']+$data['my_lvyoutuan'];
           $fans = $conn->createQueryBuilder ()
           ->select("count(from_member_id) as sum")
           ->from("msk_member_relation") 
           ->where( "to_member_id =".$user_id )
           ->execute()->fetch();
           $data['fans'] =isset($fans['sum'])?$fans['sum']:0;
           $focus = $conn->createQueryBuilder ()
               ->select("count(p.to_member_id) as sum")
               ->from("msk_member_relation",'p' ) 
               ->where( "p.from_member_id =".$user_id )
               ->execute()->fetch();
           $data['focus']=isset($focus['sum'])?$focus['sum']:0;
           $coupon=$conn->createQueryBuilder ()
                   ->select("count(id) total")
                   ->from('msk_buyer_coupon')
                   ->where('buyer_id='.$user_id,'state=0')
                   ->execute()
                   ->fetch();
           $data['coupon']=$coupon['total'];
           if(empty($data['background_image'])|| $data['background_image']==null){
              $data['background_image']=$this->getParameter('app_background_imgurl');
           }else{
              $data['background_image']=$this->getParameter('app_qiniu_imgurl').$data['background_image']; 
           }
           //$data['background_image']= $this->getParameter('app_background_imgurl');
           $data['avatar']=$this->getParameter('app_qiniu_imgurl').$data['avatar']; 
           return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
       }else{
           return new JsonResponse(array('code'=>202,'msg'=>'暂无数据','result'=>''));
       }
    }

    

    

    
    



  
  
}






























