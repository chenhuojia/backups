<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-7-22
 * Time: 14:36
 */
namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MinsuBundle\Entity\MallGoods;
use Acme\MinsuBundle\Entity\GoodsSpec;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Acme\MinsuBundle\Common\CommonController;

class MallGoodsController extends CommonController
{
    /**
     * 订单列表
     * @Route("/mallGoodsList",name="_mall_goods_list")
     * @Template("AcmeMinsuBundle:Goods:GoodsList.html.twig")
     */
    public function mallGoodsList(){
        $page  = isset($_GET['page'])?$_GET['page']:0;
        if (empty($page)) {
            $page = 1;
        }
        $where=array();
        $pageSize = $this->getParameter('pagesize');
        if ($_POST){  
            $cat_id=isset($_POST['cat_id'])?$_POST['cat_id']:0;
            $is_on_sale=isset($_POST['is_on_sale'])?$_POST['is_on_sale']:0;
            $is_new=isset($_POST['is_new'])?$_POST['is_new']:0;
            $keyword=isset($_POST['keyword'])?$_POST['keyword']:0;
            $find='';
            if ($cat_id){
                $where['p.cat_id']=$cat_id;
            }if ($is_on_sale){
                if ($is_on_sale==1) $where['p.is_on_sale']=1;
                if ($is_on_sale==2) $where['p.is_on_sale']=0;
            }
            if ($is_new){
                if ($is_new==1) $where['p.is_new']=1;
                if ($is_new==2) $where['p.is_recommend']=1;
            }if ($keyword){
                $where['p.keywords']="like '%$keyword%'";
            }
            
            if (!empty($where)){
                
                foreach ($where as $k=>$v){
                  if ($k=='p.keywords'){
                      $find .=' and '.$k.' '.$v;  
                  }else{
                      $find .=' and '.$k.'='.$v;
                  }  
                }
            }
            $find=substr($find,5);
            if ($find){
                $totalNumRes=$this->conn()->createQueryBuilder()
                ->select('count(p.goods_id) as total')
                ->from('msk_mall_goods', 'p' )
                ->where($find)
                ->execute()->fetch();
                $data=$this->conn()->createQueryBuilder()
                    ->select('p.*,c.*')
                    ->from('msk_mall_goods','p')
                    ->leftJoin('p','msk_mall_category','c','p.cat_id=c.cat_id')
                    ->where($find)
                    ->execute()
                    ->fetchAll();
            }else{
                $totalNumRes=$this->conn()->createQueryBuilder()
                    ->select('count(p.goods_id) as total')
                    ->from('msk_mall_goods', 'p' )
                    ->execute()->fetch();
                $data=$this->conn()->createQueryBuilder()
                    ->select('p.*,c.*')
                    ->from('msk_mall_goods','p')
                    ->leftJoin('p','msk_mall_category','c','p.cat_id=c.cat_id')
                    ->execute()
                    ->fetchAll();
            }             
        }else{
            $totalNumRes=$this->conn()->createQueryBuilder()
                ->select('count(p.goods_id) as total')
                ->from('msk_mall_goods', 'p' )
                ->execute()->fetch();
            $data=$this->conn()->createQueryBuilder()
                ->select('p.*,c.*')
                ->from('msk_mall_goods','p')
                ->leftJoin('p','msk_mall_category','c','p.cat_id=c.cat_id') 
                ->execute()
                ->fetchAll();
        } 
        $cate=$this->conn()->createQueryBuilder()
              ->select('p.*')
              ->from('msk_mall_category','p')
              ->execute()
              ->fetchAll();
        $totalNum = $totalNumRes['total'];
        $totalPage = ceil($totalNum / $pageSize);
        if ($totalPage != 0 && $page > $totalPage) {
            $page = $totalPage;
        }
        $startPage = ($page - 1) * $pageSize;
         
        $prePage = $page - 1;
        $nextPage = $page + 1;
        return array(
            'goods'=>$data,
            'cate'=>$cate,
            'totalPage' => $totalPage,
            'page' => $page,
            'prePage' => $prePage,
            'nextPage' => $nextPage,
            'where'=>$where
        );
       
    }

    /**
     * 商品详情
     * @Route("/goodsDetail",name="_goods_detail ")
     * @Template("AcmeMinsuBundle:Goods:GoodsList.html.twig")
     */
    public function goodsDetail(){
        
    }
    
    
    /**
     * 
     * @Route("/changeOnSale",name="_change_on_sale") 
     */
    public function changeOnSale(){
        $goods_id=$_POST['goods_id'];
        $val=$_POST['val'];
        $data=$this->conn()->createQueryBuilder()
                ->update('msk_mall_goods')
                ->set('is_on_sale',$val)
                ->set('last_update',time())
                ->where('goods_id='.$goods_id)
                ->execute();
        if ($data){
            $shop=$this->conn()->createQueryBuilder()
                ->select('*')
                ->from('msk_shop','p')
                ->leftJoin('p','msk_shop_goods','m','p.shop_id=m.shop_id')
                ->where('m.goods_id='.$goods_id)
                ->execute()->fetch();
            $this->conn()->createQueryBuilder()
                ->update('msk_shop_goods','p')
                ->set('p.is_on_sale',$val)
                ->where('p.goods_id='.$goods_id)
                ->execute();
            if ($val==1){
                if ($shop['under_goods']>0){
                    $this->conn()->createQueryBuilder()
                        ->update('msk_shop','p')
                        ->set('p.on_goods','p.on_goods+1')
                        ->set('p.under_goods','p.under_goods-1')
                        ->where('p.shop_id='.$shop['shop_id'])
                        ->execute();
                }else{
                    $this->conn()->createQueryBuilder()
                        ->update('msk_shop','p')
                        ->set('p.on_goods','p.on_goods+1')
                        ->where('p.shop_id='.$shop['shop_id'])
                        ->execute();
                }
            }elseif ($val==0){
                if ($shop['on_goods']>0){
                    $this->conn()->createQueryBuilder()
                        ->update('msk_shop','p')
                        ->set('p.on_goods','p.on_goods-1')
                        ->set('p.under_goods','p.under_goods+1')
                        ->where('p.shop_id='.$shop['shop_id'])
                        ->execute();
                }else{
                    $this->conn()->createQueryBuilder()
                        ->update('msk_shop','p')
                        ->set('p.under_goods','p.under_goods+1')
                        ->where('p.shop_id='.$shop['shop_id'])
                        ->execute();
                }
            }
        }        
        return new JsonResponse($data);
    }
    
    /**
     *
     * @Route("/changeIsNew",name="_change_is_new")
     */
    public function changeIsNew(){
        $goods_id=$_POST['goods_id'];
        $val=$_POST['val'];
        $data=$this->conn()->createQueryBuilder()
            ->update('msk_mall_goods')
            ->set('is_new',$val)
            ->set('last_update',time())
            ->where('goods_id='.$goods_id)
            ->execute();
       return new JsonResponse($data);
    }
    
    /**
     *
     * @Route("/changeIsHot",name="_change_is_hot")
     */
    public function changeIsHot(){
        $goods_id=$_POST['goods_id'];
        $val=$_POST['val'];
        $data=$this->conn()->createQueryBuilder()
        ->update('msk_mall_goods')
        ->set('is_hot',$val)
        ->set('last_update',time())
        ->where('goods_id='.$goods_id)
        ->execute();
        return new JsonResponse($data);
    }
    
    
    /**
     *
     * @Route("/changeRecommend",name="_change_recommend")
     */
    public function changeRecommend(){
        $goods_id=$_POST['goods_id'];
        $val=$_POST['val'];
        $data=$this->conn()->createQueryBuilder()
            ->update('msk_mall_goods')
            ->set('is_recommend',$val)
            ->set('last_update',time())
            ->where('goods_id='.$goods_id)
            ->execute();
        return new JsonResponse($data);
    }
    
    
    /**
     *
     * @Route("/AddGoods",name="_add_goods")
     * @Template("AcmeMinsuBundle:Goods:AddGoods.html.twig")
     */
    public function AddGoods(Request $request){
       //echo phpinfo();die;
       $shop=self::getshoplist();
       $category=self::goodsCategoryList(1);
       //$spec=self::getSpecList();
       if ($_POST){
           //print_r($_POST);die;
           $goods_name=$request->get('goods_name');
           $shop_id=$request->get('shop_id');
           $cat_id=$request->get('cat_id');
           $shop_cat_id=$request->get('shop_cat_id');
           $shop_price=$request->get('shop_price');
           $market_price=$request->get('market_price');
           $goods_img=$request->get('goods_img',0);
           $is_free_shipping=$request->get('is_free_shipping');
           $store_count=$request->get('store_count');
           $spec_type=$request->get('spec_type');
           $goods_content=$request->get('editorValue');
           $spec_id=$request->get('spec_id');
           $spec_price=$request->get('spec_price');
           $keywords=$request->get('keywords',0);
           $key_name=$request->get('key_name');
           $spec_price=$request->get('spec_price');
           $spec_store_count=$request->get('spec_store_count');
           //if (!$goods_name) return  sfView::NONE("script>'请输入商品名称'</script>");
           $goods=new MallGoods();
           $goods->setGoods_name($goods_name);
           //$goods->setShop_id($shop_id);
           $goods->setShop_price($shop_price);
           $goods->setMarket_price($market_price);
           $goods->setCat_id($cat_id);
           if ($keywords){
               $goods->setKeywords($keywords);
           }else{
               $key=$this->conn()->createQueryBuilder()->select('*')->from('msk_mall_category')->where('cat_id='.$cat_id)->execute()->fetch();
               $goods->setKeywords($key['name']);
           } 
           $goods->setGoods_content($goods_content);
           $goods->setIs_free_shipping($is_free_shipping);
           $goods->setOn_time(time());
           $goods->setStore_count($store_count);
           $manager = $this->getDoctrine()->getManager();
           $manager->persist($goods);
           $manager->flush();
           $goods_id=$goods->getGoods_id();           
           $this->conn()->insert('msk_shop_goods',array('shop_id'=>$shop_id,'goods_id'=>$goods_id,'shop_cat_id'=>$shop_cat_id)) ;
           $this->conn()->createQueryBuilder()
                        ->update('msk_shop')
                        ->set('shop_goods','shop_goods+1')
                        ->set('on_goods','on_goods+1')
                        ->where('shop_id='.$shop_id)
                        ->execute();
           $this->conn()->createQueryBuilder()
               ->update('msk_shop_category')
               ->set('goods_num','goods_num+1')
               ->where('id='.$shop_cat_id)
               ->execute();
           if ($_FILES['coverImage']['tmp_name']){
               $cover=self::QiniuUpload($_FILES['coverImage']['tmp_name'],'mall_goods');
           }
           if ($cover){
               $this->conn()->insert('msk_goods_images',array('goods_id'=>$goods_id,'type'=>1,'image_url'=>$cover));
           }
           if ($goods_img){
               foreach ($goods_img as $v){
                   $this->conn()->insert('msk_goods_images',array('goods_id'=>$goods_id,'type'=>2,'image_url'=>$v));
               }
           }
           if (!empty($key_name)){
               $spe=new GoodsSpec();
               $spe->setGoods_id($goods_id);
               $spe->setName('型号');
               $spe->setIs_show(1);
               $manager->persist($spe);
               $manager->flush();
               $type_id=$spe->getId();
               foreach ($key_name as $k =>$v){
                   $this->conn()->insert('msk_spec_goods_price',array('type_id'=>$type_id,'goods_id'=>$goods_id,'`key`'=>'型号','key_name'=>$v,'price'=>$spec_price[$k],'store_count'=>$spec_store_count[$k]));
               }
           }
          return $this->redirectToRoute('_add_goods');        
       }
       return array('shop'=>$shop,'category'=>$category,'spec'=>0);
    }
    
    
    
    
    /**
     * @Route("/getshopcategory",name="_getshopcategory")
     */
    public function getshopcategory(Request $request){
        $shop_id=$request->get('shop_id');
        $data=$this->conn()->createQueryBuilder()
                           ->select('*')
                           ->from('msk_shop_category')
                           ->where('shop_id='.$shop_id)
                           ->execute()
                           ->fetchAll();
        return new JsonResponse($data);
    } 
    
    protected function getshoplist($shop_id=0){
        if ($shop_id){
            $data=$this->conn() ->createQueryBuilder()
                                ->select('*')
                                ->from('msk_shop')
                                ->where('is_show=1','shop_id='.$shop_id)
                                ->execute()
                                ->fetchAll();
        }else{
            $data=$this->conn() ->createQueryBuilder()
                                ->select('*')
                                ->from('msk_shop')
                                ->where('is_show=1')
                                ->execute()
                                ->fetchAll();
        }
        return $data;
    }
    
    /**
     * @Route("/getSubSpec",name="_getSubSpec")
     */
    public function getSubSpec(Request $request){
       $spec_id=$request->get('spec_id',0);
       $data=$this->conn()->createQueryBuilder()
           ->select('*')
           ->from('msk_mall_spec')
           ->where('spec_type_id='.$spec_id,'is_show=1')
           ->execute()
           ->fetchAll();
           return new JsonResponse($data);
           
    }
    
    protected function getSpecList(){
        $data=$this->conn()->createQueryBuilder()
                ->select('*')
                ->from('msk_mall_spec_type')
                ->where('is_show=1')
                ->execute()
                ->fetchAll();
         return $data;
    }
    /**
     * @Route("/AddgoodsCategory",name="AddgoodsCategory_")
     * @Template("AcmeMinsuBundle:Goods:AddGoodsCategory.html.twig")
     */
    public function AddgoodsCategory(Request $request){
        if($_POST){
            $name=$request->get('name');
            $parent_id=$request->get('parent_id');
            $is_show=$request->get('is_show');
            if (empty($_FILES['logo']['tmp_name'])){
                return new JsonResponse("选择logo");
            }
            $url=$this->QiniuUpload($_FILES['logo']['tmp_name'],'category');
            $data=array(
                'name'=>$name,
                'parent_id'=>$parent_id,
                'is_show'=>$is_show,
                'logo'=>$url,
            );
            $tmp=$this->conn()->insert('msk_mall_category',$data);
            if ($tmp){
                $go=$this->generateUrl('goodsCategoryList_');
                return $this->redirect($go);
               
            }
        }
            $cate=$this->conn()->createQueryBuilder()
                ->select('*')
                ->from('msk_mall_category')
                ->where('is_show=0')
                ->execute()
                ->fetchAll();
            return array('cate'=>$cate);
            
       
    }
    
    
    
    /**
     *
     * @Route("/goodsCategoryList",name="goodsCategoryList_")
     * @Template("AcmeMinsuBundle:Goods:goodsCategoryList.html.twig")
     */
    public function goodsCategoryList($add=0){
        if ($add){
            $data=$this->conn()->createQueryBuilder()
            ->select('*')
            ->from('msk_mall_category')
            ->where('is_show=0')
            ->execute()
            ->fetchAll();
            return $data;
        }else{
            $data=$this->conn()->createQueryBuilder()
                ->select('*')
                ->from('msk_mall_category')
                ->where('is_show=0')
                ->execute()
                ->fetchAll();
            return array('data'=>$data);
        } 
    }
    
    /**
     * @Route("/categoryIsShow",name="_category_is_show")
     */
    public function categoryIsShow(){
        $cat_id=$_POST['cat_id'];
        $val=$_POST['val'];
        $data=$this->conn()->createQueryBuilder()
            ->update('msk_mall_category')
            ->set('is_show',$val)
            ->where('cat_id='.$cat_id)
            ->execute();
        return new JsonResponse($data);
    }
    
    
   protected function QiniuUpload($filename=0,$prefix=0){
        require '../vendor/php-sdk/autoload.php';
        //require_once '../vendor/php-sdk/src/Qiniu/Storage/UploadManager.php' ;
        // 引入鉴权类
        //require_once 'vendor/php-sdk/src/Qiniu/Auth.php';  // 需要填写你的 Access Key 和 Secret Key
        $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
        $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        //return new JsonResponse($auth);
        // 要上传的空间
        $bucket = 'minsuprivacy';
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
        $filePath = $filename;
        // 上传到七牛后保存的文件名
        $key = $prefix."_".time().mt_rand(1, 100).'.jpg';
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return false;
        } else {
            return $ret['key'];
        }
    }
    
    
    /**
     *
     * @Route("/goodsInfo",name="_goods_info")
     * @Template("AcmeMinsuBundle:Goods:GoodsInfo.html.twig")
     */
    public function goodsInfo(Request $request){
        $shop=self::getshoplist();
        $category=self::goodsCategoryList(1);
        //$spec=self::getSpecList();
        print_r($shop);
        return array('shop'=>$shop,'category'=>$category,'spec'=>0);
    }
    
    protected function getGoods($goods_id){
        
    }
}





























