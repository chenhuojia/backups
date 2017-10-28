<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-5-26
 * Time: 13:53
 */
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\Room;
use Acme\MinsuBundle\Entity\Images;
use Acme\MinsuBundle\Entity\Homestay;
use Acme\MinsuBundle\Entity\RoomServer;
use Acme\MinsuBundle\Entity\RoomServerRelation;

use Acme\MinsuBundle\Common\CommonController;
class apiRoomDetailController extends CommonController
{   
    public function __construct(){
    
    }
    /**
     * @Route("/apiroomdetail", name="apiroomdetail_")
     */
    public function apiroomdetailAction(Request $request)
    {
        $roomId = $request->get('room_id');
        if ($roomId) {
            $host = $this->getParameter('host');
            $imagePath = $this->getParameter('memberPath');
            $img = array();

            $em = $this->getDoctrine()->getManager();
            $roomDtlRes = $em->getConnection()->createQueryBuilder()
                ->select(
                    'p.room_hall,p.homestay_id,p.room_title,p.room_price,p.room_single_bed,p.room_double_bed,p.room_num,
                    p.cash, c.member_id'
                )
                ->from('msk_room', 'p')
                ->leftJoin('p', 'msk_homestay', 'c', 'p.homestay_id = c.id')
                ->where('p.id = :roomId')
                ->setParameter('roomId', $roomId)
                ->execute()
                ->fetchAll();

            if ($roomDtlRes) {
                $roomTitle = $roomDtlRes[0]['room_title'];
                $roomPrice = $roomDtlRes[0]['room_price'];
                $roomSingleBed = $roomDtlRes[0]['room_single_bed'];
                $roomDoubleBed = $roomDtlRes[0]['room_double_bed'];
                $roomNum = $roomDtlRes[0]['room_num'];
                $homestayId = $roomDtlRes[0]['homestay_id'];
                $roomHall = $roomDtlRes[0]['room_hall'];
                $cash = $roomDtlRes[0]['cash'];
            } else {
                $massage['status'] = '0';
                $massage['error'] = '1';
                $massage['massage'] = 'not found room!';

                return new JsonResponse($massage);
            }

            $roomImgQry = $em->createQuery(
                "select p.goods_image from AcmeMinsuBundle:Images p WHERE p.homestay_room_id = :roomId
              AND p.img_type = 1"
            );
            $roomImgQry->setParameter('roomId', $roomId);
            $roomImgRes = $roomImgQry->execute();
            if (!empty($roomImgRes)) {
                foreach ($roomImgRes as $v) {
                    array_push( $img, /*$host . $imagePath . "Room/" . $roomDtlRes[0]['member_id'] . "/" . */ $v['goods_image']);
                }
            } else {
                $img = '';
            }

            $homestayQry = $em->createQuery(
                "select p.homestay_name,p.repast from AcmeMinsuBundle:Homestay p WHERE p.id = :homestayId"
            );
            $homestayQry->setParameter('homestayId', $homestayId);
            $homestayRes = $homestayQry->execute();
            if ($homestayRes) {
                $homestayName = $homestayRes[0]['homestay_name'];
                $homestayRepast = $homestayRes[0]['repast'];
            } else {
                $homestayName = '';
                $homestayRepast = '';
            }

            $roomServerRes = $em->getConnection()->createQueryBuilder()
                ->select(
                    'p.room_server_id, c.server_name'
                )
                ->from('msk_room_server_relation', 'p')
                ->leftJoin('p', 'msk_room_server', 'c', 'p.room_server_id = c.id')
                ->where('p.room_id = :roomId')
                ->setParameter('roomId', $roomId)
                ->execute()
                ->fetchAll();
            $roomServer = array();
            if ($roomServerRes) {
                foreach ($roomServerRes as $v) {
                    $rsArr = array('id' => $v['room_server_id'], 'content' => $v['server_name']);
                    array_push($roomServer, $rsArr);
                }
            } else {
                $roomServer = '';
            }

            $data = array(
                'room_id' => $roomId,
                'img' => $img,
                'homestay_name' => $homestayName,
                'room_hall' => $roomHall,
                'room_repast' => $homestayRepast,
                'room_title' => $roomTitle,
                'room_price' => $roomPrice,
                'room_single_bed' => $roomSingleBed,
                'room_double_bed' => $roomDoubleBed,
                'room_num' => $roomNum,
                'room_server' => $roomServer,
                'cash' => $cash
            );
            //var_dump($data);
            return new JsonResponse($data);
        } else {
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not receive room id!';

            return new JsonResponse($massage);
        }
    }

    /**
     * 某房间下全部出租日期展示
     * @Route("/apiRoomCalendarList", name="apiRoomCalendarList_")
     */
    public function apiRoomCalendarList(Request $request)
    {
       
        $room_id = $request->get('room_id',0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
                                ->select('a.*')
                                ->from('msk_room_calendar', 'a')
                                ->where("a.room_id=$room_id")
                                ->andWhere("a.is_rent=1") 
                                ->orderBy('a.the_date','ASC')
                                ->execute()
                                ->fetchAll();
        if(!empty($data)){
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else {
            return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
        }
  
    }

    /**
     * 民宿管理员设置出租日期
     * @Route("apiHomestaySetCalendar",name="apiHomestaySetCalendar_")
     */
    public function apiHomestaySetCalendar(Request $request) 
    {
        $manager = $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $token = $request->get('token',0);
        $homestay_id = $request->get('homestay_id',0);
        $room_id = $request->get('room_id',0);
        $the_date = $request->get('the_date');
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        if (empty($the_date)||!preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/",$the_date))  return new JsonResponse(array('code'=>300,'msg'=>'设置的日期格式错误','result'=>''));
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("*")
            ->from('msk_homestay')
            ->where("id=$homestay_id")
            ->andWhere("member_id=$member_id")
            //->andWhere("is_manage=1")
            ->execute()->fetch();
        if (empty($a)) return new JsonResponse(array('code'=>300,'msg'=>'您没有该权限！','result'=>""));
        $b=$manager->getConnection()->createQueryBuilder ()
            ->select('m.is_rent')
            ->from('msk_room_calendar', 'm')
            ->where("m.homestay_id=".$homestay_id)
            ->andWhere("m.room_id=".$room_id)
            ->andWhere("m.the_date='$the_date'")
            ->execute()->fetch();
        if(empty($b)){
            $data=array(
                'homestay_id' => $homestay_id,
                'room_id' => $room_id,
                'the_date' => $the_date,
                'is_rent' => 1
            );
            $upb=$conn->insert('msk_room_calendar',$data);
        }elseif($b['is_rent']==1){
            $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_room_calendar', 'm' )
            ->set ('m.is_rent', 0 )
            ->where("m.homestay_id=$homestay_id")
            ->andWhere("m.room_id=$room_id")
            ->andWhere("m.the_date='$the_date'")
            ->execute ();
        }else{
            $upb = $conn->createQueryBuilder ()
            ->update ( 'msk_room_calendar', 'm' )
            ->set ('m.is_rent', 1 )
            ->where("m.homestay_id=$homestay_id")
            ->andWhere("m.room_id=$room_id")
            ->andWhere("m.the_date='$the_date'")
            ->execute ();
        }
        if($upb){
            return new JsonResponse(array('code'=>200,'msg'=>'设置成功','result'=>''));
        }else{
            return new JsonResponse(array('code'=>300,'msg'=>'设置失败','result'=>''));
        }       
    }
}
































