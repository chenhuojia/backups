<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-6-3
 * Time: 11:46
 */
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\TravelNote;
use Acme\MinsuBundle\Entity\TravelNoteImage;
use Acme\MinsuBundle\Entity\FollowCollect;
use Acme\MinsuBundle\Common\CommonController;
class apiTravelNoteController extends CommonController
{
    public function __construct(){
    
    }
    
    /**
     * @Route("aapiUploadTravelNote", name="aapiUploadTravelNote_")
     */
/*     public function apiUploadTravelNoteAction(Request $request)
    {  
        if (!$memberId = $request->get('member_id')) {
            $massage = $this->failMassageReturnAction('not found member id');
            return new JsonResponse($massage);
        }
        if (!$coverImg = $request->get('cover_img')) {
            $massage = $this->failMassageReturnAction('not found cover image');
            return new JsonResponse($massage);
        }
        if (!$title = $request->get('title')) {
            $massage = $this->failMassageReturnAction('not found title!');
            return new JsonResponse($massage);
        }
        if (!$content = $request->get('content')) {
            $massage = $this->failMassageReturnAction('not found content!');
            return new JsonResponse($massage);
        }
        $contentImg = $request->get('content_img');
        $travel_id = $request->get('travel_id',0);
        $homestay_id = $request->get('homestay_id',0);
        $addr = $request->get('addr');
        $homestay = $request->get('homestay');
        $ip = $_SERVER['REMOTE_ADDR'];
        $longitude = $request->get('longitude');
        $latitude = $request->get('latitude');
        $province = $request->get('province');
        $city = $request->get('city');
        $district = $request->get('district');
        $video = $request->get('video');
        $video_cover = $request->get('video_cover');
        //$memberPath = $this->getParameter('memberPath');        
        try { 
            $travelNote = new TravelNote();
            $travelNote->setMemberId($memberId);
            $travelNote->setTravelTitle($title);
            $travelNote->setTravelContent($content);
            $travelNote->setUploadIp("$ip");
            $travelNote->setPay_money(0);
            $travelNote->setMark_travel_id($travel_id);
             if ($addr) {
                $travelNote->setAddr($addr);
            }
            if ($longitude) {
                $travelNote->setLongitude($longitude);
            }
            if ($latitude) {
                $travelNote->setLatitude($latitude);
            }
            if ($province) {
                $travelNote->setProvince($province);
            }
            if ($city) {
                $travelNote->setCity($city);
            }
            if ($homestay_id) {
                $travelNote->setRecommendHomestay($homestay);
                $travelNote->setHomestay_id($homestay_id);
                
            }else{
                $travelNote->setHomestay_id(0);
            } 
            if ($district) {
                $travelNote->setDistrict($district);
            } 
            $travelNote->setState(0);
            $travelNote->setAddtime(time());

            $em = $this->getDoctrine()->getManager();
            $em->persist($travelNote);
            $em->flush();
            $travelNoteId = $travelNote->getId();
            $point=self::getpoint("景点");
            $b=self::addMemberPoints($memberId,$point['val'],$point['title']."($title)",0);
            $conn = $em->getConnection();
            $contentImg = json_decode($contentImg,true);
            if ($contentImg){
                $contentImgLength = count($contentImg);
                for ($i = 0; $i < $contentImgLength; $i++) {
                    try {
                        $travelNoteImage = new TravelNoteImage();
                        $travelNoteImage->setTravelNoteId($travelNoteId);
                        $travelNoteImage->setTravelNoteImage("$contentImg[$i]");
                        $travelNoteImage->setIsDefault('0');
                        $travelNoteImage->setTravelNoteImageSort($i);
                        $travelNoteImage->setAddTime(time());
                        $em->persist($travelNoteImage);
                        $em->flush();
                    } catch (Exception $e) {
                        return new JsonResponse($this->failMassageReturnAction($e->getMessage()));
                    }
                }
            }
            if ($video){
                try {
                    $travelNoteImage = new TravelNoteImage();
                    $travelNoteImage->setTravelNoteId($travelNoteId);
                    $travelNoteImage->setTravelNoteImage("$video");
                    $travelNoteImage->setIsDefault('3');
                    $travelNoteImage->setTravelNoteImageSort(0);
                    $travelNoteImage->setAddTime(time());
                    $em->persist($travelNoteImage);
                    $em->flush();
                    
                    $travelNoteImage = new TravelNoteImage();
                    $travelNoteImage->setTravelNoteId($travelNoteId);
                    $travelNoteImage->setTravelNoteImage("$video_cover");
                    $travelNoteImage->setIsDefault('2');
                    $travelNoteImage->setTravelNoteImageSort(0);
                    $travelNoteImage->setAddTime(time());
                    $em->persist($travelNoteImage);
                    $em->flush();
                } catch (Exception $e) {
                    return new JsonResponse($this->failMassageReturnAction($e->getMessage()));
                }
               
            }
            try {
                $travelNoteImage = new TravelNoteImage();
                $travelNoteImage->setTravelNoteId($travelNoteId);
                $travelNoteImage->setTravelNoteImage("$coverImg");
                $travelNoteImage->setIsDefault('1');
                $travelNoteImage->setTravelNoteImageSort(0);
                $travelNoteImage->setAddTime(time());
                $em->persist($travelNoteImage);
                $em->flush();
                $massage['status'] = '1';
                $massage['error'] = '0';
                $massage['massage'] = 'success';
                return new JsonResponse($massage);
            } catch (Exception $e) {
                return new JsonResponse($this->failMassageReturnAction($e->getMessage()));
            }           
        } catch(Exception $e) {
            return new JsonResponse($this->failMassageReturnAction($e->getMessage()));
        }
    } */

    /**
     * @Route("/apiTravelIndexData", name="apiTravelIndexData_")
     */
    public function apiTravelIndexData(Request $request)
    {
        $travelIndex = $request->get('travel_index');
        $page = $request->get('page',1);
        $province = $request->get('province');
        $longitude = $request->get('longitude');
        $latitude = $request->get('latitude');

        $host = $this->getParameter('host');
        $memberPath = $this->getParameter('memberPath');
        $pageSize = $this->getParameter('pagesize');
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();

        $startPage = ($page - 1) * $pageSize;

        if ($travelIndex == 1) {
            try {
                $travelDataQry = $conn->createQueryBuilder()
                    ->select(
                        'p.travel_title', 'p.id','c.travel_note_image','p.travel_content','p.pay_money', 'p.longitude', 'p.latitude', 'p.city'
                    )
                    ->from('msk_travel_note', 'p')
                    ->leftjoin('p', 'msk_travel_note_images', 'c', 'p.id = c.travel_note_id')
                    ->where('c.is_default = 1')
                    ->andWhere('p.state = 0')
                    ->setFirstResult($startPage)
                    ->setMaxResults($pageSize)
                    ->orderBy('p.addtime', 'desc')
                    ->execute();
                $data = $travelDataQry->fetchAll();
            } catch (Exception $e) {
                return new JsonResponse($this->failMassageReturnAction('sql exception'));
            }

            $travelDataResLenth = count($data);
            for ($i = 0; $i < $travelDataResLenth; $i++) {
                $data[$i]['travel_note_cover_image'] =$this->getParameter('app_qiniu_imgurl').$data[$i]['travel_note_image'];
                $comment=$conn->createQueryBuilder()
                    ->select("count(id) as total,avg(grade) as avge")
                    ->from("msk_post_discuss")
                    ->where('type=1','comPostId='.$data[$i]['id'])
                    ->execute()
                    ->fetch();
                if (empty($comment['avge'])){
                    $comment['avge']='10.0';
                }else{
                    $comment['avge']=$comment['avge']/4>=1?'10.0':"'".(($comment['avge']/4)*10)."'";
                }
                $data[$i]['comment_count']=$comment['total'];
                $data[$i]['comment_avg']=$comment['avge'];
                unset($data[$i]['travel_note_image']);
            }
//            var_dump($data);
            return new JsonResponse($data);
        } elseif ($travelIndex == 2) {
            try {
                $travelDataQry = $conn->createQueryBuilder()
                    ->select(
                        "p.travel_title, p.id, c.travel_note_image, count(a.travel_note_id) as myCount ,p.pay_money, p.longitude, p.latitude, p.city"
                    )
                    ->from('msk_travel_note', 'p')
                    ->leftjoin('p', 'msk_travel_note_images', 'c', 'p.id = c.travel_note_id')
                    ->leftjoin('p', 'msk_follow_collect', 'a', 'p.id = a.travel_note_id')
                    ->where('c.is_default = :is_default')
                    ->andWhere('p.state = :state')
                    ->andWhere('a.is_upvote = :is_upvote')
                    ->setParameters(array('is_default' => '1', 'state' => '0', 'is_upvote' => '1'))
                    ->setFirstResult($startPage)
                    ->setMaxResults($pageSize)
                    ->groupBy('a.travel_note_id')
                    ->orderBy('myCount', 'desc')
                    ->execute();
                $data = $travelDataQry->fetchAll();
            } catch (Exception $e) {
                return new JsonResponse($this->failMassageReturnAction('sql exception'));
            }

            $travelDataResLenth = count($data);
            for ($i = 0; $i < $travelDataResLenth; $i++) {
                $data[$i]['travel_note_cover_image'] =$this->getParameter('app_qiniu_imgurl').$data[$i]['travel_note_image'];
                unset($data[$i]['travel_note_image']);
                unset($data[$i]['myCount']);
            }
//            var_dump($data);
            return new JsonResponse($data);
        } elseif ($travelIndex == 3) {
            if (!$longitude) {
                return new JsonResponse($this->failMassageReturnAction('not receive longitude'));
            }
            if (!$latitude) {
                return new JsonResponse($this->failMassageReturnAction('not receive latitude'));
            }
            if (!$province) {
                return new JsonResponse($this->failMassageReturnAction('not receive province'));
            }
            try {
                $travelDataQry = $conn->createQueryBuilder()
                    ->select(
                        "p.travel_title, p.id, p.longitude as x, p.latitude as y, c.travel_note_image,p.pay_money, 
                        p.longitude, p.latitude, p.city"
                    )
                    ->from('msk_travel_note', 'p')
                    ->leftjoin('p', 'msk_travel_note_images', 'c', 'p.id = c.travel_note_id')
                    ->where('c.is_default = :is_default')
                    ->andWhere('p.state = :state')
                    ->andWhere('p.province = :province')
                    ->setParameters(array('is_default' => '1', 'state' => '0', 'province' => $province))
                    ->setFirstResult($startPage)
                    ->setMaxResults($pageSize)
                    ->orderBy("(($longitude - x)*($longitude - x) + ($latitude - y) * ($latitude - y))", 'ASC')
                    ->execute();
                $data = $travelDataQry->fetchAll();
            } catch (Exception $e) {
                return new JsonResponse($this->failMassageReturnAction('sql exception'));
            }

            $travelDataResLenth = count($data);
            for ($i = 0; $i < $travelDataResLenth; $i++) {
                $data[$i]['travel_note_cover_image'] =$this->getParameter('app_qiniu_imgurl').$data[$i]['travel_note_image'];
                unset($data[$i]['travel_note_image']);
                unset($data[$i]['x']);
                unset($data[$i]['y']);
            }
//            var_dump($data);
            return new JsonResponse($data);
        } elseif ($travelIndex == 4) {
            $memberId = $request->get('member_id');
            if (!$memberId) {
                return new JsonResponse($this->failMassageReturnAction('not receive member id!'));
            }

            $travelDataQry = $conn->createQueryBuilder()
                ->select(
                    "p.travel_title, p.id, c.travel_note_image, p.pay_money, 
                    p.longitude, p.latitude, p.city"
                )
                ->from('msk_member_relation', 'm')
                ->leftJoin('m', 'msk_travel_note', 'p', 'p.member_id = m.to_member_id')
                ->leftJoin('p', 'msk_travel_note_images', 'c', 'c.travel_note_id = p.id')
                ->where('c.is_default = :is_default')
                ->andWhere('m.from_member_id = :memberId')
                ->andWhere('p.state = :state')
                ->andWhere('m.relation_type = :type')
                ->setParameters(array('is_default' => '1', 'state' => '0', 'memberId' => $memberId, 'type' => 1))
                ->setFirstResult($startPage)
                ->setMaxResults($pageSize)
                ->orderBy('p.addtime', 'desc')
                ->execute();
            $data = $travelDataQry->fetchAll();

            $travelDataResLenth = count($data);
            for ($i = 0; $i < $travelDataResLenth; $i++) {
                $data[$i]['travel_note_cover_image'] =$this->getParameter('app_qiniu_imgurl').$data[$i]['travel_note_image'];
                unset($data[$i]['travel_note_image']);
                unset($data[$i]['myCount']);
            }
//            var_dump($data);
            return new JsonResponse($data);
        } else {
            return new JsonResponse($this->failMassageReturnAction('not receive parameter'));
        }
    }

    /**
     * @Route("/apiTravelNoteDetail", name="apiTravelNoteDetail_")
     */
    public function apiTravelNoteDetailAction(Request $request)
    {
        $id = $request->get('id');
        $currentMemberId = $request->get('member_id');
        if (!$id) {
            $massage = $this->failMassageReturnAction('not found id');
            return new JsonResponse($massage);
        }

        $host = $this->getParameter('host');
        $avatarPath = $this->getParameter('avatar_path');
        $memberPath = $this->getParameter('memberPath');
        $travelNoteContentImg = array();

        try {
            $em = $this->getDoctrine()->getManager();

            $updateArticleClick = $em->getRepository('AcmeMinsuBundle:TravelNote')->find($id);
            if (!$updateArticleClick) {
                throw $this->createNotFoundException(
                    'no travel note found for id ' . $id
                );
            }
            $updateArticleClick->setArticleClick($updateArticleClick->getArticleClick() + 1);
            $em->flush();

            if ($currentMemberId) {
                $upvoteCollectQry = $em->createQuery(
                    "select p.is_upvote, p.is_collect from AcmeMinsuBundle:FollowCollect p 
                WHERE p.travel_note_id = :id AND p.member_id = :mId"
                );
                $upvoteCollectQry->setParameters(array('id' => $id, 'mId' => $currentMemberId));
                $upvoteCollectRes = $upvoteCollectQry->execute();
//                var_dump($upvoteCollectRes);
                if ($upvoteCollectRes) {
                    if ($upvoteCollectRes[0]['is_upvote'] === false) {
                        $isUpvote = '0';
                    } elseif ($upvoteCollectRes[0]['is_upvote'] === true) {
                        $isUpvote = '1';
                    } elseif ($upvoteCollectRes[0]['is_upvote'] === null) {
                        $isUpvote = '0';
                    }
                    if ($upvoteCollectRes[0]['is_collect'] === false) {
                        $isCollect = '0';
                    } elseif ($upvoteCollectRes[0]['is_collect'] === true) {
                        $isCollect = '1';
                    } elseif ($upvoteCollectRes[0]['is_collect'] === null) {
                        $isCollect = '0';
                    }
                } else {
                    $isUpvote = '0';
                    $isCollect = '0';
                }
            } else {
                $isUpvote = '0';
                $isCollect = '0';
            }
//            var_dump($isUpvote);
//            var_dump($isCollect);

            $upvoteNumQry = $em->createQuery(
                "select count(p.travel_note_id) from AcmeMinsuBundle:FollowCollect p 
                  WHERE p.is_upvote = :isUpvote AND p.travel_note_id = :id"
            );
            $upvoteNumQry->setParameter('isUpvote', '1');
            $upvoteNumQry->setParameter('id', $id);
            $upvoteNumRes = $upvoteNumQry->execute();
            if ($upvoteNumRes) {
                $upvoteNum = $upvoteNumRes[0][1];
            } else {
                $upvoteNum = '0';
            }

            $travelNoteQry = $em->createQuery(
                "select p.member_id, p.travel_title, p.travel_content, p.addr, p.recommend_homestay, p.addtime,p.pay_money
                , p.longitude, p.latitude from AcmeMinsuBundle:TravelNote p WHERE p.id = :id AND p.state = 0"
            );
            $travelNoteQry->setParameter('id', $id);
            $travelNoteRes = $travelNoteQry->execute();//var_dump($travelNoteRes);

            $contentImageQry = $em->createQuery(
                "select p.travel_note_image from AcmeMinsuBundle:TravelNoteImage p 
            WHERE p.travel_note_id = :id AND p.is_default = 0 ORDER BY p.travel_note_image_sort ASC"
            );
            $contentImageQry->setParameter('id', $id);
            $contentImageRes = $contentImageQry->execute();
            foreach ($contentImageRes as $v) {
                array_push($travelNoteContentImg, $this->getParameter('app_qiniu_imgurl').$v['travel_note_image']);
            }

            $coverImageQry = $em->createQuery(
                "select p.travel_note_image from AcmeMinsuBundle:TravelNoteImage p 
            WHERE p.travel_note_id = :id AND p.is_default = 1 ORDER BY p.travel_note_image_sort ASC"
            );
            $coverImageQry->setParameter('id', $id);
            $coverImageRes = $coverImageQry->execute();
            $coverImage = $this->getParameter('app_qiniu_imgurl').$coverImageRes[0]['travel_note_image'];

            $memberId = $travelNoteRes[0]['member_id'];

            $avatarQry = $em->createQuery(
                "select p.avatar from AcmeMinsuBundle:Member p WHERE p.id = :memberId"
            );
            $avatarQry->setParameter('memberId', $memberId);
            $avatarRes = $avatarQry->execute();
            if (!empty($avatarRes) && !empty($avatarRes[0])) {
                $avatar = $this->getParameter('app_qiniu_imgurl').$avatarRes[0]['avatar'];
            } else {
                $avatar = '';
            }


            $nicknameQry = $em->createQuery(
                "select p.nickname from AcmeMinsuBundle:MemberInfo p WHERE p.member_id = :memberId"
            );
            $nicknameQry->setParameter('memberId', $memberId);
            $nicknameRes = $nicknameQry->execute();
            if ($nicknameRes) {
                $nickname = $nicknameRes[0]['nickname'];
            } else {
                $nickname = '';
            }

            $homestay = $travelNoteRes[0]['recommend_homestay'];
            $conn = $em->getConnection();
            $homestayImgQry = $conn->createQueryBuilder()
                ->select(
                    'p.goods_image', 'p.homestay_room_id', 'c.homestay_title'
                )
                ->from('msk_images', 'p')
                ->rightjoin('p', 'msk_homestay', 'c', 'c.id = p.homestay_room_id')
                ->where('c.homestay_name = :homestay')
                ->andWhere('p.img_type = :type')
                ->setParameter('homestay', $homestay)
                ->setParameter('type', 0)
                ->setMaxResults(1)
                ->execute();
            $homestayImgRes = $homestayImgQry->fetchAll();
            if (!empty($homestayImgRes)) {
                $homestayImg =$this->getParameter('app_qiniu_imgurl').$homestayImgRes[0]['goods_image'];
                $homgstayTital = $homestayImgRes[0]['homestay_title'];
                $homestayRoomId = $homestayImgRes[0]['homestay_room_id'];
            } else {
                $homestayImg = '';
                $homgstayTital = '';
                $homestayRoomId = '';
            }

            //评论数量统计
            $commentNumQry = $em->createQuery(
                "select count(p.memberId) from AcmeMinsuBundle:PostDiscuss p WHERE p.comPostId = :id AND p.type = :type"
            )
                ->setParameters(array('id' => $id, 'type' => 1));
            $commentNumQryRes = $commentNumQry->execute();

        } catch (Exception $e) {
            return new JsonResponse($this->failMassageReturnAction($e->getMessage()));
        }
        if (!empty($travelNoteRes)) {
            if (!array_key_exists('longitude', $travelNoteRes[0])) {
                $longitude = '';
            } else {
                $longitude = $travelNoteRes[0]['longitude'];
            }
            if (!array_key_exists('latitude', $travelNoteRes[0])) {
                $latitude = '';
            } else {
                $latitude = $travelNoteRes[0]['latitude'];
            }
        }


        $data = array(
            'travel_note_cover_image' => $coverImage,
            'travel_note_title' => $travelNoteRes[0]['travel_title'],
            'travel_note_addtime' => $travelNoteRes[0]['addtime'],
            'travel_note_addr' => $travelNoteRes[0]['addr'],
            'travel_note_content' => $travelNoteRes[0]['travel_content'],
            'travel_note_content_image' => $travelNoteContentImg,
            'travel_note_homestay' => $travelNoteRes[0]['recommend_homestay'],
            'member_id' => $travelNoteRes[0]['member_id'],
            'longitude' => $longitude,
            'latitude' => $latitude,
            'travel_note_homestay_image' => $homestayImg,
            'travel_note_homestay_title' => $homgstayTital,
            'travel_note_homestay_id' => $homestayRoomId,
            'member_avatar' => $avatar,
            'member_nickname' => $nickname,
            'is_upvote' => $isUpvote,
            'is_collect' => $isCollect,
            'upvote_num' => $upvoteNum,
        	'pay_money'=>$travelNoteRes[0]['pay_money'],
            'comment_num' => $commentNumQryRes[0][1]
        );
        //print_r($data);
        return new JsonResponse($data);
    }

    /**
     * @Route("/apiUpvoteCollect", name="apiUpvoteCollect_")
     */
    public function apiFollowUpvoteAction(Request $request)
    {
        if (!$travelNoteId = $request->get('travel_note_id')) {
            return new JsonResponse($this->failMassageReturnAction('not receive travel note id'));
        }
        if (!$memberId = $request->get('member_id')) {
            return new JsonResponse($this->failMassageReturnAction('not receive user id'));
        }
        if (!$action = $request->get('action')) {
            return new JsonResponse($this->failMassageReturnAction('not receive action'));
        }
        $time = time();

        $em = $this->getDoctrine()->getManager();
        $travelNoteExist = $em->getRepository('AcmeMinsuBundle:FollowCollect')->findOneBy(array(
            'travel_note_id' => $travelNoteId, 'member_id' => $memberId
        ));

        if ($action == 10) {
            $upvote = $request->get('upvote');
            if ($upvote == '') {
                return new JsonResponse($this->failMassageReturnAction('not receive upvote parameter'));
            }
            if ($travelNoteExist) {
                $travelNoteExist->setIsUpvote($upvote);
                $travelNoteExist->setAddTime($time);
                if (!$em->flush()) {
                    return new JsonResponse($this->successMassageReturnAction('success'));
                } else {
                    return new JsonResponse($this->failMassageReturnAction('update upvote fail'));
                }
            } else {
                $conn = $em->getConnection();

                try {
                    $conn->insert('msk_follow_collect',
                        array(
                            'travel_note_id' => $travelNoteId,
                            'member_id' => $memberId,
                            'is_upvote' => $upvote,
                            'is_collect' => 0,
                            'add_time' => $time
                        )
                    );
                    return new JsonResponse($this->successMassageReturnAction('success'));
                } catch (Exception $e) {
                    return new JsonResponse($this->failMassageReturnAction($e->getMessage()));
                }
            }
        } elseif ($action == 20) {
            $collect = $request->get('collect');
//            return new JsonResponse($collect);
            if ($collect == '') {
                return new JsonResponse($this->failMassageReturnAction('not receive collect parameter'));
            }
            if ($travelNoteExist) {
//                var_dump('niha');
                $travelNoteExist->setIsCollect($collect);
                $travelNoteExist->setAddTime($time);
                if (!$em->flush()) {
//                    var_dump('jsid');
                    return new JsonResponse($this->successMassageReturnAction('success'));
                } else {
                    return new JsonResponse($this->failMassageReturnAction('update collect fail'));
                }
            } else {
                $conn = $em->getConnection();
                try {
                    $conn->insert('msk_follow_collect',
                        array(
                            'travel_note_id' => $travelNoteId,
                            'member_id' => $memberId,
                            'is_upvote' => 0,
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
     * @Route("/apitest",name="api_test_")
     */
    public function apitestAction()
    {
//        var_dump($_SERVER['REMOTE_ADDR']);
        return $this->render('AcmeMinsuBundle:apitest:apitest.html.twig');
    }
    
    /**
     * @Route("/apiNearByTravel",name="apiNearByTravel_")
     */
    public function apiNearByTravel(Request $request){
        var_dump(22);exit;
        // $page = $request->get('page');
        // $longitude = $request->get('longitude');
        // $latitude = $request->get('latitude');
        // $pageSize = $this->getParameter('pagesize');
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $startPage = ($page - 1) * $pageSize;
        if (!$longitude) {
            return new JsonResponse($this->failMassageReturnAction('not receive longitude'));
        }
        if (!$latitude) {
            return new JsonResponse($this->failMassageReturnAction('not receive latitude'));
        }
    
        try {
            $squares=self::getnearBy($longitude,$latitude);
            $travelDataQry = $conn->createQueryBuilder()
            ->select(
                "p.travel_title, p.id,p.longitude, p.latitude,p.province"
            )
            ->from('msk_travel_note', 'p')
            ->where('p.state = :state')
            ->andWhere("latitude<>0 and latitude>{$squares['right-bottom']['lat']} and latitude<{$squares['left-top']['lat']} and longitude>{$squares['left-top']['lng']} and longitude<{$squares['right-bottom']['lng']}")
            ->setParameters(array('state' => '0'))
                ->setFirstResult($startPage)
                ->setMaxResults($pageSize)
                ->orderBy("(($longitude - longitude)*($longitude - longitude) + ($latitude -latitude) * ($latitude - latitude))", 'ASC')
                    ->execute();
                    $data = $travelDataQry->fetchAll();
        } catch (Exception $e) {
        return new JsonResponse($this->failMassageReturnAction('sql exception'));
        }
        if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }
    
    
    
    
    /**
    * @Route("/apiSearchNearByTravel",name="apiSearchNearByTravel_")
    */
    public function apiSearchNearByTravel(Request $request){
         $page = $request->get('page');
         $longitude = $request->get('longitude');
         $latitude = $request->get('latitude');
         $travel_title=$request->get('travel_title');
         $pageSize = $this->getParameter('pagesize');
         $em = $this->getDoctrine()->getManager();
         $conn = $em->getConnection();
         $startPage = ($page - 1) * $pageSize;
         if (!$travel_title) 
         {
             return new JsonResponse($this->failMassageReturnAction('not receive travel_title'));
         }
         if (!$longitude) 
         {
            return new JsonResponse($this->failMassageReturnAction('not receive longitude'));
         }
         if (!$latitude) 
         {
            return new JsonResponse($this->failMassageReturnAction('not receive latitude'));
         }
         try 
         {
             $squares=self::getnearBy($longitude,$latitude);
             $travelDataQry = $conn->createQueryBuilder()
                ->select(
                    "p.travel_title, p.id,p.longitude, p.latitude,p.province"
                )
                ->from('msk_travel_note', 'p')
                ->where('p.state = :state')
                ->andWhere("latitude<>0 and latitude>{$squares['right-bottom']['lat']} and latitude<{$squares['left-top']['lat']} and longitude>{$squares['left-top']['lng']} and longitude<{$squares['right-bottom']['lng']}")
                ->andWhere("p.travel_title like '%$travel_title%'")
                ->setParameters(array('state' => '0'))
                ->setFirstResult($startPage)
                ->setMaxResults($pageSize)
                ->orderBy("(($longitude - longitude)*($longitude - longitude) + ($latitude - latitude) * ($latitude - latitude))", 'ASC')
                ->execute();
                $data = $travelDataQry->fetchAll();
          }catch (Exception $e)
          {
            return new JsonResponse($this->failMassageReturnAction('sql exception'));
         }
         if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
             return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
     }
    
    
     /**
     * @Route("/apiNearByHomestay",name="apiNearHomestay_")
     */
     public function apiNearByHomestay(Request $request){
         $page = $request->get('page');
         $longitude = $request->get('longitude');
         $latitude = $request->get('latitude');
         $pageSize = $this->getParameter('pagesize');
         $em = $this->getDoctrine()->getManager();
         $conn = $em->getConnection();
         $startPage = ($page - 1) * $pageSize;
         if (!$longitude) {
             return new JsonResponse($this->failMassageReturnAction('not receive longitude'));
         }
         if (!$latitude) {
         return new JsonResponse($this->failMassageReturnAction('not receive latitude'));
         }
         try {
         $squares=self::getnearBy($longitude,$latitude);
             $travelDataQry = $conn->createQueryBuilder()
             ->select(
                 "p.homestay_name,p.id,p.longitude, p.latitude,p.province"
                     )
                     ->from('msk_homestay', 'p')
                     ->Where("latitude<>0 and latitude>{$squares['right-bottom']['lat']} and latitude<{$squares['left-top']['lat']} and longitude>{$squares['left-top']['lng']} and longitude<{$squares['right-bottom']['lng']}")
                     ->setFirstResult($startPage)
                     ->setMaxResults($pageSize)
                     ->orderBy("(($longitude - longitude)*($longitude - longitude) + ($latitude -latitude) * ($latitude - latitude))", 'ASC')
                     ->execute();
                         //return  new JsonResponse($travelDataQry);
                         $data = $travelDataQry->fetchAll();
                     } catch (Exception $e) {
                     return new JsonResponse($this->failMassageReturnAction('sql exception'));
                     }
         if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
         return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
     }
    
    
     /**
     * @Route("/apiSearchNearByHomestay",name="apiSearchNearByHomestay_")
     */
     public function apiSearchNearByHomestay(Request $request){
         $page = $request->get('page');
         $longitude = $request->get('longitude');
         $latitude = $request->get('latitude');
         $travel_title=$request->get('homestay_name');
         $pageSize = $this->getParameter('pagesize');
         $em = $this->getDoctrine()->getManager();
         $conn = $em->getConnection();
         $startPage = ($page - 1) * $pageSize;
         if (!$travel_title) {
         return new JsonResponse($this->failMassageReturnAction('not receive Homestay_name'));
         }
         if (!$longitude) {
         return new JsonResponse($this->failMassageReturnAction('not receive longitude'));
         }
         if (!$latitude) {
         return new JsonResponse($this->failMassageReturnAction('not receive latitude'));
         }
         try {
         $squares=self::getnearBy($longitude,$latitude);
         $travelDataQry = $conn->createQueryBuilder()
         ->select(
             "p.homestay_name, p.id,p.longitude, p.latitude,p.province"
         )
         ->from('msk_homestay', 'p')
         ->Where("latitude<>0 and latitude>{$squares['right-bottom']['lat']} and latitude<{$squares['left-top']['lat']} and longitude>{$squares['left-top']['lng']} and longitude<{$squares['right-bottom']['lng']}")
         ->andWhere("p.homestay_name1 like '%$travel_title%'")
         ->setFirstResult($startPage)
         ->setMaxResults($pageSize)
         ->orderBy("(($longitude - longitude)*($longitude - longitude) + ($latitude - latitude) * ($latitude - latitude))", 'ASC')
         ->execute();

         $data = $travelDataQry->fetchAll();
         } catch (Exception $e) {
             return new JsonResponse($this->failMassageReturnAction('sql exception'));
         }
         if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
         return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }

    /**
     * @Route("/apiNearByTour",name="apiNearByTour_")
     */
     public function apiNearByTour(Request $request){
         $page = $request->get('page');
         $longitude = $request->get('longitude');
         $latitude = $request->get('latitude');
         $pageSize = $this->getParameter('pagesize');
         $em = $this->getDoctrine()->getManager();
         $conn = $em->getConnection();
         $startPage = ($page - 1) * $pageSize;
         if (!$longitude) {
             return new JsonResponse($this->failMassageReturnAction('not receive longitude'));
         }
         if (!$latitude) {
         return new JsonResponse($this->failMassageReturnAction('not receive latitude'));
         }
         try {
         $squares=self::getnearBy($longitude,$latitude);
             $travelDataQry = $conn->createQueryBuilder()
             ->select(
                 "p.tour_title,p.tour_id,a.longitude, a.latitude,p.agency_name"
                     )
                     ->from('msk_tour', 'p')
                     ->leftjoin('p','msk_tour_trip','a','a.tour_id=a.tour_id')
                     ->Where("a.latitude<>0 and a.latitude>{$squares['right-bottom']['lat']} and a.latitude<{$squares['left-top']['lat']} and a.longitude>{$squares['left-top']['lng']} and a.longitude<{$squares['right-bottom']['lng']}")
                     ->setFirstResult($startPage)
                     ->setMaxResults($pageSize)
                     ->orderBy("(($longitude - longitude)*($longitude - longitude) + ($latitude -latitude) * ($latitude - latitude))", 'ASC')
                     ->execute();
                         //return  new JsonResponse($travelDataQry);
                         $data = $travelDataQry->fetchAll();
                     } catch (Exception $e) {
                     return new JsonResponse($this->failMassageReturnAction('sql exception'));
                     }
         if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
         return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
     }

     /**
     * @Route("/apiSearchNearByTour",name="apiSearchNearByTour_")
     */
     public function apiSearchNearByTour(Request $request){
         $page = $request->get('page');
         $longitude = $request->get('longitude');
         $latitude = $request->get('latitude');
         $tour_title=$request->get('tour_title');
         $pageSize = $this->getParameter('pagesize');
         $em = $this->getDoctrine()->getManager();
         $conn = $em->getConnection();
         $startPage = ($page - 1) * $pageSize;
         if (!$travel_title) {
         return new JsonResponse($this->failMassageReturnAction('not receive Homestay_name'));
         }
         if (!$longitude) {
         return new JsonResponse($this->failMassageReturnAction('not receive longitude'));
         }
         if (!$latitude) {
         return new JsonResponse($this->failMassageReturnAction('not receive latitude'));
         }
         try {
         $squares=self::getnearBy($longitude,$latitude);
         $travelDataQry = $conn->createQueryBuilder()
         ->select(
             "p.tour_title, p.tour_id,a.longitude, a.latitude,p.agency_name"
         )
         ->from('msk_tour', 'p')
         ->leftjoin('p','msk_tour_trip','a','a.tour_id=a.tour_id')
         ->Where("a.latitude<>0 and a.latitude>{$squares['right-bottom']['lat']} and a.latitude<{$squares['left-top']['lat']} and a.longitude>{$squares['left-top']['lng']} and a.longitude<{$squares['right-bottom']['lng']}")
         ->andWhere("p.tour_title like '%$tour_title%'")
         ->setFirstResult($startPage)
         ->setMaxResults($pageSize)
         ->orderBy("(($longitude - longitude)*($longitude - longitude) + ($latitude - latitude) * ($latitude - latitude))", 'ASC')
         ->execute();
         $data = $travelDataQry->fetchAll();
         } catch (Exception $e) {
             return new JsonResponse($this->failMassageReturnAction('sql exception'));
         }
         if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
         return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }
    
    
    
    
    
     /**
     * @Route("/getnearBy",name="getnearBy_")
     */
     public function getnearBy($lng,$lat){
         $distance = 1;//范围（单位千米）
         //$lat = 113.873643;
         //$lng = 22.573969;
         define('EARTH_RADIUS', 6371);//地球半径，平均半径为6371km
         $dlng = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
         $dlng = rad2deg($dlng);
         $dlat = $distance/EARTH_RADIUS;
         $dlat = rad2deg($dlat);
         $squares = array('left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
         'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
         'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
         'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
         );
         return $squares;
    }




    //add by qiu
    /**
      * 搜索旅游景点
    * @Route("/apiSearchInfoTravel",name="apiSearchInfoTravel_")
    */
    public function apiSearchInfoTravel(Request $request)
    {    
         $em = $this->getDoctrine()->getManager();
         $page = $request->get('page',1);
         $city = $request->get('city','');
         $search =$request->get('search','');
         $host = $this->getParameter('host');
         $memberPath = $this->getParameter('memberPath');
         $avatarPath = $this->getParameter('avatar_path');
         $orderlist = "p.state = 0  and c.is_default=1 ";
         if(trim($search) !== ''){
                $orderlist.= 'and p.travel_title like '.'"%'.$search.'%"'.' ';
         }
         if(trim($city) !== ''){   
              $orderlist.= 'and p.city like '.'"%'.$city.'%"';
         }
         $page=$page-1;
         $conn = $em->getConnection();
         $data = $conn->createQueryBuilder()
         ->select(
                'p.travel_title', 'p.id', 'c.travel_note_image','p.travel_content','p.pay_money', 'p.longitude', 'p.latitude', 'p.city'
                )
                ->from('msk_travel_note', 'p')
                ->leftjoin('p', 'msk_travel_note_images', 'c', 'p.id = c.travel_note_id')
                ->where($orderlist)
                ->groupBy("p.id") 
                ->addOrderBy('p.addtime', 'DESC')
                ->setFirstResult($page)
                ->setMaxResults(10)   
                ->execute()
                ->fetchAll(); 
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['travel_note_cover_image'] =$this->getParameter('app_qiniu_imgurl').$data[$i]['travel_note_image'];
            $comment=$conn->createQueryBuilder()
                    ->select("count(id) as total,avg(grade) as avge")
                    ->from("msk_post_discuss")
                    ->where('type=1','comPostId='.$data[$i]['id'],'discussParentId=0')
                    ->execute()
                    ->fetch();
                 if (empty($comment['avge'])||$comment['avge']==0){
                    $comment['avge']='10.0';
                }else{
                    $comment['avge']=$comment['avge']/4>=1?'10.0':(($comment['avge']/4)*10);
                } 
                //$comment['avge']=0;
            $data[$i]['comment_count']=$comment['total'];
            $data[$i]['comment_avg']=$comment['avge'];
            unset($data[$i]['travel_note_image']);
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
      * 搜索民宿
    * @Route("/apiSearchInfoHomestay",name="apiSearchInfoHomestay_")
    */
    public function apiSearchInfoHomestay(Request $request)
    {    
         $em = $this->getDoctrine()->getManager();
         $page = $request->get('page',0);
         $city = $request->get('city','');
         $search =$request->get('search','');
         $avatarPath = $this->getParameter('app_qiniu_imgurl');
         $orderlist = "p.state = 1 ";
         if(trim($search) !== ''){
                $orderlist.= 'and p.homestay_name like '.'"%'.$search.'%"'.' ';
         }
         if(trim($city) !== ''){   
              $orderlist.= 'and p.city like '.'"%'.$city.'%"';
         }
        $conn = $em->getConnection();
        $roomImgQuery = $conn->createQueryBuilder()
        ->select(
                'p.id', 'p.member_id', 'p.homestay_name', 'p.homestay_title', 'p.homestay_addr', 'p.bottom_price','p.sort','p.addtime','p.city','p.is_manage',
                'c.avatar', 'c.is_owner','image_url'
                //,'p.check_in_time'
        )
        ->from('msk_homestay', 'p')
        ->leftjoin('p', 'msk_member', 'c', 'p.member_id = c.id')
        ->where($orderlist)
        
        // ->andWhere("p.check_in_time is not null")
        ->setFirstResult($page)
        ->setMaxResults(10)
        ->orderBy('p.addtime','DESC')
        ->execute();
        $data = $roomImgQuery->fetchAll();
        for ($i = 0; $i < count($data); $i++) {
            $img=explode(';',$data[$i]['image_url']);
            $data[$i]['homeStayDefultImg'] = $this->getParameter('app_qiniu_imgurl').$img[0];
            $data[$i]['avatar'] =$this->getParameter('app_qiniu_imgurl').$data[$i]['avatar'];
            if ( $data[$i]['is_manage']==0)
            {
                $comment=$conn->createQueryBuilder()
                ->select("count(id) as total,avg(grade) as avge")
                ->from("msk_homestay_share_eval")
                ->where('state=0','homestay_id='.$data[$i]['id'],'pid=0')
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
                ->where('p.homestay_id='.$data[$i]['id'])
                ->execute()
                ->fetch();
                if (empty($comment['avge'])){
                    $comment['avge']='10.0';
                }else{
                    $comment['avge']=$comment['avge']/4>=1?'10.0':"'".((bcdiv($comment['avge'],4,2))*10)."'";
                }
            }
            
            $data[$i]['comment_count']=$comment['total'];
            $data[$i]['comment_avg']=$comment['avge'];
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
      * 搜索民宿\景点\旅行团
    * @Route("/apiSearchInfoAll",name="apiSearchInfoAll_")
    */
    public function apiSearchInfoAll(Request $request)
    {    
         $em = $this->getDoctrine()->getManager();
         $page = $request->get('page',0);
         $city = $request->get('city','');
         $search =$request->get('search','');
         $host = $this->getParameter('host');
         $memberPath = $this->getParameter('memberPath');
         $avatarPath = $this->getParameter('avatar_path');
         $app_qiniu_imgurl = $this->getParameter('app_qiniu_imgurl');
         $orderlist = "p.state = 1 ";
         if(trim($search) !== ''){
                $orderlist.= 'and p.homestay_title like '.'"%'.$search.'%"'.' ';
         }
         if(trim($city) !== ''){   
              $orderlist.= 'and p.city like '.'"%'.$city.'%"';
         }
        $conn = $em->getConnection();
        $roomImgQuery = $conn->createQueryBuilder()
        ->select(
                'p.id as homestay_id', 'p.member_id', 'p.homestay_name', 'p.homestay_title', 'p.homestay_addr', 'p.bottom_price','p.sort','p.addtime','p.city','p.is_manage',
                'c.avatar', 'c.is_owner',
                'a.goods_image'
                //,'p.check_in_time'
        )
        ->from('msk_homestay', 'p')
        ->leftjoin('p', 'msk_member', 'c', 'p.member_id = c.id')
        ->leftjoin('p', 'msk_images', 'a', 'a.homestay_room_id = p.id')
        ->where($orderlist)
        ->andWhere("a.is_default = 1")
        ->andWhere("a.img_type = 0")
        ->addOrderBy('p.sort', 'DESC')
        ->addOrderBy('p.addtime', 'DESC')
        ->execute();
        $homestay = $roomImgQuery->fetchAll();
        for ($i = 0; $i < count($homestay); $i++) {
            $homestay[$i]['goods_image'] = $app_qiniu_imgurl.$homestay[$i]['goods_image'];
        }  
        //搜索景点
        $orderlist1 = "p.state = 0 ";
         if(trim($search) !== ''){
                $orderlist1.= 'and p.travel_title like '.'"%'.$search.'%"'.' ';
         }
         if(trim($city) !== ''){   
              $orderlist1.= 'and p.city like '.'"%'.$city.'%"';
         }
         $conn = $em->getConnection();
         $travel = $conn->createQueryBuilder()
                ->select(
                        'p.travel_title', 'p.id as travel_id', 'c.travel_note_image','p.travel_content','p.pay_money', 'p.longitude', 'p.latitude', 'p.city'
                        )
                ->from('msk_travel_note', 'p')
                ->leftjoin('p', 'msk_travel_note_images', 'c', 'p.id = c.travel_note_id')
                ->where($orderlist1)
                ->groupBy("p.id") 
                ->addOrderBy('p.addtime', 'DESC')
                ->execute()
                ->fetchAll(); 
        for ($i = 0; $i < count($travel); $i++) {
            $travel[$i]['travel_note_cover_image'] =
            $host . $memberPath . 'travelNoteImages/' . $travel[$i]['travel_id'] . '/' . $travel[$i]['travel_note_image'];
            unset($travel[$i]['travel_note_image']);
        }   
        //旅行团
        $orderlist2 = "p.state = 1 ";
        if(trim($search) !== ''){
                $orderlist2.= 'and p.tour_title like '.'"%'.$search.'%"'.' ';
        }
        if(trim($city) !== ''){   
              $orderlist2.= 'and p.starting_place like '.'"%'.$city.'%"';
        }
        $conn = $em->getConnection();
        $tour = $conn->createQueryBuilder()
        ->select(
                'p.tour_id', 'p.agency_id', 'p.tour_title', 'p.imgurl', 'p.default_adult_price as adult_price', 'p.period','p.addtime','p.starting_place','p.service_price','p.entered'
                )
                ->from('msk_tour', 'p')
                ->where($orderlist2)
                ->addOrderBy('p.addtime', 'DESC')
                ->execute()
                ->fetchAll();  
        foreach ($tour as $key => $value) {
             //查询多少人报名的列表(最多获取3个)
            $tour_id=$tour[$key]['tour_id'];
            $tour[$key]['enroll'] = $conn->createQueryBuilder()
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
                                ->andWhere("a.the_date =$now")
                                ->execute()
                                ->fetch();
            if($price){
                $tour[$key]['adult_price'] = $price['adult_price'];
            }
         } 
        $data = array_merge($homestay, $travel, $tour);
        $data = $this->arraySortByKey($data, 'addtime');
        $data = array_slice($data,$page,10,true ); 
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
    * 根据数组中的某个键值大小进行排序，仅支持二维数组
    * 
    * @param array $array 排序数组
    * @param string $key 键值
    * @param bool $asc 默认正序
    * @return array 排序后数组
    */
    function arraySortByKey(array $array, $key, $asc = true) 
    {
      $result = array();
      // 整理出准备排序的数组
      foreach ( $array as $k => &$v ) {
        $values[$k] = isset($v[$key]) ? $v[$key] : '';
      }
      unset($v);
      // 对需要排序键值进行排序
      $asc ? asort($values) : arsort($values);
      // 重新排列原有数组
      foreach ( $values as $k => $v ) {
        $result[$k] = $array[$k];
      }
      
      return $result;
    }

    
 
         
}















