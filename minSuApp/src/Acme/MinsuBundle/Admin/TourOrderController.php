<?php

namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\MinsuBundle\Entity\TourOrder;


class TourOrderController extends Controller{
	
	/**
	 * 订单列表
	 * @Route("/tourorderList",name="_tour_order_list")
	 * @Template("AcmeMinsuBundle:TourOrder:tourorderList.html.twig")
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
	       if ($condition=='agency_name'){
	               $agency=$conn->createQueryBuilder()
        	           ->select('*')
        	           ->from('msk_travel_agency', 'm' )
        	           ->where("agency_name like '%$value%'")
        	           ->execute()->fetch();
	           $where= ' and m.agency_id ='.$agency['agency_id'];
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
	    $totalNumRes= $conn->createQueryBuilder()
    	     ->select('count(m.order_id) as total')
    	     ->from('msk_tour_order', 'm' )
    	     ->where('m.order_status!=3 '.$where)
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
	    ->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,s.agency_name,r.is_agree","k.state as tour_state" )
	    ->from ( 'msk_tour_order', 'm' )
	    ->leftJoin('m','msk_travel_agency','s','m.agency_id=s.agency_id')
	    ->leftJoin('m','msk_tour_calendar','k','m.calendar_id=k.calendar_id')
	    ->leftJoin('m','msk_tour_order_refund','r','m.order_sn=r.order_sn')
	    ->where ('m.order_status!=3'.$where)
	    ->orderBy("m.add_time","DESC")
	    ->setFirstResult($startPage)
	    ->setMaxResults($pageSize)
	    ->execute ();
	    $orderList = $query->fetchAll ();
	    return array(	        
	            'oList'=>$orderList,
                'page'=>self::pageHtml($totalPage,'tourorderList',$page,$prePage,$nextPage),
	        
	    );
	}

	 /**
	  * 删除
	  * @Route("/deltourorder",name="_deltourorder")
	  */
	 function deltourorder(){
	     $order_id=$_POST['order_id'];
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $a=$conn->createQueryBuilder()
	           ->update('msk_mall_order')
	           ->set('order_status',3)
	           ->where('order_id='.$order_id)
	           ->execute();
	     if ($a){
	         $c=$this->addTourorderlog($order_id,0,'删除成功');
	         return new JsonResponse(1);
	     }
	     $c=$this->addTourorderlog($order_id,0,'删除失败');
	     return new JsonResponse(-1);
	 }

	 function addTourorderlog($order_id,$user_id,$msg){
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $order=$conn->createQueryBuilder()
    	     ->select('order_status,pay_status,member_id')
    	     ->from('msk_tour_order')
    	     ->where('order_id='.$order_id)
    	     ->execute()
    	     ->fetch();

	         $data=array(
	             'order_id'=>$order_id,
	             'action_user'=>$user_id,
	             'order_status'=>$order['order_status'],
	             'pay_status'=>$order['pay_status'],
	             'action_note'=>$msg,
	             'log_time'=>time(),
	         );
	         $a=$conn->insert('msk_tour_order_action',$data);
	     return $a;
	 }

	 /**
	 * 订单详情
	 * @Route("/tourorderDetail",name="_tour_order_detail")
	 * @Template("AcmeMinsuBundle:TourOrder:tour_order_Info.html.twig")
	 */
	public function tourorderDetailAction()
	{
	   
	    $orderSn = $_GET['order_sn'];	    
	    $orderList['info'] = $this->gettourorderinfo($orderSn);
	    $orderList['goods']=$this->ordergoods($orderSn);
	    $orderList['action']=self::get_mall_order_log($orderList['info']['order_id']);
	    return array('order'=>$orderList);
	
	}

	 protected function gettourorderinfo($orderSn){
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $order = $conn->createQueryBuilder ()
	    ->select ( "m.*
				,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') as NewAddTime,FROM_UNIXTIME(m.pay_time, '%Y-%m-%d %H:%i:%s') as NewPayTime,
	           s.agency_name,r.is_agree,s.agency_address,s.agency_image","k.state as tour_state,k.chief_name,k.the_date" )
					->from ( 'msk_tour_order', 'm' )					 
					->leftJoin('m','msk_travel_agency','s','m.agency_id=s.agency_id')
					->leftJoin('m','msk_tour_calendar','k','m.calendar_id=k.calendar_id')
            	    ->leftJoin('m','msk_tour_order_refund','r','m.order_sn=r.order_sn')					 
					->where ( "m.order_sn=$orderSn" )
					->orderBy("m.add_time","DESC")
					->execute ()->fetch();
	     return $order;
	 }
	 
	function get_mall_order_log($order_id){
	    $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	    $data=$conn->createQueryBuilder()
	           ->select('*')
	           ->from('msk_tour_order_action')
	           ->where('order_id='.$order_id)
	           ->orderBy('log_time','desc')
	           ->execute()->fetchAll();
	    return $data;
	}
	protected function orderinfo($order_sn){
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $query = $conn->createQueryBuilder ()
	     ->select ( "m.*
				,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') as NewAddTime,FROM_UNIXTIME(m.pay_time, '%Y-%m-%d %H:%i:%s') as NewPayTime,FROM_UNIXTIME(m.confirm_time, '%Y-%m-%d') NewconfirmTime,
	           s.agency_name,s.agency_address,s.agency_image" )
	     	           ->from ( 'msk_tour_order', 'm' )
	     	           ->leftJoin('m','msk_travel_agency','s','m.sagency_id=s.agency_id')
	     	           ->where ( "m.order_sn='$order_sn'")
	     	           ->orderBy("m.add_time","DESC")
	     	           ->execute()->fetch();
	     return $query;
	 }
	 
	 protected function ordergoods($order_sn){
	     $conn = $this->getDoctrine ()->getManager ()->getConnection ();
	     $query=$conn->createQueryBuilder()
    	     ->select("*,FROM_UNIXTIME(enroll_time, '%Y-%m-%d %H:%i:%s') as NewEnrollTime")
    	     ->from('msk_tour_order_goods')
    	     ->where("order_sn=".$order_sn)
    	     ->execute()->fetchAll();
    	 foreach ($query as $key => $value) {
    	 	$query[$key]['avatar'] = $this->getParameter("app_qiniu_imgurl").$value['avatar'];
    	 }
	     return $query;
	 }

	 /**
	  * 确认
	  * @Route("/tour_order_confirm",name="_tour_order_confirm")
	  */
	 function tour_order_confirm(){
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
	  * 
	  * @Route("/tour_order_log_list",name="_tour_order_log_list")
	  * @Template("AcmeMinsuBundle:TourOrder:tour_order_log_list.html.twig")
	  */
	 function tour_order_log_list(){
	     $where='1=1';
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
    	     ->from('msk_tour_order_action', 'm' )
    	     ->leftJoin('m','msk_tour_order','p','m.order_id=p.order_id')
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
    	     ->from ( 'msk_tour_order_action', 'm' )
    	     ->leftJoin('m','msk_tour_order','p','m.order_id=p.order_id')
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
	        'page'=>self::pageHtml($totalPage,'tour_order_log_list',$page,$prePage,$nextPage),
	    );
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
