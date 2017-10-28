<?php

namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Acme\MinsuBundle\Entity\Order;

use Acme\MinsuBundle\Common\CommonController;
class WalletController extends CommonController{
	
	/**
	 * 提现申请列表
	 * @Route("/walletList",name="_walet_list_index")
	 * @Template("AcmeMinsuBundle:Wallet:drawbalance.html.twig")
	 */
	public function walletListAction()
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
		->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,
				mm.account,mi.nickname" )
		->from ( 'msk_draw_balance', 'm' )
		->leftJoin('m','msk_member','mm','m.member_id=mm.id')
		->leftJoin('m','msk_member_info','mi','m.member_id=mi.member_id')
		//     	->where ( "$where" )
		->orderBy("m.add_time","DESC")
		->execute ();
		$List = $query->fetchAll ();
	
		return array('db'=>$List);
	}
	
	
	
	/**
	 * @Route("/changeStateDB",name="_change_state_d_b")
	 */
	public function changeStateDB(){
		
		$id =$_POST['id'];
		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$conn->createQueryBuilder ()
		->update ( 'msk_draw_balance', 'm' )
		->set ( 'm.state', 1 )
		->where ( "m.id ='$id'" )
		->execute ();
	
	
		return new JsonResponse (1);
	}
	

}
