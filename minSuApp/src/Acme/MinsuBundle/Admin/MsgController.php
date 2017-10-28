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
use Acme\MinsuBundle\Entity\Msg;

class MsgController extends Controller
{
    /**
     * @Route("/systemMsg", name="systemMsg_")
     */
    public function systemMsgAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();

        $msgQry = $conn->createQueryBuilder()
            ->select(
                "p.*"
            )
            ->from('msk_msg', 'p')
            ->where('p.type in (1, 0, 4)')
            ->orderBy('p.addtime', 'desc')
            ->execute();
        $msgQryRes = $msgQry->fetchAll();
        if (!empty($msgQryRes)) {
            $msgQryResLenth = count($msgQryRes);
            for ($i = 0; $i < $msgQryResLenth; $i++) {
                $adminId = substr($msgQryRes[$i]['memberid'], 0, 1);
                $adminNameQry = $em->createQuery(
                    "select p.admin_name from AcmeMinsuBundle:MskAdmin p WHERE p.id = :id"
                )
                    ->setParameter('id', $adminId)
                    ->execute();
                $adminName = $adminNameQry[0]['admin_name'];
                unset($msgQryRes[$i]['memberid']);
                $msgQryRes[$i]['adminName'] = $adminName;
            }

            $totalPage = ceil(count($msgQryRes) / 10);
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
            $msgQryRes = array_slice($msgQryRes, $offset, 10);

            return $this->render('AcmeMinsuBundle:Default:msg.html.twig', array(
                'msg' => $msgQryRes,
                'totalPage' => $totalPage,
                'prePage' => $prePage,
                'nextPage' => $nextPage
            ));
        } else {
            return $this->render('AcmeMinsuBundle:Default:msg.html.twig', array(
                'msg' => $msgQryRes,
                'totalPage' => 0,
                'prePage' => 0,
                'nextPage' => 0
            ));
        }


    }

    /**
     * @Route("/addSystemMsg", name="addSystemMsg_")
     */
    public function addSystemMsgAction(Request $request)
    {

        return $this->render('AcmeMinsuBundle:Default:addSystemMsg.html.twig');
    }

    /**
     * @Route("/saveSystemMsg", name="saveSystemMsg_")
     */
    public function saveSystemMsgAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $admin = $session->get('adminName');
        $adminIdQry = $em->createQuery(
            "select p.id from AcmeMinsuBundle:MskAdmin p WHERE p.admin_name = :adminName"
        )
            ->setParameter('adminName', $admin)
            ->execute();

        $adminId = $adminIdQry[0]['id'];

        $msg = $request->get('msg');
        $link = $request->get('link');
        $type = $request->get('type');
        $massage = new Msg();
        $massage->setMemberid("$adminId" . "-");
        $massage->setMsg($msg);
        $massage->setUrl($link);
        $massage->setType($type);
        $massage->setIs_read(0);
        $massage->setCpid(0);
        $massage->setAddtime(time());
        $em->persist($massage);
        $em->flush();
        $response = $this->forward('AcmeMinsuBundle:Msg:systemMsg', array())->getContent();
        return new Response($response);
    }

    /**
     * @Route("/memberHasCheck", name="memberHasCheck_")
     */
    public function memberHasCheckAction(Request $request)
    {
        $em = $this->getDoctrine ()->getManager();
        $conn = $em->getConnection ();
        $id = $request->get('id');

        $memberIdQry = $em->createQuery(
            "select p.memberid from AcmeMinsuBundle:Msg p WHERE p.id = :id"
        )
            ->setParameter('id', $id)
            ->execute();
        $memberId = $memberIdQry[0]['memberid'];
        $memberIdArr = explode(',', explode('-', $memberId)[1]);

        if (!empty($memberIdArr[0])) {
            $data = array();
            $memberIdArrLenth = count($memberIdArr);
            unset($memberIdArr[$memberIdArrLenth - 1]);
            for ($i = 0; $i < $memberIdArrLenth - 1; $i++) {
                $a = $conn->createQueryBuilder ()
                    ->select ( "m.id, m.true_name, m.avatar, m.creat_date, p.nickname" )
                    ->from ( 'msk_member', 'm' )
                    ->leftjoin('m', 'msk_member_info', 'p', 'm.id = p.member_id')
                    ->where ('m.id = :id')
                    ->setParameter('id', $memberIdArr[$i])
                    ->orderBy("m.creat_date","DESC")
                    ->execute ();
                $memberList = $a->fetchAll ();
                array_push($data, $memberList[0]);
            }
            $totalPage = ceil(count($data) / 10);

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
            $memberList = array_slice($data, $offset, 10);
//            var_dump($memberList);
            for  ($i =0 ; $i<count($memberList) ;$i++){
                $avatar =	$memberList[$i]['avatar'] ;

                $avatarPath=$this->container->getParameter('app_avater_path') .$memberList[$i]['id'].'/';
                $memberList[$i]['avatar_url'] =$avatarPath .$avatar;
            }

//     	 print_r($memberList);exit();
            return $this->render('AcmeMinsuBundle:Default:hasCheckMsgMember.html.twig',
                array(
                    'memberList'=>$memberList,
                    'totalPage' => $totalPage,
                    'prePage' => $prePage,
                    'nextPage' => $nextPage,
                    'page' => $page,
                    'id' => $id
                )
            );
        } else {
            return $this->render('AcmeMinsuBundle:Default:hasCheckMsgMember.html.twig',
                array(
                    'memberList'=>"",
                    'totalPage' => 0,
                    'prePage' => 0,
                    'nextPage' => 0,
                    'page' => 0,
                    'id' => $id
                )
            );
        }
    }

    /**
     * @Route("/editMsg", name="editMsg_")
     */
    public function editMsgAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AcmeMinsuBundle:Msg')->find($id);
        $msg = $repo->getMsg();
        $url = $repo->getUrl();
        return $this->render('AcmeMinsuBundle:Default:editSystemMsg.html.twig',
            array('id' => $id, 'msg' => $msg, 'url' => $url));
    }

    /**
     * @Route("/saveEditMsg", name="saveEditMsg_")
     */
    public function saveEditMsgAction(Request $request)
    {
        $id = $request->get('id');
        $posmsg = $request->get('msg');
        $link = $request->get('link');
        $type = $request->get('type');
        $em = $this->getDoctrine()->getManager();
        $msg = $em->getRepository('AcmeMinsuBundle:Msg')->find($id);
        $msg->setMsg($posmsg);
        $msg->setUrl($link);
        $msg->setType($type);
        $msg->setAddtime(time());
        $em->flush();
        $response = $this->forward('AcmeMinsuBundle:Msg:systemMsg', array())->getContent();
        return new Response($response);
    }

    /**
     * @Route("/deleteMsg", name="deleteMsg_")
     */
    public function deleteMsgAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $msg = $em->getRepository('AcmeMinsuBundle:Msg')->find($id);
        $msg->setType(4);
        $em->flush();
        return new Response(1);
    }
}





























