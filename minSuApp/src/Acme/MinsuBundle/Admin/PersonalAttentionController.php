<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-6-17
 * Time: 14:01
 */
namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PersonalAttentionController extends Controller
{
    /**
     * @Route("/personalAttention", name="personalAttention_")
     */
    public function personalAttentionAction(Request $request)
    {
        $page = $request->get('page');
        $pageSize = $this->getParameter('attentionPageSize');

        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $totalNumQry = $em->createQuery(
            "select count(p.id) from AcmeMinsuBundle:MemberInfo p"
        );
        $totalNumRes = $totalNumQry->execute();
        if ($totalNumRes) {
            $totalNum = $totalNumRes[0][1];
        } else {
            $totalNum = 0;
        }
        $totalPage = ceil($totalNum / $pageSize);
        if ($page < 1) {
            $page = 1;
        }
        if ($page > $totalPage) {
            $page = $totalPage;
        }
        $startPage = ($page - 1) * $pageSize;
        $prePage = $page - 1;
        if ($prePage < 1) {
            $prePage = 1;
        }
        $nextPage = $page + 1;
        if ($nextPage > $totalPage) {
            $nextPage = $totalPage;
        }

        $nicknameQry = $conn->createQueryBuilder()
            ->select(
                "p.nickname, p.member_id"
            )
            ->from('msk_member_info', 'p')
            ->setFirstResult($startPage)
            ->setMaxResults($pageSize)
            ->execute();
        $nicknameRes = $nicknameQry->fetchAll();

        return $this->render('AcmeMinsuBundle:Default:personalAttention.html.twig',
            array(
                'data' => $nicknameRes,
                'totalPage' => $totalPage,
                'prePage' => $prePage,
                'nextPage' => $nextPage,
                'page' => $page
            ));
    }

    /**
     * @Route("/myPersonalAttention", name="myPersonalAttention_")
     */
    public function myPersonalAttentionAction(Request $request)
    {
        $personalPage = $request->get('personalPage');
        $memberId = $request->get('memberId');
        $page = $request->get('page');
        $pageSize = $this->getParameter('attentionPageSize');
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();

        $totalNumQry = $em->createQuery(
            "select count(p.id) from AcmeMinsuBundle:MemberRelation p
            WHERE p.relation_type = :relationType AND p.from_member_id = :fromMemberId"
        )
            ->setParameters(array('relationType' => '1', 'fromMemberId' => $memberId));
        $totalNumRes = $totalNumQry->execute();
        if ($totalNumRes[0][1] != 0) {
            if ($totalNumRes) {
                $totalNum = $totalNumRes[0][1];
            } else {
                $totalNum = 0;
            }
            $totalPage = ceil($totalNum / $pageSize);

            if ((int)($page) < 1) {
                $page = 1;
            }
            if ($page > $totalPage) {
                $page = $totalPage;
            }

            $startPage = ($page - 1) * $pageSize;

            $attentionQry = $conn->createQueryBuilder()
                ->select(
                    'p.nickname, p.introduce, c.add_time'
                )
                ->from('msk_member_relation', 'c')
                ->leftjoin('c', 'msk_member_info', 'p', 'p.member_id = c.to_member_id')
                ->where('c.from_member_id = :fromMemberId')
                ->andWhere('c.relation_type = :relationType')
                ->setParameters(array('fromMemberId' => $memberId, 'relationType' => '1'))
                ->setFirstResult($startPage)
                ->setMaxResults($pageSize)
                ->execute();
            $attentionRes = $attentionQry->fetchAll();
        } else {
            $attentionRes = null;
            $totalPage = 0;
        }

        return $this->render('AcmeMinsuBundle:Default:myPersonalAttention.html.twig',
            array(
                'data' => $attentionRes,
                'page' => $page,
                'totalPage' => $totalPage,
                'personalPage' => $personalPage
            )
        );
    }

    /**
     * @Route("/myPersonalFans", name="myPersonalFans_")
     */
    public function myPersonalFansAction(Request $request)
    {
        $personalPage = $request->get('personalPage');
        $memberId = $request->get('memberId');
        $page = $request->get('page');
        $pageSize = $this->getParameter('attentionPageSize');
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();

        $totalNumQry = $em->createQuery(
            "select count(p.id) from AcmeMinsuBundle:MemberRelation p
            WHERE p.relation_type = :relationType AND p.to_member_id = :toMemberId"
        )
            ->setParameters(array('relationType' => '1', 'toMemberId' => $memberId));
        $totalNumRes = $totalNumQry->execute();
        if ($totalNumRes[0][1] != 0) {
            if ($totalNumRes) {
                $totalNum = $totalNumRes[0][1];
            } else {
                $totalNum = 0;
            }
            $totalPage = ceil($totalNum / $pageSize);

            if ((int)($page) < 1) {
                $page = 1;
            }
            if ($page > $totalPage) {
                $page = $totalPage;
            }

            $startPage = ($page - 1) * $pageSize;

            $attentionQry = $conn->createQueryBuilder()
                ->select(
                    'p.nickname, p.introduce, c.add_time'
                )
                ->from('msk_member_relation', 'c')
                ->leftjoin('c', 'msk_member_info', 'p', 'p.member_id = c.from_member_id')
                ->where('c.to_member_id = :toMemberId')
                ->andWhere('c.relation_type = :relationType')
                ->setParameters(array('toMemberId' => $memberId, 'relationType' => '1'))
                ->setFirstResult($startPage)
                ->setMaxResults($pageSize)
                ->execute();
            $attentionRes = $attentionQry->fetchAll();
        } else {
            $attentionRes = null;
            $totalPage = 0;
        }

        return $this->render('AcmeMinsuBundle:Default:myPersonalAttention.html.twig',
            array(
                'data' => $attentionRes,
                'page' => $page,
                'totalPage' => $totalPage,
                'personalPage' => $personalPage
            )
        );
    }
}


































