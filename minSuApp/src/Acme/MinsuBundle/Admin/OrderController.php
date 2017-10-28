<?php

namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\MinsuBundle\Entity\Order;
use Acme\MinsuBundle\Entity\OrderPay;
use Acme\MinsuBundle\Entity\MallOrder;

class OrderController extends Controller{
	
	/**
	 * 订单列表
	 * @Route("/orderList",name="_order_list")
	 * @Template("AcmeMinsuBundle:Order:orderList.html.twig")
	 */
	public function orderListAction()
	{
		//     echo date('Y-m-d H:i:s',"1461312499");
		//     	if(isset($_POST['isOwner'])){
		//     		if($_POST['isOwner'] != ""){
		//     			$stat = $_POST['isOwner'];$where ="m.is_owner = $stat";}else {$where ='m.is_owner is not null';}
		//     	}else{
		//     		$where ='m.member_state is not null';
		//     	}
		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$query = $conn->createQueryBuilder ()
		->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime" )
		->from ( 'msk_order', 'm' )
		//     	->where ( "$where" )
		->orderBy("m.add_time","DESC")
		->execute ();
		$orderList = $query->fetchAll ();
		return array('oList'=>$orderList);
	}
	
	/**
	 * 订单列表
	 * @Route("/mallorderList",name="_mall_order_list")
	 * @Template("AcmeMinsuBundle:Order:mallorderList.html.twig")
	 */
	public function mallorderListAction()
	{  
	    $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	    $condition=isset($_GET['condition'])?$_GET['condition']:0;
	    $value=isset($_GET['value'])?$_GET['value']:0;
        $stat = isset($_GET['state'])?$_GET['state']:0;
        $val = isset($_GET['val'])?$_GET['val']:0;
	    $page  = isset($_GET['page'])?$_GET['page']:0;
	    if (!empty($stat) && !empty($val)){
	        $where= ' and m.'.$stat.' = '."'$val'"; 
	    }elseif (!empty($condition) && !empty($value)){
	       if ($condition=='shop_name'){
	               $shop=$conn->createQueryBuilder()
        	           ->select('*')
        	           ->from('msk_shop', 'm' )
        	           ->where("shop_name like '%$value%'")
        	           ->execute()->fetch();
	           $where= ' and m.shop_id ='.$shop['shop_id'];
	       }else{
	           $where= ' and m.'.$condition.' like '."'%$value%'";
	       } 
	    }else{
	        $where='';
	    }
	    if (empty($page)) {
	        $page = 1;
	    }
	    $pageSize = $this->getParameter('pagesize');
/* 	    $em = $this->getDoctrine ()->getManager ();
	    
	    $totalNumRes = $em->createQuery(
	        "select count(p.order_id) from AcmeMinsuBundle:MallOrder p where p.is_show =1".$where
	    ) */
	    $totalNumRes= $conn->createQueryBuilder()
    	     ->select('count(m.order_id) as total')
    	     ->from('msk_mall_order', 'm' )
    	     ->where('m.is_show=1 '.$where)
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
	    ->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,s.shop_name,i.nickname,r.is_agree" )
	    ->from ( 'msk_mall_order', 'm' )
	    ->leftJoin('m','msk_shop','s','m.shop_id=s.shop_id')
	    ->leftJoin('m','msk_member_info','i','m.user_id=i.id')
	    ->leftJoin('m','msk_mall_order_refund','r','m.order_id=r.order_id')
	    ->where ('m.is_show=1'.$where)
	    ->orderBy("m.add_time","DESC")
	    ->setFirstResult($startPage)
	    ->setMaxResults($pageSize)
	    ->execute ();
	    $orderList = $query->fetchAll ();
	    return array(	        
	            'oList'=>$orderList,
                'page'=>self::pageHtml($totalPage,'mallorderList',$page,$prePage,$nextPage),
	        
	    );
	}
	
	
	/**
	 * 
	 * @Route("/malldeliverylist",name="_mall_delivery_list")
	 * @Template("AcmeMinsuBundle:Order:malldeliveryList.html.twig")
	 */
	public function malldeliverylistAction()
	{
	    $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	    $condition=isset($_GET['condition'])?$_GET['condition']:0;
	    $value=isset($_GET['value'])?$_GET['value']:0;
	    $page  = isset($_GET['page'])?$_GET['page']:0;
	    $type=$_GET['type'];
	    switch ($type){
	        case 1:
	            $shipping_status='m.shipping_status=1';
	            break;
	        case 2:
	            $shipping_status='m.shipping_status=2';
	            break;
	    }
	   if (!empty($condition) && !empty($value)){
	           $where= ' and m.'.$condition.' like '."'%$value%'";
	    }else{
	        $where='';
	    }
	    if (empty($page)) {
	        $page = 1;
	    }
	    $pageSize = $this->getParameter('pagesize');
	    $totalNumRes= $conn->createQueryBuilder()
    	    ->select('count(m.order_id) as total')
    	    ->from ( 'msk_mall_order', 'm' )
    	    ->where('m.is_show=1','m.pay_status=2',$shipping_status.$where)
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
    	    ->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,
    	        FROM_UNIXTIME(m.pay_time, '%Y-%m-%d %H:%i:%s') NewPayTime " )
    	    ->from ( 'msk_mall_order', 'm' )
    	    ->where ('m.is_show=1','m.pay_status=2',$shipping_status.$where)
    	    ->orderBy("m.pay_time","DESC")
    	    ->setFirstResult($startPage)
    	    ->setMaxResults($pageSize)
    	    ->execute ();
	    $orderList = $query->fetchAll ();
	    return array(
	        'oList'=>$orderList,
	        'page'=>self::pageHtml($totalPage,'malldeliverylist',$page,$prePage,$nextPage,$type),
	        'type'=>$type
	    );
	}
	
	/**
	 * 订单列表
	 * @Route("/mallreturnorderlist",name="_mallreturnorderlist")
	 * @Template("AcmeMinsuBundle:Order:mallreturnorderlist.html.twig")
	 */
	public function mallreturnorderlistAction()
	{   $where='m.is_show=1';
	        $searchtype=isset($_GET['searchType'])?$_GET['searchType']:5;
	        $searchText=isset($_GET['searchText'])?$_GET['searchText']:5;
	        $goods_return=isset($_GET['goods_return'])?$_GET['goods_return']:5;
	        $is_agree=isset($_GET['is_agree'])?$_GET['is_agree']:0;
	        if ($searchtype==999 && $goods_return==999 && $is_agree==999 ){
	            $where .='';
	        }elseif ($searchtype==999 && ($goods_return!=999 || $is_agree!=999)){
	            if ($goods_return!=999 && $goods_return!=5){
	                $where .=' and m.goods_return='.$goods_return;
	            }elseif ($is_agree!=999 && $is_agree!=5){
	                $where .=' and m.is_agree='.$is_agree;
	            }
	        }elseif ($searchtype!=999 && $searchtype!=5){
	            $where .=" and p.$searchtype like "."'%$searchText%'";
	        }
	    $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	    $page  = isset($_GET['page'])?$_GET['page']:0;	    	    
	    if (empty($page)) {
	        $page = 1;
	    }
	    $pageSize = $this->getParameter('pagesize');
	    $totalNumRes= $conn->createQueryBuilder()
    	    ->select('count(m.refund_id) as total')
    	    ->from ( 'msk_mall_order_refund', 'm' )
    	     ->leftJoin('m','msk_mall_order','p','m.order_id=p.order_id')
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
	           ->select ( "m.*,FROM_UNIXTIME(m.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime,
    	        FROM_UNIXTIME(m.replytime, '%Y-%m-%d %H:%i:%s') NewReplyTime,p.order_sn,p.shop_id,s.shop_name" )
	    	        ->from ( 'msk_mall_order_refund', 'm' )
	    	        ->leftJoin('m','msk_mall_order','p','m.order_id=p.order_id')
	    	        ->leftJoin('m','msk_shop','s','m.shop_id=s.shop_id')
	    	        ->orderBy("m.replytime","DESC")
	    	        ->where($where)
	    	        ->setFirstResult($startPage)
	    	        ->setMaxResults($pageSize)
	    	        ->execute ();
	    $orderList = $query->fetchAll ();
	    foreach ($orderList as $k=>$v){
	        $orderList[$k]['goods']=$this->ordergoods($v['order_id']);
	    }
	    //print_r($orderList);die;
	    return array(
	        'oList'=>$orderList,
	        'page'=>self::pageHtml($totalPage,'mallreturnorderlist',$page,$prePage,$nextPage),
	    );
	}
	
	/**
	 * 退货单详情
	 * @Route("/mallreturnDetail",name="_mall_return_detail")
	 * @Template("AcmeMinsuBundle:Order:mallReturnInfo.html.twig")
	 */
	public function mallreturnDetailAction()
	{
	    $order_id = $_GET['order_id'];
	    $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	    $orderList=$conn->createQueryBuilder()
        	           ->select("p.*,m.order_sn,u.nickname,FROM_UNIXTIME(p.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime")
        	           ->from('msk_mall_order_refund','p')
        	           ->leftJoin('p','msk_mall_order','m','p.order_id=m.order_id')
        	           ->leftJoin('p','msk_member_info','u','p.user_id=u.member_id')
        	           ->where('p.order_id='.$order_id)
        	           ->execute()
        	           ->fetch();
	    $goods=$this->ordergoods($order_id);
	    print_r($goods);
	    return array('order'=>$orderList);
	
	}
	
	/**
	 * 订单详情
	 * @Route("/orderDetail",name="_order_detail")
	 * @Template("AcmeMinsuBundle:Order:orderInfo.html.twig")
	 */
	public function orderDetailAction()
	{
		
		$orderSn = $_GET['order_sn'];
		
		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$query = $conn->createQueryBuilder ()
		->select ( "m.*
				,FROM_UNIXTIME(m.finnshed_time, '%Y-%m-%d %H:%i:%s') NewfinnshedTime,
				FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,FROM_UNIXTIME(m.payment_time, '%Y-%m-%d %H:%i:%s') NewPaymentTime,FROM_UNIXTIME(m.buyer_checkout, '%Y-%m-%d') Newcheckout,
				FROM_UNIXTIME(m.buyer_checkin, '%Y-%m-%d') Newcheckin,mm.account,mi.nickname" )
		->from ( 'msk_order', 'm' )
		    	
		    	->leftJoin('m','msk_member','mm','m.buyer_id=mm.id')
		   	->leftJoin('m','msk_member_info','mi','m.buyer_id=mi.member_id')
		    	
		    	->where ( "m.order_sn=$orderSn" )
		->orderBy("m.add_time","DESC")
		->execute ();
		$orderList = $query->fetchAll ();
		
		
		return array('v'=>current($orderList));
		
	}
	
	
	/**
	 * 订单详情
	 * @Route("/mallorderDetail",name="_mall_order_detail")
	 * @Template("AcmeMinsuBundle:Order:mall_order_Info.html.twig")
	 */
	public function mallorderDetailAction()
	{
	   
	    $orderSn = $_GET['order_sn'];	    
	    $orderList['info'] = $this->getmallorderinfo($orderSn);
	    $orderList['goods']=$this->ordergoods($orderList['info']['order_id']);
	    $orderList['action']=self::get_mall_order_log($orderList['info']['order_id']);
	    return array('order'=>$orderList);
	
	}
	function get_mall_order_log($order_id){
	    $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	    $data=$conn->createQueryBuilder()
	           ->select('*')
	           ->from('msk_mall_order_action')
	           ->where('order_id='.$order_id)
	           ->orderBy('log_time','desc')
	           ->execute()->fetchAll();
	    return $data;
	}
	
	/**
	 * 获取物流
	 * @Route("/shippingList",name="_shippingList")
	 */
	 function shippingList(){
	      $shipping_code=$_POST['shipping_code'];
	      $shipping_name=$_POST['shipping_name'];
	      return new JsonResponse(array(12,44));
	 }
	
	 /**
	  * 获取物流
	  * @Route("/order_refund",name="_order_refund")
	  */
	 function order_refund(){
	     $order_id=$_POST['order_id'];
	     $shop_id=$_POST['shop_id'];
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	       $query = $conn->createQueryBuilder ()
        	       ->select("m.*,p.nickname,FROM_UNIXTIME(m.addtime, '%Y-%m-%d %H:%i:%s') as time")
        	       ->from('msk_mall_order_refund','m')
        	       ->leftJoin('m','msk_member_info','p','m.user_id = p.member_id')
        	       ->where('m.order_id='.$order_id,'m.shop_id='.$shop_id)
        	       ->execute()
        	       ->fetch();
	     return new JsonResponse($query);
	 }
	 
	 /**
	  * 无效
	  * @Route("order_invalid",name="_order_invalid")
	  */
	 function order_invalid(){
	     $order_id=$_POST['order_id'];
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $query = $conn->createQueryBuilder ()   	     
    	     ->update('msk_mall_order')
    	     ->set('order_status',5)
    	     ->where('order_id='.$order_id)
    	     ->execute();
	     if ($query){
	         $c=$this->addorderlog($order_id,0,'设置订单无效成功');
	         return new JsonResponse(1);
	     }else{
	         $c=$this->addorderlog($order_id,0,'设置订单无效失败');
	         return new JsonResponse(-2);
	     } 	    
	 }
	 
	 /**
	  * 无效
	  * @Route("/order_confirm",name="_order_confirm")
	  */
	 function order_confirm(){
	     $order_id=$_POST['order_id'];
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $query = $conn->createQueryBuilder ()
    	     ->update('msk_mall_order')
    	     ->set('order_status',2)
    	     ->where('order_id='.$order_id)
    	     ->execute();
	     if ($query){
	         $c=$this->addorderlog($order_id,0,'订单确认成功');
	         return new JsonResponse(1);
	     }else{
	         $c=$this->addorderlog($order_id,0,'订单确认失败');
	         return new JsonResponse(-2);
	     }
	 }
	 
	 /**
	  * 同意退款
	  * @Route("/agreerefund",name="_agreerefund")
	  */
	 function agreerefund(){
	     $order_id=$_POST['order_id'];
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $a=$conn->createQueryBuilder()
	           ->update('msk_mall_order')
	           ->set('pay_status',4)
	           ->where('order_id='.$order_id)
	           ->execute();
	     $b=$conn->createQueryBuilder()
        	     ->update('msk_mall_order_refund')
        	     ->set('is_agree',1)
        	     ->where('order_id='.$order_id)
        	     ->execute();
	     $c=$this->addorderlog($order_id,0,'退款成功');
	     return new JsonResponse($c);
	       if ($a && $b && $c)
	           return new JsonResponse(1);
	     return new JsonResponse(12);
	 }
	 
	 /**
	  * 不同意退款
	  * @Route("/unagreerefund",name="_unagreerefund")
	  */
	 function unagreerefund(){
	     $order_id=$_POST['order_id'];
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $a=$conn->createQueryBuilder()
    	     ->update('msk_mall_order')
    	     ->set('pay_status',4)
    	     ->where('order_id='.$order_id)
    	     ->execute();
	     $b=$conn->createQueryBuilder()
    	     ->update('msk_mall_order_refund')
    	     ->set('is_agree',0)
    	     ->where('order_id='.$order_id)
    	     ->execute();
	     $c=$this->addorderlog($order_id,0,'退款失败');
	     if ($a && $b && $c)
	         return new JsonResponse(1);
	 }
	 
	 
	 /**
	  * 
	  * @Route("/mall_order_log_list",name="_mall_order_log_list")
	  * @Template("AcmeMinsuBundle:Order:mall_order_log_list.html.twig")
	  */
	 function mall_order_log_list(){
	     $where='m.is_show=1';
         $searchType=isset($_GET['searchType'])?$_GET['searchType']:0;
         $searchText=isset($_GET['searchText'])?$_GET['searchText']:0;
         if ($searchType!=999 && ($searchText != '' ||  $searchText !=null || !empty($searchText))){
            $where .=" and p.$searchType like"."'%$searchText%'"; 
	     }
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();	     
	     $page  = isset($_GET['page'])?$_GET['page']:0;	     
	     if (empty($page)) {
	         $page = 1;
	     }
	     $pageSize = $this->getParameter('pagesize');
	     $totalNumRes= $conn->createQueryBuilder()
    	     ->select('count(m.action_id) as total')
    	     ->from('msk_mall_order_action', 'm' )
    	     ->leftJoin('m','msk_mall_order','p','m.order_id=p.order_id')
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
    	     ->select ( "m.*,FROM_UNIXTIME(m.log_time, '%Y-%m-%d %H:%i:%s') NewAddTime,p.order_sn" )
    	     ->from ( 'msk_mall_order_action', 'm' )
    	     ->leftJoin('m','msk_mall_order','p','m.order_id=p.order_id')
    	     ->orderBy("m.log_time","DESC")
    	     ->where($where)
    	     ->setFirstResult($startPage)
    	     ->setMaxResults($pageSize)
    	     ->execute ();
	     $orderList=$query->fetchAll();
	     foreach ($orderList as $k=>$v){
	         if($v['action_user']==0){
	             $orderList[$k]['action_user_name']="管理员";
	         }else{
	             $user=$conn->createQueryBuilder()
    	             ->select("*")
    	             ->from('msk_member_info')
    	             ->where('member_id='.$v['action_user'])
    	             ->execute()
    	             ->fetch();
	             $orderList[$k]['action_user_name']=$user['nickname'];
	         }
	     }
	    return array(
	        'oList'=>$orderList,
	        'page'=>self::pageHtml($totalPage,'mall_order_log_list',$page,$prePage,$nextPage),
	    );
	 }
	 
	 
	 function addorderlog($order_id,$user_id,$msg){
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $order=$conn->createQueryBuilder()
    	     ->select('order_status,shipping_status,pay_status,user_id')
    	     ->from('msk_mall_order')
    	     ->where('order_id='.$order_id)
    	     ->execute()
    	     ->fetch();

	         $data=array(
	             'order_id'=>$order_id,
	             'action_user'=>$user_id,
	             'order_status'=>$order['order_status'],
	             'shipping_status'=>$order['shipping_status'],
	             'pay_status'=>$order['pay_status'],
	             'action_note'=>$msg,
	             'log_time'=>time(),
	         );
	         $a=$conn->insert('msk_mall_order_action',$data);
	     return $a;
	 }
	 
	 function httpsRequest($url, $data = null ) {
	     $curl = curl_init();
	     curl_setopt($curl, CURLOPT_URL, $url);
	     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	     if (!empty($data)){
	         curl_setopt($curl, CURLOPT_POST, 1);
	         curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	     }
	     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	     $output = curl_exec($curl);
	     curl_close($curl);
	     return $output;
	 }
	 
	 /**
	  * 删除
	  * @Route("/delmallorder",name="_delmallorder")
	  */
	 function delmallorder(){
	     $order_id=$_POST['order_id'];
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $a=$conn->createQueryBuilder()
	           ->update('msk_mall_order')
	           ->set('is_show',0)
	           ->where('order_id='.$order_id)
	           ->execute();
	     if ($a){
	         $c=$this->addorderlog($order_id,0,'删除成功');
	         return new JsonResponse(1);
	     }
	     $c=$this->addorderlog($order_id,0,'删除失败');
	     return new JsonResponse(-1);
	 }
	 
	 /**
	  * 订单详情
	  * @Route("/malldeliveryinfo",name="_mall_delivery_info")
	  * @Template("AcmeMinsuBundle:Order:malldeliveryInfo.html.twig")
	  */
	 public function malldeliveryinfoAction()
	 {     
 	     $orderSn = $_GET['order_sn'];
 	     $id=isset($_GET['id'])?$_GET['id']:0;	 
	     $orderList['info'] = $this->orderinfo($orderSn);	     
	     $orderList['goods']=$this->ordergoods($orderList['info']['order_id']);
	     if ($id){
	         $orderList['delivery']=$this->deliveryList($orderList['info']['order_id'],$id);
	     }else{
	         $orderList['delivery']=$this->deliveryList($orderList['info']['order_id']);
	     }  
	    return array('order'=>$orderList);
	 }
	 
	 
	 /**
	  * 订单详情
	  * @Route("/malldeliveryupdate",name="_mall_delivery_update")
	  * 
	  */
	 public function malldeliveryupdateAction()
	 {   
	     $data=json_decode($_POST['data'],true);
	     $order_id=$data['order_id'];
	     $invoice_no=$data['invoice_no'];
	     $note=isset($data['note'])?trim($data['note']):0;
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $result=$conn->createQueryBuilder()
	            ->update('msk_delivery_doc')
	            ->set('invoice_no',$invoice_no)
	            ->set('note',"'$note'")
	            ->where('order_id='.$order_id)
	            ->execute();
	     $c=$this->addorderlog($order_id,0,'更新物流信息成功');
	     if($result) return new JsonResponse(1);
	     return new JsonResponse(2);
	 }
	 
	 /**
	  * 订单详情
	  * @Route("/delivery",name="_delivery") 
	  */
	 
	 public function deliveryAction(){
	     $order_id=$_POST['order_id'];
	     $invoice_no=$_POST['invoice_no'];
	     $goods=$_POST['goods'];
	     $note=$_POST['note'];
	     if ($order_id){
            $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	        $order=$conn->createQueryBuilder()
        	     ->select("*")
        	     ->from('msk_mall_order')
        	     ->where('order_id='.$order_id)
        	     ->execute()->fetch();
	         $data=array(
	             'order_id'=>$order_id,
	             'order_sn'=>$order['order_sn'],
	             'user_id'=>$order['user_id'],
	             'admin_id'=>$this->get('session')->get('adminUserId'),
	             'consignee'=>$order['consignee'],
	             'mobile'=>$order['mobile'],
	             'city'=>$order['city'],
	             'address'=>$order['address'],
	             'shipping_code'=>$order['shipping_code'],
	             'shipping_name'=>$order['shipping_name'],
	             'shipping_price'=>$order['shipping_price'],
	             'invoice_no'=>$invoice_no,
	             'note'=>$note,
	             'create_time'=>time(),
	         );	         
	         $result=$conn->insert('msk_delivery_doc',$data);
	         if ($result)
	             $a=$conn->createQueryBuilder()
	                  ->update('msk_mall_order')
	                  ->set('shipping_status',2)
	                  ->where('order_id='.$order_id)
	                  ->execute();
    	        foreach ($goods as $v){
    	            $a=$conn->createQueryBuilder()
        	            ->update('msk_mall_order_goods')
        	            ->set('is_send',1)
        	            ->where('rec_id='.$v)
        	            ->execute();
    	        }
	       }
	       if ($a){
	               $c=$this->addorderlog($order_id,0,'发货成功');
	           	  return $this->redirect($this->generateUrl('_mall_delivery_info','order_sn='.$order['order_sn']));
	       }	     
	 }

	 protected function orderinfo($order_sn){
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $query = $conn->createQueryBuilder ()
	     ->select ( "m.*
				,FROM_UNIXTIME(m.shipping_time, '%Y-%m-%d %H:%i:%s') as NewshippingTime,
				FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') as NewAddTime,FROM_UNIXTIME(m.pay_time, '%Y-%m-%d %H:%i:%s') as NewPayTime,FROM_UNIXTIME(m.confirm_time, '%Y-%m-%d') NewconfirmTime,
	           s.shop_name,s.shop_address,s.shop_logo" )
	     	           ->from ( 'msk_mall_order', 'm' )
	     	           ->leftJoin('m','msk_shop','s','m.shop_id=s.shop_id')
	     	           ->where ( "m.order_sn='$order_sn'")
	     	           ->orderBy("m.add_time","DESC")
	     	           ->execute()->fetch();
	     return $query;
	 }
	 
	 protected function ordergoods($order_id){
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $query=$conn->createQueryBuilder()
    	     ->select('*')
    	     ->from('msk_mall_order_goods')
    	     ->where("order_id=".$order_id)
    	     ->execute()->fetchAll();
	     return $query;
	 }
    
	 protected function deliveryList($order_id,$id=0){
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     if ($id){
	         $where="p.id=".$id;
	     }else{
	         $where="p.order_id=".$order_id;
	     } 
	     $data=$conn->createQueryBuilder()
        	     ->select("p.id,p.consignee,p.shipping_name,p.invoice_no,FROM_UNIXTIME(p.create_time, '%Y-%m-%d %H:%i:%s') as Newcreate_time,p.note,m.admin_name")
        	     ->from('msk_delivery_doc','p')
        	     ->leftJoin('p','msk_admin','m','p.admin_id=m.id')
        	     ->where($where)
        	     ->orderBy('p.create_time','desc')
        	     ->execute()
    	         ->fetchAll();
	     return $data;
	 }
	 
	 
	 protected function getmallorderinfo($orderSn){
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $order = $conn->createQueryBuilder ()
	    ->select ( "m.*
				,FROM_UNIXTIME(m.shipping_time, '%Y-%m-%d %H:%i:%s') as NewshippingTime,
				FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') as NewAddTime,FROM_UNIXTIME(m.pay_time, '%Y-%m-%d %H:%i:%s') as NewPayTime,FROM_UNIXTIME(m.confirm_time, '%Y-%m-%d') NewconfirmTime,i.nickname,
	           s.shop_name,r.is_agree,s.shop_address,s.shop_logo" )
					->from ( 'msk_mall_order', 'm' )					 
					->leftJoin('m','msk_shop','s','m.shop_id=s.shop_id')
            	    ->leftJoin('m','msk_member_info','i','m.user_id=i.id')
            	    ->leftJoin('m','msk_mall_order_refund','r','m.order_id=r.order_id')					 
					->where ( "m.order_sn=$orderSn" )
					->orderBy("m.add_time","DESC")
					->execute ()->fetch();
	     return $order;
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
