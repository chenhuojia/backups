<?php 
namespace Acme\MinsuBundle\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class AdminController extends Controller{
    
    protected $qiniu='http://ogm9hltgr.bkt.clouddn.com/'; //七牛域名    
    /**
     * 分页函数
     * **/
    protected function pageHtml($totalPage,$url,$page,$prePage,$nextPage,$type=1){
        $html ="<div class='page-dis'><div class='meneame'><a href=".$url."?page=1&type=".$type.">首页</a>";
        $html .="<a href=".$url."?page=$prePage&type=$type>< </a>";
        if($totalPage >= 7){
            if($page <= 4){
                for($i=1;$i<7;$i++){
                    $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
                }
            }elseif ($page > ($totalPage - 4)){
                for($i=$totalPage-7;$i<$totalPage;$i++){
                    $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
                }
            }else{
                for($i=($page-3);$i<($page+3);$i++){
                    $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
                }
            }
        }else{
            if ($totalPage!=0){
                for($i=1;$i<=$totalPage;$i++){
                    $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
                }
            }
        }
        $html .="<a href=".$url."?page=$nextPage&type=$type>></a>";
        $html .="<a href=".$url."?page=$totalPage&type=$type>尾页</a></div></div>";
        return $html;
    }

	/**
	 * 七牛上传
	 * **/
    protected  function QiniuUpload($filename=0,$prefix=0){
        require '../vendor/php-sdk/autoload.php';
        $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
        $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
        $bucket = 'minsu2';
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        $filePath = $filename;
        $key = $prefix."_".time().mt_rand(1, 100).'.jpg';
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return false;
        } else {
            return $ret['key'];
        }
    }
	
}