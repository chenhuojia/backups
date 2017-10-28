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
use Acme\MinsuBundle\Entity\MskAdmin;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Acme\MinsuBundle\Admin\AdminController;
class advsImageController extends AdminController
{
    //private $qiniu='http://ogm9hltgr.bkt.clouddn.com/';
    /**
     * @Route("/upload/{page}",defaults={"page" = 1},name="upload_")
     */
    public function uploadAction(Request $request,$page)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("AcmeMinsuBundle:MskAdvsImages");
        $qb = $repo->createQueryBuilder('a');
        $qb->select('count(a)');
        $qb->where('a.advs_image_sort_id = 5');
        $total = $qb->getQuery()->getSingleScalarResult();

        $pageSize = 10;
        $startPage = ($page - 1) * $pageSize;
        $totalPage = ceil($total / $pageSize);
        $arr['total'] = $total;
        $arr['pageSize'] = $pageSize;
        $arr['totalPage'] = $totalPage;

        $pageQuery = $em->createQuery(
            "select p.advs_image_path,p.advs_admin_id from AcmeMinsuBundle:MskAdvsImages p
              where p.advs_image_sort_id = '5' ORDER BY p.advs_image_sort_num DESC"
        )->setFirstResult($startPage)
            ->setMaxResults($pageSize);
        $paginate = $pageQuery->getResult();

        $query = $em->createQuery(
            "select p.id,p.advs_image_sort_num,p.advs_is_default,p.advs_text from AcmeMinsuBundle:MskAdvsImages p
              where p.advs_image_sort_id = '5' ORDER BY p.advs_image_sort_num DESC"
        )->setFirstResult($startPage)
            ->setMaxResults($pageSize);
        $sortNum = $query->execute();

         $adminIdQuery = $em->createQuery(
             "SELECT a.advs_admin_id from AcmeMinsuBundle:MskAdvsImages a
               WHERE a.advs_image_sort_id = '5' ORDER BY a.advs_image_sort_num DESC"
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

        return $this->render("AcmeMinsuBundle:Default:advsImage.html.twig",
                array('arr' => $arr)
            );
    }

    /**
     * @Route("/advsimghandle",name="advsimghandle_")
     */
    public function imghandleAction(Request $request)
    {   
        $advsImageSortId = 5;
        $inputName = "imageUpload";
        $session = $request->getSession();
        $adminUserId = $session->get('adminUserId');
        $adminUserId = trim(addslashes($adminUserId));     
        if ($newImageName=self::QiniuUpload($_FILES[$inputName]['tmp_name'],'index_banner')) {
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
           // $response = $this->forward("AcmeMinsuBundle:advsImage:upload",array('page' => 1));
           // return $response;
            return $this->redirect($this->generateUrl('upload_'));
        }else{
            return new JsonResponse("6");
        }
    }

    /**
     * @Route("/imgdelete",name="imgdelete_")
     */
    function imgdeleteAction (Request $request)
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
     * @Route("/sortchange",name="sortchange_")
     */
    function sortchangeAction(Request $request)
    {
        $sortNum = $request->get("sortNum");
        $imgSrc = $request->get('src');
        $imgName = substr(strrchr($imgSrc,'/'),1);
        $imgName = trim(addslashes($imgName));

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "update AcmeMinsuBundle:MskAdvsImages p set p.advs_image_sort_num = '{$sortNum}' where p.advs_image_path = '{$imgName}'"
        );

        if ($query->execute()) {
            return new JsonResponse('1');
        } else {
            return new JsonResponse('2');
        }
    }
    
    //20160711
    
    
    
    /**
     * @Route("/changeurl",name="changeurl_")
     */
    function changeurlAction(Request $request)
    {
    	$url = $request->get("url");
    	$id = $request->get('id');
    
    	$em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery(
    			"update AcmeMinsuBundle:MskAdvsImages p set p.advs_text = '{$url}' where p.id = '{$id}'"
    	);
    
    	if ($query->execute()) {
    		return new JsonResponse('1');
    	} else {
    		return new JsonResponse('2');
    	}
    }

    /**
     * @Route("/setdefault",name="setdefault_")
     */
    function setdefaultAction (Request $request) {
        $imgSrc = $request->get('src');
        $imgName = substr(strrchr($imgSrc,'/'),1);

        $em = $this->getDoctrine()->getManager();
        $getDefaultQry = $em->createQuery(
            "select p.id from AcmeMinsuBundle:MskAdvsImages p WHERE p.advs_is_default = 1 AND p.advs_image_sort_id = 1"
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
     * @Route("/dltdefault",name="dltdefault_")
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

































