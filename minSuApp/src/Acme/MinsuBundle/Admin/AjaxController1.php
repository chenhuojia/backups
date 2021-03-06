<?php

namespace Acme\MinsuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\MinsuBundle\Entity\RoomServer;
use Acme\MinsuBundle\Entity\HomestayType;
use Acme\MinsuBundle\Entity\IndexRec;
//use Symfony\Component\Translation\IdentityTranslator;

class AjaxController extends Controller
{
	/**
	 * @Route("/AjaxGetAreaCity",name="AjaxGetAreaCity_")
	 */
	public function AjaxGetAreaCityAction() {
		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$parentId = $_POST['areaId'];
		$loc =$conn->createQueryBuilder ()
		->select ( "m.*" )
		->from ( 'msk_area', 'm' )
		->where("m.area_parent_id=$parentId")
		->execute ();
		$locSheng = $loc->fetchAll ();
		return new JsonResponse ($locSheng);
	}
	
	/**
	 * @Route("/uploadEImg",name="article_upload_Eimg")

	 */
	public function Euploadimg() {
		// 		$accName = $this->GetUserAccName ( $request_1 );
		$accName='adminPublic';
		$tmp_file = $_FILES ['artImage'] ['tmp_name'];
		$file_types = explode ( ".", $_FILES ['artImage'] ['name'] );
		$file_type = $file_types [count ( $file_types ) - 1];
	
		$fileName = uniqid ();
		$fileType = $file_type;
		// 		$fileSavePath = 'bundles/EventBook/user/upload/EventImg/';
		$fileSavePath=	$this->container->getParameter('userUploadImagePath');
		if (! is_dir ( $fileSavePath . $accName . '/' ))
			mkdir ( $fileSavePath . $accName );
	
			copy ( $tmp_file, $fileSavePath . $accName . '/' . $fileName . '.' . $fileType );
			$data ['fileName'] = $fileName . '.' . $fileType;
			$data ['accName'] = $accName;
			
			$accName ="adminPublic";
			$data['img_url'] =$this->container->getParameter('adminUpLoadImgUI') .$accName."/" .$fileName . '.' . $fileType;;
			
			return new JsonResponse ( $data );
	}
	
	/**
	 * @Route("/uploadgroupImg",name="uploadgroupImg_")
	
	 */
	public function uploadgroupImgAction() {
		// 		$accName = $this->GetUserAccName ( $request_1 );
		$accName='group';
		$tmp_file = $_FILES ['artImage'] ['tmp_name'];
		$file_types = explode ( ".", $_FILES ['artImage'] ['name'] );
		$file_type = $file_types [count ( $file_types ) - 1];
	
		$fileName = uniqid ();
		$fileType = $file_type;
		// 		$fileSavePath = 'bundles/EventBook/user/upload/EventImg/';
		$fileSavePath=	$this->container->getParameter('userUploadImagePath');
		if (! is_dir ( $fileSavePath . $accName . '/' ))
			mkdir ( $fileSavePath . $accName );
	
			copy ( $tmp_file, $fileSavePath . $accName . '/' . $fileName . '.' . $fileType );
			$data ['fileName'] = $fileName . '.' . $fileType;
			$data ['accName'] = $accName;
			
			
			$data['img_url'] =$this->container->getParameter('app_group_path') ."/" .$fileName . '.' . $fileType;;
				
			return new JsonResponse ( $data );
	}
	

	/**
	 * @Route("/AjaxCHomeStay",name="AjaxCHomeStay_")
	 */
	public function AjaxCHomeStay(){
		$id =$_POST['id'];
		$state =$_POST['state'];
		$rtest =$_POST['rtest'];
		
		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$conn->createQueryBuilder ()
		->update ( 'msk_homestay', 'm' )
		->set ( 'm.state', $state )
		->set ('m.refuse_reason',"'$rtest'")
		->where ( "m.id ='$id'" )
		->execute ();
		
	
		return new JsonResponse (1);
	}
	/**
	 * @Route("/Ajaxaddtravelmoney",name="Ajaxaddtravelmoney_")
	 */
	public function Ajaxaddtravelmoney(){
		$id =$_POST['tid'];
		$mon =$_POST['mon'];
		$addmon =$_POST['addmon'];
		$sum  = intval($mon)+intval($addmon);

		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$conn->createQueryBuilder ()
		->update ( 'msk_travel_note', 'm' )
		->set ( 'm.pay_money', $sum )
		->where ( "m.id ='$id'" )
		->execute ();
	
	
		return new JsonResponse ("1");
	}
	
	
	/**
	 * @Route("/ajaxchangelogostate",name="ajaxchangelogostate_")
	 */
	public function ajaxchangelogostate(){
		$id =$_POST['id'];
		$val =$_POST['val'];
	
		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$conn->createQueryBuilder ()
		->update ( 'msk_travel_note', 'm' )
		->set ( 'm.logo_state', $val )
		->where ( "m.id ='$id'" )
		->execute ();
	
	
		return new JsonResponse ("1");
	}
	
	/**
	 * @Route("/ajaxhometaysetsort",name="ajaxhometaysetsort_")
	 */
	public function ajaxhometaysetsort(){
		$id =$_POST['id'];
		$val =$_POST['val'];
	
		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$conn->createQueryBuilder ()
		->update ( 'msk_homestay', 'm' )
		->set ( 'm.sort', $val )
		->where ( "m.id ='$id'" )
		->execute ();
	
	
		return new JsonResponse ("1");
	}
	
	
	
	/**
	 * @Route("/uploadManyImg",name="upload_many_img")
	
	 */
	public function uploadManyImg() {
		// 		$accName = $this->GetUserAccName ( $request_1 );
		
		$mebid =$_GET['memberid'] ;
 		$accName='HomeStay/'.$mebid;
 		
 		
 		
		$tmp_file = $_FILES ['artImage'] ['tmp_name'];
		$file_types = explode ( ".", $_FILES ['artImage'] ['name'] );
		$file_type = $file_types [count ( $file_types ) - 1];
	
		$fileName = uniqid ();
		$fileType = $file_type;
		// 		$fileSavePath = 'bundles/EventBook/user/upload/EventImg/';
		$fileSavePath=	$this->container->getParameter('memberPath');
		if (! is_dir ( $fileSavePath . $accName . '/' ))
			mkdir ( $fileSavePath . $accName );
	
			copy ( $tmp_file, $fileSavePath . $accName . '/' . $fileName . '.' . $fileType );
			$data ['fileName'] = $fileName . '.' . $fileType;
			$data ['accName'] = $accName;
				
			$accName='HomeStay/'.$mebid;
			$data['img_url'] =$this->container->getParameter('adminUpLoadImgUI') .$accName."/" .$fileName . '.' . $fileType;;
				
			return new JsonResponse ( $data );
	}
	
	
	/**
	 * @Route("/upload_points_goods_img",name="upload_points_goods_img_")

	 */
	public function upload_points_goods_img() {
		// 		$accName = $this->GetUserAccName ( $request_1 );
		$accName='goods';
		$tmp_file = $_FILES ['artImage'] ['tmp_name'];
		$file_types = explode ( ".", $_FILES ['artImage'] ['name'] );
		$file_type = $file_types [count ( $file_types ) - 1];
	
		$fileName = uniqid ();
		$fileType = $file_type;
		// 		$fileSavePath = 'bundles/EventBook/user/upload/EventImg/';
		$fileSavePath=	$this->container->getParameter('userUploadImagePath');
		if (! is_dir ( $fileSavePath . $accName . '/' ))
			mkdir ( $fileSavePath . $accName );
	
			copy ( $tmp_file, $fileSavePath . $accName . '/' . $fileName . '.' . $fileType );
			$data ['fileName'] = $fileName . '.' . $fileType;
			$data ['accName'] = $accName;
			
			$accName ="adminPublic";
			$data['img_url'] =$this->container->getParameter('adminUpLoadImgUI') .$accName."/" .$fileName . '.' . $fileType;;
			
			return new JsonResponse ( $data );
	}
	
	
	
	/**
	 * 删除主页推荐
	 * @Route("/del_index_tm",name="del_index_tm_")
	 */
	public function del_index_tm() {
			
		$manager = $this->getDoctrine ()->getManager ();
		$biId=$_POST['rec_id'];
		$st = $manager->getRepository ( "AcmeMinsuBundle:IndexRec" );
		$st_data = $st->findOneBy( array('id'=>$biId) );
		
		$manager->beginTransaction ();
		$manager->remove ( $st_data );
		$manager->flush ();
		$manager->commit ();
	
	return  new  JsonResponse("1");
	
	}
	
	
	/**
	 * @Route("/ajax_change_index_m_j_stat",name="ajax_change_index_m_j_stat_")
	 */
	public function ajax_change_index_m_j_stat(){
		$id =$_POST['id'];
		$val =$_POST['val'];
	
		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$conn->createQueryBuilder ()
		->update ( 'msk_index_recommend', 'm' )
		->set ( 'm.sort', $val )
		->where ( "m.id ='$id'" )
		->execute ();
	
	
		return new JsonResponse ("1");
	}
}
