<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-6-15
 * Time: 13:32
 */
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\HomestayCollect;
use Acme\MinsuBundle\Entity\FollowCollect;
use Acme\MinsuBundle\Entity\MemberRelation;
use Acme\MinsuBundle\Entity\ImToken;
use Acme\MinsuBundle\Entity\CPost;
use Acme\MinsuBundle\Common\CommonController;
class apiPersonalMsgController extends CommonController
{


    
    /**
     * @Route("/apiCollectCount", name="apiCollectCount_")
     */
    public function apiCollectCountController(Request $request){
        $memberId = $request->get('member_id',0);
        if ($memberId){
            $em = $this->getDoctrine()->getManager();
            $conn = $em->getConnection();
            $minsu=$conn->createQueryBuilder()
            ->select('count(homestay_id) as minsu')
            ->from("msk_homestay_collect")
            ->where('is_collect=1','member_id='.$memberId)
            ->execute()->fetch();
            $travel=$conn->createQueryBuilder()
            ->select('count(id) as travel')
            ->from("msk_follow_collect")
            ->where('is_collect=1','member_id='.$memberId)
            ->execute()->fetch();
            $shop=$conn->createQueryBuilder()
                ->select('count(id) as shop')
                ->from("msk_shop_collect")
                ->where('is_collect=1','user_id='.$memberId)
                ->execute()->fetch();
            $data=array(
                'minsu'=>$minsu['minsu'],
                'travel'=>$travel['travel'],
                'shop'=>$shop['shop'],
            );
            return  new JsonResponse(array('code'=>200,'result'=>$data));
        }
        return new JsonResponse($this->failMassageReturnAction('not user member'));
    }
    
    /**
     * @Route("/apiPersonalCollect", name="apiPersonalCollect_")
     */
    public function apiPersonalCollectAction(Request $request)
    {
        $action = $request->get('action',10);
        if (!$action) {
            return new JsonResponse($this->failMassageReturnAction('not receive action'));
        }
        $host = $this->getParameter('host');
        $avatarPath = $this->getParameter('avatar_path');
        $memberPath = $this->getParameter('memberPath');
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();

        if ($action == 10) {
            if (!$memberId = $request->get('member_id')) {
                return new JsonResponse($this->failMassageReturnAction('not receive member id'));
            }
            $data = array();

            $homestayIdQry = $em->createQuery(
                "select p.homestay_id from AcmeMinsuBundle:HomestayCollect p
            WHERE p.is_collect = :isCollect AND p.member_id = :memberId ORDER BY p.add_time DESC "
            )
                ->setParameters(array('isCollect' => '1', 'memberId' => $memberId));
            $homestayIdRes = $homestayIdQry->execute();
            if ($homestayIdRes) {
                foreach ($homestayIdRes as $v) {
                    $query = $conn->createQueryBuilder()
                        ->select(
                            "p.id, p.member_id, p.homestay_name, p.homestay_title, p.bottom_price, p.image_url, m.avatar, m.is_owner"
                        )
                        ->from('msk_homestay', 'p')
                        ->leftjoin('p', 'msk_member', 'm', 'p.member_id = m.id')
                        ->where('p.id = :id')
                        ->andWhere('m.member_state = :memberState')
                        ->andWhere('p.state = :state')
                        ->setParameters(array("id" => $v['homestay_id'], 'isDefault' => '1', 'memberState' => '1',
                        'state' => 1))
                        ->execute();
                    $res = $query->fetchAll();//var_dump($res);
                    if ($res) {
                        foreach ($res as $v2) {
                            $v2['avatar'] = $this->getParameter('app_qiniu_imgurl'). $v2['avatar'];
                            $img=explode(';',$v2['image_url']);
                            $v2['homeStayDefultImg'] = $this->getParameter('app_qiniu_imgurl').$img[0];
                            unset($v2['goods_image']);
                            unset($v2['member_id']);
                            array_push($data, $v2);
                        }
                    }
                }
//            var_dump($data);
//                var_dump($data);
                return new JsonResponse($data);
            } else {
                return new JsonResponse($this->failMassageReturnAction('not found homestay collect'));
            }
        }elseif($action == 30) {
            if (!$memberId = $request->get('member_id')) {
                return new JsonResponse($this->failMassageReturnAction('not receive member id'));
            }
            $data = array();
             $collect=$conn->createQueryBuilder()
                      ->select('p.shop_id,p.shop_name,p.shop_logo,p.on_goods')
                      ->from('msk_shop_collect','m')
                      ->leftJoin('m','msk_shop','p','m.shop_id=p.shop_id')
                      ->where('m.user_id='.$memberId,'m.is_collect=1')
                      ->orderBy('m.collect_time','desc')
                      ->execute()
                      ->fetchAll();
            if ($collect) {
                
                return new JsonResponse($collect);
            } else {
                return new JsonResponse($this->failMassageReturnAction('not found homestay collect'));
            }
        }elseif ($action == 20) {
            if (!$memberId = $request->get('member_id')) {
                return new JsonResponse($this->failMassageReturnAction('not receive member id'));
            }
            $data = array();

            $travelNoteIdQry = $em->createQuery(
                "select p.travel_note_id from AcmeMinsuBundle:FollowCollect p
                WHERE p.member_id = :memberId AND p.is_collect = :isCollect ORDER BY p.add_time DESC "
            )
                ->setParameters(array('memberId' => $memberId, 'isCollect' => '1'));
            $travelNoteIdRes = $travelNoteIdQry->execute();//var_dump($travelNoteIdRes);
            if ($travelNoteIdRes) {
                foreach ($travelNoteIdRes as $v) {
                    $travelNoteQry = $conn->createQuerybuilder()
                        ->select(
                            "p.id, p.member_id, p.travel_title, c.travel_note_image, p.longitude, 
                            p.latitude, p.pay_money, p.city"
                        )
                        ->from('msk_travel_note', 'p')
                        ->leftjoin('p', 'msk_travel_note_images', 'c', 'p.id = c.travel_note_id')
                        ->where('p.id = :id')
                        ->andWhere('c.is_default = :isDefault')
                        ->andWhere('p.state = :state')
                        ->setParameters(array('id' => $v['travel_note_id'], 'isDefault' => '1', 'state' => 0))
                        ->execute();
                    $travelNoteRes = $travelNoteQry->fetchAll();
                    if ($travelNoteRes) {
                        foreach ($travelNoteRes as $v1) {
                            $v1['travel_note_cover_image'] =$this->getParameter('app_qiniu_imgurl'). $v1['travel_note_image'];
                            unset($v1['travel_note_image']);
                            array_push($data, $v1);
                        }
                    }
                }
//                var_dump($data);
                return new JsonResponse($data);
            } else {
                return new JsonResponse($this->failMassageReturnAction('not found the collect id'));
            }
        }
    }

    /**
     * @Route("apiAttention", name="apiAttention_")
     */
    public function apiAttentionAction(Request $request)
    {
        if (!$memberId = $request->get('from_member_id')) {
            return new JsonResponse($this->failMassageReturnAction('not receive member id'));
        }
        if (!$toMemberId = $request->get('to_member_id')) {
            return new JsonResponse($this->failMassageReturnAction('not receive to member id'));
        }
        $attention = $request->get('attention');
        if ($attention !== '0' && $attention !== '1') {
            return new JsonResponse($this->failMassageReturnAction('not receive attention'));
        }
        $time = time();

        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $isExistIdQry = $em->getRepository('AcmeMinsuBundle:MemberRelation')->findOneBy(
            array('from_member_id' => $memberId, 'to_member_id' => $toMemberId)
        );
        if ($isExistIdQry) {
            $isExistIdQry->setRelationType($attention);
            $isExistIdQry->setAddTime($time);
            if (!$em->flush()) {
                return new JsonResponse($this->successMassageReturnAction('success'));
            } else {
                return new JsonResponse($this->failMassageReturnAction('insert fail'));
            }
        } else {
            try {
                $conn->insert('msk_member_relation',
                    array(
                        'from_member_id' => $memberId,
                        'to_member_id' => $toMemberId,
                        'relation_type' => $attention,
                        'add_time' => $time
                    ));
                return new JsonResponse($this->successMassageReturnAction('success'));
            } catch (Exception $e) {
                return new JsonResponse($this->failMassageReturnAction($e->getMessage()));
            }
        }
    }

    /**
     * @Route("/apiPersonalShare", name="apiPersonalShare_")
     */
    public function apiPersonalShareAction(Request $request)
    {
        $user_id = $request->get('member_id');
        $action = $request->get('action');
        $conn = $this->conn();
        $em=$this->getDoctrine ()->getManager ();
        $page =$request->get('page',0);
        $personal = $em->createQuery("select p.my_minsu,p.my_sense,p.my_yigong,p.my_daoyou,p.my_lvyoutuan from AcmeMinsuBundle:MemberInfo p WHERE p.member_id = :memberId")
            ->setParameters(array('memberId' => $user_id))->execute();
        $all=$personal['my_minsu']+$personal['my_sense']+$personal['yigong']+$personal['daoyou']+$personal['my_lvyoutuan'];
        $avatarPath=$this->getParameter('app_qiniu_imgurl');
        switch ($action){
            case 10:
                $data['count']=$personal[0]['my_minsu'];
                $orderlist='p.state=1 and p.member_id = '.$user_id;
                $data['list']=self::gethomestayList($orderlist,$page,$avatarPath);                
                break;
            case 20:
                $data['count']=$personal[0]['my_sense'];
                $orderlist = 'c.is_default = 1 and p.state = 0 and p.member_id = '.$user_id;
                $data['list']=self::getjingdianList($orderlist,$page,$avatarPath);
                break;
            case 30:
                $data['count']=$all;
                $where='p.member_id='.$user_id;
                $data['list']=self::mynotelist($where,$page);
                break;
            default:
                $this->Send(300,'非法操作');
        }
        if (empty($data['list'])){
            $this->Send(202,'暂无数据',array('count'=>0,'list'=>array()));
        }
        $this->Send(200,'success',$data);
        
    }

    public function AddUrlfuc($groupList){

        $UrlPath=$this->container->getParameter('app_group_path');

        for($i=0;$i<count($groupList);$i++){

            $groupList[$i]['img_url'] = $UrlPath .$groupList[$i]['img'];
        }
        return $groupList;
    }

    /**
     * @Route("/apiMyAttention", name="apiMyAttention_")
     */
    public function apiMyAttentionAction(Request $request)
    {
        $token = $request->get('token');
        self::validationToken($token);
        $action = $request->get('action');
        $offset = $request->get('page',0);         
        $conn = $this->conn();
        if ($action == '10') {
            $Qry = $conn->createQueryBuilder()
                ->select(
                    "m.id,p.nickname, c.avatar, m.to_member_id"
                )
                ->from('msk_member_relation', 'm')
               ->leftJoin('m', 'msk_member', 'c', 'm.to_member_id = c.id')
                ->leftJoin('m', 'msk_member_info', 'p', 'm.to_member_id = p.member_id')
                ->where('m.from_member_id = :memberId')
                ->andWhere('m.relation_type = :type')
                ->andWhere('c.member_state = :state')
                ->orderBy('m.add_time','desc')
                ->setParameters(
                    array(
                        'memberId' => $this->user_id,
                        'state' => 1,
                        'type' => 1
                    )
                )
                ->setFirstResult($offset)
                ->setMaxResults(10)
                ->execute();
            $qryRes = $Qry->fetchAll();
            if (!empty($qryRes)) {
                $count=$conn->createQueryBuilder()
                ->select( "count(m.id) as total")
                ->from('msk_member_relation', 'm')
                ->leftJoin('m', 'msk_member', 'c', 'm.to_member_id = c.id')
                ->where('m.from_member_id ='.$this->user_id,'m.relation_type = 1','c.member_state = 1')
                ->execute()->fetch();
                $data['count']=$count['total'];
                $qryResLenth = count($qryRes);
                for ($i = 0; $i < $qryResLenth; $i++) {
					$qryRes[$i]['both']=1;
                    $qryRes[$i]['avatar'] = $this->getParameter('app_qiniu_imgurl'). $qryRes[$i]['avatar'];
                    $qryRes[$i]['member_id'] = $qryRes[$i]['to_member_id'];
                    unset($qryRes[$i]['to_member_id']);
                }
                $data['list']=$qryRes;
                $this->Send(200,'success',$data);
            }else{
                $this->Send(202,'暂无数据');
            }  
        } elseif ($action == '20') {
            $Qry = $conn->createQueryBuilder()
                ->select(
                    "m.id,p.nickname, c.avatar, m.from_member_id,m.to_member_id"
                )
                ->from('msk_member_relation', 'm')
                ->leftJoin('m', 'msk_member', 'c', 'm.from_member_id = c.id')
                ->leftJoin('m', 'msk_member_info', 'p', 'm.from_member_id = p.member_id')
                ->where('m.to_member_id = :memberId')
                ->andWhere('c.member_state = :state')
                ->setParameters(
                    array(
                        'memberId' =>$this->user_id,
                        'state' => 1
                    )
                )
                ->setFirstResult($offset)
                ->setMaxResults(10)
                ->execute();
            $qryRes = $Qry->fetchAll();
            if (!empty($qryRes)) {
                $count=$conn->createQueryBuilder()
                ->select('count(m.id) as total')
                ->from('msk_member_relation','m')
                ->leftJoin('m', 'msk_member', 'c', 'm.from_member_id = c.id')
                ->where('m.to_member_id ='.$this->user_id,'m.relation_type =1','c.member_state =1')->execute()->fetch();
                $data['count']=$count['total'];
                $qryResLenth = count($qryRes);
                for ($i = 0; $i < $qryResLenth; $i++) {
                    if (self::bothatton($qryRes[$i]['from_member_id'],$this->user_id)){
						$qryRes[$i]['both']=1;
					}else {
						$qryRes[$i]['both']=0;
					} 
                    $qryRes[$i]['avatar'] = $this->getParameter('app_qiniu_imgurl'). $qryRes[$i]['avatar'];
                    $qryRes[$i]['member_id'] = $qryRes[$i]['from_member_id'];
                    unset($qryRes[$i]['to_member_id']);
                }
                $data['list']=$qryRes;
                $this->Send(200,'success',$data);
            }else{
                $this->Send(202,'暂无数据');
            }
        } else {
            $this->Send(300,'not found action');
        }
    }

	private function bothatton($id,$user_id){
		$data=$this->conn()->createQueryBuilder()
                ->select("m.*")
                ->from('msk_member_relation', 'm')
				->where('m.to_member_id='.$id,'m.from_member_id='.$user_id,'m.relation_type =1')->execute()->fetch();
	   return $data;
	}

    /**
     * @Route("/apiChangePersonalMsg", name="apiChangePersonalMsg_")
     */
    public function apiChangePersonalMsgAction(Request $request)
    {
        $token = $request->get('token');
        $user_id=$this->validationToken($token);
        $avatar = $request->get('avatar',0);
        $nickname = $request->get('nickname',0);
        $introduce = $request->get('introduce',0);
        if(is_array($user_id)) return new JsonResponse($user_id);
        $em = $this->getDoctrine()->getManager();
        if ($avatar) 
        {    
            $oldImgQry = $em->createQuery("select p.avatar from AcmeMinsuBundle:Member p WHERE p.id = :memberId AND p.member_state = :state");
            $oldImgQry->setParameters(array('memberId' => $user_id,'state' => 1));
            $oldImgQryRes = $oldImgQry->execute();
            if (!empty($oldImgQryRes)) 
            {
                $updateAvatar = $em->getRepository('AcmeMinsuBundle:Member')->find($user_id);
                $updateAvatar->setAvatar("$avatar");
                if ($em->flush()) return new JsonResponse(array('code'=>300,'msg'=>'修改失败','result'=>''));
            }
        }elseif(!empty($nickname) || !empty($introduce)) {
            $memberInfoRpt = $this->getDoctrine()->getRepository('AcmeMinsuBundle:MemberInfo');
            $memberInfoQry = $memberInfoRpt->findOneBy(array('member_id' => $user_id));    
            if ($nickname) $memberInfoQry->setNickname($nickname);
            elseif ($introduce)  $memberInfoQry->setIntroduce($introduce);
            if ($em->flush()) return new JsonResponse(array('code'=>300,'msg'=>'修改失败','result'=>''));
        } 
         /* $messageQry = $this->conn()->createQueryBuilder()
            ->select('p.avatar, c.nickname')
            ->from('msk_member', 'p')
            ->leftJoin('p', 'msk_member_info', 'c', 'p.id = c.member_id')
            ->where('p.id = :memberId')
            ->setParameters(array('memberId' => $user_id))
            ->execute();
        $messageQryRes = $messageQry->fetchAll();
        $avatarFullPath = $this->getParameter('app_qiniu_imgurl'). $messageQryRes[0]['avatar'];
        $nickname = $messageQryRes[0]['nickname'];
        $tokenRes = $this->curl('/user/refresh', array('userId' => $user_id, 'name' => $nickname, 'portraitUri' => $avatarFullPath));
        $tokenResArr = json_decode($tokenRes, true);  */
        return new JsonResponse(array('code'=>200,'msg'=>'修改成功','result'=>''));
    }

    /**
     * @Route("/apiPersonalPageMsg", name="apiPersonalPageMsg_")
     */
    public function apiPersonalPageMsgAction(Request $request)
    {
        $memberId = $request->get('member_id');
        $avatarPath = $this->getParameter('app_qiniu_imgurl');
        $em = $this->getDoctrine()->getManager();
        $background_imag =$this->getParameter('app_background_imgurl');//默认背景图
        if ($memberId) {
            if (!$visitorId = $request->get('visitor_id')) {
                $msgQry = $this->findPersonalMsgSql($memberId);
                $msgQryRes = $msgQry->fetchAll();
                $data = array();
                if (!empty($msgQryRes[0]['avatar'])) {
                    $data['avatar'] = $avatarPath .$msgQryRes[0]['avatar'];
                    $data['nickname'] = $msgQryRes[0]['nickname'];
                    $data['introduce'] = $msgQryRes[0]['introduce'];
                    $data['attention'] = $msgQryRes[0]['count(m.from_member_id)'];
                    $data['member_points'] = $msgQryRes[0]['member_points'] ?: "0";
                    $data['available_balance'] =$msgQryRes[0]['available_balance'];
                    if(!empty($msgQryRes[0]['background_image'])){//用户改变背景图时,替换
                        $data['background_image'] = $avatarPath .$msgQryRes[0]['background_image'];
                    }else{
                        $data['background_image'] =$background_imag;
                    }
                    return new JsonResponse($data);
                } else {
                    return new JsonResponse($this->failMassageReturnAction('not found msg!'));
                }
            } elseif ($visitorId = $request->get('visitor_id')) {
                $msgQry = $this->findPersonalMsgSql($visitorId);
                $msgQryRes = $msgQry->fetchAll();
                $data = array();

                //查询是否关注
                $isHaveRelQry = $em->createQuery(
                    "select p.relation_type from AcmeMinsuBundle:MemberRelation p 
                    WHERE p.from_member_id = :memberId AND p.to_member_id = :visitorId"
                )
                    ->setParameters(
                        array(
                            'memberId' => $memberId,
                            'visitorId' => $visitorId
                        )
                    );
                $isHaveRelQryRes = $isHaveRelQry->execute();
                if (!empty($isHaveRelQryRes)) {
                    if ($isHaveRelQryRes[0]['relation_type']) {
                        $data['relation_type'] = '1';
                    } else {
                        $data['relation_type'] = '0';
                    }
                } else {
                    $data['relation_type'] = '0';
                }

                if (!empty($msgQryRes[0]['avatar'])) {
                    $data['avatar'] = $avatarPath . $msgQryRes[0]['avatar'];
                    $data['nickname'] = $msgQryRes[0]['nickname'];
                    $data['introduce'] = $msgQryRes[0]['introduce'];
                    $data['attention'] = $msgQryRes[0]['count(m.from_member_id)'];
                    $data['member_points'] = $msgQryRes[0]['member_points'] ?: "0";
                    $data['available_balance'] =$msgQryRes[0]['available_balance'];
                    if(!empty($msgQryRes[0]['background_image'])){//用户改变背景图时,替换
                        $data['background_image'] = $avatarPath .$msgQryRes[0]['background_image'];
                    }else{
                        $data['background_image'] =$background_imag;
                    }
                } else {
                    return new JsonResponse($this->failMassageReturnAction('not found msg!'));
                }
                return new JsonResponse($data);
            } else {
                return new JsonResponse($this->failMassageReturnAction('not receive id'));
            }
        } else {
            $visitorId = $request->get('visitor_id');
            if ($visitorId) {
                $msgQry = $this->findPersonalMsgSql($visitorId);
                $msgQryRes = $msgQry->fetchAll();
                if (!empty($msgQryRes[0]['avatar'])) {
                    $data['avatar'] = $avatarPath .  $msgQryRes[0]['avatar'];
                    $data['nickname'] = $msgQryRes[0]['nickname'];
                    $data['introduce'] = $msgQryRes[0]['introduce'];
                    $data['attention'] = $msgQryRes[0]['count(m.from_member_id)'];
                    $data['member_points'] = $msgQryRes[0]['member_points'] ?: "0";
                    $data['relation_type'] = '0';
                    $data['available_balance'] =$msgQryRes[0]['available_balance'];
                    if(!empty($msgQryRes[0]['background_image'])){//用户改变背景图时,替换
                        $data['background_image'] = $avatarPath .$msgQryRes[0]['background_image'];
                    }
                } else {
                    return new JsonResponse($this->failMassageReturnAction('not found msg!'));
                }
//                var_dump($data);
                return new JsonResponse($data);
            } else {
                return new JsonResponse($this->failMassageReturnAction('not receive id!'));
            }
        }
    }

private  function findPersonalMsgSql($id)
    {
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();

        $msgQry = $conn->createQueryBuilder()
            ->select(
                "p.available_balance,p.avatar, p.member_points, c.nickname, c.introduce, count(m.from_member_id),c.background_image"
            )
            ->from('msk_member', 'p')
            ->leftJoin('p', 'msk_member_info', 'c', 'p.id = c.member_id')
            ->leftJoin('p', 'msk_member_relation', 'm', 'p.id = m.to_member_id and m.relation_type=1')
            ->where('p.id = :memberId')
            ->setParameter('memberId', $id)
            ->execute();
        return $msgQry;
    }

    /**
     * @Route("/apiDeleteTravelNote", name="apiDeleteTravelNote_")
     */
    public function apiDeleteTravelNoteAction(Request $request)
    {
        $travelNoteId = $request->get('id');
        if (!$travelNoteId) {
            return new JsonResponse($this->failMassageReturnAction('not found id'));
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AcmeMinsuBundle:TravelNote');
        $travelNote = $repo->find(
            array('id' => $travelNoteId)
        );
        if (!is_null($travelNote)) {
            $travelNote->setState('2');
            $em->flush();
        }
        return new JsonResponse($this->successMassageReturnAction('success'));
    }

    /**
     * @Route("/apiDeleteHomestay", name="apiDeleteHomestay_")
     */
    public function apiDeleteHomestayAction(Request $request)
    {
        $homestayId = $request->get('id');
        if (!$homestayId) {
            return new JsonResponse($this->failMassageReturnAction('not found id'));
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AcmeMinsuBundle:Homestay');
        $homestay = $repo->find($homestayId);
        if (!empty($homestay)) {
            $homestay->setState('3');
            $em->flush();
        }
        return new JsonResponse($this->successMassageReturnAction('success'));
        /*$imgRepo = $em->getRepository('AcmeMinsuBundle:Images');
        $homestayImage = $imgRepo->findBy(array(
                'homestay_room_id' => $homestayId
            ));
        if (!empty($homestayImage)) {
            foreach ($homestayImage as $value) {
                $em->remove($value);
            }
        }
        $em->flush();*/
    }

    /**
     * @Route("/apiIm", name="apiIm_")
     */
    public function apiImAction(Request $request)
    {
        $memberId = $request->get('member_id');
        if (!$memberId) {
            return new JsonResponse($this->failMassageReturnAction('not receive member id'));
        }
        $avatarPath = $this->getParameter('avatar_path');
        $imageHost = $this->getparameter('image_host');

        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $messageQry = $conn->createQueryBuilder()
            ->select(
                'p.avatar, c.nickname'
            )
            ->from('msk_member', 'p')
            ->leftJoin('p', 'msk_member_info', 'c', 'p.id = c.member_id')
            ->where('p.id = :memberId')
            ->setParameters(array(
                'memberId' => $memberId
            ))
            ->execute();
        $messageQryRes = $messageQry->fetchAll();
        $avatarFullPath = $this->getParameter('app_qiniu_imgurl'). $messageQryRes[0]['avatar'];
        $nickname = $messageQryRes[0]['nickname'];


        $tokenQry = $em->createQuery(
            "select p.token from AcmeMinsuBundle:ImToken p WHERE p.member_id = :memberId"
        )
            ->setParameter('memberId', $memberId)
            ->execute();
       //var_dump($tokenQry);
        if (!empty($tokenQry)) {
            $massage = $this->successMassageReturnAction('success');
            $massage['token'] = $tokenQry[0]['token'];
            $massage['id'] = $memberId;
            $massage['avatar'] = $avatarFullPath;
            $massage['nickname'] = $nickname;
            return new JsonResponse($massage);
        } else {
            $tokenRes = $this->curl('/user/getToken', array('userId' => $memberId, 'name' => $nickname, 'portraitUri' => $avatarFullPath));
            $tokenResArr = json_decode($tokenRes, true);
         // var_dump($tokenResArr);die();
            if (!empty($tokenResArr)) {
                $imToken = new ImToken();
                $imToken->setMemberId($memberId);
                $imToken->setToken($tokenResArr['token']);
                $em->persist($imToken);
                $em->flush();
//        var_dump($tokenResArr);
                $massage = $this->successMassageReturnAction('success');
                $massage['token'] = $tokenResArr['token'];
                $massage['id'] = $memberId;
                $massage['avatar'] = $avatarFullPath;
                $massage['nickname'] = $nickname;
                return new JsonResponse($massage);
            } else {
                $massage = $this->failMassageReturnAction('fail');
                $massage['token'] = 'null';
                $massage['id'] = $memberId;
                $massage['avatar'] = "null";
                $massage['nickname'] = 'null';
                return new JsonResponse($massage);
            }

        }
    }

   

    /**
     * @Route("/apiAllPersonalData", name="apiAllPersonalData_")
     */
    public function apiAllPersonalDataAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $dataQry = $conn->createQueryBuilder()
            ->select(
                'p.id, p.avatar, c.nickname'
            )
            ->from('msk_member', 'p')
            ->leftJoin('p', 'msk_member_info', 'c', 'p.id = c.member_id')
            ->orderBy('p.id', 'desc')
            ->execute();
        $dataQryRes = $dataQry->fetchAll();
        if (!empty($dataQryRes)) {
            for ($i = 0, $len = count($dataQryRes); $i < $len; $i++) {
                $dataQryRes[$i]['avatar'] =$this->getParameter('app_qiniu_imgurl'). $dataQryRes[$i]['avatar'];
            }
        }
        /*$dataQryRes = array(array());*/
        return new JsonResponse($dataQryRes);
    }

    /**
     * @Route("apiIsManager", name="apiIsManager_")
     */
    public function apiIsManager(Request $request)
    {
        $memberId = $request->get('member_id');
        if (!$memberId) {
            return new JsonResponse($this->failMassageReturnAction('paras error'));
        }

        $em = $this->getDoctrine()->getManager();
        $managerQry = $em->getConnection()->createQueryBuilder()
            ->select(
                'p.id'
            )
            ->from('msk_app_super_account', 'p')
            ->where('p.member_id = :memberId')
            ->andWhere('p.state = :state')
            ->setParameters(
                array(
                    'memberId' => $memberId,
                    'state' => 1
                )
            )
            ->execute();
        try {
            $managerQryRes = $managerQry->fetchAll();
        } catch (Exception $e) {
            return new JsonResponse($this->failMassageReturnAction('sql error'));
        }

        if (!empty($managerQryRes)) {
            return new JsonResponse($this->successMassageReturnAction('is manager'));
        } else {
            return new JsonResponse($this->failMassageReturnAction('not is manager'));
        }
    }

    /**
     * @Route("/apiShieldHomestay", name="apiShieldHomestay_")
     */
    public function apiShieldHomestayAction(Request $request)
    {
        $homestayId = $request->get('homestay_id');
        $reason = $request->get('reason');
        if (!$homestayId || !$reason) {
            return new JsonResponse($this->failMassageReturnAction('paras error'));
        }

        $em = $this->getDoctrine()->getManager();
        try {
            $em->getConnection()->createQueryBuilder()
                ->update('msk_homestay', 'p')
                ->set('p.state', ':state')
                ->set('p.refuse_reason', ':reason')
                ->where('p.id = :id')
                ->setParameters(
                    array(
                        'state' => 2,
                        'reason' => $reason,
                        'id' => $homestayId
                    )
                )
                ->execute();
        } catch (Exception $e) {
            return new JsonResponse($this->failMassageReturnAction('sql error'));
        }

        return new JsonResponse($this->successMassageReturnAction('success'));
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
     * 我的帖子
     * @Route("/apimynote", name="apimynote_")
     */
    public function apimynote(Request $request){
        $token=$request->get('token');
        $page=$request->get('page');
        self::validationToken($token);
        $where='p.member_id='.$this->user_id;
        $data=self::mynotelist($where,$page);
        if ($data) $this->Send(200,'success',$data);
        $this->Send(300,'没有数据');
        
    }
    
    
    /**
     * 我的数据统计
     * @Route("/mycountnum", name="mycountnum_")
     */
    public function mycountnum(Request $request){
        $token=$request->get('token');
        $user_id=$this->validationToken($token);
       if(is_array($user_id)) return new JsonResponse($user_id);
        $em = $this->getDoctrine()->getManager();
        
        $tiezi=$em->getConnection()->createQueryBuilder()
            ->select("count(id) as tiezi")
            ->from('msk_community_post')
            ->where('state=0','memberId='.$user_id)
            ->execute()->fetch();
        $homestay =$em->createQuery(
            "select count(p.id) as home from AcmeMinsuBundle:Homestay p
                      WHERE p.member_id =". $user_id ."AND p.state = 1"
        )->execute();
        $trave=$em->createQuery(
            "select count(p.id) as travel_note_num from AcmeMinsuBundle:TravelNote p
                    where p.member_id = ". $user_id ."AND p.state = 0"
        )->execute();
        $data=array(
            'code'=>200,
            'msg'=>'success',
            'result'=>array(
                    'homestay'=>$homestay[0]['home'],
                    'travel'=>$trave[0]['travel_note_num'],
                    'tiezi'=>$tiezi['tiezi']
                )            
        );
        return new JsonResponse($data);
    }
}



































