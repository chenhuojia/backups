<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-10-10
 * Time: 09:46
 */
namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\MinsuBundle\Entity\ShopApply;
use Acme\MinsuBundle\Entity\MallGoods;
use Doctrine\DBAL\Types\JsonArrayType;

use Acme\MinsuBundle\Common\CommonController;
class apiShopController extends CommonController
{
    public function __construct(){
    
    }
    
    /**
     * @Route("/addshop", name="addshop_")
     */
    public function addshopAction(Request $request){
        if ($_POST){
            $data=$_POST['data'];
            $data=json_decode($data,true);
            $manager = $this->getDoctrine()->getManager();
            if (!$data['member_id'])return new JsonResponse('请登陆',300);
            if (!$data['user_name'])return new JsonResponse('请填写用户真实姓名',300);
            if (!$data['phone']||!preg_match("/1[3578]{1}\d{9}$/",$data['phone']))return new JsonResponse('手机号码有误',300);
            if (!$data['shop_name'])return new JsonResponse('请填写店铺名',300);
            if (!$data['shop_address'])return new JsonResponse('请填写店铺地址',300);
            if (!$data['shop_logo'])return new JsonResponse('请上传店铺logo',300);
            if (!$data['id_card1'])return new JsonResponse('请上传身份证照片正面',300);
            if (!$data['id_card2'])return new JsonResponse('请上传身份证照片反面',300);
            if (!$data['id_card3'])return new JsonResponse('请上传手持身份证正照片',300);
            if (!$data['business_license'])return new JsonResponse('请上传营业执照',300);           
            $a=$manager->getConnection()->createQueryBuilder ()
                ->select("*")
                ->from('msk_shop_apply')
                ->where('user_id='.$data['member_id'])
                ->execute()->fetch();            
            if ($a) return new JsonResponse(array('status'=>300,'msg'=>'你已经提交了审核，不能重复提交了','result'=>''));           
            $shop_apply = new ShopApply();
            $shop_apply->setAddtime(time());
            $shop_apply->setUser_name($data['user_name']);
            $shop_apply->setIs_checked(2);
            $shop_apply->setPhone($data['phone']);
            $shop_apply->setShop_address($data['shop_address']);
            $shop_apply->setShop_logo($data['shop_logo']);
            $shop_apply->setShop_name($data['shop_name']);
            $shop_apply->setUser_id($data['member_id']);
            $shop_apply->setId_card1($data['id_card1']);
            $shop_apply->setId_card2($data['id_card2']);
            $shop_apply->setId_card3($data['id_card3']);
            $shop_apply->setBusiness_license($data['business_license']);
            $manager->persist($shop_apply);
            $manager->flush(); 
            $bool  =$shop_apply->getId(); 
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$bool));
        }     
        
    }
    
    /**
     * @Route("/getApplyStatus", name="getApplyStatus_")
     */
    public function getApplyStatusAction(){
        $user_id=$_POST['member_id'];
        $manager = $this->getDoctrine()->getManager();
        $a=$manager->getConnection()->createQueryBuilder ()
            ->select("is_checked,reply")
            ->from('msk_shop_apply')
            ->where('user_id='.$user_id)
            ->execute()->fetch();
        if ($a)return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$a));
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
        
    }

    /**
     * @Route("/updateApply", name="updateApply_")
     */
    public function updateApplyAction(){
        $data=$_POST['data'];
        $data=json_decode($data,true);
        $manager = $this->getDoctrine()->getManager();
        if (!$data['member_id'])return new JsonResponse('请登陆',300);
        if (!$data['user_name'])return new JsonResponse('请填写用户真实姓名',300);
        if (!$data['phone']||!preg_match("/1[3578]{1}\d{9}$/",$data['phone']))return new JsonResponse('手机号码有误',300);
        if (!$data['shop_name'])return new JsonResponse('请填写店铺名',300);
        if (!$data['shop_address'])return new JsonResponse('请填写店铺地址',300);
        if (!$data['shop_logo'])return new JsonResponse('请上传店铺logo',300);
        if (!$data['id_card'])return new JsonResponse('请上传身份证照片',300);
        if (!$data['business_license'])return new JsonResponse('请上传营业执照',300);
        $conn=$manager->getConnection();
        $a=$conn->createQueryBuilder ()
            ->select("is_checked,reply")
            ->from('msk_shop_apply')
            ->where('user_id='.$data['member_id'])
            ->execute()->fetch();
        if ($a){ 
            $user_name        =$data['user_name'];
            $shop_address     =$data['shop_address'];
            $shop_logo        =$data['shop_logo'];
            $business_license =$data['business_license'];
            $id_card          =$data['id_card'];
             try {
                 $conn->createQueryBuilder ()
                     ->update ('msk_shop_apply')
                     ->set('is_checked',2)
                     ->set('user_name',"'$user_name'")
                     ->set('phone',$data['phone'])
                     ->set('shop_address',"'$shop_address'")
                     ->set('shop_logo',"'$shop_logo'")
                     ->set('id_card',"'$id_card'")
                     ->set('business_license',"'$business_license'")
                     ->set('addtime',time())
                     ->set('reply',0)
                     ->where("user_id =".$data['member_id'])
                     ->execute();
                }catch (Exception $e) {
                    return new JsonResponse(array('code'=>300,'msg'=>'fales','result'=>''));
                }
                return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>''));
        }     
    }
    
    
    /**
     * @Route("/mygoods", name="mygoods_")
     */
    public function mygoodsAction(Request $request){
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $offset=isset($_POST['offset'])?$_POST['offset']:0;
        $user_id=$request->get('member_id');       
        $type=$request->get('type',1);
        $pis_on_sale='p.is_on_sale=1';
        $sis_on_sale='s.is_on_sale=1';
        if ($type==2){
            $pis_on_sale='p.is_on_sale=0';
            $sis_on_sale='s.is_on_sale=0';
        }
        $data['count'] = $conn->createQueryBuilder ()
                        ->select("shop_id,on_goods,under_goods,category_sum")
                        ->from('msk_shop')
                        ->where('user_id='.$user_id,'is_show = 0')
                        ->execute()->fetch();
        if ($data['count']){
            $query = $conn->createQueryBuilder ()
                ->select("p.goods_id,p.goods_name,p.store_count,p.shop_price,p.sales_sum,mi.image_url,p.is_free_shipping,p.on_time")
                ->from('msk_shop_goods','s')
                ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
                ->leftJoin('s','msk_goods_images','mi','s.goods_id=mi.goods_id')
                ->where('mi.type=1',$pis_on_sale,$sis_on_sale,'s.shop_id = '.$data['count']['shop_id'],'s.is_deleted=0')
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->orderBy('p.on_time','desc')
                ->execute();
            $data['goods']=$query->fetchAll();
            foreach ($data['goods'] as $k=>$v){
                if($v['is_free_shipping']==0){
                    $stock= $conn->createQueryBuilder ()
                    ->select("stock")
                    ->from('msk_goods_shipping')
                    ->where('goods_id='.$v['goods_id'])
                    ->execute()->fetch();
                    $data['goods'][$k]['stock']=$stock['stock'];
                }else $data['goods'][$k]['stock']=0;
                $data['goods'][$k]['image_url']=$this->getParameter('app_qiniu_imgurl').$v['image_url'];
                $data['goods'][$k]['on_time']=date('Y/m/d',$v['on_time']);
            } 
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else  return new JsonResponse(array('code'=>300,'msg'=>'没有店铺','result'=>''));
        
        
    }
    
    
    /**
     * @Route("/shopCat", name="shopCat_")
     */
    public function shopCatAction(Request $request){
        $shop_id=$request->get('shop_id');
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $data = $conn->createQueryBuilder ()
        ->select("id,name,shop_id,goods_num")
        ->from('msk_shop_category')
        ->where('shop_id='.$shop_id,'parent_id = 0')
        ->execute()->fetchAll();       
        if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }
    

    /**
     * @Route("/shopCatGoods", name="shopCatGoods_")
     */
    public function shopCatGoodsAction(Request $request){
        $offset=isset($_POST['offset'])?$_POST['offset']:0;
        $shop_id=$request->get('shop_id');
        $id=$request->get('id');
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $query = $conn->createQueryBuilder ()
                ->select("p.goods_id,p.goods_name,p.store_count,p.shop_price,p.sales_sum,mi.image_url,p.is_free_shipping,p.on_time")
                ->from('msk_shop_goods','s')
                ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
                ->leftJoin('s','msk_goods_images','mi','s.goods_id=mi.goods_id')
                ->where('mi.type=1','s.shop_id = '.$shop_id,'s.shop_cat_id = '.$id,'s.is_deleted=0','s.is_on_sale=1','p.is_on_sale=1')
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->orderBy('p.on_time','desc')
                ->execute();
            $data=$query->fetchAll();
            foreach ($data as $k=>$v){
                if($v['is_free_shipping']==0){
                    $stock= $conn->createQueryBuilder ()
                    ->select("stock")
                    ->from('msk_goods_shipping')
                    ->where('goods_id='.$v['goods_id'])
                    ->execute()->fetch();
                    $data[$k]['stock']=$stock['stock'];
                }else $data[$k]['stock']=0;
                $data[$k]['image_url']=$this->getParameter('app_qiniu_imgurl').$v['image_url'];
                $data[$k]['on_time']=date('Y/m/d',$v['on_time']);
            } 
        if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }
    
    
    
    /**
     * @Route("/shopDet", name="shopDet_")
     */
    public function shopDetAction(Request $request){
        $user_id=$request->get('member_id',0);
        $shop_id=$request->get('shop_id',0);
        if ($shop_id && $user_id){
            $data = $this->conn()->createQueryBuilder ()
                ->select("p.shop_id,p.shop_logo,p.shop_name,p.shop_address,p.createtime")
                ->from('msk_shop','p')
                ->where('p.shop_id='.$shop_id,'p.is_show = 1')
                ->execute()->fetch();            
             $collect=$this->conn()->createQueryBuilder()
                      ->select('*')
                      ->from('msk_shop_collect')
                      ->where('shop_id='.$shop_id,'user_id='.$user_id,'is_collect=1')
                      ->execute()
                      ->fetch();
             if ($collect){
                 $data['is_collect']=1;
             }else {
                 $data['is_collect']=0;
             } 
        }elseif ($user_id){
            $data = $this->conn()->createQueryBuilder ()
                ->select("shop_id,shop_logo,shop_name,shop_address,createtime")
                ->from('msk_shop')
                ->where('user_id='.$user_id,'is_show = 1')
                ->execute()->fetch();
        }elseif ($shop_id){
            $data = $this->conn()->createQueryBuilder ()
                ->select("p.shop_id,p.shop_logo,p.shop_name,p.shop_address,p.createtime")
                ->from('msk_shop','p')
                ->where('p.shop_id='.$shop_id,'p.is_show = 1')
                ->execute()->fetch();
            $data['is_collect']=0;
        }              
         if ($data){
             $data['shop_logo']=$this->getParameter('app_qiniu_imgurl').$data['shop_logo'];
             $data['createtime']=date('Y.m.d',$data['createtime']);
             return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
         }       
         return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }
    
    /**
     * @Route("/shopCategory", name="shopCategory_")
     */
    public function shopCategoryAction(Request $request){
        $shop_id=$request->get('shop_id');
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $data = $conn->createQueryBuilder ()
            ->select("id,name")
            ->from('msk_shop_category')
            ->where('shop_id='.$shop_id)
            ->execute()->fetchAll();
        if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
        
    }
    
    /**
     * @Route("/editShopGoods", name="editShopGoods_")
     */
    public function editShopGoodsAction(Request $request){
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $shop_id=$request->get('shop_id');
        //$user_id=$request->get('member_id');
        $type=$request->get('type',1);
        $id=$request->get('id',0);
        $goods_id=$_POST['goods_id'];
        $goods_id=json_decode($goods_id,true);
        $msg='is_deleted';
        $value=1;
        switch ($type){
            case 1:
                break;
            case 2:
                $msg='is_on_sale';
                $value=0;
                break;
            case 3:
                $msg='is_on_sale';
                break;
            case 4:
                $msg='shop_cat_id';
                $value=$id;
                break;
            default:
                return new JsonResponse(array('code'=>300,'msg'=>'操作失败','result'=>''));
                break;
        }     
        foreach ($goods_id as $k=>$v){
            $upb =$conn->createQueryBuilder ()
                ->update ('msk_shop_goods')
                ->set ($msg,$value)
                ->where("goods_id =".$v,'shop_id = '.$shop_id)
                ->execute();
        }
        if ($upb) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$upb));
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
        
    }
    
    /**
     * @Route("/shopInfo", name="shopInfo_")
     */
    public function shopInfoAction(Request $request){
        $shop_id=$request->get('shop_id');
        $type=$request->get('type',1);
        $user_id=$request->get('member_id',0);
        $offset=isset($_POST['offset'])?$_POST['offset']:0;
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $data = $conn->createQueryBuilder ()
            ->select("shop_id,shop_logo,shop_name,on_goods,visit_sum")
            ->from('msk_shop')
            ->where('shop_id='.$shop_id,'is_show = 1')
            ->execute()->fetch();
        if ($data){
            $order='p.on_time';
            $msg='desc';
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
            if ($user_id){
                $user=$conn->createQueryBuilder()
                    ->select('*')
                    ->from('msk_member_info')
                    ->where('member_id='.$user_id)
                    ->execute()
                    ->fetch();
                $ac=$conn->insert('msk_shop_visit',array('shop_id'=>$shop_id,'user_id'=>$user_id,'user_name'=>$user['nickname'],'visit_time'=>time()));
                $collect=$conn->createQueryBuilder()
                        ->select('*')
                        ->from("msk_shop_collect")
                        ->where("user_id=$user_id","shop_id=$shop_id",'is_collect=1')
                        ->execute()
                        ->fetch();
                if ($collect) $data['is_collect']=1;
                else $data['is_collect']=0;
            }else{
                $data['is_collect']=0;
                $ac=$conn->insert('msk_shop_visit',array('shop_id'=>$shop_id,'user_id'=>0,'user_name'=>'游客','visit_time'=>time()));
            }
            $conn->createQueryBuilder()
                ->update('msk_shop')
                ->set('visit_sum',$data['visit_sum']+1)
                ->where('shop_id='.$shop_id)
                ->execute();
            $query = $conn->createQueryBuilder ()
                ->select("p.goods_id,p.goods_name,p.market_price,p.shop_price,p.sales_sum,mi.image_url,p.is_free_shipping")
                ->from('msk_shop_goods','s')
                ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
                ->leftJoin('s','msk_goods_images','mi','s.goods_id=mi.goods_id')
                ->where('mi.type=1','s.shop_id = '.$shop_id,'s.is_deleted=0')
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->orderBy($order,$msg)
                ->execute();
            $data['goods']=$query->fetchAll();
            foreach ($data['goods'] as $k=>$v){
                if($v['is_free_shipping']==0){
                    $stock= $conn->createQueryBuilder ()
                    ->select("stock")
                    ->from('msk_goods_shipping')
                    ->where('goods_id='.$v['goods_id'])
                    ->execute()->fetch();
                    $data['goods'][$k]['stock']=$stock['stock'];
                }else $data['goods'][$k]['stock']=0;  
                $data['goods'][$k]['image_url']= $this->getParameter('app_qiniu_imgurl').$v['image_url'];         
            }                    
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else  return new JsonResponse(array('code'=>300,'msg'=>'没有店铺','result'=>''));
                
    }

    /**
     * @Route("/shopCount", name="shopCount_")
     */
    public function shopCountAction(Request $request){
        $user_id=$request->get('member_id');
        date_default_timezone_set("Asia/Shanghai");
        $time=date("Y-m-d 00:00:00");
        $deadline=date("Y-m-d 24:00:00");
        $news=strtotime($time);
        $dead=strtotime($deadline);
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $shop=$conn->createQueryBuilder ()
            ->select("p.shop_id,p.visit_sum")
            ->from('msk_shop','p')            
            ->where('p.user_id ='.$user_id)
            ->execute()->fetch();
        if ($shop){
            $today_visit=$conn->createQueryBuilder ()
            ->select("count(p.id) as today_visit")
            ->from('msk_shop_visit','p')
            ->where('p.shop_id ='.$shop['shop_id'],'p.visit_time >'.$news,'p.visit_time <'.$dead)
            ->execute()->fetch();
            $today_order=$conn->createQueryBuilder ()
                    ->select("count(p.order_sn) as today_order")
                    ->from('msk_mall_order','p')
                    ->where('p.shop_id ='.$shop['shop_id'],'p.add_time >'.$news,'p.add_time <'.$dead)
                    ->execute()->fetch();
            $today_income=$conn->createQueryBuilder ()
                ->select("sum(p.income) as today_income")
                ->from('msk_shop_income','p')
                ->where('p.shop_id ='.$shop['shop_id'],'p.add_time >'.$news,'p.add_time <'.$dead)
                ->execute()->fetch();
            $data['shop_id']=$shop['shop_id'];
            $data['today_vist']=$today_visit['today_visit'];
            $data['visit_sum']=$shop['visit_sum'];
            $data['today_order']=$today_order['today_order'];
            $data['today_income']=$today_income['today_income'];
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    
    }
    
    /**
     * @Route("/setFreeshipping", name="setFreeshipping_")
     */
    public function setFreeshippingAction(Request $request){
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $shop_id=$request->get('shop_id',0);
        $is_far_unfree=$request->get('type',0);
        if ($shop_id){
            $activity=$conn->createQueryBuilder ()
                    ->select("*")
                    ->from('msk_shop_activity','p')
                    ->where('p.shop_id ='.$shop_id)
                    ->execute()->fetch();
            if ($activity){
                try {
                    $conn->createQueryBuilder ()
                        ->update ('msk_shop_activity')                       
                        ->set('is_close',0)
                        ->set('is_far_unfree',$is_far_unfree)
                        ->set('start_time',time())
                        ->set('end_time',time()+3600*24*30*6)
                        ->where("shop_id =".$shop_id)
                        ->execute();
                }catch (Exception $e) {
                    return new JsonResponse(array('code'=>300,'msg'=>'fales','result'=>''));
                }                
            }else {
                $a=$conn->insert('msk_shop_activity',
                    array(
                        'shop_id' =>$shop_id,
                        'name' => '包邮',
                        'type' => 1,
                        'money' =>0,
                        'is_far_unfree'=>$is_far_unfree,
                        'description'=>'海外、港澳台、新疆、西藏、内蒙、青海、甘肃、宁夏、南海',
                        'start_time'=>time(),
                        'end_time'=>time()+3600*24*30*6,
                    ));
            }
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>''));
        }   
       
    }
    
    /**
     * @Route("/setshipAddress", name="setshipAddress_")
     */
     public function setshipAddressAction(Request $request){
       $conn=$this->getDoctrine ()->getManager ()->getConnection ();
       $shop_id = $request->get('shop_id',0);
       $address = $request->get('address',0);      
        try {
            $conn->createQueryBuilder ()
                ->update ('msk_shop')                       
                ->set('ship_address',"'$address'")
                ->where("shop_id =".$shop_id)
                ->execute();
        }catch (Exception $e) {
                    return new JsonResponse(array('code'=>300,'msg'=>'fales','result'=>''));
                } 
       return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>''));
     }
     


     
     /**
      * @Route("/getshippinglist", name="getshippinglist_")
      */
     public function getshippinglistAction(Request $request){
         $shop_id = $request->get('shop_id',0);
		 $offset=$request->get('offset',0);
         $data=$this->conn()->createQueryBuilder()
                ->select("shipping_id,shipping_name,shipping_price,shop_id")
                ->from('msk_shipping')
                ->where('shop_id='.$shop_id,'enabled=1')
				->setMaxResults(10)
                ->setFirstResult($offset)
                ->execute()
                ->fetchAll();
         if (!empty($data)){
             return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
         }
         return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
     }
     
     /**
      * @Route("/addshipping", name="addshipping_")
      */
     public function addshippingAction(Request $request){
         $shop_id = $request->get('shop_id',0);
         $shipping_price = $request->get('shipping_price',0);
	     $shipping_name = $request->get('shipping_name',0);
		 $arr=array(
				'shop_id'=>$shop_id,
				'shipping_name'=>$shipping_name,
				'shipping_price'=>$shipping_price
				);
         $data=$this->conn()->insert('msk_shipping',$arr);
         if ($data){
             return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
         }
         return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
     }

     /**
      * @Route("/setshipping", name="setshipping_")
      */
     public function setshippingAction(Request $request){
         $shop_id = $request->get('shop_id',0);
		 $shipping_id = $request->get('shipping_id',0);
         $shipping_price = $request->get('shipping_price',0);
	     $shipping_name = $request->get('shipping_name',0);
         $data=$this->conn()->createQueryBuilder()
                ->update('msk_shipping')
				->set('shipping_name',"'$shipping_name'")
				->set('shipping_price',"'$shipping_price'")
                ->where('shop_id='.$shop_id,'shipping_id='.$shipping_id)
                ->execute();
         if ($data){
             return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
         }
         return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
     }
     
	 /**
      * @Route("/delshipping", name="delshipping_")
      */
     public function delshippingAction(Request $request){
         $shop_id = $request->get('shop_id',0);
		 $shipping_id = $request->get('shipping_id',0);
         $data=$this->conn()->createQueryBuilder()              
                ->update('msk_shipping')
				->set('enabled',0)
                ->where('shop_id='.$shop_id,'shipping_id='.$shipping_id)
                ->execute();
         if ($data){
             return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>1));
         }
         return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
     }     

     /**
      * @Route("/moreEdit", name="moreEdit_")
      */
     public function moreEditAction(Request $request){
        $conn=$this->getDoctrine ()->getManager ()->getConnection ();
        $user_id = $request->get('member_id',0);
        $shop=$conn->createQueryBuilder ()
                ->select("shop_id,ship_address,stock")
                ->from('msk_shop')
                ->where('user_id='.$user_id,'is_show = 1')
                ->execute()->fetch();
            if (!empty($shop)){
                $ship=$conn->createQueryBuilder ()
                    ->select("money,is_far_unfree,description")
                    ->from('msk_shop_activity')
                    ->where('shop_id='.$shop['shop_id'],'is_close = 0','type=1','end_time >'.time())
                    ->execute()->fetch();
                if ($ship){  
                    $data=array(
                        'shop_id'=>$shop['shop_id'],
                        'ship_address'=>$shop['ship_address'],
                        'stock'=>$shop['stock'],
                        'is_free'=>1,
                        'is_far_unfree' =>$ship['is_far_unfree'],
                        'description'=>$ship['description'],
                    );
                    return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
                }else{
                    $data=array(
                        'shop_id'=>$shop['shop_id'],
                        'ship_address'=>"",
                        'stock'=>$shop['stock'],
                        'is_free'=>0,
                        'description'=>'海外、港澳台、新疆、西藏、内蒙、青海、甘肃、宁夏、南海',
                        'is_far_unfree'=>0,
                    );
                    return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
                }  
                
            }
            return new JsonResponse(array('code'=>300,'msg'=>'fales','result'=>''));        
     }

     
     
     /**
      * @Route("/addGoods", name="addGoods_")
      */
     public function addGoodsAction(Request $request){
         $user_id=$request->get('member_id');        
         $data=$_POST['data'];
         $data=json_decode($data,true);
         if (empty($data['cover']))  return new JsonResponse(array('code'=>300,'msg'=>'请选择封面'));
         if (empty($data['goods_name']))  return new JsonResponse(array('code'=>300,'msg'=>'请输入商品标题'));
         if (empty($data['goods_desc']))  return new JsonResponse(array('code'=>300,'msg'=>'请输入商品描述'));
         if (empty($data['shop_cat_id']))  return new JsonResponse(array('code'=>300,'msg'=>'请选择分类至'));
         if (empty($data['cat_id']))  return new JsonResponse(array('code'=>300,'msg'=>'请选择分类'));
         if (!empty($data['spec'])){
             foreach ($data['spec'] as $v){
                 if (empty($v['key_name']) || empty($v['price']) || empty($v['store_count'])){
                     return new JsonResponse(array('code'=>300,'msg'=>'请输入商品型号信息'));
                 }
             }
         }else{
             if (empty($data['goods_price']))  return new JsonResponse(array('code'=>300,'msg'=>'请输入价格'));
             if (empty($data['store_count']))  return new JsonResponse(array('code'=>300,'msg'=>'请输入商品库存'));
         }
         $category=$this->conn()->createQueryBuilder()
                    ->select('*')
                    ->from('msk_mall_category')
                    ->where('cat_id='.$data['cat_id'])
                    ->execute()
                    ->fetch();
         $shop=$this->conn()->createQueryBuilder()
                  ->select("*")
                  ->from('msk_shop')
                  ->where('user_id='.$user_id)
                  ->execute()
                  ->fetch();
         if (empty($shop)){
             return new JsonResponse(array('code'=>300,'msg'=>'你没有店铺','result'=>''));
         }
         //return new JsonResponse($shop);
         $mallgoods=new MallGoods();
         $mallgoods->setCat_id($data['cat_id']);
         $mallgoods->setGoods_name($data['goods_name']);
         $mallgoods->setGoods_remark($data['goods_desc']);
         $mallgoods->setKeywords($category['name']);
         $mallgoods->setShop_price($data['goods_price']);
         $mallgoods->setMarket_price($data['goods_price']);
         $mallgoods->setOn_time(time());
         $mallgoods->setLast_update(time());
         $this->getDoctrine ()->getManager ()->persist($mallgoods);
         $this->getDoctrine ()->getManager ()->flush();
         $goods_id=$mallgoods->getGoods_id();
         if ($goods_id>0){              
             $result=$this->conn()->insert('msk_goods_images',array('goods_id'=>$goods_id,'type'=>1,'image_url'=>$data['cover']));
            if (!empty($data['other'])){
                foreach ($data['other'] as $v){
                    $result=$this->conn()->insert('msk_goods_images',array('goods_id'=>$goods_id,'type'=>2,'image_url'=>$v));
                }
            }
            if (!empty($data['spec'])){
                foreach ($data['spec'] as $v){
                    $this->conn()->insert('msk_spec_goods_price',array('goods_id'=>$goods_id,'type_id'=>1,'`key`'=>'型号','key_name'=>$v['key_name'],'price'=>$v['price'],'store_count'=>$v['store_count']));
                }
            }
            $this->conn()->insert('msk_shop_goods',array('goods_id'=>$goods_id,'shop_id'=>$shop['shop_id'],'shop_cat_id'=>$data['shop_cat_id']));
            $this->conn()->createQueryBuilder()
                ->update('msk_shop')
                ->set('shop_goods','shop_goods+1')
                ->set('on_goods','on_goods+1')
                ->where('shop_id='.$shop['shop_id'])
                ->execute();
            $this->conn()->createQueryBuilder()
                ->update('msk_shop_category')
                ->set('goods_num','goods_num+1')
                ->where('id='.$data['shop_cat_id'])
                ->execute();
         }
         return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$goods_id));
     }

     

     /**
      * @Route("/addShopCat", name="addShopCat_")
      *
      **/
      
     public function addShopCatAction(Request $request){
         $shop_id=$request->get('shop_id',0);
         $parent_id=$request->get('parent_id',0);
         $category=$request->get('category',0);
         if (!$category) return new JsonResponse(array('code'=>300,'msg'=>'请填写分类名称','result'=>''));
         $shop=$this->conn()->createQueryBuilder()
         ->select('*')
         ->from('msk_shop')
         ->where('shop_id='.$shop_id)
         ->execute()
         ->fetch();
         if (!$shop) return new JsonResponse(array('code'=>300,'msg'=>'店铺ID有误,店铺不存在','result'=>''));
         $data=array(
             'parent_id'=>$parent_id,
             'name'=>$category,
             'shop_id'=>$shop_id,
         );
         $ret=$this->conn()->insert('msk_shop_category',$data);
         if ($ret) return new JsonResponse(array('code'=>200,'msg'=>'添加成功','result'=>''));
         return new JsonResponse(array('code'=>300,'msg'=>'添加失败','result'=>''));
     }
     
      
        /**
        * @Route("/collectshop", name="collectshop_")
        *
        **/
       public function collectshopAction(Request $request){
            $shop_id=$request->get('shop_id',0);
            $user_id=$request->get('member_id',0);
            $time=time();
            $collect=$this->conn()->createQueryBuilder()
                    ->select('*')
                    ->from('msk_shop_collect')
                    ->where("user_id=$user_id","shop_id=$shop_id")
                    ->execute()->fetch();           
            if ($collect){
                if ($collect['is_collect']==1){
                    $ret=$this->conn()->createQueryBuilder()
                    ->update('msk_shop_collect')
                    ->set('is_collect',0)
                    ->where("user_id=$user_id","shop_id=$shop_id")
                    ->execute();
                }else{
                    $ret=$this->conn()->createQueryBuilder()
                        ->update('msk_shop_collect')
                        ->set('is_collect',1)
                        ->where("user_id=$user_id","shop_id=$shop_id")
                        ->execute();
                }  
            }else{
                $data=array(
                    'user_id'=>$user_id,
                    'shop_id'=>$shop_id,
                    'collect_time'=>$time,
                );
				$ret=$this->conn()->insert('msk_shop_collect',$data);
			}  
          
            if ($ret) {
                $ret=$this->conn()->createQueryBuilder()
                    ->update('msk_shop')
                    ->set('collect_num','collect_num+1')
                    ->where("shop_id=$shop_id")
                    ->execute();
                return new JsonResponse(array('code'=>200,'msg'=>'success'));
            } else {
                return new JsonResponse(array('code'=>300,'msg'=>'false'));
            }
       }
     
}