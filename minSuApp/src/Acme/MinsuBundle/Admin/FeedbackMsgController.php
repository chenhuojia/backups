<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-7-27
 * Time: 13:56
 */
namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FeedbackMsgController extends Controller
{
    /**
     * @Route("/feedbackMsg", name="feedbackMsg_")
     */
    public function feedbackMsgAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $msgQry = $conn->createQueryBuilder()
            ->select("p.*, c.avatar, m.nickname")
            ->from('msk_feedback', 'p')
            ->leftJoin('p', 'msk_member', 'c', 'p.member_id = c.id')
            ->leftJoin('p', 'msk_member_info', 'm', 'p.member_id = m.member_id')
            ->execute();
        $msgQryRes = $msgQry->fetchAll();
//        var_dump($msgQryRes);
        if (!empty($msgQryRes)) {
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
            $memberList = array_slice($msgQryRes, $offset, 10);
//            var_dump($memberList);
            for  ($i =0 ; $i<count($memberList) ;$i++){
                $avatar =	$memberList[$i]['avatar'] ;

                $avatarPath=$this->getParameter('app_avater_path') .$memberList[$i]['member_id'].'/';
                $memberList[$i]['avatar_url'] =$avatarPath .$avatar;

                $imgPath = $this->getParameter('feedback_image');
                $imageArr = explode(',', $memberList[$i]['image']);

                $lenth = count($imageArr);
                unset($imageArr[$lenth-1]);
                for ($j = 0; $j < $lenth - 1; $j++) {
                    $imageArr[$j] = $imgPath . $memberList[$i]['member_id'] . '/' . $imageArr[$j];
                }
                $memberList[$i]['image'] = $imageArr;
            }

//     	 print_r($memberList);exit();
            return $this->render('AcmeMinsuBundle:Default:feedback.html.twig',
                array(
                    'msg'=>$memberList,
                    'totalPage' => $totalPage,
                    'prePage' => $prePage,
                    'nextPage' => $nextPage,
                    'page' => $page
                )
            );
        } else {
            return $this->render('AcmeMinsuBundle:Default:feedback.html.twig',
                array(
                    'msg'=>"",
                    'totalPage' => 0,
                    'prePage' => 0,
                    'nextPage' => 0,
                    'page' => 0
                )
            );
        }
    }

    /**
     * @Route("/changeFBState", name="changeFBState_")
     */
    public function changeFBStateAction(Request $request)
    {
        $id = $request->get('id');
        $state = $request->get('feedbackState');
        $em = $this->getDoctrine()->getManager();
        $feedback = $em->getRepository('AcmeMinsuBundle:Feedback')->find($id);
        $feedback->setState($state);
        $em->flush();
        return new Response(1);
    }
}





























