<?php

namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\HttpFoundation\Cookie;
use Acme\MinsuBundle\Entity\MskAdmin;

class LoginController extends  Controller {
	/**
	 * @Route("/login",name="login_")
	 */
	public function indexAction() {
        //phpinfo();exit;
		return $this->render('AcmeMinsuBundle:Login:login.html.twig');
	}

    /**
     * @Route("/validate",name="validate_")
     */
    public function validateAction(Request $request)
    {
        $response = array();
        if (!$request->isXmlHttpRequest()){exit();}

        $admin_name = stripcslashes(trim($request->request->get('admin_name')));
        $admin_password = stripcslashes(trim($request->request->get('admin_password')));
        $admin_password = md5($admin_password);

        //验证码验证
        $captcha = stripcslashes(trim($request->request->get('captcha')));
        $session = $this->get('session');
        $randcode = $session->get('randcode');

    /*    if ($captcha !== $randcode) {
            $response['code'] = 0;
            return new JsonResponse($response);
            return false;
        }*/

        $em = $this->getDoctrine()->getManager()->getRepository('AcmeMinsuBundle:MskAdmin');
        $msk_m = $em->findOneBy(array('admin_name' => $admin_name));

        if (!$msk_m) {
            $response['code'] = 2;
            return new JsonResponse($response);
            return false;
        }

        $db_getAdminName = $msk_m->getAdminName();
        $db_getAdminPassword = $msk_m->getAdminPassword();

        if ($admin_name === $db_getAdminName && $admin_password === $db_getAdminPassword) {
            $response['code'] = 1;
            $response['admin_name'] = $admin_name;
            $lastLoginTime = $msk_m->getAdminLoginTime();
            $loginNum = $msk_m->getAdminLoginNum();
            $id = $msk_m->getId();

            $session = $request->getSession();
            if ($session) {
                $session->set('login',true);
                $session->set('adminName',$admin_name);
                $session->set('lastLoginTime',$lastLoginTime);
                $session->set('loginNum',$loginNum);
                $session->set('adminUserId',$id);
            }

            $lastLoginNum = $loginNum + 1;
            $time = time();
            $setem = $this->getDoctrine()->getManager();
            $query = $setem->createQuery(
                "update AcmeMinsuBundle:MskAdmin p set p.admin_login_time = '{$time}',p.admin_login_num = '{$lastLoginNum}' where p.id = '{$id}'"
            );
            $query->execute();

            return new JsonResponse($response);
        } else {
            $response['code'] = 2;
            return new JsonResponse($response);
            return false;
        }
    }
}































