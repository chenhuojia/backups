<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-4-20
 * Time: 16:53
 */
namespace Acme\MinsuBundle\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\MskAdvsImages;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Acme\MinsuBundle\Admin\AdminController;
class TravelImageController extends AdminController
{   
    
    /**
     * @Route("/travel/{page}",defaults={"page" = 1},name="travel_")
     */
    public function travelAction(Request $request,$page)
    {
        $session = $request->getSession();
        $adminName = $session->get('adminName');

        $em = $this->getDoctrine()->getManager();
        $MskAdmin = $em->getRepository("AcmeMinsuBundle:MskAdmin");
        $adminUser = $MskAdmin->findOneBy(
            array('admin_name' => $adminName)
        );
        $adminUserId = $adminUser->getId();

        $repo = $em->getRepository("AcmeMinsuBundle:MskAdvsImages");
        $qb = $repo->createQueryBuilder('a');
        $qb->select('count(a)');
        $qb->where('a.advs_admin_id = :adminId and a.advs_image_sort_id = 2');
        $qb->setParameter('adminId',$adminUserId);
        $total = $qb->getQuery()->getSingleScalarResult();
        $pageSize = 10;
        $startPage = ($page - 1) * $pageSize;
        $totalPage = ceil($total / $pageSize);
        $arr['total'] = $total;
        $arr['pageSize'] = $pageSize;
        $arr['totalPage'] = $totalPage;
        $em = $this->getDoctrine()->getManager();
        $pageQuery = $em->createQuery(
            "select p.advs_image_path,p.advs_admin_id from AcmeMinsuBundle:MskAdvsImages p
            where p.advs_image_sort_id = '2' ORDER BY p.advs_image_sort_num DESC"
        )->setFirstResult($startPage)
            ->setMaxResults($pageSize);
        $paginate = $pageQuery->getResult();

        $query = $em->createQuery(
            "select p.advs_image_sort_num,p.advs_is_default, p.id, p.advs_text from AcmeMinsuBundle:MskAdvsImages p
            where p.advs_image_sort_id = '2' ORDER BY p.advs_image_sort_num DESC"
        )->setFirstResult($startPage)
            ->setMaxResults($pageSize);
        $sortNum = $query->execute();

        $adminIdQuery = $em->createQuery(
            "SELECT a.advs_admin_id from AcmeMinsuBundle:MskAdvsImages a
              WHERE a.advs_image_sort_id = '2' ORDER BY a.advs_image_sort_num DESC"
        )->setFirstResult($startPage)
            ->setMaxResults($pageSize);
        $adminId = $adminIdQuery->execute();
        $an = array();
        foreach ($adminId as $v) {
            foreach ($v as $v1) {
                $adminNameQuery = $em->createQuery(
                    "select p.admin_name from AcmeMinsuBundle:MskAdmin p WHERE p.id = '{$v1}'"
                );
                $adminName = $adminNameQuery->execute();
                array_push($an,$adminName);
            }
        }

        $cfgImagePath = $this->getParameter("image_path");

        $imgPath = array();
        foreach ($paginate as $v) {
            $tmpPath = $this->qiniu. $v['advs_image_path'];
            array_push($imgPath,$tmpPath);
        }

        $stlenth = count($sortNum);
        $imgPathLenth = count($imgPath);

        for ($i = 0; $i <$stlenth ;$i++) {
            for ($j = 0; $j < $imgPathLenth; $j++) {
                if ($i == $j) {
                    $sortNum[$i]['imgPath'] = $imgPath[$j];
                } else {
                    continue;
                }
            }
        }

        $tmpAn = array();
        foreach ($an as $value) {
            foreach ($value as $value2) {
                foreach ($value2 as $value3) {
                    array_push($tmpAn,$value3);
                }
            }
        }

        $anlenth = count($tmpAn);
        for ($i = 0; $i < $stlenth; $i++) {
            for ($j = 0; $j < $anlenth ; $j ++) {
                if ($i == $j) {
                    $sortNum[$i]['adminName'] = $tmpAn[$j];
                }
            }
        }

        $arr['sortPath'] = $sortNum;

        return $this->render("AcmeMinsuBundle:Default:travelImage.html.twig",
            array('arr' => $arr)
        );
    }

    /**
     * @Route("/travelimghandle",name="travelimghandle_")
     */
    public function imghandleAction(Request $request)
    {
        $advsImageSortId = '2';
        $inputName = "imageUpload";
        $session = $request->getSession();
        $adminUserId = $session->get('adminUserId');
        $adminUserId = trim(addslashes($adminUserId));

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($newImageName=self::QiniuUpload($_FILES[$inputName]['tmp_name'], 'travel_banner')) {
                $mskAdvsImages = new MskAdvsImages();
                $mskAdvsImages->setAdvsImagePath("{$newImageName}");
                $mskAdvsImages->setAdvsAddTime(time());
                $mskAdvsImages->setAdvsImageSortId($advsImageSortId);
                $mskAdvsImages->setAdvsAdminId($adminUserId);
                $mskAdvsImages->setAdvsImageSortNum('0');
                $mskAdvsImages->setAdvsIsDefault('0');
                $em = $this->getDoctrine()->getManager();
                $em->persist($mskAdvsImages);
                $em->flush();  
                return $this->redirect($this->generateUrl('travel_'));
            }else{
                echo '上传失败';
            }
        }

        return new Response('');
    }

    /**
     * @Route("/travelimgdelete",name="travelimgdelete_")
     */
    function travelimgdeleteAction (Request $request)
    {
    $imgSrc = $request->get('imgSrc');
        $imgName = substr(strrchr($imgSrc,'/'),1);
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("delete from AcmeMinsuBundle:MskAdvsImages p where p.advs_image_path = :advs_image_path");
        $query->setParameter("advs_image_path",$imgName);
        if ($query->execute()) {
            return new JsonResponse('1');
        } else {
            return new JsonResponse('2');
        }
    }

    /**
     * @Route("/travelsortchange",name="travelsortchange_")
     */
    function sortchangeAction(Request $request)
    {
        $sortNum = $request->get("sortNum");
        $imgSrc = $request->get('src');
        $imgName = substr(strrchr($imgSrc,'/'),1);
        $imgName = trim(addslashes($imgName));
        $advs_image_path = $imgName;

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("update AcmeMinsuBundle:MskAdvsImages p set p.advs_image_sort_num = '{$sortNum}' where p.advs_image_path = '{$advs_image_path}'");

        if ($query->execute()) {
            return new JsonResponse('1');
        } else {
            return new JsonResponse('2');
        }

    }

    /**
     * @Route("/travelsetdefault",name="travelsetdefault_")
     */
    function setdefaultAction (Request $request) {
        $imgSrc = $request->get('src');
        $imgName = substr(strrchr($imgSrc,'/'),1);
        $imgName = trim(addslashes($imgName));

        $em = $this->getDoctrine()->getManager();
        $getDefaultQry = $em->createQuery(
            "select p.id from AcmeMinsuBundle:MskAdvsImages p WHERE p.advs_is_default = 1 AND p.advs_image_sort_id = 2"
        );
        $getDefaultRes = $getDefaultQry->execute();
        if ($getDefaultRes) {
            $defaultId = $getDefaultRes[0]['id'];
            $dltDefaultQry = $em->createQuery(
                "update AcmeMinsuBundle:MskAdvsImages p set p.advs_is_default = '0' WHERE p.id = {$defaultId}"
            );
            $dltDefaultQry->execute();
        }
        
        $query = $em->createQuery(
            "update AcmeMinsuBundle:MskAdvsImages p set p.advs_is_default = '1' where p.advs_image_path = '{$imgName}'"
        );

        if ($query->execute()) {
            return new JsonResponse('1');
        } else {
            return new JsonResponse('2');
        }
    }

    /**
     * @Route("/traveldltdefault",name="traveldltdefault_")
     */
    function dltdefaultAction (Request $request) {
        $imgSrc = $request->get('src');
        $imgName = substr(strrchr($imgSrc,'/'),1);
        $imgName = trim(addslashes($imgName));

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "update AcmeMinsuBundle:MskAdvsImages p set p.advs_is_default = '0' where p.advs_image_path = '{$imgName}'"
        );

        if ($query->execute()) {
            return new JsonResponse('1');
        } else {
            return new JsonResponse('2');
        }
    }

}

































