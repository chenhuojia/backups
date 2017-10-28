<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-7-27
 * Time: 14:37
 */
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\Tests\Compiler\C;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Acme\MinsuBundle\Entity\Feedback;

use Acme\MinsuBundle\Common\CommonController;

class apiFeedbackMsgController extends Controller
{
    /**
     * @Route("/apiUFBMsg", name="apiUFBMsg_")
     */
    public function apiUFBMsgAction(Request $request)
    {
        $memberId = $request->get('member_id');
        $text = $request->get('text');
        $image = $request->get('image');
        if (!$memberId) {
            return new JsonResponse($this->failMassageReturnAction('not found member_id'));
        }
        if (!$text) {
            return new JsonResponse($this->failMassageReturnAction('not found text'));
        }

        $em = $this->getDoctrine()->getManager();
        $feedbackMsg = new Feedback();
        $feedbackMsg->setMemberId($memberId);
        $feedbackMsg->setText($text);
        $feedbackMsg->setState(0);
        if ($image) {
            /* $imageArr = json_decode($image, true);
            $imageArrLenth = count($imageArr);
            $imageNameStr = "";
            for ($i = 0; $i < $imageArrLenth; $i++) {
                $imageStr = base64_decode($imageArr[$i]['img']);
                $path = $this->getParameter('feedback_image');
                $fullPath = $path . $memberId . "/";
                $sf = new Filesystem();
                if ($sf) {
                    if (!is_dir($fullPath)) {
                        $sf->mkdir($fullPath, 0755, true);
                    }
                }

                $imgType = '.jpg';
                $randNum = "";
                for ($j = 0; $j < 8; $j++) {
                    $tmpNum = intval(mt_rand(1, 9));
                    $randNum = $randNum . $tmpNum;
                }
                $imgName = $randNum;
                $imageNameStr .= "$imgName$imgType,";
                file_put_contents("$fullPath$imgName$imgType", $imageStr);
            } */
//            var_dump($imageNameStr);
            $feedbackMsg->setImage($image);
        }
        $feedbackMsg->setAddtime(time());
        $em->persist($feedbackMsg);
        $em->flush();
        return new JsonResponse($this->successMassageReturnAction('success'));
    }

    /**
     * @Route("/failMassageReturn", name="failMassageReturn_")
     */
    public function failMassageReturnAction($reminderMassage)
    {
        $massage['status'] = '0';
        $massage['error'] = '1';
        $massage['massage'] = $reminderMassage;
        return $massage;
    }

    /**
     * @Route("/successMassageReturn", name="successMassageReturn_")
     */
    public function successMassageReturnAction($reminderMassage)
    {
        $massage['status'] = '1';
        $massage['error'] = '0';
        $massage['massage'] = $reminderMassage;
        return $massage;
    }
}