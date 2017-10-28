<?php

namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\MinsuBundle\Entity\Order;
use Acme\MinsuBundle\Entity\OrderPay;
use Acme\MinsuBundle\Entity\RoomCheckinDate;
use Acme\MinsuBundle\Entity\OrderEvaluation;

use Acme\MinsuBundle\Common\CommonController;
class apiOrderController extends CommonController{
	
    public function __construct(){
    
    }

    
	/**
	 * 房间下单
	 * @Route("apiOrderRoom",name="api_order_room")
	 */
	public function apiOrderRoom() {
		
		
		
		$manager = $this->getDoctrine()->getManager();
		
		$data = isset($_POST)?$_POST:'';
		
	//	echo $data['restock_text'];exit();
		
		$par =array('homestay_id','homestay_name','homestay_addr','room_id',
				   'room_name','room_bed_type',
				'room_bed','buyer_id','buyer_name','buyer_phone',
				'buyer_checkin','buyer_checkout','buyer_num','goods_amount','order_amount',
				'coupon_amount','restock_text','buyer_day');
		
		
	$parBoo  =	$this->checkKeyForArr($par, $data);
	
//	echo $parBoo;exit();
	
	if($parBoo>0 &&  $data!=""){
		
		$order_sn  = $this->_gen_order_sn();
		

		
		$order  = new Order();
		$order->setOrderSn($order_sn);
		$order->setHomestayAddr($data['homestay_addr']);
		$order->setRoomId($data['room_id']);
		$order->setRoomName($data['room_name']);
		$order->setRoomBedType($data['room_bed_type']);
		$order->setRoomBed($data['room_bed']);
		$order->setBuyerId($data['buyer_id']);
		$order->setBuyerName($data['buyer_name']);
		$order->setBuyerPhone($data['buyer_phone']);
		$order->setBuyerCheckin($data['buyer_checkin']);
		$order->setBuyerCheckout($data['buyer_checkout']);
		$order->setBuyerNum($data['buyer_num']);
		$order->setGoodsAmount($data['goods_amount']);
		$order->setOrderAmount($data['order_amount']);
		$order->setCouponAmount($data['coupon_amount']);
		$order->setAddTime(time());
		
		$order->setHomestayId($data['homestay_id']);
		$order->setHomestayName($data['homestay_name']);
		$order->setRestockText($data['restock_text']);
		$order->setBuyerDay($data['buyer_day']);
		$manager->persist($order);
		$manager->flush();
		
		$bool  =$order->getOrderId();
		
		
		
		
			
		if($bool){
		
			$message['status'] = 1;
			$message['error'] = 0;
			$message['order_sn'] = $order_sn;
			$message['msg'] ="Add Success!";
			$message['addTime'] =$order->getAddTime();
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
	 * 支付成功
	 * @Route("apiOrderPaySuc",name="api_order_room_pay_success")
	 */
	public function apiOrderPaySuc() {
		
		$manager = $this->getDoctrine()->getManager();
		
		$conn = $manager->getConnection();
		
		
		$data = isset($_POST)?$_POST:'';
		
		//	echo $data['restock_text'];exit();
		
		$par =array('order_sn','pay_sn','buyer_id','api_pay_state','payment_code','payment_time','room_id','date_has_checkin','order_amount','coupon_amount');
		
		
		$parBool  =	$this->checkKeyForArr($par, $data);
		
		
		
		if($parBool>0 &&  $data!=""){
	
		    //order_pay
			$order_pay  = new OrderPay();
			
			$order_pay->setPaySn($data['pay_sn']);
			
			$order_pay->setBuyerId($data['buyer_id']);
			
			$order_pay->setApiPayState($data['api_pay_state']);
		
			$manager->persist($order_pay);
			
			
			
			//date_quitity
			$quity = new RoomCheckinDate();
			
			$quity->setDateHasCheckin($data['date_has_checkin']);
			
			$quity->setRoomId($data['room_id']);
			
			$quity->setState(0);
			
			$quity->setAddTime(time());
			
			$manager->persist($quity);
			
			$manager->flush();
			
			$bool  =$order_pay->getPayId();
			
			$qRes = $quity->getId();
			
			//order_state
			
			
			$order_sn =$data['order_sn'];
			
			$pay_sn = $data['pay_sn'];			
			
			$pay_code =$data['payment_code'];
			
			$pay_time =$data['payment_time'];
			
			$coupon_amount =$data['coupon_amount'];
			
			$order_amount =$data['order_amount'];
			
			$order_state =50;
			
			$upb =	$conn->createQueryBuilder ()
			->update ( 'msk_order', 'm' )
			->set ( 'm.pay_sn' , $pay_sn)
			->set ( 'm.payment_code', $pay_code )
			->set ( 'm.payment_time', $pay_time )
			->set ('m.order_state',$order_state )
			->set ('m.order_amount',$order_amount)
			->set ('m.coupon_amount',$coupon_amount)
			->where ( "m.order_sn =$order_sn" )
			->execute ();
				
			if($bool && $upb && $qRes){
		
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
	 * 取消订单
	 * @Route("apiOrderCancel",name="api_order_room_pay_cancel")
	 */
	public function apiOrderCancel() {	
	
		$manager = $this->getDoctrine()->getManager();
		
		$conn = $manager->getConnection();
		
		
		$data = isset($_POST)?$_POST:'';
		
		
		$par =array('order_sn','order_state');
		
		
		$parBool  =	$this->checkKeyForArr($par, $data);
		
		
		
		if($parBool>0 &&  $data!=""){
			
			$order_sn =$data['order_sn'];
				
	
			$order_state =$data['order_state'];
				
			$upb =	$conn->createQueryBuilder ()
			->update ( 'msk_order', 'm' )
			->set ('m.order_state',$order_state )
			->where ( "m.order_sn =$order_sn" )
			->execute ();
			
			if($upb){
			
				$message['status'] = 1;
				$message['error'] = 0;
				$message['msg'] ="Cancel Success!";
			}else{
			
				$message['status'] = 0;
				$message['error'] = 1;
				$message['msg'] ="Cancel Error!";
			}
			
			
		}else{
		
			$message['status'] = 0;
			$message['error'] = 1;
			$message['message'] = 'Parameters Error!';
		
		}
		
		return new JsonResponse($message);
	}
	
	/**
	 * 订单评论
	 * @Route("apiOrderEvaluation",name="_api_order__evaluation")
	 */
	public function apiOrderEvaluation() {
		
		$manager = $this->getDoctrine()->getManager();
		
		$conn = $manager->getConnection();
		
		
		$data = isset($_POST)?$_POST:'';
		
		//	echo $data['restock_text'];exit();
		
		$par =array('order_sn','guest_id','grade','evaluation');
		
		
		$parBool  =	$this->checkKeyForArr($par, $data);
		
		
		
		if($parBool>0 &&  $data!=""){
			
			
			$order_sn =$data['order_sn'];
			
			$eval = new OrderEvaluation();
				
			$eval->setOrderSn($data['order_sn']);
				
			$eval->setGuestId($data['guest_id']);
			
			$eval->setGrade($data['grade']);
			
			$eval->setEvaluation($data['evaluation']);
			
			$eval->setAddTime(time());
				
			$manager->persist($eval);
				
			$manager->flush();
			
			
			$eid  =$eval ->getId();
			
			
			if($eid){
				
				
				$upb =	$conn->createQueryBuilder ()
				->update ( 'msk_order', 'm' )
				->set ('m.evaluation_state',1 )
				
				->set('m.finnshed_time',time())
				->where ( "m.order_sn =$order_sn" )
				->execute ();
				
				
				if($upb){
						
					$message['status'] = 1;
					$message['error'] = 0;
					$message['msg'] ="Success";
				}else{
						
					$message['status'] = 0;
					$message['error'] = 1;
					$message['msg'] ="Error";
				}
					
			}else{
				
				$message['status'] = 0;
				$message['error'] = 1;
				$message['msg'] ="Evaluation Error!";
				
			}
			
		
			
		}else{
		
			$message['status'] = 0;
			$message['error'] = 1;
			$message['message'] = 'Parameters Error!';
		
		}
		
		return new JsonResponse($message);
		
	}

	
	/**
	 * 订单列表
	 * @Route("apiMemberOrderList",name="api_order_member_order_list_")
	 */
	public function apiMemberOrderList() {	
		
		
		$sec =$this->container->getParameter('sysSec');
		
		$conn =$this->getDoctrine()->getManager()->getConnection();

		
		$data = isset($_POST)?$_POST:'';
		
		//	echo $data['restock_text'];exit();
		
		$par =array('buyer_id');
		
		
		$parBool  =	$this->checkKeyForArr($par, $data);
		
		
		
		if($parBool>0 &&  $data!=""){
			
			
			
			$Path =$this->container->getParameter('app_image_path');
			
			
			$proName_room ="Room";

			$query = $conn->createQueryBuilder()
			
			->select('m.order_sn,m.homestay_id,m.homestay_addr,m.buyer_name,m.buyer_phone,m.room_id,m.homestay_name,m.room_bed_type,m.room_bed,m.buyer_checkin,m.buyer_checkout,m.room_name,
					m.buyer_day,m.finnshed_time,m.goods_amount,m.coupon_amount,m.order_amount,m.evaluation_state,m.order_state,m.add_time,m.buyer_num,m.restock_text
					')
			->from('msk_order', 'm')
			->where('m.buyer_id = :buyer_id' )
			->setParameter('buyer_id',$data['buyer_id'])
			->orderBy('m.add_time','DESC')
			->execute();
			$message = $query->fetchAll();
			
			
			
	
 			$res_10 = $this->	getOrder10($data['buyer_id']) ;
		
// 			if($res_10){
				
// 			for($i0=0;$i0<count($res_10);$i0++){
				
// 				$osn = $res_10[$i0]['order_sn'];
				
// 				$format  = strtotime(date("Y-m-d H:i:s",time()))  - strtotime(date("Y-m-d H:i:s",$message[$i0]['add_time']));
				
					
// 				if($format >$sec ||  $format ==$sec){
						
// 					$this->updateOrder($osn);
						
// 				}
				
// 			}
// 				}
 			if($res_10){
 				
 			for($i0=0;$i0<count($res_10);$i0++){
 					
 			$osn = $res_10[$i0]['order_sn'];
 			
			$format  =strtotime(date("Y-m-d H:i:s",$res_10[$i0]['add_time']+$sec)) - strtotime(date("Y-m-d H:i:s",time())) ;
			
			if($format <0  || $format ==0){
				$this->updateOrder($osn);
			
			}
 				}
 			}
			
			for ($i =0 ;$i <count($message);$i++){
				
				$rid  =	$message[$i]['room_id'];
					
				$hid  =$message[$i]['homestay_id'];

			
			$query_room = $conn->createQueryBuilder()
				
						->select('m.*')
								->from('msk_images', 'm')
								->where('m.homestay_room_id = :rid' )
								->andWhere('m.img_type=1')
								->andWhere('m.is_default=1')
								->setParameter('rid',$rid)
								->execute();
			$img_data = $query_room->fetchAll();
			
			if($img_data){

				$_imgValue  = current($img_data);
				
			
					
				$message[$i]['room_img_url'] =  $Path.$proName_room.'/'.$_imgValue['member_id'].'/'.$_imgValue['goods_image']  ;
				
			//	$message[$i]['repast'] =$_imgValue['repast'];
			} else {
				
				$message[$i]['room_img_url'] =null;
				
				//$message[$i]['repast'] = "0";
			}
			
			
			
			//房东头像
			
			$query_avater  = $conn->createQueryBuilder()
				
						->select('m.*,mm.avatar,mi.nickname')
								->from('msk_homestay', 'm')
								->leftJoin('m','msk_member','mm','m.member_id=mm.id')
								->leftJoin('m','msk_member_info','mi','m.member_id=mi.member_id')
								->where('m.id = :hid' )
								->andWhere('m.is_manage=1')
								->setParameter('hid',$hid)
								->execute();
			
			
			$_m_avater_list  =$query_avater ->fetchAll();
			
			
			
			if($_m_avater_list){
				
				$avater_value = current($_m_avater_list);
				
				$av_memberid  = $avater_value['member_id'];
				
				$message[$i]['avatar_url']  = $this->container->getParameter('app_avater_path') .$av_memberid.'/' .$avater_value['avatar'];
				
				$message[$i]['homestay_phone']  = $avater_value['homestay_phone'];
				
				$message[$i]['owner_id']  =$avater_value['member_id'];
				
				$message[$i]['owner_nickname']  =$avater_value['nickname'];
				
			}else{
				
				$message[$i]['avatar_url'] =null;
				
				$message[$i]['homestay_phone']  = null;
				
				$message[$i]['owner_id']  =null;
				
				$message[$i]['owner_nickname']=null;
			}
			
			//	$avatarPath=$this->container->getParameter('app_avater_path') .$groupList[$i]['memberId'].'/';
			
			
				}
				
		
				
				
		}
		else{
		
			$message['status'] = 0;
			$message['error'] = 1;
			$message['message'] = 'Parameters Error!';
		
		}
		
		return new JsonResponse($message);
		
	}
	
	
	
	/**
	 * 房东订单列表
	 * @Route("apiOwnerOrderList",name="api_order_owner_order_list_")
	 */
	public function apiOwnerOrderList() {
		

		$conn =$this->getDoctrine()->getManager()->getConnection();
		
		$sec =$this->container->getParameter('sysSec');
		
		$data = isset($_POST)?$_POST:'';
		
		//	echo $data['restock_text'];exit();
		
		$par =array('owner_id');
		
		
		$parBool  =	$this->checkKeyForArr($par, $data);
		
		
		
		if($parBool>0 &&  $data!=""){
			
			

			$Path =$this->container->getParameter('app_image_path');
				
				
			$proName_room ="Room";
			
			$query = $conn->createQueryBuilder()
				
			->select('m.order_sn,m.room_id,m.homestay_id,m.homestay_name,m.room_bed_type,m.room_bed,m.buyer_checkin,m.buyer_checkout,m.buyer_id,m.room_name,
					m.buyer_name,m.buyer_phone,
					m.buyer_day,m.finnshed_time,m.goods_amount,m.coupon_amount,m.order_amount,m.evaluation_state,m.order_state,m.add_time,m.buyer_num,m.restock_text
					')
					->from('msk_homestay', 'h')
								->Join('h','msk_order', 'm','h.id=m.homestay_id')
								->where('h.member_id = :member_id' )
								->andWhere('h.is_manage=1')
								->setParameter('member_id',$data['owner_id'])
								->orderBy('m.add_time','DESC')
								->execute();
			$message = $query->fetchAll();
			

		
			for ($i =0 ;$i <count($message);$i++){
			
				$rid  =	$message[$i]['room_id'];
				
				$bid =$message[$i]['buyer_id'];
				
					
				$query_room = $conn->createQueryBuilder()
			
				->select('m.*')
				->from('msk_images', 'm')
				->where('m.homestay_room_id = :rid' )
				->andWhere('m.img_type=1')
				->andWhere('m.is_default=1')
				->setParameter('rid',$rid)
				->execute();
				$img_data = $query_room->fetchAll();
					
				if($img_data){
			
					$_imgValue  = current($img_data);
						
					$message[$i]['room_img_url'] =  $Path.$proName_room.'/'.$data['owner_id'].'/'.$_imgValue['goods_image']  ;
			
					//	$message[$i]['repast'] =$_imgValue['repast'];
				} else {
			
					$message[$i]['room_img_url'] =null;
			
					//$message[$i]['repast'] = "0";
				}
				
				
				//购买者头像
				$query_avater  = $conn->createQueryBuilder()
				
				->select('m.*')
				->from('msk_member', 'm')
				->where('m.id = :bid' )
				->setParameter('bid',$bid)
				->execute();
					
					
				$_m_avater_list  =$query_avater ->fetchAll();
					
					
					
				if($_m_avater_list){
				
					$avater_value = current($_m_avater_list);
				
					$av_memberid  = $avater_value['id'];
				
					$message[$i]['avatar_url']  = $this->container->getParameter('app_avater_path') .$av_memberid.'/' .$avater_value['avatar'];
				
				}else{
				
					$message[$i]['avatar_url'] =null;
				}
			}
				
			
		}else{
		
			$message['status'] = 0;
			$message['error'] = 1;
			$message['message'] = 'Parameters Error!';
		
		}
		
		return new JsonResponse($message);
	}
	
	
	/**
	 * 返回系统时间
	 * @Route("apiGetSysTime",name="apiGetSysTime_")
	 */
	public function apiGetSysTime() {
		
		$sec =$this->container->getParameter('sysSec');

		$conn =$this->getDoctrine()->getManager()->getConnection();
		
		
		
		$data = isset($_POST)?$_POST:'';
		
		//	echo $data['restock_text'];exit();
		
		$par =array('order_sn');
		
		
		$parBool  =	$this->checkKeyForArr($par, $data);
		
		
		
		if($parBool>0 &&  $data!=""){
			
			$query_  = $conn->createQueryBuilder()
			
			->select('m.add_time')
			->from('msk_order', 'm')
			->where('m.order_sn = :id' )
			->andWhere('m.order_state=10')
			->setParameter('id',$data['order_sn'])
			->execute();
			
			$list  =current($query_ ->fetchAll());
			
			
			
			if($list){
			//$format  = strtotime(date("Y-m-d h:i:s",time()))  - strtotime(date("Y-m-d h:i:s",$list['add_time']+$sec));
	
				$format  =strtotime(date("Y-m-d H:i:s",$list['add_time']+$sec)) - strtotime(date("Y-m-d H:i:s",time())) ;
				
			//echo  $format;exit();
			
			if($format >0){
				$message['status'] = 1;
				
				$message['error'] = 0;
				
				$message['sec'] =$format;
				
				$message['text'] = $this->time2string($format);
				
		
				
			}else{
					$this->updateOrder($data['order_sn']);
				
						$message['status'] = 0;
						$message['error'] = 1;
						$message['message'] = 'out of time';
				
			}
				
			}else{
				
				$message['status'] = 0;
				$message['error'] = 1;
				$message['message'] = 'no find';
				
			}
			
		}else{
		
			$message['status'] = 0;
			$message['error'] = 1;
			$message['message'] = 'Parameters Error!';
		
		}
		
		return new JsonResponse($message);
	}
	
	
	
	/**
	 * 民宿评论列表
	 * @Route("apiGetHomeStayEvalutaion",name="api_Get_HomeStay_Evalutaion_")
	 */
	public function apiGetHomeStayEvalutaion() {
		
		$conn =$this->getDoctrine()->getManager()->getConnection();
		
		
		
		$data = isset($_POST)?$_POST:'';
		
		//	echo $data['restock_text'];exit();
		
		$par =array('homestay_id');
		
		
		$parBool  =	$this->checkKeyForArr($par, $data);
		
		
		
		if($parBool>0 &&  $data!=""){
			
			
			$query_  = $conn->createQueryBuilder()
			->select(
					"oe.guest_id,oe.grade,oe.evaluation,oe.add_time,mm.avatar,mi.nickname"
			)
			->from("msk_order", 'c')
			->Join('c','msk_order_evaluation','oe','c.order_sn=oe.order_sn')
			->Join('oe','msk_member','mm','oe.guest_id = mm.id')
			->leftJoin('mm','msk_member_info','mi','mm.id=mi.member_id')
			->where('c.homestay_id = :homesId')
			->setParameter('homesId', $data['homestay_id'])
			->orderBy('oe.add_time','DESC')
			->execute();				
			$eval_list  =$query_ ->fetchAll();
			//var_dump($eval_list);die;
			
			if($eval_list){
			for($i=0 ;$i<count($eval_list);$i++){
				$av_memberid= $eval_list[$i]['guest_id'];
				
				$av_img =$eval_list[$i]['avatar'];
				
				$eval_list[$i]['av_url']  = $this->container->getParameter('app_avater_path') .$av_memberid.'/' .$av_img;
				
			}
			
			$message= $eval_list;
			
			}else{
				
				$message['status'] = 0;
				$message['error'] = 1;
				$message['message'] = ' error';
			}
		}else{
			
	$message=	$this->msg_error();
		
		}
		
		return new JsonResponse($message);
	}
	
	private function updateOrder($order_sn){
		
		$conn =$this->getDoctrine()->getManager()->getConnection();
		
		
		$upb =	$conn->createQueryBuilder ()
		->update ( 'msk_order', 'm' )
		->set ( 'm.order_state' , 0)
		
		->where ( "m.order_sn =$order_sn" )
		->execute ();
		
		
		return $upb;
	}
	
	private function getOrder10($buyer_id){
		
		$conn =$this->getDoctrine()->getManager()->getConnection();
		$query_10 = $conn->createQueryBuilder()
		
		->select('m.*')
		->from('msk_order', 'm')
		->where('m.buyer_id = :buyer_id' )
		->andWhere('m.order_state=10')
		->setParameter('buyer_id',$buyer_id)
		->execute();
		$res_10 = $query_10->fetchAll();
		
		
		return $res_10;
	}
}
