<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-5-25
 * Time: 9:31
 */
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\Images;
use Acme\MinsuBundle\Entity\Homestay;
use Acme\MinsuBundle\Entity\Member;
use Acme\MinsuBundle\Entity\MemberInfo;
use Acme\MinsuBundle\Entity\Room;
use Acme\MinsuBundle\Entity\HomestayCollect;
use Acme\MinsuBundle\Entity\TravelNoteImage;
use Acme\MinsuBundle\Entity\RestockOfHomestay;
use Acme\MinsuBundle\Entity\RoomServerRelation;
use Acme\MinsuBundle\Entity\RoomCheckinDate;

use Acme\MinsuBundle\Common\CommonController;
class apiHomestayDetailController extends CommonController
{

    public function __construct(){
    
    }
    
    /**
     * @Route("/apihomestaydetail", name="apihomestaydetail_");
     */
    public function apihomestaydetailAction(Request $request)
    {
        $isOwner = $request->get('is_owner');
        $homestayId = $request->get('homestay_id');
        $em = $this->getDoctrine()->getManager();
        $homestayQuery = $em->getConnection()->createQueryBuilder()
            ->select(
                //p.check_in_time, p.check_out_time,
                'p.homestay_name,p.member_id,p.bottom_price,p.homestay_title,p.homestay_addr,p.dscp,p.homestay_phone, p.longitude, p.latitude, p.reception_time,p.homestay_type_id,p.least_day,p.invoice,p.ban_event,p.	image_url,p.video_url'
            )
            ->from('msk_homestay', 'p')
            ->where('p.id = :id')
            ->andWhere('p.state = 1')
            ->andWhere('p.is_manage = :isManage')
            ->setParameter('id', $homestayId);
        //avatar
        $memberIdQry = $em->createQuery(
            "select p.member_id from AcmeMinsuBundle:Homestay p WHERE p.id = :id"
        );
        $memberIdQry->setParameter('id', $homestayId);
        $memberId = $memberIdQry->execute();
        if ($memberId) {
            $memberId = $memberId[0]['member_id'];
        } else {
            $message['status'] = '0';
            $message['error'] = '1';
            $message['message'] = 'not member id!';
            return new JsonResponse($message);
        }
        $memberQuery = $em->createQuery(
            "select p.avatar from AcmeMinsuBundle:Member p WHERE p.id = :id"
        );
        $memberQuery->setParameter('id', $memberId);
        //nickname
        $memberInfoQuery = $em->createQuery(
            "select p.nickname from AcmeMinsuBundle:MemberInfo p WHERE p.member_id IN
             (SELECT a.member_id FROM AcmeMinsuBundle:Homestay a WHERE a.id = :id)"
        );
        $memberInfoQuery->setParameter('id', $homestayId);
        //select the upvote number
        $upvoteNumQry = $em->createQuery(
            "select count(p.id) as upvote_num from AcmeMinsuBundle:HomestayUpvote p WHERE p.homestay_id = :homestayid AND p.upvote = :upvote"
        )
        ->setParameters(array('homestayid' => $homestayId, 'upvote' => 1));
        if ($isOwner == '1') {
            $isManage = 1;
            $homestayQuery->setParameter("isManage", $isManage);
            $homestayQueryRes = $homestayQuery->execute()->fetchAll();
           
            if (!empty($homestayQueryRes)) {
                if (is_null($homestayQueryRes[0]['longitude'])) {
                    $longitude = 'null';
                } else {
                    $longitude = $homestayQueryRes[0]['longitude'];
                }
                if (is_null($homestayQueryRes[0]['latitude'])) {
                    $latitude = 'null';
                } else {
                    $latitude = $homestayQueryRes[0]['latitude'];
                }
                $bottomPrice = $homestayQueryRes[0]['bottom_price'];
                $homestayTitle = $homestayQueryRes[0]['homestay_title'];
                $homestayAddr = $homestayQueryRes[0]['homestay_addr'];
                $homestayDescription = $homestayQueryRes[0]['dscp'];
                $homestayPhone = $homestayQueryRes[0]['homestay_phone'];
                $homestayName = $homestayQueryRes[0]['homestay_name'];
                //$checkInTime = $homestayQueryRes[0]['check_in_time'];
                //$checkOutTime = $homestayQueryRes[0]['check_out_time'];
                $receptionTime = $homestayQueryRes[0]['reception_time'];
                $leastTime = $homestayQueryRes[0]['least_day'];
                $banEvent = $homestayQueryRes[0]['ban_event'];
                $invoice = $homestayQueryRes[0]['invoice'];
                $homestay_type_id =$homestayQueryRes[0]['homestay_type_id'];
            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'not found homestay!';

                return new JsonResponse($message);
            }
            //$imgQueryRes = $imgQuery->execute();
            if (!empty($homestayQueryRes[0]['image_url'])) {
                   $img=explode(';',$homestayQueryRes[0]['image_url']);
                   $defaultImg=$this->getParameter('app_qiniu_imgurl').$img[0];
                   foreach ($img as $k=>$v){
                       $imgQueryRes[$k]['goods_image'] = $this->getParameter('app_qiniu_imgurl'). $v;
                   } 
            }

            if (!empty($homestayQueryRes[0]['video_url'])) {
                $video=explode(';',$homestayQueryRes[0]['video_url']);
                $homestayVideoPath = $this->getParameter('app_qiniu_imgurl'). $video[1];
                $homestayVideoCoverPath = $this->getParameter('app_qiniu_imgurl'). $video[0];
            } else { 
                $homestayVideoPath = '';
                $homestayVideoCoverPath = '';
            }

            $memberIdQry = $em->createQuery(
                "select p.member_id from AcmeMinsuBundle:Homestay p WHERE p.id = :id"
            );
            $memberIdQry->setParameter('id', $homestayId);
            $memberId = $memberIdQry->execute();
            if ($memberId) {
                $memberId = $memberId[0]['member_id'];
            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'not member id!';

                return new JsonResponse($message);
            }
            $memberQuery = $em->createQuery(
                "select p.avatar from AcmeMinsuBundle:Member p WHERE p.id = :id"
            );
            $memberQuery->setParameter('id', $memberId);
            $memberQueryRes = $memberQuery->execute();
            if (!empty($memberQueryRes)) {
                $avatar =$this->getParameter('app_qiniu_imgurl'). $memberQueryRes[0]['avatar'];
            }

            $memberInfoQueryRes = $memberInfoQuery->execute();
            if (!empty($memberInfoQueryRes)) {
                $nickname = $memberInfoQueryRes[0]['nickname'];
            }

            $roomId = array();
            $roomImgData = array();

            $em = $this->getDoctrine()->getManager();
            $roomQuery = $em->createQuery(
                "select p.id,p.room_title,p.room_price,p.room_single_bed,p.room_double_bed,p.room_num 
              from AcmeMinsuBundle:Room p WHERE p.homestay_id = :homestay_id"
            );
            $roomQuery->setParameter('homestay_id', $homestayId);
            $homestayRoom = $roomQuery->execute();

            foreach ($homestayRoom as $v) {
                array_push($roomId, $v['id']);
            }
            $roomIdLenth = count($roomId);
            for ($i = 0; $i < $roomIdLenth; $i++) {
                $roomImgQry = $em->createQuery(
                    "select p.goods_image from AcmeMinsuBundle:Images p 
                  WHERE p.homestay_room_id = :roomId AND p.img_type = 1"
                );
                $roomImgQry->setParameter('roomId', $roomId[$i]);
                $roomImgQry->setMaxResults(1);
                $roomImgDataRes = $roomImgQry->execute();
                foreach ($roomImgDataRes as $v) {
                    array_push($roomImgData, $v);
                }
            }

            $roomImgLenth = count($roomImgData);
            $homestayRoomLenth = count($homestayRoom);
            for ($i = 0; $i < $roomImgLenth; $i++) {
                for ($j = 0; $j < $homestayRoomLenth; $j++) {
                    if ($i == $j) {
                        $homestayRoom[$j]['goods_image'] = $this->getParameter('app_qiniu_imgurl'). $roomImgData[$i]['goods_image'];
                        continue;
                    }
                }
            }

            $upvoteNumRes = $upvoteNumQry->execute();
            if (!empty($upvoteNumRes)) {
                $upvoteNum = $upvoteNumRes[0]['upvote_num'];
            } else {
                $upvoteNum = '0';
            }

            //is collect
            $member_Id = $request->get('member_id');
            if ($member_Id) {
                $isCollectQry = $em->createQuery(
                    "select p.is_collect from AcmeMinsuBundle:HomestayCollect p 
                  WHERE p.homestay_id = :homestayId AND p.member_id = :memberId"
                );
                $isCollectQry->setParameters(array('homestayId' => $homestayId, 'memberId' => $member_Id));
                $isCollectRes = $isCollectQry->execute();//var_dump($isCollectRes);
                if ($isCollectRes) {
                    $isCollect = $isCollectRes[0]['is_collect'];
                    if ($isCollect == false) {
                        $isCollect = '0';
                    } elseif ($isCollect == true) {
                        $isCollect = '1';
                    }
                } else {
                    $isCollect = '0';
                }

                $isUpvoteRes =$em->createQuery(
                    "select p.upvote from AcmeMinsuBundle:HomestayUpvote p WHERE p.homestay_id = :homestayid AND p.member_id = :memberid"
                )
                    ->setParameters(array('homestayid' => $homestayId, 'memberid' => $member_Id))
                    ->execute();
                if (!empty($isUpvoteRes)) {
                    if ($isUpvoteRes[0]['upvote'] === false) {
                        $isUpvote = '0';
                    } else {
                        $isUpvote = '1';
                    }
                } else {
                    $isUpvote = '0';
                }
            } else {
                $isCollect = '0';
                $isUpvote = '0';
            }

            //select restock content
            $restockQry = $em->getConnection()->createQueryBuilder()
                ->select(
                    "p.restockId, c.content"
                )
                ->from("msk_restock", 'c')
                ->rightJoin('c', 'msk_restock_of_homestay', 'p', 'p.restockId = c.id')
                ->where('p.homesId = :homesId')
                ->setParameter('homesId', $homestayId)
                ->execute();
            $restockQryRes = current($restockQry->fetchAll());
            
           // print_r($restockQryRes);exit();
            
            
            if (!empty($restockQry)) {
//                 foreach ($restockQryRes as $v) {
                    $restock = $restockQryRes['restockId'];
                    $restockContent = $restockQryRes['content'];
//                 }
            } else {
                $restock = '';
                $restockContent = '';
            }
            
       //SUM grade & evaluation     
            $Qry_q_e = $em->getConnection()->createQueryBuilder()
            ->select(
            		"SUM(oe.grade) sum_grade,count(*) sum_eval"
            )
            ->from("msk_order", 'c')
            ->Join('c','msk_order_evaluation','oe','c.order_sn=oe.order_sn')
            ->where('c.homestay_id = :homesId')
            ->setParameter('homesId', $homestayId)
            ->execute();
            $_gr_ev_QryRes = current($Qry_q_e->fetchAll());
            
            
            if($_gr_ev_QryRes['sum_eval']>0){
            	
            $sum_grade =	number_format($_gr_ev_QryRes['sum_grade']/$_gr_ev_QryRes['sum_eval'],1);
            
            $sum_eval =  $_gr_ev_QryRes['sum_eval'];
            	
            }else{
            	$sum_grade ="0";
            	
            	$sum_eval ="0";
            	
            }
            $data = array(
                'img' => $imgQueryRes,
                'default_img' => $defaultImg,
                'bottom_price' => $bottomPrice,
                'homestay_title' => $homestayTitle,
                'homestay_addr' => $homestayAddr,
                'homestay_description' => $homestayDescription,
                'member_avatar' => $avatar?$avatar:0,
                'member_nickname' => $nickname,
                'homestay_room' => $homestayRoom,
                'homestay_phone' => $homestayPhone,
                'homestay_name' => $homestayName,
                'is_collect' => $isCollect,
                'homestay_video' => $homestayVideoPath,
                'video_cover' => $homestayVideoCoverPath,
                'member_id' => $memberId,
                'longitude' => $longitude,
                'latitude' => $latitude,
                'upvote_num' => $upvoteNum,
                'is_upvote' => $isUpvote,
                'invoice' => $invoice,
                //'check_in_time' => $checkInTime,
                //'check_out_time' => $checkOutTime,
                'reception_time' => $receptionTime,
                'least_day' => $leastTime,
                'ban_event' => $banEvent,
                'restock' => $restock,
                'restock_content' => $restockContent,
            	'homestay_type_id'=>	$homestay_type_id,
            	'sum_grade' =>$sum_grade,
            	'sum_eval' =>$sum_eval
            );
            //var_dump($data);
           $this->Send(200,'success',$data);
        } elseif ($isOwner == '0') {
            $isManage = 0;
            $homestayQuery->setParameter("isManage", $isManage);
            $homestayQueryRes = $homestayQuery->execute()->fetchAll();
           
            if (!empty($homestayQueryRes)) {
                if (is_null($homestayQueryRes[0]['longitude'])) {
                    $longitude = 'null';
                } else {
                    $longitude = $homestayQueryRes[0]['longitude'];
                }
                if (is_null($homestayQueryRes[0]['latitude'])) {
                    $latitude = 'null';
                } else {
                    $latitude = $homestayQueryRes[0]['latitude'];
                }
                $bottomPrice = $homestayQueryRes[0]['bottom_price'];
                $homestayTitle = $homestayQueryRes[0]['homestay_title'];
                $homestayAddr = $homestayQueryRes[0]['homestay_addr'];
                $homestayDescription = $homestayQueryRes[0]['dscp'];
                $homestayPhone = $homestayQueryRes[0]['homestay_phone'];
                $homestayName = $homestayQueryRes[0]['homestay_name'];
                $homestay_type_id =$homestayQueryRes[0]['homestay_type_id'];
            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'not found homestay!';

                return new JsonResponse($message);
            }

        //$imgQueryRes = $imgQuery->execute();
            if (!empty($homestayQueryRes[0]['image_url'])) {
                   $img=explode(';',$homestayQueryRes[0]['image_url']);
                   foreach ($img as $k=>$v){
                       $imgQueryRes[$k]['goods_image'] = $this->getParameter('app_qiniu_imgurl'). $v;
                   } 
                    
                
            }
            if (!empty($homestayQueryRes[0]['video_url'])) {
                $video=explode(';',$homestayQueryRes[0]['video_url']);
                $homestayVideoPath = $this->getParameter('app_qiniu_imgurl'). $video[1];
                $homestayVideoCoverPath = $this->getParameter('app_qiniu_imgurl'). $video[0];
            } else { 
                $homestayVideoPath = '';
                $homestayVideoCoverPath = '';
            }

            $memberIdQry = $em->createQuery(
                "select p.member_id from AcmeMinsuBundle:Homestay p WHERE p.id = :id"
            );
            $memberIdQry->setParameter('id', $homestayId);
            $memberId = $memberIdQry->execute();
            if ($memberId) {
                $memberId = $memberId[0]['member_id'];
            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'not member id!';

                return new JsonResponse($message);
            }
            $memberQuery = $em->createQuery(
                "select p.avatar from AcmeMinsuBundle:Member p WHERE p.id = :id"
            );
            $memberQuery->setParameter('id', $memberId);
            $memberQueryRes = $memberQuery->execute();
            if (!empty($memberQueryRes)) {
                $avatar = $this->getParameter('app_qiniu_imgurl'). $memberQueryRes[0]['avatar'];
            }else{
                $avatar=null;
            } 

            $memberInfoQueryRes = $memberInfoQuery->execute();
            if (!empty($memberInfoQueryRes)) {
                $nickname = $memberInfoQueryRes[0]['nickname'];
            }else{
                $nickname =null;
            } 

            $upvoteNumRes = $upvoteNumQry->execute();
            if (!empty($upvoteNumRes)) {
                $upvoteNum = $upvoteNumRes[0]['upvote_num'];
            } else {
                $upvoteNum = '0';
            }

            $member_id = $request->get('member_id');
            if ($member_id) {
                $isCollectQry = $em->createQuery(
                    "select p.is_collect from AcmeMinsuBundle:HomestayCollect p 
                  WHERE p.homestay_id = :homestayId AND p.member_id = :memberId"
                );
                $isCollectQry->setParameters(array('homestayId' => $homestayId, 'memberId' => $member_id));
                $isCollectRes = $isCollectQry->execute();
                if ($isCollectRes) {
                    $isCollect = $isCollectRes[0]['is_collect'];
                    if ($isCollect == false) {
                        $isCollect = '0';
                    } elseif ($isCollect == true) {
                        $isCollect = '1';
                    }
                } else {
                    $isCollect = '0';
                }

                $isUpvoteRes =$em->createQuery(
                    "select p.upvote from AcmeMinsuBundle:HomestayUpvote p WHERE p.homestay_id = :homestayid AND p.member_id = :memberid"
                )
                    ->setParameters(array('homestayid' => $homestayId, 'memberid' => $member_id))
                    ->execute();
                if (!empty($isUpvoteRes)) {
                    if ($isUpvoteRes[0]['upvote'] === false) {
                        $isUpvote = '0';
                    } else {
                        $isUpvote = '1';
                    }
                } else {
                    $isUpvote = '0';
                }
            } else {
                $isCollect = '0';
                $isUpvote = '0';
            }

            $data = array(
                'status' => '1',
                'error' => '0',
                'massage' => 'success',
                'img' => $imgQueryRes,
                'default_img' => $defaultImg,
                'bottom_price' => $bottomPrice,
                'homestay_title' => $homestayTitle,
                'homestay_addr' => $homestayAddr,
                'member_avatar' => $avatar?$avatar:0,
                'member_nickname' => $nickname?$nickname:0,
                'homestay_phone' => $homestayPhone,
                'homestay_name' => $homestayName,
                'is_collect' => $isCollect,
                'homestay_video' => $homestayVideoPath,
                'video_cover' => $homestayVideoCoverPath,
                'member_id' => $memberId,
                'longitude' => $longitude,
                'latitude' => $latitude,
                'upvote_num' => $upvoteNum,
                'is_upvote' => $isUpvote
            		,
            		'homestay_type_id'=>	$homestay_type_id
            );
            //var_dump($data);
            return new JsonResponse($data);
        } else {
            $message['status'] = '0';
            $message['error'] = '1';
            $message['message'] = 'not share or homestay';

            return new JsonResponse($message);
        }
    }

    /**
     * @Route("apiGetHomeStay", name="apiGetHomeStay_")
     */
    public function apiGetHomeStayAction(Request $request)
    {
//         $page = $request->get('page');
        $memberPath = $this->getParameter('memberPath');
        $avatarPath = $this->getParameter('avatar_path');
//         $pageSize = $this->getParameter('pagesize');
        $host = $this->getParameter('host');
        $data = array();
        $em = $this->getDoctrine()->getManager();
//         $startPage = ($page - 1) * $pageSize;
        $page =isset($_POST['page'])?$_POST['page']:0;
        $conn = $em->getConnection();
        $roomImgQuery = $conn->createQueryBuilder()
            ->select(
                'p.id', 'p.member_id', 'p.homestay_name', 'p.homestay_title', 'p.homestay_addr', 'p.bottom_price','p.sort','p.addtime','p.city','p.is_manage',
                'c.avatar', 'c.is_owner',
                'p.image_url,p.video_url'
            		//,'p.check_in_time'
            )
            ->from('msk_homestay', 'p')
            ->leftjoin('p', 'msk_member', 'c', 'p.member_id = c.id')
            ->andWhere("p.state = 1")
           // ->andWhere("p.check_in_time is not null")
            ->setFirstResult($page)
            ->setMaxResults(10)
            ->addOrderBy('p.sort', 'DESC')
            ->addOrderBy('p.addtime', 'DESC')
//             ->orderBy('p.addtime','DESC')
            ->execute();
        $roomImgData = $roomImgQuery->fetchAll();

        if ($roomImgData) {
            $dataLenth = count($roomImgData);
            for ($i = 0; $i < $dataLenth; $i++) {
                $avatar = $this->getParameter('app_qiniu_imgurl').$roomImgData[$i]['avatar'];
                $img=explode(';',$roomImgData[$i]['image_url']);
                $homestaDefaultImg = $this->getParameter('app_qiniu_imgurl').$img[0];
                if ( $roomImgData[$i]['is_manage']==0)
                {
                    $comment=$conn->createQueryBuilder()
                    ->select("count(id) as total,avg(grade) as avge")
                    ->from("msk_homestay_share_eval")
                    ->where('state=0','homestay_id='.$roomImgData[$i]['id'])
                    ->execute()
                    ->fetch();
                    if (empty($comment['avge'])){
                        $comment['avge']='10.0';
                    }else{
                        $comment['avge']=$comment['avge']/4>=1?'10.0':"'".((bcdiv($comment['avge'],4,2))*10)."'";
                    }
                }else{
                    $comment=$conn->createQueryBuilder()
                        ->select("count(id) as total,avg(grade) as avge")
                        ->from("msk_order",'p')
                        ->leftJoin('p','msk_order_evaluation','m','p.order_sn=m.order_sn')
                        ->where('p.homestay_id='.$roomImgData[$i]['id'])
                        ->execute()
                        ->fetch();
                    if (empty($comment['avge'])){
                        $comment['avge']='10.0';
                    }else{
                        $comment['avge']=$comment['avge']/4>=1?'10.0':"'".((bcdiv($comment['avge'],4,2))*10)."'";
                    }
                } 
                
                $roomImgData[$i]['comment_count']=$comment['total'];
                $roomImgData[$i]['comment_avg']=$comment['avge'];
                unset($roomImgData[$i]['goods_image']);
                unset($roomImgData[$i]['avatar']);
                $roomImgData[$i]['avatar'] = $avatar;
                $roomImgData[$i]['homeStayDefultImg'] = $homestaDefaultImg;
                array_push($data, $roomImgData[$i]);
                
                if (!empty($roomImgData[$i]['video_url'])) $data[$i]['is_have_video'] = '1';
                else $data[$i]['is_have_video'] = '0';
            }
//            var_dump($data);
            return new JsonResponse($data);
        } else {
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found homestay!';

            return new JsonResponse($massage);
        }
    }

    /*public function apiGetHomeStayAction(Request $request)
    {
        $page = $request->get('page');
        $memberPath = $this->getParameter('memberPath');
        $avatarPath = $this->getParameter('avatar_path');
        $pageSize = $this->getParameter('pagesize');
        $host = $this->getParameter('host');
        $data = array();
        $em = $this->getDoctrine()->getManager();
        $startPage = ($page - 1) * $pageSize;

        $conn = $em->getConnection();
        $roomImgQuery = $conn->createQueryBuilder()
            ->select(
                'p.id', 'p.member_id', 'p.homestay_name', 'p.homestay_title', 'p.homestay_addr', 'p.bottom_price',
                'c.avatar', 'c.is_owner',
                'a.goods_image'
            )
            ->from('msk_homestay', 'p')
            ->leftjoin('p', 'msk_member', 'c', 'p.member_id = c.id')
            ->leftjoin('p', 'msk_images', 'a', 'a.homestay_room_id = p.id')
            ->where("a.img_type = 0")
            ->andWhere("a.is_default = 1")
            ->setFirstResult($startPage)
            ->setMaxResults($pageSize)
            ->execute();
        $roomImgData = $roomImgQuery->fetchAll();

        if ($roomImgData) {
            $dataLenth = count($roomImgData);
            for ($i = 0; $i < $dataLenth; $i++) {
                $avatar = $host . $avatarPath . $roomImgData[$i]['member_id'] . '/' . $roomImgData[$i]['avatar'];
                $homestaDefaultImg = $host . $memberPath . 'HomeStay/' . $roomImgData[$i]['member_id'] . '/' . $roomImgData[$i]['goods_image'];
                unset($roomImgData[$i]['goods_image']);
                unset($roomImgData[$i]['avatar']);
                $roomImgData[$i]['avatar'] = $avatar;
                $roomImgData[$i]['homeStayDefultImg'] = $homestaDefaultImg;
                array_push($data, $roomImgData[$i]);
            }
            //var_dump($data);
            return new JsonResponse($data);
        } else {
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found homestay!';

            return new JsonResponse($massage);
        }
    }*/
    /**
     * @Route("/apiHomestayCollect", name="apiHomeStayCollect_")
     */
    public function apiHomestayCollectAction(Request $request)
    {
        if (!$homestayId = $request->get('homestay_id')) {
            return new JsonResponse($this->failMassageReturnAction('not receive homestay id'));
        }
        if (!$memberId = $request->get('member_id')) {
            return new JsonResponse($this->failMassageReturnAction('not receive user id'));
        }
        if (!$action = $request->get('action')) {
            return new JsonResponse($this->failMassageReturnAction('not receive action'));
        }
        $collect = $request->get('collect');
        if ($collect == '') {
            $collect = '0';
        }
        $time = time();

        $em = $this->getDoctrine()->getManager();
        $homestayCollect = $em->getRepository('AcmeMinsuBundle:HomestayCollect')->findOneBy(array(
            'homestay_id' => $homestayId, 'member_id' => $memberId
        ));

        if ($action == 10) {
            if ($homestayCollect) {
                $homestayCollect->setIsCollect($collect);
                $homestayCollect->setAddTime($time);
                if (!$em->flush()) {
                    return new JsonResponse($this->successMassageReturnAction('success'));
                } else {
                    return new JsonResponse($this->failMassageReturnAction('update collect fail'));
                }
            } else {
                $conn = $em->getConnection();
                try {
                    $conn->insert('msk_homestay_collect',
                        array(
                            'homestay_id' => $homestayId,
                            'member_id' => $memberId,
                            'is_collect' => $collect,
                            'add_time' => $time
                        )
                    );
                    return new JsonResponse($this->successMassageReturnAction('success'));
                } catch (Exception $e) {
                    return new JsonResponse($this->failMassageReturnAction($e->getMessage()));
                }
            }
        } else {
            return new JsonResponse($this->failMassageReturnAction('action not correct'));
        }
    }


    /**
     * @Route("/failMassageReturn", name="failMassageReturn_")
     */
    public function failMassageReturnAction($reminderMassage)
    {
        $massage['status'] = '0';
        $massage['error'] = '1';
        $massage['massage'] = $reminderMassage;
        return $massage;
    }

    /**
     * @Route("/successMassageReturn", name="successMassageReturn_")
     */
    public function successMassageReturnAction($reminderMassage)
    {
        $massage['status'] = '1';
        $massage['error'] = '0';
        $massage['massage'] = $reminderMassage;
        return $massage;
    }
    
    /**
     * @Route("apiProvinceHomeStay", name="apiProvinceHomeStay_")
     */
    public function apiProvinceHomeStayAction(Request $request)
    {
    	//         $page = $request->get('page');
    	$memberPath = $this->getParameter('memberPath');
    	$avatarPath = $this->getParameter('avatar_path');
    	//         $pageSize = $this->getParameter('pagesize');
    	$host = $this->getParameter('host');
    	$data = array();
    	$em = $this->getDoctrine()->getManager();
    	//         $startPage = ($page - 1) * $pageSize;
    
    
    	//$page =isset($_POST['page'])?$_POST['page']:0;
    	$page = $request->get('page',0); 
    	 
    	//$pro =isset($_POST['pro'])?$_GET['pro']:'';
        $pro = $request->get('pro',''); 
    	 
    	 
    	if ($pro!=''){
    		 
    		$where = 'p.homestay_addr LIKE :par';
    		 
    		$parameters = array('par' => '%' . $pro . '%');
    		 
    		$conn = $em->getConnection();
    		$roomImgQuery = $conn->createQueryBuilder()
    		->select(
    				'p.id', 'p.member_id', 'p.homestay_name', 'p.homestay_title', 'p.homestay_addr', 'p.bottom_price','p.addtime','p.sort','p.city','p.is_manage',
    				'c.avatar', 'c.is_owner',
    				'a.goods_image'
    				//,'p.check_in_time'
    				)
    				->from('msk_homestay', 'p')
    				->leftjoin('p', 'msk_member', 'c', 'p.member_id = c.id')
    				->leftjoin('p', 'msk_images', 'a', 'a.homestay_room_id = p.id')
    				->where("a.img_type = 0")
    				->andWhere("a.is_default = 1")
    				->andWhere("p.state = 1")
    				//->andWhere("p.check_in_time is not null")
    				->andWhere($where)->setParameters($parameters)
    				 
    				->setFirstResult($page)
    				->setMaxResults(10)
    				->addOrderBy('p.sort', 'DESC')
    				->addOrderBy('p.addtime', 'DESC')
//     				 ->orderBy("p.addtime",'DESC')
    				 
    				->execute();
    				$roomImgData = $roomImgQuery->fetchAll();
    				
    				$homestayVideoQry = $conn->createQueryBuilder()
    				->select("p.goods_image, c.member_id, c.id")
    				->from('msk_images', 'p')
    				->rightjoin('p', 'msk_homestay', 'c', 'c.id = p.homestay_room_id')
    				->where('p.img_type = 3')
    				//            ->andWhere("p.is_default = 1")
    				->andWhere("c.state = 1")
    				->orderBy('c.addtime','DESC')
    				
    				->execute();
    				$homestayVideoQryRes = $homestayVideoQry->fetchAll();
    
    	        if ($roomImgData) {
            $dataLenth = count($roomImgData);
            for ($i = 0; $i < $dataLenth; $i++) {
                $avatar = $this->getParameter('app_qiniu_imgurl').$roomImgData[$i]['avatar'];
                $homestaDefaultImg = $this->getParameter('app_qiniu_imgurl').$roomImgData[$i]['goods_image'];
                unset($roomImgData[$i]['goods_image']);
                unset($roomImgData[$i]['avatar']);
                $roomImgData[$i]['avatar'] = $avatar;
                $roomImgData[$i]['homeStayDefultImg'] = $homestaDefaultImg;
                array_push($data, $roomImgData[$i]);
            }

            if (!empty($homestayVideoQryRes)) {
                $homestayVideoQryResLenth =  count($homestayVideoQryRes);
                for ($j = 0; $j < $dataLenth; $j++) {
                    for ($i = 0; $i < $homestayVideoQryResLenth; $i++) {
                        if ($data[$j]['id'] == $homestayVideoQryRes[$i]['id']) {
                            $data[$j]['is_have_video'] = '1';
                            break;
                        } else {
                            $data[$j]['is_have_video'] = '0';
                        }
                    }
                }
            } else {
                for ($k = 0; $k < $dataLenth; $k++) {
                    $data[$k]['is_have_video'] = '0';
                }
            }
//            var_dump($data);
            return new JsonResponse($data);
        } else {
    					$massage['status'] = '0';
    					$massage['error'] = '1';
    					$massage['massage'] = 'not found homestay!';
    
    					return new JsonResponse($massage);
    				}
    	} else{
    		 
    		$message['status'] = 0;
    		$message['error'] = 1;
    		$message['msg']="Parameters Error!";
    		return new JsonResponse($message);
    	}
    }
    
    
    /**
     * @Route("apiSearchHomeStay", name="apiSearchHomeStay_")
     */
    public function apiSearchHomeStayAction(Request $request)
    {
    	//         $page = $request->get('page');
    	$memberPath = $this->getParameter('memberPath');
    	$avatarPath = $this->getParameter('avatar_path');
    	//         $pageSize = $this->getParameter('pagesize');
    	$host = $this->getParameter('host');
    	$data = array();
    	$em = $this->getDoctrine()->getManager();
    	//         $startPage = ($page - 1) * $pageSize;
    
    
    	$page =isset($_POST['page'])?$_POST['page']:0;
    
    
    	$pro =isset($_POST['search_name'])?$_POST['search_name']:'';
    
    
    	if ($pro!=''){
    		 
    		$where = 'p.homestay_name LIKE :par';
    		 
    		$parameters = array('par' => '%' . $pro . '%');
    		 
    		$conn = $em->getConnection();
    		$roomImgQuery = $conn->createQueryBuilder()
    		->select(
    				'p.id', 'p.member_id', 'p.homestay_name', 'p.homestay_title', 'p.homestay_addr', 'p.bottom_price','p.addtime','p.sort','p.city','p.is_manage',
    				'c.avatar', 'c.is_owner',
    				'a.goods_image'
    				//,'p.check_in_time'
    				)
    				->from('msk_homestay', 'p')
    				->leftjoin('p', 'msk_member', 'c', 'p.member_id = c.id')
    				->leftjoin('p', 'msk_images', 'a', 'a.homestay_room_id = p.id')
    				->where("a.img_type = 0")
    				->andWhere("a.is_default = 1")
    				->andWhere("p.state = 1")
    			//	->andWhere("p.check_in_time is not null")
    				->andWhere($where)->setParameters($parameters)
    					
    				->setFirstResult($page)
    				->setMaxResults(10)
    					
//     				->orderBy("p.addtime",'DESC')
    		->addOrderBy('p.sort', 'DESC')
    		->addOrderBy('p.addtime', 'DESC')
    					
    				->execute();
    				$roomImgData = $roomImgQuery->fetchAll();
    
    				$homestayVideoQry = $conn->createQueryBuilder()
    				->select("p.goods_image, c.member_id, c.id")
    				->from('msk_images', 'p')
    				->rightjoin('p', 'msk_homestay', 'c', 'c.id = p.homestay_room_id')
    				->where('p.img_type = 3')
    				//            ->andWhere("p.is_default = 1")
    				->andWhere("c.state = 1")
    				->orderBy('c.addtime','DESC')
    				->execute();
    				$homestayVideoQryRes = $homestayVideoQry->fetchAll();
    
    				if ($roomImgData) {
    					$dataLenth = count($roomImgData);
    					for ($i = 0; $i < $dataLenth; $i++) {
    						$avatar = $this->getParameter('app_qiniu_imgurl').$roomImgData[$i]['avatar'];
    						$homestaDefaultImg = $this->getParameter('app_qiniu_imgurl').$roomImgData[$i]['goods_image'];
    						unset($roomImgData[$i]['goods_image']);
    						unset($roomImgData[$i]['avatar']);
    						$roomImgData[$i]['avatar'] = $avatar;
    						$roomImgData[$i]['homeStayDefultImg'] = $homestaDefaultImg;
    						array_push($data, $roomImgData[$i]);
    					}
    
    					if (!empty($homestayVideoQryRes)) {
    						$homestayVideoQryResLenth =  count($homestayVideoQryRes);
    						for ($j = 0; $j < $dataLenth; $j++) {
    							for ($i = 0; $i < $homestayVideoQryResLenth; $i++) {
    								if ($data[$j]['id'] == $homestayVideoQryRes[$i]['id']) {
    									$data[$j]['is_have_video'] = '1';
    									break;
    								} else {
    									$data[$j]['is_have_video'] = '0';
    								}
    							}
    						}
    					} else {
                            for ($k = 0; $k < $dataLenth; $k++) {
                                $data[$k]['is_have_video'] = '0';
                            }
                        }
    					//            var_dump($data);
    					return new JsonResponse($data);
    				} else {
    					$massage['status'] = '0';
    					$massage['error'] = '1';
    					$massage['massage'] = 'not found homestay!';
    
    					return new JsonResponse($massage);
    				}
    	} else{
    		 
    		$message['status'] = 0;
    		$message['error'] = 1;
    		$message['msg']="Parameters Error!";
    		return new JsonResponse($message);
    	}
    }

    /**
     * @Route("/apiHomestayUpvote", name="apiHomestayUpvote_")
     */
    public function apiHomestayUpvoteAction(Request $request)
    {
        if (!$homestayId = $request->get('homestay_id')) {
            return new JsonResponse($this->failMassageReturnAction('not receive homestay id'));
        }
        if (!$memberId = $request->get('member_id')) {
            return new JsonResponse($this->failMassageReturnAction('not receive user id'));
        }
        $upvote = $request->get('upvote');
        if ($upvote == '') {
            return new JsonResponse($this->failMassageReturnAction('not receive upvote parameter'));
        }
        $time = time();

        $em = $this->getDoctrine()->getManager();
        $travelNoteExist = $em->getRepository('AcmeMinsuBundle:HomestayUpvote')->findOneBy(array(
            'homestay_id' => $homestayId, 'member_id' => $memberId
        ));

        $upvoteNumQry = $em->createQuery(
            "select count(p.id) as upvote_num from AcmeMinsuBundle:HomestayUpvote p WHERE p.homestay_id = :homestayid AND p.upvote = :upvote"
        )
            ->setParameters(array('homestayid' => $homestayId, 'upvote' => 1));

        if ($travelNoteExist) {
            $travelNoteExist->setUpvote($upvote);
            $travelNoteExist->setAddTime($time);
            if (!$em->flush()) {
                $upvoteNumRes = $upvoteNumQry->execute();
                if (!empty($upvoteNumRes)) {
                    $upvoteNum = $upvoteNumRes[0]['upvote_num'];
                } else {
                    $upvoteNum = '0';
                }
                $massage = $this->successMassageReturnAction('success');
                $massage['upvote_num'] = $upvoteNum;
                return new JsonResponse($massage);
            } else {
                return new JsonResponse($this->failMassageReturnAction('update upvote fail'));
            }
        } else {
            $conn = $em->getConnection();

            try {
                $conn->insert('msk_homestay_upvote',
                    array(
                        'homestay_id' => $homestayId,
                        'member_id' => $memberId,
                        'upvote' => $upvote,
                        'addtime' => $time
                    )
                );
                $upvoteNumRes = $upvoteNumQry->execute();
                if (!empty($upvoteNumRes)) {
                    $upvoteNum = $upvoteNumRes[0]['upvote_num'];
                } else {
                    $upvoteNum = '0';
                }
                $massage = $this->successMassageReturnAction('success');
                $massage['upvote_num'] = $upvoteNum;
                return new JsonResponse($massage);
            } catch (Exception $e) {
                return new JsonResponse($this->failMassageReturnAction($e->getMessage()));
            }
        }

    }

    /**
     * @Route("/apiSaveRule", name="apiSaveRule_")
     */
    public function apiSaveRuleAction(Request $request)
    {
        $homestayId = $request->get('homestay_id');
        $invoice = $request->get('invoice');
        $checkInTime = $request->get('check_in_time');
        $checkOutTime = $request->get('check_out_time');
        $receptionTime = $request->get('reception_time');
        $leastDay = $request->get('least_day');
        $banEvent = $request->get('ban_event');
        $restock = $request->get('restock');
        if (!$homestayId || !strlen($invoice) || !$checkInTime || !$checkOutTime || !$receptionTime
            || !$leastDay || !$banEvent || !$restock) {
            return new JsonResponse($this->failMassageReturnAction('paras error'));
        }

        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $conn->createQueryBuilder()
            ->update('msk_homestay', 'p')
            ->set('p.invoice', ':invoice')
            ->set('p.check_in_time', ':checkInTime')
            ->set('p.check_out_time', ':checkOutTime')
            ->set('p.reception_time', ':receptionTime')
            ->set('p.least_day', ':leastTime')
            ->set('p.ban_event', ':banEvent')
            ->where('p.id = :homestayId')
            ->setParameters(
                array(
                    'homestayId' => $homestayId,
                    'invoice' => $invoice,
                    'checkInTime' => $checkInTime,
                    'checkOutTime' => $checkOutTime,
                    'receptionTime' => $receptionTime,
                    'leastTime' => $leastDay,
                    'banEvent' => $banEvent
                ))
            ->execute();

        try {
            $em->createQueryBuilder()
                ->delete('AcmeMinsuBundle:RestockOfHomestay', 'c')
                ->where('c.homesId = :homesId')
                ->setParameter('homesId', $homestayId)
                ->getQuery()
                ->execute();

            $restockRelHsty = new RestockOfHomestay();
            $restockRelHsty->setRestockId($restock);
            $restockRelHsty->setHomesId($homestayId);
            $em->persist($restockRelHsty);
            $em->flush();
        } catch (Exception $e) {
            return new JsonResponse($this->failMassageReturnAction('error'));
        }

        return new JsonResponse($this->successMassageReturnAction('success'));
    }

    /**
     * @Route("/apiSaveHomestay", name="apiSaveHomestay_")
     */
    public function apiSaveHomestayAction(Request $request)
    {
        $id = $request->get('id');
        $memberId = $request->get('member_id');
        $homestayTitle = $request->get('homestay_title');
        $homestayTypeId = $request->get('homestay_type_id');
        $bottomPrice = $request->get('bottom_price');
        $homestayPhone = $request->get('homestay_phone');
        $dscp = $request->get('dscp');
        $homestayImg = $request->get('homestayImg');
        if (!$id || !$memberId || !$homestayTitle || !$homestayTypeId || !$bottomPrice
            || !$homestayPhone || !$dscp) {
            return new JsonResponse($this->failMassageReturnAction('paras error'));
        }

        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();

        $conn->createQueryBuilder()
            ->update('msk_homestay', 'p')
            ->set('p.homestay_title', ':homestayTitle')
            ->set('p.homestay_type_id', ':homestayTypeId')
            ->set('p.bottom_price', ':bottomPrice')
            ->set('p.homestay_phone', ':homestayPhone')
            ->set('p.dscp', ':dscp')
            ->where('p.id = :id')
            ->setParameters(
                array(
                    'id' => $id,
                    'homestayTitle' => $homestayTitle,
                    'homestayTypeId' => $homestayTypeId,
                    'bottomPrice' => $bottomPrice,
                    'homestayPhone' => $homestayPhone,
                    'dscp' => $dscp
                )
            )
            ->execute();

        if ($homestayImg) {
            $pathParas = $this->getParameter('memberPath');
            $ItemName ='HomeStay';
            $pathHomestay = $pathParas . $ItemName . "/" . $memberId . "/";

            $imgQry = $conn->createQueryBuilder()
                ->select(
                    "p.goods_image"
                )
                ->from('msk_images', 'p')
                ->where('p.homestay_room_id = :homesId')
                ->andWhere('p.img_type in (:hType)')
                ->setParameters(
                    array(
                        'homesId' => $id,
                        'hType' => 0,
                    )
                )
                ->execute();
            try {
                $imgQryRes = $imgQry->fetchAll();
            } catch (Exception $e) {
                $massage['state'] = '0';
                $massage['error'] = '1';
                $massage['massage'] = 'error';
                return new JsonResponse($massage);
            }

            /* if (!empty($imgQryRes)) {
                foreach ($imgQryRes as $v) {
                    if (file_exists($v['goods_image'])) {
                        unlink($pathHomestay . "/" . $v['goods_image']);
                    }
                }
            } */

            $em->createQueryBuilder()
                ->delete('AcmeMinsuBundle:Images', 'c')
                ->where('c.homestay_room_id = :homesId ')
                ->andWhere('c.img_type in (:hType)')
                ->setParameters(
                    array(
                        'homesId' => $id,
                        'hType' => 0
                    ))
                ->getQuery()
                ->execute();

            if (!is_dir($pathHomestay)) {
                mkdir($pathHomestay);
            }

            $homestayImg = json_decode($homestayImg, true);
            $lenth = count($homestayImg);
            for ($i = 0; $i < $lenth; $i++) {
               // $img = base64_decode($homestayImg[$i]['goods_image']);
                if ($img) {
                    //$imgName = uniqid() . $i;
                    //if (file_put_contents($pathHomestay . "$imgName.jpg", $img)) {
                        $imageDB = new Images();
                        $imageDB->setHomestay_room_id($id);
                        $imageDB->setMember_id($memberId);
                        $imageDB->setImg_type(0);
                        $imageDB->setGoods_image("{$homestayImg[$i]['goods_image']}");
                        $imageDB->setImg_dscp($homestayImg[$i]['img_dscp'] ?: null);
                        $imageDB->setGoods_image_sort(0);
                        $imageDB->setIs_default($homestayImg[$i]['is_default'] ?: '0');
                        $imageDB->setAdd_time(time());
                        try {
                            $em->persist($imageDB);
                            $em->flush();
                        } catch (Exception $e) {
                            $massage['state'] = '0';
                            $massage['error'] = '1';
                            $massage['massage'] = 'fail';
                            return new JsonResponse($massage);
                        }
                    /* } else {
                        return new JsonResponse($this->failMassageReturnAction('error'));
                    } */
                } else {
                    return new JsonResponse($this->failMassageReturnAction('error'));
                }
            }
        }

        $massage['state'] = '1';
        $massage['error'] = '0';
        $massage['massage'] = 'success';
        return new JsonResponse($massage);
    }

    /**
     * @Route("/apiSaveVideo", name="apiSaveVideo_")
     */
    public function apiSaveVideoAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        if (empty($_FILES) == false  && isset($_POST['member_id']) && isset($_POST['homestay_id'])
            && $_POST['member_id']!="" && $_POST['member_id']!=0 &&isset($_POST['homestay_id'])!=0) {
//             if($_FILES["videofile"]["error"] > 0){
// //         exit("文件上传发生错误：".$_FILES["file"]["error"]);
//                 $message['status'] = 0;
//                 $message['error'] = 1;
//                 $message['msg']=$_FILES["videofile"]["error"];
//                 return new JsonResponse($message);
//             } 

            //delete database rel to file
            $conn = $manager->getConnection();
            $fileQry = $conn->createQueryBuilder()
                ->select(
                    'p.goods_image'
                )
                ->from('msk_images', 'p')
                ->where('p.member_id = :memberId')
                ->andWhere('p.homestay_room_id = :homesId')
                ->andWhere('p.img_type = :vType')
                ->setParameters(
                    array(
                        'memberId' => $_POST['member_id'],
                        'homesId' => $_POST['homestay_id'],
                        'vType' => 3
                    )
                )
                ->execute();
            try {
                $fileQryRes = $fileQry->fetchAll();
            } catch (Exception $e) {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg']= "error";
                return new JsonResponse($message);
            }

            //delete file
            //$savePath = $this->container->getParameter('memberPath')."Video/";
            $memberid=$_POST['member_id'];
            /* if (!empty($fileQryRes)) {
                foreach ($fileQryRes as $v) {
                    unlink($savePath . $memberid . '/' . $v['goods_image']);
                }
            } */

            $manager->createQueryBuilder()
                ->delete("AcmeMinsuBundle:Images", "p")
                ->where('p.member_id = :memberId')
                ->andWhere('p.homestay_room_id = :homesId')
                ->andWhere('p.img_type = :vType')
                ->setParameters(
                    array(
                        'memberId' => $_POST['member_id'],
                        'homesId' => $_POST['homestay_id'],
                        'vType' => 3
                    )
                )
                ->getQuery()
                ->execute();

           /*  if (!is_dir($savePath)) mkdir($savePath);
            if (!is_dir($savePath . $memberid . '/')) mkdir($savePath . $memberid . '/'); */

            //获得文件扩展名
            /* $temp_arr = explode(".", $_FILES["videofile"]["name"]);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);

            $unname =uniqid();
            $new_name =  $unname.".".$file_ext;
            //将文件移动到存储目录下
            move_uploaded_file($_FILES["videofile"]["tmp_name"],$savePath . $memberid . '/' . $new_name);
 */
            //封面图
  /*           $temp_arr01 = explode(".", $_FILES["imgesfile"]["name"]);
            $file_ext01 = array_pop($temp_arr01);
            $file_ext01 = trim($file_ext01);
            $file_ext01 = strtolower($file_ext01);

            $new_name01 = $unname.'.jpg';

            move_uploaded_file($_FILES["imgesfile"]["tmp_name"],$savePath . $memberid . '/' . $new_name01);
 */

            $imgType ="3";
            /*************S数据写入**********************/
            $images =new Images();
            $images->setHomestay_room_id($_POST['homestay_id']);
            $images->setMember_id($memberid);
            $images->setImg_type($imgType); //0homestay1room2poster3video
            $images->setGoods_image($_POST['videofile']);
            $images->setImg_dscp("");
            $images->setGoods_image_sort(0);
            $images->setIs_default(0);
            $images->setAdd_time(time());
            try {
                $manager->persist($images);
                $manager->flush();
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] ="Upload Success!";
            }catch (Exception $e) {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] ="Upload Error!";
                return new JsonResponse($message);
            }
            /*************E数据写入**********************/
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg']="Parameters Error!";
            return new JsonResponse($message);
        }

        return new JsonResponse($message);
    }

    /**
     * @Route("/apiSaveTravelNote", name="apiSaveTravelNote_")
     */
    public function apiSaveTravelNoteAction(Request $request)
    {
        $id = $request->get('id');
        $memberId = $request->get('member_id');
        $coverImg = $request->get('cover_img');
        $contentImg = $request->get('content_img');
        $content = $request->get('content');
        if (!$id || !$memberId || !$coverImg || !$contentImg || !$content) {
            return new JsonResponse($this->failMassageReturnAction('paras error'));
        }

        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        try {
            $conn->createQueryBuilder()
                ->update('msk_travel_note', 'p')
                ->set('p.travel_content', ':content')
                ->set('p.addtime', ':time')
                ->where('p.id = :id')
                ->setParameters(
                    array(
                        'content' => $content,
                        'id' => $id,
                        'time' => time()
                    )
                )
                ->execute();
        } catch (Exception $e) {
            return new JsonResponse($this->failMassageReturnAction('error'));
        }

        $imgQry = $conn->createQueryBuilder()
            ->select(
                "p.travel_note_image"
            )
            ->from('msk_travel_note_images', 'p')
            ->where('p.travel_note_id = :id')
            ->setParameter('id', $id)
            ->execute();
        try {
            $imgQryRes = $imgQry->fetchAll();
        } catch (Exception $e) {
            return new JsonResponse($this->failMassageReturnAction('error'));
        }

       /*  $pathPara = $this->getParameter('memberPath');
        $typeImg = "travelNoteImages";
        if (!empty($imgQryRes)) {
            foreach ($imgQryRes as $v) {
                if (file_exists($pathPara . "$typeImg/" . $id . "/" . $v['travel_note_image'])) {
                    unlink($pathPara . "$typeImg/" . $id . "/" . $v['travel_note_image']);
                }
            }
        } */

        try {
            $em->createQueryBuilder()
                ->delete('AcmeMinsuBundle:TravelNoteImage', 'p')
                ->where('p.travel_note_id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->execute();
        } catch (Exception $e) {
            return new JsonResponse($this->failMassageReturnAction('error'));
        }

        $time = time();
        //save cover image
         if ($coverImg) {
            //$baseCoverImgName = uniqid();
            //if (file_put_contents($pathPara . "$typeImg/" . $id . "/" . $baseCoverImgName . ".jpg", $baseCoverImg)) { */
                try {
                    $img = new TravelNoteImage();
                    $img->setTravelNoteId($id);
                    $img->setTravelNoteImage("$coverImg");
                    $img->setTravelNoteImageSort(0);
                    $img->setIsDefault(1);
                    $img->setAddTime($time);
                    $em->persist($img);
                    $em->flush();
                } catch (Exception $e) {
                    return new JsonResponse($this->failMassageReturnAction('error'));
                }
            /* } else {
                return new JsonResponse($this->failMassageReturnAction('error'));
            } */
        } else {
            return new JsonResponse($this->failMassageReturnAction('error'));
        }

        //save content images
        $contentImg = json_decode($contentImg, true);

            if (!empty($contentImg)) {
                $lenth = count($contentImg);
                for ($i = 0; $i < $lenth; $i++) {
                    /* if ($baseImg = base64_decode($contentImg[$i]['img'])) {
                        $baseImgName = uniqid() . $i;
                        if (file_put_contents($pathPara . "$typeImg/" . $id . "/" . $baseImgName . ".jpg", $baseImg)) { */
                            try {
                                $img = new TravelNoteImage();
                                $img->setTravelNoteId($id);
                                $img->setTravelNoteImage("{$contentImg[$i]['img']}");
                                $img->setTravelNoteImageSort($i);
                                $img->setIsDefault(0);
                                $img->setAddTime($time);
                                $em->persist($img);
                                $em->flush();
                            } catch (Exception $e) {
                                return new JsonResponse($this->failMassageReturnAction('error'));
                            }
                        /* } else {
                            return new JsonResponse($this->failMassageReturnAction('error'));
                        } 
                    } else {
                        return new JsonResponse($this->failMassageReturnAction('error'));
                    }*/
                }
            }

        return new JsonResponse($this->successMassageReturnAction('success'));
    }

    /**
     * @Route("/apiSaveRoom", name="apiSaveRoom_")
     */
    public function apiSaveRoomAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
        } elseif ($request->isMethod('GET')) {
            $data = $request->query->all();
        } else {
            $returnMsg = $this->failMassageReturnAction('paras error');
            return new JsonResponse($returnMsg);
        }

        $em = $this->getDoctrine()->getManager();
        $roomUpdQry = $em->getConnection()->createQueryBuilder()
            ->update('msk_room', 'p')
            ->set('p.homestay_id', ':homestayId')
            ->set('p.room_title', ':title')
            ->set('p.room_single_bed', ':singleBed')
            ->set('p.room_double_bed', ':doubleBed')
            ->set('p.room_num', ':num')
            ->set('p.room_hall', ':hall')
            ->set('p.repast', ':repast')
            ->set('p.cash', ':cash')
            ->set('p.room_kitchen', ':kitchen')
            ->set('p.room_toilet', ':toilet')
            ->set('p.room_balcony', ':balcony')
            ->set('p.state', ':state')
            ->set('p.addtime', ':time')
            ->set('p.room_price',":price")
            ->where('p.id = :id')
            ->setParameters(
                array(
                    'homestayId' => $data['homestay_id'],
                    'title' => $data['room_title'],
                    'singleBed' => $data['room_single_bed'],
                    'doubleBed' => $data['room_double_bed'],
                    'num' => $data['room_num'],
                    'hall' => $data['room_hall'],
                    'repast' => $data['repast'],
                    'cash' => $data['cash'],
                    'kitchen' => $data['room_kitchen'],
                    'toilet' => $data['room_toilet'],
                    'balcony' => $data['room_balcony'],
                    'state' => $data['state'],
                    'time' => time(),
                    'id' => $data['room_id'],
                		'price' =>$data['room_price']
                )
            );

        try {
            $roomUpdQry->execute();
        } catch (Exception $e) {
            $returnMsg = $this->failMassageReturnAction('sql error');
            return new JsonResponse($returnMsg);
        }

        if (array_key_exists('room_server_id', $data) && !is_null($data['room_server_id'])) {
            $serverArr = explode(',', $data['room_server_id']);

            try {
                $em->createQueryBuilder()
                    ->delete('AcmeMinsuBundle:RoomServerRelation', 'p')
                    ->where('p.room_id = :id')
                    ->setParameter('id', $data['room_id'])
                    ->getQuery()
                    ->execute();
                foreach ($serverArr as $value) {
                    $roomServer = new RoomServerRelation();
                    $roomServer->setRoom_id($data['room_id']);
                    $roomServer->setRoom_server_id($value);
                    $em->persist($roomServer);
                    $em->flush();
                }
            } catch (Exception $e) {
                $returnMsg = $this->failMassageReturnAction('sql error');
                return new JsonResponse($returnMsg);
            }
        }

        if (array_key_exists('roomImg', $data) && !is_null($roomImg = $data['roomImg'])) {
            $pathPara = $this->getParameter('memberPath');
            $imgQry = $em->getConnection()->createQueryBUilder()
                ->select(
                    "p.goods_image"
                )
                ->from('msk_images', 'p')
                ->where('p.homestay_room_id = :roomId')
                ->andWhere('p.img_type = :type')
                ->setParameters(
                    array(
                        'roomId' => $data['room_id'],
                        'type' => 1
                    )
                )
                ->execute();
            try {
                $imgQryRes = $imgQry->fetchAll();
            } catch (Exception $e) {
                $returnMsg = $this->failMassageReturnAction('sql error');
                return new JsonResponse($returnMsg);
            }

           /*  if (!empty($imgQryRes)) {
                foreach ($imgQryRes as $v) {
                    $imgFile = $pathPara . "Room/" . $data['member_id'] . "/" . $v['goods_image'];
                    if (file_exists($imgFile)) {
                        unlink($imgFile);
                    }
                }
            } */

            $roomImg = json_decode($roomImg, true);
            $lenth = count($roomImg);
            $em->createQueryBuilder()
                ->delete('AcmeMinsuBundle:Images', 'p')
                ->where('p.homestay_room_id = :id')
                ->andWhere('p.img_type = :type')
                ->setParameters(
                    array(
                        'id' => $data['room_id'],
                        'type' => 1
                    ))
                ->getQuery()
                ->execute();
            for ($i = 0; $i < $lenth; $i++) {
                //$baseImg = base64_decode($roomImg[$i]['imgArr']);
               // $baseImgName = uniqid() . $i;
               // if (file_put_contents($pathPara . "Room/" . $data['member_id'] . "/" . $baseImgName . ".jpg", $baseImg)) {
                    try {
                        $img = new Images();
                        $img->setHomestay_room_id($data['room_id']);
                        $img->setMember_id($data['member_id']);
                        $img->setImg_type(1);
                        $img->setGoods_image("{$roomImg[$i]['imgArr']}");
                        $img->setImg_dscp($roomImg[$i]['img_dscp']);
                        $img->setGoods_image_sort(0);
                        $img->setIs_default($roomImg[$i]['is_default']);
                        $img->setAdd_time(time());
                        $em->persist($img);
                        $em->flush();
                    } catch (Exception $e) {
                        return new JsonResponse($this->failMassageReturnAction('sql error'));
                    }
                //} else {
                //   return new JsonResponse($this->failMassageReturnAction('file move error'));
                //}
            }
        }
        $scsReturnMsg = $this->successMassageReturnAction('success');
        return new JsonResponse($scsReturnMsg);
    }

    /**
     * @Route("/apiDateSelect", name="apiDateSelect_")
     */
    public function apiDateSelectAction(Request $request)
    {
        /**
         * homestay id
         * date_select the date user select,the type is string like '20160907,20160908...'
         * */
        $homestayId = $request->get('homestay_id');
        $dateSelect = $request->get('date_select');
        if (!$homestayId || !$dateSelect) {
            $returnMsg = $this->failMassageReturnAction('paras error');
            return new JsonResponse($returnMsg);
        }
        $em = $this->getDoctrine()->getManager();
        $dateSelectArr = explode(',', $dateSelect);

        /**
         * use the homestay id to select the all room id
         */
        $roomIdQry = $em->getConnection()->createQueryBuilder()
            ->select(
                'c.id'
            )
            ->from('msk_room', 'c')
            ->where('c.homestay_id = :homestayId')
            ->setParameter('homestayId', $homestayId)
            ->orderBy('c.id', 'desc')
            ->execute();
        try {
            $roomIdQryRes = $roomIdQry->fetchAll();
        } catch (Exception $e) {
            $returnMsg = $this->failMassageReturnAction('sql error');
            return new JsonResponse($returnMsg);
        }

        if (empty($roomIdQryRes)) {
            $returnMsg = $this->failMassageReturnAction('no room');
            return new JsonResponse($returnMsg);
        }

        foreach ($roomIdQryRes as $value) {
            $roomIdArr[] = $value['id'];
        }
        /* $roomIdArr = $roomIdQryRes[0];*/
        $data = array();
        foreach ($roomIdArr as $id) {
            try {
                $roomCheckinQryRes = $em->getConnection()->createQueryBuilder()
                    ->select(
                        'p.date_has_checkin'
                    )
                    ->from('msk_room_checkin_date', 'p')
                    ->where('p.room_id = :id')
                    ->setParameters(
                        array(
                            'id' => $id,
                        )
                    )
                    ->execute()
                    ->fetchAll();
            } catch (Exception $e) {
                $returnMsg = $this->failMassageReturnAction('sql error');
                return new JsonResponse($returnMsg);
            }

            if (!empty($roomCheckinQryRes)) {
                $dbRoomCheckin = $roomCheckinQryRes[0]['date_has_checkin'];
                $dbRoomCheckinArr = explode(',', $dbRoomCheckin);
                $intersectArr = array_intersect($dateSelectArr, $dbRoomCheckinArr);
                if (!empty($intersectArr)) {
                    $status = 0;
                } else {
                    $status = 1;
                }
            } else {
                $status = 1;
            }
            array_push($data, array('room_id' => $id, 'status' => $status));
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/apiRoomHasCheckin", name="apiRoomHasCheckin_")
     */
    public function apiRoomHasCheckinAction(Request $request)
    {
        $roomId = $request->get('room_id');
        if (!$roomId) {
            $returnMsg = $this->failMassageReturnAction('paras error');
            return new JsonResponse($returnMsg);
        }

        $em = $this->getDoctrine()->getManager();
        $data = array();
        $dateHasCheckinQry = $em->getConnection()->createQueryBuilder()
            ->select(
                'p.date_has_checkin'
            )
            ->from('msk_room_checkin_date', 'p')
            ->where('p.room_id = :roomId')
            ->setParameter('roomId', $roomId)
            ->execute();
        try {
            $dateHasCheckinQryRes = $dateHasCheckinQry->fetchAll();
        } catch (Exception $e) {
            $returnMsg = $this->failMassageReturnAction('sql error');
            return new JsonResponse($returnMsg);
        }

        $arrValue = '';
        if (!empty($dateHasCheckinQryRes)) {
            foreach ($dateHasCheckinQryRes as $value) {
                if (isset($value)) {
//                    var_dump($value['date_has_checkin']);
                    foreach ($value as $a) {
                        $arrValue .= "$a,";
                    }
                }

            }
//            $data = $dateHasCheckinQryRes[0];
        }
        $data['date_has_checkin'] = substr($arrValue, 0, strlen($arrValue) - 1);
        if (empty($data)) {
            $data['date_has_checkin'] = "";
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/apiCityDateSelect", name="apiCityDateSelect_")
     */
    public function apiCityDateSelectAction(Request $request)
    {
        $city = $request->get('city');
        $dateSelect = $request->get('date_select');
        $pageOffset = $request->get('page_offset');
        if (!$city || !$dateSelect || is_null($pageOffset)) {
            $returnMsg = $this->failMassageReturnAction('paras error');
            return new JsonResponse($returnMsg);
        }
        $dateSelectArr = explode(',', $dateSelect);
        $memberPathPara = $this->getParameter('memberPath');
        $avatarPathPara = $this->getParameter('avatar_path');
        $imageHost = $this->getParameter('image_host');

        $em = $this->getDoctrine()->getManager();
        try {
            $dateHasCheckinQryRes = $em->getConnection()->createQueryBuilder()
                ->select(
                    'p.room_id, p.date_has_checkin'
                )
                ->from('msk_room_checkin_date', 'p')
                ->orderBy('p.add_time', 'desc')
                ->execute()
                ->fetchAll();
        } catch (\Exception $e) {
            $returnMsg = $this->failMassageReturnAction('sql error');
            return new JsonResponse($returnMsg);
        }

        if (empty($dateHasCheckinQryRes)) {
            $returnMsg = $this->failMassageReturnAction('no match room');
            return new JsonResponse($returnMsg);
        }

        $notQualifyRoomIdArr = array();
        $allRoomIdArr = array();
        foreach ($dateHasCheckinQryRes as $v) {
            array_push($allRoomIdArr, $v['room_id']);
            $dateHasCheckinArr = explode(',', $v['date_has_checkin']);
            $intersectArr = array_intersect($dateSelectArr, $dateHasCheckinArr);
            if (!empty($intersectArr)) {
                array_push($notQualifyRoomIdArr, $v['room_id']);
            }
        }

        $qualifyRoomIdArr = array_diff($allRoomIdArr, $notQualifyRoomIdArr);
        if (empty($qualifyRoomIdArr)) {
            $returnMsg = $this->failMassageReturnAction('no match homestay');
            return new JsonResponse($returnMsg);
        }

        $searchData = array();
        foreach ($qualifyRoomIdArr as $v) {
            try {
                $homestayQryRes = $em->getConnection()->createQueryBuilder()
                    ->select(
                        'p.id, p.member_id, p.homestay_name, p.homestay_title, p.bottom_price, 
                        p.homestay_addr, c.avatar, c.is_owner, m.goods_image'
                    )
                    ->from('msk_room', 'n')
                    ->leftJoin('n', 'msk_homestay', 'p', 'p.id = n.homestay_id')
                    ->leftJoin('p', 'msk_member', 'c', 'p.member_id = c.id')
                    ->leftJoin('p', 'msk_images', 'm', 'p.id = m.homestay_room_id')
                    ->where('n.id = :roomId')
                    ->andWhere('n.state = :roomState')
                    ->andWhere('p.state = :state')
                    ->andWhere('p.is_manage = :isManage')
                    ->andWhere('p.city like :city')
                    ->andWhere('c.member_state = :memberState')
                    ->andWhere('m.is_default = :default')
                    ->andWhere('m.img_type in (:imgType)')
                    ->setParameters(
                        array(
                            'roomId' => $v,
                            'roomState' => 1,
                            'state' => 1,
                            'isManage' => 1,
                            'city' => "%" . $city . "%",
                            'memberState' => 1,
                            'default' => 1,
                            'imgType' => 0
                        )
                    )
                    ->execute()
                    ->fetchAll();
                if (!empty($homestayQryRes) || !empty($homestayQryRes[0]['goods_image'])) {
                    $fullPathImg = $imageHost . '/' . $memberPathPara . 'HomeStay/' . $homestayQryRes[0]['member_id'] . '/';
                    foreach ($homestayQryRes as $key => $value) {
                        $homestayQryRes[0]['homeStayDefultImg'] = $this->getParameter('app_qiniu_imgurl'). $value['goods_image'];
                        $homestayQryRes[0]['avatar'] =$this->getParameter('app_qiniu_imgurl'). $value['avatar'];
                        unset($homestayQryRes[$key]['goods_image']);
                    }

                    array_push($searchData, $homestayQryRes[0]);
                }
            } catch (Exception $e) {
                $returnMsg = $this->failMassageReturnAction('sql error');
                return new JsonResponse($returnMsg);
            }
        }

        function array_unique_callback(array $arr, callable $callback, $strict = false) {
            return array_filter(
                $arr,
                function ($item) use ($strict, $callback) {
                    static $haystack = array();
                    $needle = $callback($item);
                    if (in_array($needle, $haystack, $strict)) {
                        return false;
                    } else {
                        $haystack[] = $needle;
                        return true;
                    }
                }
            );
        }

        if (!empty($searchData)) {
            $data = array_unique_callback(
                $searchData,
                function ($searchData) {
                    return $searchData['id'];
                });

            foreach ($data as $key => $mv) {
                $videoQryRes = $em->getConnection()->createQueryBuilder()
                    ->select('p.goods_image')
                    ->from('msk_images', 'p')
                    ->where('p.id = :id')
                    ->andWhere('p.img_type = :imgType')
                    ->setParameters(
                        array(
                            'id' => $mv['id'],
                            'imgType' => 3
                        )
                    )
                    ->execute()
                    ->fetchAll();
                if (empty($videoQryRes)) {
                    $data[$key]['is_have_video'] = 0;
                } else {
                    $data[$key]['is_have_video'] = 1;
                }
            }
        } else {
            $data = null;
        }

        $pageSlice = array_slice($data, $pageOffset, 10);

        return new JsonResponse($pageSlice);
    }

    /**
     * @Route("/apiDateManage", name="apiDateManage_")
     */
    public function apiDateManageAction(Request $request)
    {
        $dateSelect = $request->get('date_select');
        $roomId = $request->get('room_id');
        if (!$dateSelect || !$roomId) {
            $returnMsg = $this->failMassageReturnAction('paras error');
            return new JsonResponse($returnMsg);
        }

        $em = $this->getDoctrine()->getManager();
        try {
            $roomCheckInDate = new RoomCheckinDate();
            $roomCheckInDate->setRoomId($roomId);
            $roomCheckInDate->setDateHasCheckin($dateSelect);
            $roomCheckInDate->setState(1);
            $roomCheckInDate->setAddTime(time());
            $em->persist($roomCheckInDate);
            $em->flush();
        } catch (Exception $e) {
            $returnMsg = $this->failMassageReturnAction('sql error');
            return new JsonResponse($returnMsg);
        }
        $returnMsg = $this->successMassageReturnAction('success');
        return new JsonResponse($returnMsg);
    }

    /**
     * @Route("/apiShieldTravelNote", name="apiShieldTravelNote_")
     */
    public function apiShieldTravelNoteAction(Request $request)
    {
        $travelNoteId = $request->get('travel_note_id');
        if (!$travelNoteId) {
            $returnMsg = $this->failMassageReturnAction('paras error');
            return new JsonResponse($returnMsg);
        }

        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository('AcmeMinsuBundle:TravelNote');
        $travelNOte = $res->findBy(
            array(
                'id' => $travelNoteId
            )
        );

        if (!$travelNOte) {
            $returnMsg = $this->failMassageReturnAction('not found travel note');
            return new JsonResponse($returnMsg);
        }

        try {
            foreach ($travelNOte as $v) {
                $v->setState(1);
                $em->persist($v);
                $em->flush();
            }
        } catch (Exception $e) {
            $returnMsg = $this->failMassageReturnAction('sql error');
            return new JsonResponse($returnMsg);
        }
        $returnMsg = $this->successMassageReturnAction('success');
        return new JsonResponse($returnMsg);
    }

    /**
     * @Route("/apiShieldCPost", name="apiShieldCPost_")
     */
    public function apiShieldCPostAction(Request $request)
    {
        $CPostId = $request->get('c_post_id');
        if (!$CPostId) {
            $returnMsg = $this->failMassageReturnAction('paras error');
            return new JsonResponse($returnMsg);
        }

        $em = $this->getDoctrine()->getManager();
        try {
            $em->getConnection()->createQueryBuilder()
                ->update('msk_community_post', 'p')
                ->set('p.state', ':state')
                ->where('p.id = :id')
                ->setParameters(
                    array(
                        'state' => 1,
                        'id' => $CPostId
                    )
                )
                ->execute();
        } catch (Exception $e) {
            $returnMsg = $this->failMassageReturnAction('sql error');
            return new JsonResponse($returnMsg);
        }
        $returnMsg = $this->successMassageReturnAction('success');
        return new JsonResponse($returnMsg);
    }

    /**
     * @Route("/apiHomeStayCommentList", name="apiHomeStayCommentList_")
     */
    public function apiHomeStayCommentList(Request $request){
         $homestay_id=$request->get('homestay_id',0);
         $offset=$request->get('offset',0);
         if (!$homestay_id) return new JsonResponse(array('code'=>300,'msg'=>'民宿不存在，请重新选择','result'=>'')); 
         $em = $this->getDoctrine()->getManager()->getConnection();
         $data=$em->createQueryBuilder()
                ->select("p.id as comment_id,p.homestay_id,p.member_id as user_id,s.nickname as user_name,m.avatar as avator,p.grade as rank,p.eval as content,p.addtime")
                ->from('msk_homestay_share_eval','p')
                ->leftJoin('p','msk_member','m','p.member_id=m.id')
                ->leftJoin('p','msk_member_info','s','p.member_id=s.member_id')
                ->where('homestay_id='.$homestay_id,'state=0')
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->orderBy('addtime','desc')
                ->execute()
                ->fetchAll();
         foreach ($data as $k=>$v){
             $data[$k]['avator']=$this->getParameter('app_qiniu_imgurl').$v['avator'];
             $data[$k]['addtime']=date("m月d日 H:i:s",$v['addtime']);
         }
        $count=$em->createQueryBuilder()
                ->select('count(id) as total')
                ->from('msk_homestay_share_eval')
                ->where('homestay_id='.$homestay_id,'state=0')
                ->execute()
                ->fetch();       
        if($data){
            return new JsonResponse(array('code'=>200,'msg'=>'success','total'=>$count['total'],'result'=>$data));
        } 
        return new JsonResponse(array('code'=>202,'msg'=>'该民宿暂时没有评论','result'=>''));             
    }
    
    
    /**
     * 筛选民宿
     * @Route("/apiFilterHomestay", name="apiFilterHomestay_")
     */
    public function apiFilterHomestay(Request $request){
        $homestay_type_id=$request->get('type_id');
        $bottom_price=$request->get('bottom_price',0);
        $height_price=$request->get('height_price',5000);
        $page=$request->get('page',0);
        $city=$request->get('city',0);
        $em = $this->getDoctrine()->getManager()->getConnection();
        if ($homestay_type_id!=1){
            $where="h.homestay_type_id=$homestay_type_id";
        }else{
            $where='';
        } 
        if (!empty($city)){
            $city="h.city = "."'$city'";
        }else{
            $city='';
        } 
        $data=$em->createQueryBuilder()
            ->select('h.id,h.member_id,h.homestay_name,h.homestay_title,h.bottom_price,m.avatar,h.is_manage,m.is_owner,h.image_url')
            ->from('msk_homestay','h')
            ->leftJoin('h','msk_member','m','h.member_id=m.id')
            ->where($city,$where)
            ->andWhere('h.bottom_price > '.$bottom_price)
            ->andWhere('h.bottom_price < '.$height_price)
            ->setFirstResult($page)
            ->setMaxResults(10)
        //->andWhere('is_manage=1')
            ->orderBy('h.addtime','desc')
            ->groupBy('h.id')
            ->execute()
            ->fetchAll();        
        if ($data)
        {
            //print_r($data);die;
            foreach ($data as $k=>$v)
            {
                $data[$k]['avatar']=$this->getParameter('app_qiniu_imgurl').$v['avatar'];
                $img=explode(';',$v['image_url']);
                $data[$k]['goods_image']=$this->getParameter('app_qiniu_imgurl').$img[0];
                if ($v['is_manage']==1)
                {
                    $comment=$em->createQueryBuilder()
                        ->select("count(id) as total,avg(grade) as avge")
                        ->from("msk_order",'p')
                        ->leftJoin('p','msk_order_evaluation','m','p.order_sn=m.order_sn')
                        ->where('homestay_id='.$v['id'])
                        ->execute()
                        ->fetch();
                    if (empty($comment['avge']))
                    {
                        $comment['avge']='10.0';
                    }else
                    {
                        $comment['avge']=$comment['avge']/4>=1?'10.0':"'".((bcdiv($comment['avge'],4,2))*10)."'";
                    }
                }else
                {
                    $comment=$em->createQueryBuilder()
                        ->select("count(id) as total,avg(grade) as avge")
                        ->from("msk_homestay_share_eval")
                        ->where('state=0','homestay_id='.$v['id'],'pid=0')
                        ->execute()
                        ->fetch();
                        if (empty($comment['avge'])){
                            $comment['avge']='10.0';
                        }else{
                            $comment['avge']=$comment['avge']/4>=1?'10.0':"'".((bcdiv($comment['avge'],4,2))*10)."'";
                        }
                }
                $data[$k]['comment_count']=$comment['total'];
                $data[$k]['comment_avg']=$comment['avge'];
            }
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else
        {
            return new JsonResponse(array('code'=>202,'msg'=>'暂无数据','result'=>''));
        }
    }
             
      /**
     * @Route("/apiHomestayDetailInfo", name="apiHomestayDetailInfo_")
     */
    public function apiHomestayDetailInfo(Request $request){
        $member_id =isset($_Sesstion['member_id'])?$_Sesstion['member_id']:0;
        //$member_id=$request->get('member_id',0);
        $homestay_id = $request->get('homestay_id',0);
        $em = $this->getDoctrine()->getManager()->getConnection();
        $data=$em->createQueryBuilder()
            ->select( 'p.id,p.member_id,p.homestay_name,p.bottom_price,p.homestay_title,p.homestay_addr,
              p.dscp,p.homestay_phone, p.longitude, p.latitude,p.reception_time,p.homestay_type_id,
              p.least_day, p.invoice, p.ban_event,p.video_url,p.image_url,i.avatar')
            ->from('msk_homestay','p')
            ->leftJoin('p','msk_member','i','p.member_id=i.id')
            ->where('p.id='.$homestay_id)
            ->andWhere('p.state=1')
            ->execute()
            ->fetch();        
        if ($data)
        {   
            $video=explode(';',$data['video_url']);
            if ($data['video_url']){
                $data['video_url'] = $this->getParameter('app_qiniu_imgurl').$video[1];
                $data['video_cover_url'] = $this->getParameter('app_qiniu_imgurl').$video[0];
            }else{
                $data['video_url'] =0;
                $data['video_cover_url'] =0;
            }

            $img=explode(';',$data['image_url']);
            if(is_array($img)){

                foreach ($img as $key => $value) {
                     $data['image'][] = $this->getParameter('app_qiniu_imgurl').$img[$key];
                     unset($data['image_url']);
                }
            }
            $data['avatar']=$this->getParameter('app_qiniu_imgurl').$data['avatar'];
            $data['is_upvote']=0;
            $upvote=$em->createQueryBuilder()
                    ->select( 'p.*')
                    ->from('msk_homestay_upvote','p')
                    ->where('p.member_id='.$member_id)
                    ->andWhere('p.homestay_id='.$homestay_id)
                    ->andWhere('p.upvote=1')
                    ->execute()
                    ->fetch(); 
            $upvote1=$em->createQueryBuilder()
                ->select( 'count(id) as count')
                ->from('msk_homestay_upvote','p')
                ->where('p.homestay_id='.$homestay_id)
                ->andWhere('p.upvote=1')
                ->execute()
                ->fetch();
             if(!empty($upvote)){
                 $data['is_upvote']=1;
             } 
             $data['upvote_num']=$upvote1['count'];          
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else
        {
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }
    }


    
}




























