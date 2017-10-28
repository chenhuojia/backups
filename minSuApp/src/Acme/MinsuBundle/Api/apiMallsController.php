<?php
/**
 * Created by PhpStorm.
* User: Administrator
* Date: 2016-10-9
* Time: 10:00
*/
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\MskAdvsImageSort;
use Acme\MinsuBundle\Entity\MskAdvsImag;
use Qiniu\Auth;
use Acme\MinsuBundle\Common\CommonController;
class apiMallsController extends CommonController
{   
    
    public function __construct(){
    
    }
    
    
    /**
     * @Route("/advBanner", name="advBanner_")
     */
    public function advBannerAction(){      
         $data = $this->conn()->createQueryBuilder ()
                ->select("p.id,p.advs_text,p.advs_image_path,p.advs_url_path")
              //->select('p.*')                                               
                ->from('msk_advs_images','p')  
                ->leftJoin('p','msk_advs_image_sort','m','p.advs_image_sort_id = m.id')
                //->where("p.advs_image_sort_id=".$sort['id'])
                ->where("m.sort_type='mall'")
                ->execute()->fetchAll();    
        if ($data){
            foreach ($data as $k=>$v){
                //$data[$k]['advs_image_path']=$this->getParameter('app_qiniu_imgurl').$v['advs_image_path'];
            }
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
        
    }
    
    /**
     * @Route("/mallcatlist", name="mallcatlist_")
     */
    public function mallcatlistAction(){
        //$conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $data = $this->conn()->createQueryBuilder ()
                ->select("cat_id,name,logo")
                ->from('msk_mall_category')
                ->where("parent_id=0")
                ->execute()->fetchAll();
        return new JsonResponse($data);
    }
    
    /**
     * @Route("/mallgoodslist", name="mallgoodslist_")
     */
    public function mallgoodslistAction(){
        $offset=isset($_POST['offset'])?$_POST['offset']:0; 
        $type=isset($_POST['type'])?$_POST['type']:1;
        $where = 'p.is_on_sale=1';
        switch ($type){
            case 1:
                break;
            case 2:
                $where .=' and p.is_hot=1';
                break;
            case 3:
                $where .=' and p.is_new=1';
                break;
            default:return new JsonResponse('参数错误');
        }
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $query = $conn->createQueryBuilder ()
                ->select("p.goods_id,p.goods_name,p.market_price,p.shop_price,p.sales_sum,mi.image_url")
                ->from('msk_mall_goods','p')
                ->leftJoin('p','msk_goods_images','mi','p.goods_id=mi.goods_id')               
                ->where('mi.type=1',$where)
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->groupBy('p.goods_id')
                ->orderBy('p.on_time','desc')
                ->execute();
        $conn=$query->fetchAll();
        foreach ($conn as $k=>$v){
            //$conn[$k]['image_url']=$this->getParameter('app_qiniu_imgurl').$v['image_url'];
        }
        return new JsonResponse($conn);
    }

    
    /**
     * @Route("/searchgoods", name="searchgoods_")
     */
    public function searchgoodsAction(){
        $mess=null;
        $msg='desc';
        $order='p.on_time';
        $offset=isset($_POST['offset'])?$_POST['offset']:0;
        $type=isset($_POST['type'])?$_POST['type']:1;
        $cat_id=isset($_POST['cat_id'])?$_POST['cat_id']:0;
        $goods_name=isset($_POST['goods_name'])?$_POST['goods_name']:0;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        if ($goods_name){
            $mess='p.keywords like '."'%{$goods_name}%'";
            $hot=$conn->createQueryBuilder()
                ->select("*")
                ->from('msk_hot_keyword')
                ->where("keyword like '%{$goods_name}%'")
                ->execute()
                ->fetch();
            if ($hot){
                $hot=$conn->createQueryBuilder()
                ->update("msk_hot_keyword")
                ->set('heat',$hot['heat']+1)
                ->where('id='.$hot['id'])
                ->execute();
            }else{
                $conn->insert('msk_hot_keyword',array('keyword'=>$goods_name));
            } 
            
        } 
        if ($cat_id) $mess='c.cat_id='.$cat_id;
        switch ($type){
            case 1:
                break;
            case 2:
                $order='p.sales_sum';
                break;
            case 3:
                $order='p.comment_count';
                break;
            case 4:
                $order='p.shop_price';
                break;
            case 5:
                $order='p.shop_price';
                $msg='asc';
                break;
            default:return new JsonResponse(false);
        }
        $query = $conn->createQueryBuilder ()
                    ->select("p.goods_id,p.goods_name,p.market_price,p.shop_price,p.sales_sum,mi.image_url")
                    ->from('msk_mall_goods','p')
                    ->leftJoin('p','msk_goods_images','mi','p.goods_id=mi.goods_id')
                    ->leftJoin('p','msk_mall_category','c','p.cat_id=c.cat_id')
                    ->where('mi.type=1',$mess,'p.is_on_sale=1')
                    ->setMaxResults(10)
                    ->setFirstResult($offset)
                    ->orderBy($order,$msg)
                    ->execute();
        $data=$query->fetchAll();
        if ($data){
            return new JsonResponse($data);
        }else return new JsonResponse(false); 
        
    }
    
    /**
     * @Route("/searchgood", name="searchgood_")
     */
    public function searchgoodAction(){
        $offset=isset($_POST['offset'])?$_POST['offset']:0;
        $goods_name=$_POST['goods_name'];
        $msg='desc';
        $order='p.on_time';
        if (isset($_POST['sales'])){$order='p.sales_sum';}
        if (isset($_POST['comment'])){$order='p.comment_count';}
        if (isset($_POST['price'])){
            $order='p.shop_price';
            if ($_POST['price']==2){
                $msg='desc';
            }elseif ($_POST['price']==1){
                $msg='asc';
            }
        }
        $cat_id=$_POST['cat_id'];
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $query = $conn->createQueryBuilder ()
        ->select("p.goods_id,p.goods_name,p.market_price,p.shop_price,p.sales_sum,mi.image_url")
        ->from('msk_mall_goods','p')
        ->leftJoin('p','msk_goods_images','mi','p.goods_id=mi.goods_id')
        ->leftJoin('p','msk_mall_category','c','p.cat_id=c.cat_id')
        ->where('mi.type=1','c.cat_id='.$cat_id,'p.is_on_sale=1')
        ->setMaxResults(10)
        ->setFirstResult($offset)
        ->orderBy($order,$msg)
        ->execute();
        $conn=$query->fetchAll();
        return new JsonResponse($conn);
    }
    
    /**
     * @Route("/goodsDet", name="goodsDet_")
     */
    public function goodsDetAction(){
        $goods_id=$_POST['goods_id'];
        $conn = $this->getDoctrine ()->getManager ()->getConnection();
        $query = $conn->createQueryBuilder ()
                ->select("p.goods_id,p.goods_name,p.market_price,p.is_free_shipping,p.shop_price,p.sales_sum,p.store_count,p.comment_count,s.shop_id,s.shop_name,s.shop_logo,s.on_goods as shop_goods")
                ->from('msk_mall_goods','p')
                ->leftJoin('p','msk_shop_goods','mi','p.goods_id=mi.goods_id')
                ->leftJoin('mi','msk_shop','s','mi.shop_id=s.shop_id')
                ->where('p.goods_id='.$goods_id)
                ->execute();
        $data=$query->fetch();  
        if ($data['is_free_shipping']==0){
           $stock= $conn->createQueryBuilder ()
                            ->select("stock")
                            ->from('msk_goods_shipping')
                            ->where('goods_id='.$goods_id)
                            ->execute()->fetch();
           $data['stock']=$stock['stock'];
        }else $data['stock']=0;

        $comment=$conn->createQueryBuilder ()
            ->select("avg(goods_rank) as goods_avg")
            ->from('msk_comment')
            ->where('goods_id='.$goods_id,'parent_id=0')
            ->execute()->fetch();
        if ($comment['goods_avg']==0){
            $data['goods_raise']="100%";
        }else{
            $data['goods_raise']=($comment['goods_avg']/4)>1?"100%":(bcdiv($comment['goods_avg'], 4,2)*100)."%";
        }
        $data['goods_image']=$conn->createQueryBuilder ()
                            ->select("image_url")
                            ->from('msk_goods_images')
                            ->where('goods_id='.$goods_id)
                            ->execute()->fetchAll();
        return new JsonResponse($data);
    }
    
    
    /**
     * @Route("/getGoodSpec", name="getGoodSpec_")
     */
    public function getGoodSpec(Request $request){
        $goods_id=$request->get('goods_id');
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        if(!empty($goods_id)){
            $data=$conn->createQueryBuilder ()
                ->select("id,name")
                ->from('msk_goods_spec')
                ->where('goods_id='.$goods_id)
                ->execute()
                ->fetchAll();
            if ($data){
                foreach ($data as $k =>$v){
                  $data[$k]['sub']=$conn->createQueryBuilder ()
                                    ->select("id as spec_id,`key_name`,price,store_count")
                                    ->from('msk_spec_goods_price')
                                    ->where('goods_id='.$goods_id,'type_id ='.$v['id'])
                                    ->execute()
                                    ->fetchAll();  
                }
                return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
            }
        }
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }
    
    /**
     * @Route("/goodsComment", name="goodsComment_")
     */
    public function goodsCommentAction(){
        $offset=isset($_POST['offset'])?$_POST['offset']:0;
        $goods_id=$_POST['goods_id'];
        $msg=null;
        if (isset($_POST['img']) && $_POST['img']==1){
            $msg='p.is_img=1';
        }
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $data=$conn->createQueryBuilder ()
                ->select("p.comment_id,p.user_id,p.username,p.goods_rank,p.content,p.is_img,p.add_time,mi.avatar")
                ->from('msk_comment','p')
                ->leftJoin('p','msk_member','mi','p.user_id=mi.id')
                ->where('p.goods_id='.$goods_id,'p.parent_id=0',$msg)
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->orderBy('p.add_time','desc')
                ->execute()
                ->fetchAll();
        foreach ($data as $k=>$v){
            $data[$k]['add_time']=date('m月d日',$data[$k]['add_time']);
            $data[$k]['sub']=$conn->createQueryBuilder ()
                                      ->select("comment_id,username,content,FROM_UNIXTIME(add_time, '%m月%d日 ') NewAddTime")
                                      ->from('msk_comment')
                                      ->where('parent_id='.$v['comment_id'])
                                      ->orderBy('add_time','desc')
                                      ->execute()
                                      ->fetchAll();
            if ($v['is_img']==1){
                $data[$k]['image']=$conn->createQueryBuilder ()
                                      ->select("img")
                                      ->from('msk_comment_image')
                                      ->where('comment_id='.$v['comment_id'])
                                      ->execute()
                                      ->fetchAll();
            }else{
                $data[$k]['image']=array();
            } 
        }
        return new JsonResponse($data);
    }
    
    
    /**
     * @Route("/getGoodNum", name="getGoodNum_")
     */
    public function getGoodNum(Request $request){
        $key=$request->get('key',0);
        $goods_id=$request->get('goods_id');
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        if(!empty($key)){
            $data=$conn->createQueryBuilder ()
                    ->select("price,store_count")
                    ->from('msk_spec_goods_price')
                    ->where('goods_id='.$goods_id,'`key` like'."'%$key%'")
                    ->execute()
                    ->fetch();
        }else{
            $data=$conn->createQueryBuilder ()
                    ->select("shop_price,store_count")
                    ->from('msk_mall_goods')
                    ->where('goods_id='.$goods_id)
                    ->execute()
                    ->fetch();
        }
        if ($data){
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }

    /**
     * @Route("/minsu", name="minsu_")
     */
    public function minsuAction(){
        //include ('vendor/php-sdk/autoload.php');
        require '../vendor/php-sdk/autoload.php';
        // 寮曞叆閴存潈绫�
        //require_once 'vendor/php-sdk/src/Qiniu/Auth.php';  // 闇�瑕佸～鍐欎綘鐨� Access Key 鍜� Secret Key
         $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
        $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC'; 
        
        
       /* $accessKey = 'qLnL64pg3KZ2JHWC2fXm-eaqS31wIt7fNKeQVviX';
        $secretKey = 'ehJe2v2kHDGpUHGNWYi7Otxy0X45d-ZAHbVu6UKI';*/
        
        // 鏋勫缓閴存潈瀵硅薄
        $auth = new Auth($accessKey, $secretKey);
        //return new JsonResponse($auth);
        // 瑕佷笂浼犵殑绌洪棿
        $bucket = 'minsu2';
        //$bucket = 'huojia';
        // 鐢熸垚涓婁紶 Token
        $token = $auth->uploadToken($bucket);
        if ($token){
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$token));
        }
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }   

    /**
     * @Route("/minsuprivacy", name="minsuprivacy_")
     */
    public function minsuprivacyAction(){
        include ('../vendor/php-sdk/autoload.php');
        // 寮曞叆閴存潈绫�
        //require_once 'vendor/php-sdk/src/Qiniu/Auth.php';  // 闇�瑕佸～鍐欎綘鐨� Access Key 鍜� Secret Key
        $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
        $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC'; 
        // 鏋勫缓閴存潈瀵硅薄
        $auth = new Auth($accessKey, $secretKey);
        // 瑕佷笂浼犵殑绌洪棿
        $bucket = 'minsuprivacy';
         
        // 鐢熸垚涓婁紶 Token
        $token = $auth->uploadToken($bucket);
        if ($token){
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$token));
        }
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    
    }
    
    /**
     * @Route("/a", name="a_")
     */
    public function aAction(){
        include ('../vendor/php-sdk/autoload.php');
        // 寮曞叆閴存潈绫�
        //require_once 'vendor/php-sdk/src/Qiniu/Auth.php';  // 闇�瑕佸～鍐欎綘鐨� Access Key 鍜� Secret Key
        $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
        $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
         
        // 鏋勫缓閴存潈瀵硅薄
        $auth = new Auth($accessKey, $secretKey);
        // 瑕佷笂浼犵殑绌洪棿
        $bucket = 'minsuprivacy';
         
        // 鐢熸垚涓婁紶 Token
        $token = $auth->uploadToken($bucket);
        if ($token){
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$token));
        }
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    
    }
    
     /**
     * @Route("/hotgoodslist", name="hotgoodslist_")
     */
    public function hotgoodslistAction(){
        $offset=isset($_POST['offset'])?$_POST['offset']:0;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $query = $conn->createQueryBuilder ()
        ->select("p.goods_id,p.goods_name,p.market_price,p.shop_price,p.sales_sum,mi.image_url")
        ->from('msk_hot_goods','h')
        ->leftJoin('h','msk_mall_goods','p','h.goods_id=p.goods_id')
        ->leftJoin('h','msk_goods_images','mi','h.goods_id=mi.goods_id')
        ->where('mi.type=1','p.is_on_sale=1')
        ->setMaxResults(10)
        ->setFirstResult($offset)
        ->orderBy('p.on_time','desc')
        ->execute();
        $conn=$query->fetchAll();
        return new JsonResponse($conn);
    }
    
     /**
     * @Route("/newgoodslist", name="newgoodslist_")
     */
    public function newgoodslistAction(){
        $offset=isset($_POST['offset'])?$_POST['offset']:0;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $query = $conn->createQueryBuilder ()
        ->select("p.goods_id,p.goods_name,p.market_price,p.shop_price,p.sales_sum,mi.image_url")
        ->from('msk_new_goods','h')
        ->leftJoin('h','msk_mall_goods','p','h.goods_id=p.goods_id')
        ->leftJoin('h','msk_goods_images','mi','h.goods_id=mi.goods_id')
        ->where('mi.type=1','p.is_on_sale=1')
        ->setMaxResults(10)
        ->setFirstResult($offset)
        ->orderBy('p.on_time','desc')
        ->execute();
        $conn=$query->fetchAll();
        return new JsonResponse($conn);
    }
    
    /**
     * @Route("/recommendshops", name="recommendshops_")
     */
    public function recommendshopsAction(){
        $offset=isset($_POST['offset'])?$_POST['offset']:0;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $query = $conn->createQueryBuilder ()
        ->select("s.shop_id,s.shop_name,s.shop_logo,s.shop_goods,s.on_goods,s.shop_address,s.createtime,s.stock")
            ->from('msk_recommend_shop','p')
            ->leftJoin('p','msk_shop','s','p.shop_id=s.shop_id')
            ->where('s.is_show=1')
            ->setMaxResults(10)
            ->setFirstResult($offset)
            ->orderBy('s.createtime','desc')
            ->execute();
        $conn=$query->fetchAll();
        return new JsonResponse($conn);
    }
    
    /**
     * @Route("/randhotgoods", name="randhotgoods_")
     */
    public function randhotgoodsAction(){
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $query = $conn->createQueryBuilder ('p')
        ->select("p.goods_id,p.goods_name,p.market_price,p.shop_price,p.sales_sum,mi.image_url")
        ->from('msk_hot_goods','h')
        ->leftJoin('h','msk_mall_goods','p','h.goods_id=p.goods_id')
        ->leftJoin('h','msk_goods_images','mi','h.goods_id=mi.goods_id')
        ->where('mi.type=1','p.is_on_sale=1')
        ->setMaxResults(3)
        ->orderBy('rand()')
        ->execute();
        $conn=$query->fetchAll();
        return new JsonResponse($conn);
    }
    
    
    /**
     * @Route("/hotKeyWord", name="hotKeyWord_")
     */
    public function hotKeyWordAction(){
        $conn=$this->getDoctrine()->getManager()->getConnection();
        $data=$conn->createQueryBuilder()
        ->select('keyword')
        ->from('msk_hot_keyword')
        ->setMaxResults(10)
        ->orderBy('heat','desc')
        ->execute()
        ->fetchAll();
        if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }
}