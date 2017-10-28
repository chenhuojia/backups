<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-5-30
 * Time: 14:08
 */
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\MskAdvsImageSort;
use Acme\MinsuBundle\Entity\MskAdvsImag;

use Acme\MinsuBundle\Common\CommonController;

class apiAdvsIndexController extends CommonController
{
    /**
     * @Route("/apiadvsindeximg", name="advsindeximg_")
     */
    public function advsindeximgAction(Request $request)
    {
        $imgStatus = $request->get('img');
        $imgPath = $this->getParameter('image_path');
        $host = $this->getParameter('host');
        $imgUrl = $host . $imgPath;
        $em = $this->getDoctrine()->getManager();
        $img = array();
        $message['status'] = '0';
        $message['error'] = '1';
        $message['message'] = '';

        $defaultImgQry = $em->createQuery(
            "select p.advs_image_path,p.advs_admin_id, p.advs_text from AcmeMinsuBundle:MskAdvsImages p WHERE 
              p.advs_image_sort_id = :sortId AND p.advs_is_default = 1"
        );

        $imgQry = $em->createQuery(
            "select p.advs_image_path,p.advs_admin_id, p.advs_text from AcmeMinsuBundle:MskAdvsImages p WHERE 
              p.advs_image_sort_id = :sortId AND p.advs_is_default = 0"
        );

        $imgSortQry = $em->createQuery(
            "select p.sort_type from AcmeMinsuBundle:MskAdvsImageSort p WHERE p.id = :sortId"
        );

        if ($imgStatus == 0) {
            $defaultImgQry->setParameter('sortId', 1);
            $imgQry->setParameter('sortId', 1);
            $imgSortQry->setParameter('sortId', 1);

            $imgSortRes = $imgSortQry->execute();
            if (!empty($imgSortRes)) {
                $sortType = $imgSortRes[0]['sort_type'];
            } else {
                $sortType = 'index';
            }

            $defaultImgRes = $defaultImgQry->execute();
            if ($defaultImgRes) {
                $defaultImg['img'] =/*  $imgUrl . "$sortType/" . $defaultImgRes[0]['advs_admin_id'] . "/" . */ $defaultImgRes[0]['advs_image_path'];
                $defaultImg['advs_text'] = $defaultImgRes[0]['advs_text'];
                $imgQry->setMaxResults(4);
                $imgRes = $imgQry->execute();

                if (!empty($imgRes)) {
                    $imgResLenth = count($imgRes);
                    for ($i = 0; $i < $imgResLenth; $i++) {
                        if ($imgRes[$i]['advs_text'] == null) {
                            $imgRes[$i]['advs_text'] = 'null';
                        }
                        $imgRes[$i]['img'] = /* $imgUrl . "$sortType/" . $imgRes[$i]['advs_admin_id'] . "/" . */ $imgRes[$i]['advs_image_path'];
                        unset($imgRes[$i]['advs_image_path']);
                        unset($imgRes[$i]['advs_admin_id']);
                    }
                    array_unshift($imgRes, $defaultImg);

                    return new JsonResponse($imgRes);
                } else {
                    $message['message'] = 'not find images!';
                    return new JsonResponse($message);
                }

            } else {
                $imgQry->setMaxResults(5);
                $imgRes = $imgQry->execute();
                if (!empty($imgRes)) {
                    $imgResLenth = count($imgRes);
                    for ($i = 0; $i < $imgResLenth; $i++) {
                        if ($imgRes[$i]['advs_text'] == null) {
                            $imgRes[$i]['advs_text'] = 'null';
                        }
                        $imgRes[$i]['img'] =$this->getParameter('app_qiniu_imgurl').$imgRes[$i]['advs_image_path'];
                        unset($imgRes[$i]['advs_image_path']);
                        unset($imgRes[$i]['advs_admin_id']);
                    }
                    return new JsonResponse($imgRes);
                } else {
                    $message['message'] = 'not find images!';
                    return new JsonResponse($message);
                }
                
            }
        } elseif ($imgStatus == 1) {
            $defaultImgQry->setParameter('sortId', 2);
            $imgQry->setParameter('sortId', 2);
            $imgSortQry->setParameter('sortId', 2);

            $imgSortRes = $imgSortQry->execute();
            foreach ($imgSortRes as $v) {
                $sortType = $v['sort_type'];
            }

            $defaultImgRes = $defaultImgQry->execute();
            if ($defaultImgRes) {
                $defaultImg['img'] =$this->getParameter('app_qiniu_imgurl').$defaultImgRes[0]['advs_image_path'];
                $defaultImg['advs_text'] = $defaultImgRes[0]['advs_text'];
                if ($defaultImg['advs_text'] == null) {
                    $defaultImg['advs_text'] = 'null';
                }
                $imgQry->setMaxResults(2);
                $imgRes = $imgQry->execute();
                if (!empty($imgRes)) {
                    $imgResLenth = count($imgRes);
                    for ($i = 0; $i < $imgResLenth; $i++) {
                        if ($imgRes[$i]['advs_text'] == null) {
                            $imgRes[$i]['advs_text'] = 'null';
                        }
                        $imgRes[$i]['img'] = $this->getParameter('app_qiniu_imgurl').$imgRes[$i]['advs_image_path'];
                        unset($imgRes[$i]['advs_image_path']);
                        unset($imgRes[$i]['advs_admin_id']);
                    }
                    array_unshift($imgRes, $defaultImg);
                    return new JsonResponse($imgRes);
                } else {
                    $message['message'] = 'not find images!';
                    return new JsonResponse($message);
                }
            } else {
                $imgQry->setMaxResults(3);
                $imgRes = $imgQry->execute();
                if (!empty($imgRes)) {
                    $imgResLenth = count($imgRes);
                    for ($i = 0; $i < $imgResLenth; $i++) {
                        if ($imgRes[$i]['advs_text'] == null) {
                            $imgRes[$i]['advs_text'] = 'null';
                        }
                        $imgRes[$i]['img'] = $this->getParameter('app_qiniu_imgurl').$imgRes[$i]['advs_image_path'];
                        unset($imgRes[$i]['advs_image_path']);
                        unset($imgRes[$i]['advs_admin_id']);
                    }
                    return new JsonResponse($imgRes);
                } else {
                    $message['message'] = 'not find images!';
                    return new JsonResponse($message);
                }
            }
        } else {
            $message['message'] = 'parameter not right!';
            return new JsonResponse($message);
        }
    }

    /**
     * @Route("apiEntryImage", name="apiEntryImage_")
     */
    public function apiEntryImageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $path = $this->getParameter('advs_img_entry_path_url');

        $qry = $em->createQuery(
            "select p.advs_text, p.advs_image_path, p.advs_admin_id from AcmeMinsuBundle:MskAdvsImages p
            WHERE p.advs_image_sort_id = 4 AND p.advs_is_default = 1 ORDER BY p.advs_image_sort_num DESC"
        )
            ->setMaxResults(1);
        $qryRes = $qry->execute();
        if (!empty($qryRes)) {
            $qryResLenth = count($qryRes);
            for ($i = 0; $i < $qryResLenth; $i++) {
                if ($qryRes[$i]['advs_text'] == null) {
                    $qryRes[$i]['advs_text'] = 'null';
                }
                $qryRes[$i]['image'] =$this->getParameter('app_qiniu_imgurl').$qryRes[$i]['advs_image_path'];
                unset($qryRes[$i]['advs_image_path']);
                unset($qryRes[$i]['advs_admin_id']);
            }

            return new JsonResponse($qryRes);
        } else {
            $massage['state'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found image!';
            return new JsonResponse($massage);
        }
    }
    
       
    /**
     * @Route("apiHotGoods", name="apiHotGoods_")
     */
    public function apiHotGoodsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $path = $this->getParameter('app_qiniu_imgurl');
    
        $data = $this->conn()->createQueryBuilder()
        ->select('advs_text,advs_image_path,advs_url_path')
        ->from('msk_advs_images')
        ->where('advs_image_sort_id = 5','advs_is_default = 1')
        ->orderBy('advs_image_sort_num','desc')
        ->setMaxResults(5)->execute()->fetchAll();
        if (!empty($data)){
           foreach ($data as $k=>$v){
               $data[$k]['advs_image_path']=$path.$v['advs_image_path'];
           }
           $this->Send(200,'success',$data); 
        }
        $this->Send(202,'暂无数据');
    }
        
}






























