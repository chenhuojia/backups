<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-7-22
 * Time: 14:36
 */
namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MinsuBundle\Entity\GuideComment;

class GuideController extends Controller
{
    //导游列表
    /**
     * @Route("/guideList",name="guideList_")
     */
    public function guideListAction(Request $request)
    {
        $stat=$request->get("isOwner","");
        $searchText=$request->get("searchText","");
        if ($stat != "") $where = "m.state = $stat";
        else $where = 'm.state is not null';
        //搜索
        if($searchText!=''){
          if($_POST['searchType']  =='nic'){
            $where  = $where ." and  m.real_name LIKE '%$searchText%' "; 
          }elseif($_POST['searchType']  =='acc'){
            $where  = $where ." and  m.phone LIKE '%$searchText%' "; 
          }
        }
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $memberList = $conn->createQueryBuilder ()
                    ->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,p.avatar, p.id,p.member_state" )
                    ->from ( 'msk_guide', 'm' )
                    ->leftjoin('m', 'msk_member', 'p', 'm.member_id = p.id')
                    ->where ( "$where" )
                    ->orderBy("m.add_time","DESC")
                    ->execute ()
                    ->fetchAll ();
        foreach ($memberList as $key => $value) {
          $where1 = "o.guide_id =".$value['guide_id']; 
          $order = $conn->createQueryBuilder ()
                        ->select ( "count(o.order_id) as sums" )
                        ->from ( 'msk_guide_order', 'o' )
                        ->where ( "$where1" )
                        ->execute ()
                        ->fetch();
           $memberList[$key]['sums'] =$order['sums'];
        }
        $totalPage = ceil(count($memberList) / 10);
        $page = $request->get('page');
        if ($page == '') {
            $page = 1;
        }
        $prePage = $page - 1;
        if ($prePage < 1) {
            $prePage = 1;
        }
        $nextPage = $page + 1;
        if ($nextPage > $totalPage) {
            $nextPage = $totalPage;
        }
        $offset = ($page - 1) * 10;
        $memberList = array_slice($memberList, $offset, 10);
        for  ($i =0 ; $i<count($memberList) ;$i++){
             $avatar =  $memberList[$i]['avatar'] ;
             $avatarPath=$this->container->getParameter('app_avater_path') .$memberList[$i]['id'].'/';
             $memberList[$i]['avatar_url'] =$avatarPath .$avatar;
        }
        return $this->render('AcmeMinsuBundle:Default:guide.html.twig',
            array(
                'memberList'=>$memberList,
                'totalPage' => $totalPage,
                'prePage' => $prePage,
                'nextPage' => $nextPage,
                'page' => $page
            )
        );
    }


    /**
     * 更改导游的状态
     * @Route("/guideStateChange", name="guideStateChange_")
     */
    public function guideStateChangeAction(Request $request)
    {
        $id = $request->get('guide_id');
        $state = $request->get('state');
        $em = $this->getDoctrine()->getManager();
        $memberQry = $em->getRepository('AcmeMinsuBundle:Guide')->findOneBy(
            array('guide_id' => $id)
        );
        $memberQry->setState($state);
        $em->flush();
        return new Response(1);
    }


    /**
     * 导游的详情
     * @Route("/guideDataDetail",name="guideDataDetail_")
     */
    public function guideDataDetailAction()
    {
        $hid = isset($_GET['guide_id'])?$_GET['guide_id']:0;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        //查询导游的基本信息
        $hstReturn = $conn->createQueryBuilder ()
                    ->select ( "m.*,FROM_UNIXTIME(m.add_time, '%Y-%m-%d %H:%i:%s') NewAddTime,mm.true_name" )
                    ->from ( 'msk_guide', 'm' )
                    ->leftjoin ( 'm', 'msk_member', 'mm', 'm.member_id=mm.id' )
                    ->where ( "m.guide_id = $hid" )
                    ->execute ()
                    ->fetch();
        $certRes = $conn->createQueryBuilder()
            ->select('c.*')
            ->from('msk_guide_certification', 'c')
            ->Where('c.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetch();
        //查到评论数
        $comment = $conn->createQueryBuilder()
            ->select('count(c.comment_id) as comment_cout')
            ->from('msk_guide_comment', 'c')
            ->Where('c.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        $comment = $comment[0]['comment_cout'];
        //查到旅行相册数
        $album = $conn->createQueryBuilder()
            ->select('count(a.album_id) as album_cout')
            ->from('msk_guide_album', 'a')
            ->Where('a.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        $album = $album[0]['album_cout'];
        //查到评论数
        $travel = $conn->createQueryBuilder()
            ->select('count(a.travel_id) as travel_cout')
            ->from('msk_guide_travel', 'a')
            ->Where('a.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        $travel = $travel[0]['travel_cout'];
        $noimg = $this->getParameter('app_none_imgurl');
        if($certRes['positive_identity'] ==null) $certRes['positive_identity'] =$noimg;
        else $certRes['positive_identity'] = $this->getParameter("qiniu_minsu_imgurl").$certRes['positive_identity'];
        if($certRes['opposite_identity'] ==null) $certRes['opposite_identity'] =$noimg; 
        else $certRes['opposite_identity'] = $this->getParameter("qiniu_minsu_imgurl").$certRes['opposite_identity']; 
        if($certRes['guide_card'] ==null) $certRes['guide_card'] =$noimg;
        else $certRes['guide_card'] = $this->getParameter("qiniu_minsu_imgurl").$certRes['guide_card'];
        if($certRes['handheld_identity'] ==null) $certRes['handheld_identity'] =$noimg; 
        else $certRes['handheld_identity'] = $this->getParameter("qiniu_minsu_imgurl").$certRes['handheld_identity'];  
        //return $this->redirect($this->generateUrl('guideDataDetail_', ['guide_id' => 11]));
        return $this->render('AcmeMinsuBundle:Default:guideDetail.html.twig',
            array(
                'v'=>$hstReturn,
                'cert' => $certRes,
                'comment' => $comment,
                'album' => $album,
                'travel' =>$travel
               
            ));
    }

    /**
     * 导游带团景点的详情
     * @Route("/guideDataTravel",name="guideDataTravel_")
     */
    public function guideDataTravelAction()
    {
        $hid = isset($_GET['guide_id'])?$_GET['guide_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到旅行相册数
        $album = $conn->createQueryBuilder()
            ->select('a.*')
            ->from('msk_guide_travel', 'a')
            ->Where('a.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        return $this->render('AcmeMinsuBundle:Default:guideTravel.html.twig',
            array(
                'album' => $album 
            ));
    }

    /**
     * 导游旅行图片的详情
     * @Route("/guideDataAlbum",name="guideDataAlbum_")
     */
    public function guideDataAlbumAction()
    {
        $hid = isset($_GET['guide_id'])?$_GET['guide_id']:0;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到旅行相册数
        $album = $conn->createQueryBuilder()
            ->select("a.*,FROM_UNIXTIME(a.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime")
            ->from('msk_guide_album', 'a')
            ->Where('a.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        return $this->render('AcmeMinsuBundle:Default:guideAlbum.html.twig',
            array(
                'album' => $album
               
            ));
    }

    /**
     * 导游相关评论的详情
     * @Route("/guideDataComment",name="guideDataComment_")
     */
    public function guideDataCommentAction()
    {
        $hid = isset($_GET['guide_id'])?$_GET['guide_id']:0;;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
         //查到旅行相册数
        $comment = $conn->createQueryBuilder()
            ->select("a.*,FROM_UNIXTIME(a.addtime, '%Y-%m-%d %H:%i:%s') NewAddTime")
            ->from('msk_guide_comment', 'a')
            ->Where('a.guide_id = :hid')
            ->setParameters(array('hid' => $hid))
            ->execute()
            ->fetchAll();
        return $this->render('AcmeMinsuBundle:Default:guideComment.html.twig',
            array(
                'comment' => $comment
            ));
    }

    /**
     * 后台删除导游评论
     * @Route("/guideDataCommentDel",name="guideDataCommentDel_")
     */
    public function guideDataCommentDelAction() {
        $manager = $this->getDoctrine ()->getManager ();
        $guide_id = isset($_GET['guide_id'])?$_GET['guide_id']:0;
        $comment_id = isset($_GET['comment_id'])?$_GET['comment_id']:0;
       
        $st = $manager->getRepository ( "AcmeMinsuBundle:GuideComment" );
        $st_data = $st->findOneBy( array('comment_id'=>$comment_id,'guide_id'=>$guide_id) );
        $manager->beginTransaction ();
        $manager->remove ($st_data);
        $manager->flush();
        $manager->commit ();
        return $this->redirect($this->generateUrl('guideDataComment_', ['guide_id' => $guide_id]));
       
    }


}





























