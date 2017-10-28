<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-5-16
 * Time: 11:27
 */
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Acme\MinsuBundle\Entity\Member;
use Acme\MinsuBundle\Entity\MemberInfo;

use Acme\MinsuBundle\Common\CommonController;
class apiLoginController extends CommonController
{
    
    public function __construct(){
    
    }
    
    
    /**
     * @Route("/apivalid",name="api_valid_")
     */
    public function apivalidAction(Request $request)
    {
        if ($pAccount = $request->get('pAccount')) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "select p.id, p.member_state from AcmeMinsuBundle:Member p where p.account = :pAccount"
            );
            $query->setParameter("pAccount",$pAccount);
            $memberId = $query->execute();

            if (!empty($memberId)) {
                if ($memberId[0]['member_state'] == 1) {
                    $host = $this->getParameter('host');
                    //$host = "192.168.1.108/msk/web/";
                    $memberId = $memberId[0]['id'];
                    $time = time();
                    $currenip = $_SERVER['REMOTE_ADDR'];

                    $memberQuery = $em->createQuery(
                        "select p.member_login_ip,p.member_login_time,p.login_num,p.avatar,p.member_qqopenid from AcmeMinsuBundle:Member p WHERE p.id = :memberId AND p.member_state = 1"
                    );
                    $memberQuery->setParameter("memberId",$memberId);
                    $memberQuery = $memberQuery->execute();
                    if (!empty($memberQuery)) {
                        $loginIp = $memberQuery[0]['member_login_ip'];
                        $loginTime = $memberQuery[0]['member_login_time'];
                        $loginNum = $memberQuery[0]['login_num'] + 1;
                        $qAccount = $memberQuery[0]['member_qqopenid'];
                        $avatarRes = $memberQuery[0]['avatar'];
                    } else {
                        $message['status'] = '0';
                        $message['error'] = '1';
                        $message['message'] = 'the acount is closed';

                        return new JsonResponse($message);
                    }


                    $memberObj = $em->getRepository('AcmeMinsuBundle:Member')->findOneByid($memberId);
                    $memberObj->setMemberOldLoginIp($loginIp);
                    $memberObj->setMemberLoginIp($currenip);
                    $memberObj->setMemberOldLoginTime($loginTime);
                    $memberObj->setMemberLoginTime($time);
                    $memberObj->setLoginNum($loginNum);
                    $memberObj->setMemberState(1);
                    $memberObj->setIsOwner(0);
                    if ($em->flush()) {
                        $message['status'] = '0';
                        $message['error'] = '1';
                        $message['message'] = 'change error';

                        return new JsonResponse($message);
                    }

                    $avatarPath = $this->getParameter('avatar_path');
                    $avatarImg = $host . $avatarPath . $memberId . '/' .$avatarRes;
                    //$avatar = base64_encode(file_get_contents($avatarImg));

                    $memberInfoQuery = $em->createQuery(
                        "select p.nickname,p.introduce from AcmeMinsuBundle:MemberInfo p WHERE p.member_id = :memberId"
                    );
                    $memberInfoQuery->setParameter('memberId',$memberId);
                    $memberInfoQueryRes = $memberInfoQuery->execute();
                    if ($memberInfoQueryRes) {
                        if (!$nickname = $memberInfoQueryRes[0]['nickname']) {
                            $nickname = "";
                        }
                        if ($introduce = $memberInfoQueryRes[0]['introduce']) {
                            $introduce = "";
                        }
                    } else {
                        $nickname = "";
                        $introduce = "";
                    }

                    $memberId = "{$memberId}";
                    $data = array(
                        'status' => '1',
                        'error' => '0',
                        'message' => 'success',
                        'pAccount' => $pAccount,
                        'qAccount' => $qAccount,
                        'avatar' => $avatarImg,
                        'nickname' =>$nickname,
                        'introduce' => $introduce,
                        'memberId' => $memberId
                    );
                    //Token
                    $strToken = md5($pAccount).'|'.$memberId.'|'.time();
                    $token = $this->myEncode($strToken);
                    $this->getDoctrine()->getManager()->getConnection()->createQueryBuilder ()
                        ->update ( 'msk_member', 'm' )
                        ->set ('m.token',"'$token'")
                        ->andwhere( "m.id =$memberId" )
                        ->execute ();
                    return new JsonResponse($data);
                } else {
                    $message['status'] = '0';
                    $message['error'] = '1';
                    $message['message'] = 'black list';

                    return new JsonResponse($message);
                }
            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'account not exist';

                return new JsonResponse($message);
            }
        } elseif($qAccount = $request->get('qAccount')) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery("select p.id, p.member_state from AcmeMinsuBundle:Member p where p.member_qqopenid = :qAccount");
            $query->setParameter("qAccount",$qAccount);

            if ($memberId = $query->execute()) {
                if ($memberId[0]['member_state'] == 1) {
                    $host = $this->getParameter('host');
                    //$host = "192.168.1.108/msk/web/";
                    $memberId = $memberId[0]['id'];
                    $time = time();
                    $currenip = $_SERVER['REMOTE_ADDR'];

                    $memberQuery = $em->createQuery(
                        "select p.member_login_ip,p.member_login_time,p.login_num,p.avatar,p.account from AcmeMinsuBundle:Member p WHERE p.id = :memberId"
                    );
                    $memberQuery->setParameter("memberId",$memberId);
                    $memberQuery = $memberQuery->execute();
                    $loginIp = $memberQuery[0]['member_login_ip'];
                    $loginTime = $memberQuery[0]['member_login_time'];
                    $loginNum = $memberQuery[0]['login_num'] + 1;
                    $pAccount = $memberQuery[0]['account'];

                    $memberObj = $em->getRepository('AcmeMinsuBundle:Member')->findOneByid($memberId);
                    $memberObj->setMemberOldLoginIp($loginIp);
                    $memberObj->setMemberLoginIp($currenip);
                    $memberObj->setMemberOldLoginTime($loginTime);
                    $memberObj->setMemberLoginTime($time);
                    $memberObj->setLoginNum($loginNum);
                    if ($em->flush()) {
                        $message['status'] = '0';
                        $message['error'] = '1';
                        $message['message'] = 'change error';

                        return new JsonResponse($message);
                    }

                    $avatarRes = $memberQuery[0]['avatar'];
                    $avatarPath = $this->getParameter('avatar_path');
                    $avatarImg = $host . $avatarPath . $memberId . '/' .$avatarRes;
                    //$avatar = base64_encode(file_get_contents($avatarImg));

                    $memberInfoQuery = $em->createQuery(
                        "select p.nickname,p.introduce from AcmeMinsuBundle:MemberInfo p WHERE p.member_id = :memberId"
                    );
                    $memberInfoQuery->setParameter('memberId',$memberId);
                    $memInfoQrRes = $memberInfoQuery->execute();
                    $nickname = $memInfoQrRes[0]['nickname'];
                    $introduce = $memInfoQrRes[0]['introduce'];

                    $data = array(
                        'status' => '1',
                        'error' => '0',
                        'message' => 'account exist',
                        'pAccount' => $pAccount,
                        'qAccount' => $qAccount,
                        'avatar' => $avatarImg,
                        'nickname' =>$nickname,
                        'introduce' => $introduce,
                        'memberId' => $memberId
                    );
                    //Token
                    $strToken = md5($pAccount).'|'.$memberId.'|'.time();
                    $token = $this->myEncode($strToken);
                    $this->getDoctrine()->getManager()->getConnection()->createQueryBuilder ()
                        ->update ( 'msk_member', 'm' )
                        ->set ('m.token',"'$token'")
                        ->andwhere( "m.id =$memberId" )
                        ->execute ();
                    return new JsonResponse($data);
                } else {
                    $message['status'] = '0';
                    $message['error'] = '1';
                    $message['message'] = 'black list';

                    return new JsonResponse($message);
                }
            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'account not exist';

                return new JsonResponse($message);
            }
        }else {
            $message['status'] = '0';
            $message['error'] = '1';
            $message['message'] = 'not receive account';

            return new JsonResponse($message);
        }

    }

    //$img = base64_decode('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');


    /**
     * @Route("/apiregister",name="api_register_")
     */
    public function apiregisterAction(Request $request)
    {
        $avatarPath = $this->getParameter('avatar_path');
        $time = time();
        $loginIp = $_SERVER['REMOTE_ADDR'];
        $loginNum = '1';

        $userAvatar = $request->get('avatar');
        $avatar = json_decode($userAvatar, true); //return new Response($avatar);
        $pAccount = $request->get('pAccount');
        $qAccount = $request->get('qAccount');
        $nickname = $request->get('nickname');
        $introduce = $request->get('introduce');
        if (!$pAccount && !$nickname) {
            $message['status'] = '0';
            $message['error'] = '1';
            $message['message'] = 'not receive account!';

            return new JsonResponse($message);
        }

        $em = $this->getDoctrine()->getManager();

        $isExistMemberRes = $em->createQuery(
            "select p.id from AcmeMinsuBundle:Member p where p.account = :account"
        )
            ->setParameter('account', $pAccount)
            ->execute();
        if (empty($isExistMemberRes)) {
            $img = base64_decode($avatar[0]['avatar']);
            $member = new Member();
            $member->setAccount($pAccount);
            if (isset($qAccount)) {
                $member->setMemberQqopenid($qAccount);
            }
            $member->setMemberLoginTime($time);
            $member->setMemberOldLoginTime($time);
            $member->setMemberLoginIp($loginIp);
            $member->setMemberOldLoginIp($loginIp);
            $member->setLoginNum($loginNum);
            $member->setIsOwner(0);
            $member->setMemberState(1);
            $member->setCreatDate($time);
            $em->persist($member);
            if ($em->flush()) {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'add member fail';

                return new JsonResponse($message);
            }

            $id = $member->getId();

            $memberInfo = new MemberInfo();
            $memberInfo->setNickname($nickname);
            if (isset($introduce)) {
                $memberInfo->setIntroduce($introduce);
            }
            $memberInfo->setMemberId($id);
            $em->persist($memberInfo);
            if ($em->flush()) {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'add memberInfo fail';

                return new JsonResponse($message);
            }

            $memberAvatarPath = $avatarPath . $id . '/';
            $sf = new Filesystem();
            if ($sf) {
                if (!is_dir($memberAvatarPath)) {
                    $sf->mkdir($memberAvatarPath);
                }
            }

            $imgType = 'jpg';
            $randNum = "";
            for ($i = 0; $i < 5; $i++) {
                $tmpNum = intval(mt_rand(1, 9));
                $randNum = $randNum . $tmpNum;
            }
            $imgName = $id . $randNum;
            file_put_contents("$memberAvatarPath$imgName.$imgType", $img);

            $insertAvatar = $em->getRepository('AcmeMinsuBundle:member')->find($id);
            $insertAvatar->setAvatar("$imgName.$imgType");
            if (!$em->flush()) {
                $message['status'] = '1';
                $message['error'] = '0';
                $message['message'] = 'success';

                return new JsonResponse($message);
            } else {
                $message['status'] = '0';
                $message['error'] = '1';
                $message['message'] = 'insert img fail!';
                //Token
                $strToken = md5($pAccount).'|'.$memberId.'|'.time();
                $token = $this->myEncode($strToken);
                $this->getDoctrine()->getManager()->getConnection()->createQueryBuilder ()
                    ->update ( 'msk_member', 'm' )
                    ->set ('m.token',"'$token'")
                    ->andwhere( "m.id =$id" )
                    ->execute ();
                return new JsonResponse($message);
            }
        } else {
            $message['status'] = '0';
            $message['error'] = '1';
            $message['message'] = 'account exist';

            return new JsonResponse($message);
        }
    }

    /**
     * @Route("/apiPhoneMassage", name="phoneMassage_")
     */
    public function apiPhoneMassageAction(Request $request)
    {
        $phone = $request->get('phone');
        $code = $request->get('code');
        if (!$phone) {
            $massage['state'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found phone';
            return new JsonResponse($massage);
        }
        if (!$code) {
            $massage['state'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found code';
            return new JsonResponse($massage);
        }

        // 配置项
        $api = 'https://webapi.sms.mob.com';//（例：https://webapi.sms.mob.com);
        $appkey = '13184cdc0d8d8';

        // 发送验证码
        $response = $this->postRequest($api . '/sms/verify', array(
            'appkey' => $appkey,
            'phone' => $phone,
            'zone' => '86',
            'code' => $code,
        ));

        return new Response($response);
    }

    /**
     * 发起一个post请求到指定接口
     *
     * @param string $api 请求的接口
     * @param array $params post参数
     * @param int $timeout 超时时间
     * @return string 请求结果
     */
    function postRequest($api, array $params = array(), $timeout = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        // 以返回的形式接收信息
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 设置为POST方式
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        // 不验证https证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept: application/json',
        ));
        // 发送数据
        $response = curl_exec($ch);
        // 不要忘记释放资源
        curl_close($ch);
        return $response;
    }

    /**
     * @Route("/apiIsBlackList", name="apiIsBlackList_")
     */
    public function apiIsBlackListAction(Request $request)
    {
        $memberId = $request->get('member_id');
        if (!$memberId) {
            $massage['state'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found member id';
            return new JsonResponse($massage);
        }

        $em = $this->getDoctrine()->getManager();
        $memberIdQry = $em->createQuery(
            "select p.member_state from AcmeMinsuBundle:Member p WHERE p.id = :id"
        )
            ->setParameter('id', $memberId)
            ->execute();
        if (!empty($memberIdQry)) {
            $state = $memberIdQry[0]['member_state'];
            if ($state == 1) {
                $massage['state'] = '1';
                $massage['error'] = '0';
                $massage['massage'] = 'the account is normal';
                return new JsonResponse($massage);
            } else {
                $massage['state'] = '0';
                $massage['error'] = '1';
                $massage['massage'] = 'the account in black list';
                return new JsonResponse($massage);
            }
        } else {
            $massage['state'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'the account is not found';
            return new JsonResponse($massage);
        }
    }

    /**
     * @Route("/apiBindQQ", name="apiBindQQ_")
     */
    public function apiBindQQAction(Request $request)
    {
        $qAccount = $request->get('qAccount');
        if (!$qAccount) {
            return new JsonResponse($this->fail('not receive paras'));
        }
        $pAccount = $request->get('pAccount');
        if (!$pAccount) {
            return new JsonResponse($this->fail('not receive paras'));
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AcmeMinsuBundle:Member');
        $member = $repository->findOneBy(
            array('account' => $pAccount)
        );
        if (!$member) {
            return new JsonResponse($this->fail('account not exist'));
        }
        $member->setMemberQqopenid($qAccount);
        $em->flush();
        return new JsonResponse($this->success('success'));
    }

    private function success($msg)
    {
        $message['status'] = '1';
        $message['error'] = '0';
        $message['message'] = "$msg";
        return $message;
    }

    private function fail($msg)
    {
        $message['status'] = '0';
        $message['error'] = '1';
        $message['message'] = "$msg";
        return $message;
    }

    /**
     * @Route("/apitest",name="api_test_")
     */
    public function apitestAction()
    {
        return $this->render('AcmeMinsuBundle:apitest:apitest.html.twig');
    }
}




















