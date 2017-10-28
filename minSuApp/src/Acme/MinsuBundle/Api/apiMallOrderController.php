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
use Acme\MinsuBundle\Entity\UserAddress;
use Acme\MinsuBundle\Entity\OrderPay;
use Acme\MinsuBundle\Entity\Comment;
use Acme\MinsuBundle\Common\CommonController;
use Acme\MinsuBundle\Common\Payment;
class apiMallOrderController extends CommonController
{ 

    public function __construct(){
    
    }
    
    /**
     * @Route("/setUserAddress", name="setUserAddress_")
     */
    public function setUserAddressAction(Request $request){
       $conn = $this->getDoctrine ()->getManager();
       $user_id=$request->get('member_id'); 
       $address_id=$request->get('address_id',0);
       $data=$_POST['data'];
       $data=json_decode($data,true);
       if (!$data['consignee']) return new JsonResponse(array('code'=>300,'msg'=>'请填写收货人','result'=>''));
       if (!$data['mobile']||!preg_match("/1[3578]{1}\d{9}$/",$data['mobile'])) return new JsonResponse(array('code'=>300,'msg'=>'请填写正确手机号码','result'=>''));
       if (!$data['city']) return new JsonResponse(array('code'=>300,'msg'=>'请填写收货地区','result'=>''));
       if (!$data['address']) return new JsonResponse(array('code'=>300,'msg'=>'请填写收货地址','result'=>''));     
       if ($address_id){          
           if ($data['is_default']){
               $v=$this->conn()->createQueryBuilder()
                   ->update("msk_user_address")               
                   ->set('is_default',0)
                   ->where('user_id='.$user_id)
                   ->execute();               
           }else { $data['is_default']=0;  } 
           $v=$this->conn()->createQueryBuilder()
                   ->update("msk_user_address")
                   ->set('consignee',"'{$data['consignee']}'")
                   ->set('address',"'{$data['address']}'")
                   ->set('city',"'{$data['city']}'")
                   ->set('mobile',$data['mobile'])
                   ->set('is_default',$data['is_default'])
                   ->where('address_id='.$address_id)
                   ->execute();
           if ($v){
               $a=$address_id;
           }else{
               return new JsonResponse(array('code'=>300,'msg'=>'你没有进行修改!','result'=>''));
           }
       }else{
           $userAddress=new UserAddress();
           $userAddress->setaddress($data['address']);
           $userAddress->setconsignee($data['consignee']);
           $userAddress->setcountry(0);
           $userAddress->setis_default(0);
           $userAddress->settwon(0);
           $userAddress->setdistrict(0);
           $userAddress->setmobile($data['mobile']);
           $userAddress->setcity($data['city']);
           $userAddress->setprovince(0);
           $userAddress->setuser_id($user_id);
           $conn->persist($userAddress);
           $conn->flush();
           $a=$userAddress->getaddress_id();
   		}
        if ($a){
                $data['address_id']=$a;
                return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
              }
            return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }  

    
    /**
     * @Route("/delUserAddress", name="delUserAddress_")
     */
    public function delUserAddress(Request $request){   
        $address_id=$request->get('address_id',0);
        $user_id=$request->get('member_id',0);
        $address=$this->conn()->createQueryBuilder()
        ->select('*')
        ->from('msk_user_address')
        ->where('address_id='.$address_id,'user_id='.$user_id)
        ->execute()->fetch();
        if (!$address) return new JsonResponse(array('code'=>300,'msg'=>'地址ID有误，请重新输入','result'=>''));
    
        $ret=$this->conn()->createQueryBuilder()
        ->delete('msk_user_address')
        ->where('address_id='.$address_id)
        ->execute();
        if ($ret) return new JsonResponse(array('code'=>200,'msg'=>'删除成功','result'=>''));
        return new JsonResponse(array('code'=>300,'msg'=>'删除失败','result'=>''));
    }
    
    /**
     * @Route("/confirmOrder", name="confirmOrder_")
     */
    
    public function confirmOrderAction(Request $request){
        $user_id=$request->get('member_id');
        $goods_id=$request->get('goods_id');
        $address_id=$request->get('address_id');
        $goods_num=$request->get('goods_num',1);
        $spec_id=$request->get('spec_id',0);
        $data=$this->get_order($user_id,$goods_id,$goods_num,$spec_id,$address_id);
        $data['coupon']=$this->conn()->createQueryBuilder ()
                ->select("id as coupon_id,coupon_value,min_amount,coupon_dscp,deadline")
                ->from('msk_buyer_coupon')
                ->where('buyer_id='.$user_id,'deadline >'.time(),'state = 0')
                ->execute()->fetchAll(); 
        if ($data){
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }
    
    /**
     * @Route("/addorder", name="addorder_")
     */
    public function addorderAction(Request $request){
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $user_id=$request->get('member_id');
        $goods_id=$request->get('goods_id');
        $address_id=$request->get('address_id');
        $goods_num=$request->get('goods_num');
        $spec_id=$request->get('spec_id');
        $coupon_id=$request->get('coupon_id',0);
        $order_sn  = $this->get_order_sn();       
        $data      = $this->get_order($user_id,$goods_id,$goods_num,$spec_id,$address_id,$coupon_id);
        if ($data['code']<0) return new JsonResponse(array('code'=>300,'msg'=>$data['msg'],'result'=>''));
        $d=$this->addOrder($user_id,$goods_id,$spec_id,$order_sn,$data['result'],$coupon_id);       
        $d=explode('|',$d);
        if (count($d)>1){            
            $b=$this->addorderlog($d[0], $user_id, '下单成功'); 
            $c=$this->upgoods($goods_id, $goods_num,$spec_id);        
            $data['result']['order']=array('order_id'=>$d[0],'order_sn'=>$order_sn,'create_time'=>date('Y.m.d H:i',$d[1])); 
            return new JsonResponse($data);
        }
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>'')); 
    }
    
    /**
     * @Route("/removeOrder", name="removeOrder_")
     */
    public function removeOrderAction(Request $request){
        $user_id=$request->get('member_id');
        $order_sn=$request->get('order_sn');
        $order=$this->conn()->createQueryBuilder ()
                    ->select('*')
                    ->from('msk_mall_order')
                    ->where("order_sn='$order_sn'")
                    ->execute()->fetch();
        $data=$this->conn()->createQueryBuilder ()
                    ->update('msk_mall_order')
                    ->set('order_status',6)
                    ->where("order_sn='$order_sn'")
                    ->execute();
        if ($data){
            $this->addorderlog($order['order_id'], $user_id,'取消了订单');
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>''));
        }
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    }
    
    /**
     * @Route("/getOrderDet", name="getOrderDet_")
     */
    public function getOrderDetAction(Request $request){
        $order_sn=$request->get('order_sn',0);
        $data['order']=$this->conn()->createQueryBuilder()
                    ->select('p.order_id,p.shop_id,s.shop_name,s.shop_logo,p.order_sn,p.pay_status,p.add_time,p.consignee,p.mobile,p.address,p.goods_num,
                        p.total_amount,p.goods_price,p.order_amount,p.coupon_price,p.shipping_price')
                    ->from('msk_mall_order','p')
                    ->leftJoin('p','msk_shop','s','p.shop_id=s.shop_id')
                    ->where('order_sn='.$order_sn)
                    ->execute()
                    ->fetch();
        $data['goods']=$this->conn()->createQueryBuilder()
                        ->select('rec_id,goods_id,goods_name,goods_num,goods_price,goods_spec_price_id as spec_id,spec_key,spec_key_name')
                        ->from('msk_mall_order_goods')
                        ->where('order_id='.$data['order']['order_id'])
                        ->execute()
                        ->fetchAll();
        if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
        
    }
    

   /**
     * @Route("/mallorderlist", name="mallorderlist_")
     */
    public function mallorderlistAction(Request $request){
        $user_id=$request->get('member_id');
        $type=$request->get('type',8);
        $shop_id=$request->get('shop_id',0);
        $offset=$request->get('offset',0);
        $where='p.user_id='.$user_id;
        if ($shop_id){
            $where='p.shop_id='.$shop_id;
        }
        $is_send=null;
        $btn=array();
        //$is_send='p.is_send=0';
        switch ($type){
            case 1:
                $where .=' and p.pay_status=1';
                $btn=array(
                    '取消订单',
                    '去付款',
                );
                if ($shop_id){
                    $btn=array(
                        '联系买家',
                    );
                }
                break;
            case 2:
                $btn=array(
                    '联系商家',
                    '申请退货',
                    '提醒发货',
                );
                if ($shop_id){
                    $btn=array(
                        '联系买家',
                        '确认发货',
                    );
                }
                $where .=' and p.shipping_status=1';
                break;
            case 3:
                $where .=' and p.shipping_status=2';
                 $btn=array(
                    '申请退货',
                    '联系商家',
                    '查看物流',
                    '确认收货',
                );
                if ($shop_id){
                     $btn=array(
                         '联系买家',
                         '查看物流',
                         '查看评价',
                     );
                 }
                break;
            case 4:
                $where .=' and p.pay_status=3';
                $btn=array(
                    '联系商家',
                    '取消退款',
                );
                if ($shop_id){
                    $btn=array(
                        '联系买家',
                        '拒绝退货 ',
                        '同意退货',
                    );
                }
                break;
            case 5:
                $where='p.order_status=3';
                $btn=array(
                    '评价',
                );
                break;
            case 6:
                $where='p.order_status=4';
                $btn=array(
                    '删除订单',
                    '去评价'
                );
                break;
            case 7:
                $where  ='p.order_status > 1';
                $where .=' and p.pay_status =2 or p.pay_status =4';
                $where .=' and p.shipping_status=3 or p.shipping_status=5';
                //$is_send='p.is_send=1';
                break;
            case 8:
                $where='p.order_status=5';
                if ($shop_id){
                    $btn=array(
                        '删除订单',
                        '重新下单',
                    );
                }
                //$is_send='p.is_send=1';
                break;
            default:return new JsonResponse(array('code'=>300,'msg'=>'参数错误','result'=>''));
        }
        $data=$this->conn()->createQueryBuilder()
                ->select('s.user_id,e.avatar,m.nickname,s.shop_id,shop_name,s.shop_logo,p.order_id,p.order_sn,p.order_status,p.pay_status,p.shipping_status,p.goods_num,p.order_amount')
                ->from('msk_mall_order','p')
                ->leftJoin('p','msk_shop','s','p.shop_id=s.shop_id')
                ->leftJoin('p','msk_member_info','m','p.user_id=m.member_id')
                ->leftJoin('p','msk_member','e','p.user_id=e.id') 
                ->where($where,'p.is_show=1')
                ->orderBy('p.add_time','desc')
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->execute()
                ->fetchAll();
        foreach ($data as $k=>$v){
            $data[$k]['btn']=$btn;
            $data[$k]['goods']=self::getordergoods($v['order_id']);
        }
        if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        return new JsonResponse(array('code'=>202,'msg'=>'没有数据','result'=>''));        
    }
    
    
    
    /**
     * 支付成功
     * @Route("mallorderPaySuc",name="mallorderPaySuc_")
     */
    public function mallorderPaySucAction() {
        $data=$_GET;
        $sign=array_pop($data);
        array_pop($data);
        $data=self::argSort($data);
        $str=self::createLinkstring($data);
        $a=self::rsaVerify($str,$sign);
        if($a)
        {
            $order_sn =trim($_GET['out_trade_no']); //商户订单号          
            $pay_sn=trim($data['trade_no']);
            $pay_time=strtotime(isset($_GET['gmt_payment'])?$_GET['gmt_payment']:0);
            if($_GET['success'] == 'true')
            {
                $b=self::update_pay_status($order_sn,2,$pay_time,$pay_sn); // 修改订单支付状态
                return new JsonResponse(array('status'=>1,'error'=>0,'msg'=>'success'));
            }else{
                return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'fail'));
            } 
            /* elseif ($_POST['trade_status'] == 'TRADE_SUCCESS')
            {
                self::update_pay_status($order_sn,2,$pay_time,$pay_sn); // 修改订单支付状态
                return new JsonResponse('success');
            } */
        }
        else
        {
            return new JsonResponse(array('status'=>0,'error'=>1,'msg'=>'fail')); //验证失败
        }  
    }
    
    function addwallet($user_id,$income,$expend,$title,$desc){
        $data=array(
            'member_id'=>$user_id,
            'income'=>$income,
            'expend'=>$expend,
            'title'=>$title,
            'dscp'=>$desc,
            'add_time'=>time()
        );
        return $this->conn()->insert('msk_my_wallet',$data);
    }
    
    function addshopwallet($user_id,$shop_id,$shop_name,$income,$expend=0){
        $data=array(
            'user_id'=>$user_id,
            'income'=>$income,
            'expend'=>$expend,
            'shop_id'=>$shop_id,
            'shop_name'=>$shop_name,
            'add_time'=>time()
        );
        return $this->conn()->insert('msk_shop_income',$data);
    }
    
    /**
     * @Route("/getuseraddress", name="getuseraddress_")
     */
    public function getuseraddressAction(Request $request){
        $user_id=$request->get('member_id',0);
        $offset=$request->get('offset',0);
        $data=$this->conn()->createQueryBuilder()
                ->select('address_id,user_id,consignee,city,address,mobile,is_default')
                ->from('msk_user_address')
                ->where('user_id='.$user_id)
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->execute()
                ->fetchAll();
        if ($data) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    
    }
    
    /**
     * @Route("/setDefaultAddress", name="setDefaultAddress_")
     */
    public function setDefaultAddressAction(Request $request){
        $user_id=$request->get('member_id',0);
        $address_id=$request->get('address_id',0);
        if (!$address_id) return new sonResponse(array('code'=>300,'msg'=>'false','result'=>'')); 
        $data=$this->conn()->createQueryBuilder()
                ->update('msk_user_address')
                ->set('is_default',0)
                ->where('user_id='.$user_id)
                ->execute();
        $ret=$this->conn()->createQueryBuilder()
            ->update('msk_user_address')
            ->set('is_default',1)
            ->where('address_id='.$address_id)
            ->execute();
        if ($ret) return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$ret));
        return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>''));
    
    }
    
    
    /**
     * 支付成功
     * @Route("alipay",name="alipay_")
     */
    public function alipay(){
       $alipay= new Payment();
       $data=$alipay->alipay_sign("hsadhkasdhas");
       return new JsonResponse(array('code'=>300,'msg'=>'false','result'=>$data));
    }
    
    
    /**
     * 添加评论
     * @Route("addcomment",name="addcomment_")
     */    
    public function addcommentAction(Request $request){
       $data=$_POST['data'];
       $data=json_decode($data,true);
       $order_id=isset($data[0]['order_id'])?$data[0]['order_id']:0;
       $user_id=$data[0]['member_id'];
       $user=self::getuserinfo($user_id);
       if ($order_id){         
             foreach ($data as $v){
                   if (isset($v['image'])){
                       $comment_id=self::comment($v['content'],$v['grade'],$user_id,$user['nickname'],$v['goods_id'],$order_id,$v['image']);
                   }else{
                       $comment_id=self::comment($v['content'],$v['grade'],$user_id,$user['nickname'],$v['goods_id'],$order_id);
                   } 
                   self::goodscomment($v['goods_id']);
            } 
            self::uporderstatus($order_id,4);
           $this->addorderlog($order_id, $user_id, '提交了评价'); 
       }else{
           if (isset($data[0]['image'])){
               $comment_id=self::comment($data[0]['content'],$data[0]['grade'],$user_id,$user['nickname'],$data[0]['goods_id'],0,$data[0]['image']);
           }else{
               $comment_id=self::comment($data[0]['content'],$data[0]['grade'],$user_id,$user['nickname'],$data[0]['goods_id'],0);
           }
           self::goodscomment($data[0]['goods_id']);
       }  
      if ($comment_id){           
          return new JsonResponse(array('code'=>200,'msg'=>'success'));
      } 
      return new JsonResponse(array('code'=>300,'msg'=>'发表评论失败'));
    }
    
    
    
    function comment($content,$grade,$user_id,$user_name,$goods_id=0,$order_id=0,$img=0){
        $comment=new Comment();
        $comment->setAdd_time(time());
        $comment->setContent($content);
        $comment->setGoods_id($goods_id);
        $comment->setGoods_rank($grade);
        $comment->setUser_id($user_id);
        $comment->setUsername($user_name);
        $comment->setIp_address($_SERVER['REMOTE_ADDR']);
        $comment->setOrder_id($order_id);
        if ($img){
            $comment->setIs_img(1);
            $this->getDoctrine ()->getManager ()->persist($comment);
            $this->getDoctrine ()->getManager ()->flush();
            $comment_id=$comment->getComment_id();
            foreach ($img as $kk => $vv){
                $this->conn()->insert('msk_comment_image',array('comment_id'=>$comment_id,'img'=>$vv));
            }
        }else{
            $this->getDoctrine ()->getManager ()->persist($comment);
            $this->getDoctrine ()->getManager ()->flush();
            $comment_id=$comment->getComment_id();
        }
        return $comment_id;
    }
    
    
    function goodscomment($goods_id){
        return $this->conn()->createQueryBuilder()
        ->update('msk_mall_goods')
        ->set('comment_count','comment_count+1')
        ->where('goods_id='.$goods_id)
        ->execute();
    }
    
    function getuserinfo($user_id){
        $user=$this->conn()->createQueryBuilder()
        ->select("*")
        ->from('msk_member_info')
        ->where('member_id='.$user_id)
        ->execute()
        ->fetch();
        return $user;
    }
    
    function getordergoods($order_id){
        return $this->conn()->createQueryBuilder()
                ->select('p.rec_id,p.goods_id,p.goods_name,p.goods_price,m.image_url,p.spec_key,p.spec_key_name,p.goods_num')
                ->from('msk_mall_order_goods','p')
                ->leftJoin('p','msk_goods_images','m','p.goods_id=m.goods_id')
                ->where('p.order_id='.$order_id,'m.type=1')
                ->execute()
                ->fetchAll();
    }
    
    function uporderstatus($order_id,$status){
        return $this->conn()->createQueryBuilder()
        ->update('msk_mall_order')
        ->set('order_status',$status)
        ->where('order_id='.$order_id)
        ->execute();
    }
    
    
    /**
     * 退货
     * @Route("refundapply",name="refundapply_")
     */
    public function refundapply(Request $request){
        $order_sn=$request->get('order_sn',0);
        $reason=$request->get('reason',0);
        $user_id=$request->get('member_id',0);
        $order=$this->conn()->createQueryBuilder()
                ->select("*")
                ->from('msk_mall_order')
                ->where("order_sn= '$order_sn'",'shipping_status =2 or shipping_status = 3','pay_status=2','user_id='.$user_id)
                ->execute()
                ->fetch();
        if (empty($order)) return new JsonResponse(array('code'=>300,'msg'=>'订单编号有误'));
        $apply=$this->conn()->createQueryBuilder()
                ->select('*')
                ->from('msk_mall_order_refund')
                ->where('order_id='.$order['order_id'],'user_id='.$user_id)
                ->execute()->fetch();
        if ($apply){
             if ($apply['is_agree']==2) return new JsonResponse(array('code'=>2,'msg'=>'你已经提交过申请，等待商家处理!'));
            if ($apply['is_agree']==1) return new JsonResponse(array('code'=>1,'msg'=>'你已经提交过申请，商家已同意退款/退货!'));
            if ($apply['is_agree']==0) return new JsonResponse(array('code'=>0,'msg'=>'你已经提交过申请，商家不同意退款/退货!'));
        }
        $data=array(
            'order_id'=>$order['order_id'],
            'reason'=>$reason,
            'user_id'=>$user_id,
            'goods_return'=>1,
            'addtime'=>time(),
            'shop_id'=>$order['shop_id'],
            'apply_price'=>$order['order_amount'],
        );
        if ($order['pay_code']=="money"){
            $data['bank_name']='余额';
        }else{
            $data['bank_name']=$order['pay_code'];
        }
        $result=$this->conn()->insert('msk_mall_order_refund',$data);
        $this->addorderlog($order['order_id'], $user_id,'提交退货申请');
        if ($result) return new JsonResponse(array('code'=>200,'msg'=>'提交申请成功，等待审核'));
        return new JsonResponse(array('code'=>300,'msg'=>'提交申请失败'));
    }
    
    /**
     *
     * @Route("unagreeRefund",name="unagreeRefund_")
     */
    public function unagreeRefund(Request $request){
        $user_id=$request->get('member_id');
        $order_id=$request->get('order_id');
        $ret=$this->conn()->createQueryBuilder()
        ->update('msk_mall_order_refund')
        ->set('is_agree',0)
        ->where('order_id='.$order_id)
        ->execute();
        if ($ret){
            $this->addorderlog($order_id, $user_id,'不同意退货申请');
            return new JsonResponse(array('code'=>200,'msg'=>'处理成功'));
        }else return new JsonResponse(array('code'=>300,'msg'=>'false'));
    }
    
    /**
     * @Route("Sign",name="Sign_")
     **/
    public function Sign(Request $request){
        $unorder=$request->get('unsign');
        //$unorder='partner="2088022552382131"&seller_id="2088022552382131"&out_trade_no=" 1632145605"&subject="广州一天游"&body="广州一天游"&total_fee="50"&notify_url="http://121.196.230.128:9978/alipayNotify"&service="mobile.securitypay.pay"&payment_type="1"&_input_charset="utf-8"&it_b_pay="30m"&return_url="m.alipay.com"';
        $order_id=$request->get('order_id');
        $order_sn=$request->get('order_sn');
        $type=$request->get('type',1);       
        preg_match("/total_fee={1}(.*)&notify_url{1}/", $unorder,$arr);
		if (empty($arr[1])){
			return new JsonResponse(array('code'=>300,'msg'=>'解析错误'));
		}
        $total_fee=str_replace("\"", '', $arr[1]);
        if ($type==1){
            $order=$this->conn()->createQueryBuilder()
            ->select('*')
            ->from('msk_mall_order')
            ->where('order_id='.$order_id,'pay_status=1')
            ->execute()
            ->fetch();
        }elseif ($type==2){
            $order=$this->conn()->createQueryBuilder()
                ->select('*')
                ->from('msk_tour_order')
                ->where('order_id='.$order_id,'pay_status=0')
                ->execute()
                ->fetch();
        }elseif ($type==3){
            $order=$this->conn()->createQueryBuilder()
                ->select('*')
                ->from('msk_order')
                ->where('order_sn='."'$order_sn'",'pay_status=10')
                ->execute()
                ->fetch();
        }
        //return new JsonResponse(array('code'=>$order,'msg'=>$total_fee));
        if ((int)$order['order_amount']==(int)$total_fee){
             $alipay= new Payment();
             $sign=$alipay->alipay_sign($unorder);
            //$sign=$aop->alRsaSign($unorder); 
            //$sign=explode('&',$order);
            return new JsonResponse(array('code'=>200,'sign'=>$sign));
        }
        return new JsonResponse(array('code'=>300,'msg'=>'价钱不对')); 
    }
    

    

    private function getOrderInfo($order_sn,$goods,$order_amount) {
    
        // 签约合作者身份ID
        $orderInfo["partner"]="\"2088022552382131\"";
    
        // 签约卖家支付宝账号
        $orderInfo["seller_id"]="\"2088022552382131\"";
    
        // 商户网站唯一订单号
        $orderInfo["out_trade_no"]="\"$order_sn\"";
    
        // 商品名称
        $orderInfo["subject"]="\"".$goods['goods_name']."\"";
    
        // 商品详情
        $orderInfo["body"]="\"".$goods['goods_dec']."\"";
    
        // 商品金额
        $orderInfo["total_fee"]="\"$order_amount\"";
    
        // 服务器异步通知页面路径
        $orderInfo["notify_url"]="\"http://121.196.230.128:9978/alipayNotify\"";
    
        // 服务接口名称， 固定值
        $orderInfo["service"]="\"mobile.securitypay.pay\"";
    
        // 支付类型， 固定值
        $orderInfo["payment_type"]="\"1\"";
    
        // 参数编码， 固定值
        $orderInfo["_input_charset"]="\"utf-8\"";
    
        // 设置未付款交易的超时时间
        // 默认30分钟，一旦超时，该笔交易就会自动被关闭。
        // 取值范围：1m～15d。
        // m-分钟，h-小时，d-天，1c-当天（无论交易何时创建，都在0点关闭）。
        // 该参数数值不接受小数点，如1.5h，可转换为90m。
        $orderInfo["it_b_pay"]="\"30m\"";
    
        // extern_token为经过快登授权获取到的alipay_open_id,带上此参数用户将使用授权的账户进行支付
        // orderInfo += "&extern_token=" + "\"" + extern_token + "\"";
    
        // 支付宝处理完请求后，当前页面跳转到商户指定页面的路径，可空
        $orderInfo["return_url"]="\"m.alipay.com\"";
        self::argSort($orderInfo);
        return $orderInfo;
    }
    
    /**
     * @Route("alipayNotify",name="alipayNotify_")
     **/
    public function alipayNotify(){
        $data=$_POST;
		$sign=array_pop($data);
		array_pop($data);
		$alipay= new Payment();
		$data=$alipay->argSort($data);
		$str=$alipay->createLinkstring($data);
		$a=$alipay->rsaVerify($str,$sign);		
         if($a)
            {
                $order_sn =trim($_POST['out_trade_no']); //商户订单号             
               /*  $trade_no = $_POST['trade_no'];//支付宝交易号
                //$trade_status = $_POST['trade_status'];//交易状态 */
                $pay_sn=trim($data['trade_no']);
                $pay_time=strtotime(isset($_POST['gmt_payment'])?$_POST['gmt_payment']:0);
                if($_POST['trade_status'] == 'TRADE_FINISHED')
                {
                    $b=self::update_pay_status($order_sn,2,$pay_time,$pay_sn); // 修改订单支付状态
                    return new JsonResponse('success');
                }
                elseif ($_POST['trade_status'] == 'TRADE_SUCCESS')
                {
                    self::update_pay_status($order_sn,2,$pay_time,$pay_sn); // 修改订单支付状态
                    return new JsonResponse('success');
                }
            }
            else
            {
                return new JsonResponse('fail'); //验证失败
            } 
    }
    
    /**
     * @Route("alipayrefund",name="alipayrefund_")
     **/    
    function alipayrefund(Request $request){   
		$order_sn=$request->get('order_sn');
        $alipay=new Payment();
        $result=$alipay->alipay_refund($order_sn,0.01);
        return new JsonResponse($result);
    }
    
    function rsaVerify($prestr, $sign) {
        $sign = base64_decode($sign);
        $dir=dirname(__FILE__);
        $public_key= file_get_contents($dir.'/alipay_public_key.pem');
        $pkeyid = openssl_get_publickey($public_key);
        if ($pkeyid) {
            $verify = openssl_verify($prestr, $sign, $pkeyid);
            openssl_free_key($pkeyid);
        }
        if($verify == 1){
            return  1;
        }else{
            return 0;
        }
    }
    
    function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
    
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
    
        return $arg;
    }
    
    function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }
    
    
    /**
     * 支付完成修改订单
     * $order_sn 订单号
     * $pay_status 默认2 为已支付
     * 
     */
       
   public function update_pay_status($order_sn,$pay_status = 2,$pay_time=0,$pay_sn=0)
    {
        $count=$this->conn()->createQueryBuilder()
        ->select('count(order_id) as total')
        ->from('msk_mall_order')
        ->where("order_sn ='$order_sn'",'pay_status = 1')
        ->execute()
        ->fetch();
        if($count['total'] == 0) return 0;
        $order =$this->conn()->createQueryBuilder()
        ->select('*')
        ->from('msk_mall_order')
        ->where("order_sn = '$order_sn'")
        ->execute()
        ->fetch();
        $a=$this->conn()->createQueryBuilder()
            ->update('msk_mall_order')
            ->set('order_status',2)
            ->set('pay_status',$pay_status)
            ->set('pay_time',$pay_time)
            ->set('pay_sn',"'{$pay_sn}'")
            ->set('pay_code',"'alipay'")
            ->set('pay_name',"'支付宝'")
            ->where("order_sn = '$order_sn'")
            ->execute();      
        $shop= $this->conn()->createQueryBuilder()
            ->select('*')
            ->from('msk_shop')
            ->where("shop_id =".$order['shop_id'])
            ->execute()
            ->fetch();
        self::minus_stock($order['order_id']);
        self::addorderlog($order['order_id'],$order['user_id'],'订单付款成功','付款成功');        
        self::addwallet($order['user_id'], $order['order_amount'],0, '下单消费','支付成功');
        self::addwallet($shop['user_id'],0 ,$order['order_amount'], '营业收入','订单交易成功');
        self::addshopwallet($order['user_id'],$shop['shop_id'], $shop['shop_name'], $order['order_amount']);
        if ($a){
            return new JsonResponse(1);
        }else{
            return new JsonResponse(0);
        } 
    }
    
    function minus_stock($order_id){
        $orderGoodsArr = $this->conn()->createQueryBuilder()
        ->select('*')
        ->from('msk_mall_order_goods')
        ->where("order_id = '$order_id'")
        ->execute()
        ->fetchAll();
        foreach($orderGoodsArr as $key => $val)
        {
            // 有选择规格的商品
            if(!empty($val['spec_key_name']))
            {   // 先到规格表里面扣除数量 再重新刷新一个 这件商品的总数量
                $this->conn()->createQueryBuilder()
                ->update('msk_spec_goods_price')
                ->set('store_count',"store_count-{$val['goods_num']}")
                ->where("goods_id =".$val['goods_id'],"key_name='{$val['spec_key_name']}'")
                ->execute();
                self::refresh_stock($val['goods_id']);
            }else{
                $this->conn()->createQueryBuilder()
                ->update('msk_mall_goods')
                ->set('store_count',"store_count-{$val['goods_num']}")
                ->where("goods_id =".$val['goods_id'])
                ->execute();
            }
        }
        return true;
    }
    
    function refresh_stock($goods_id){
        $count = $this->conn()->createQueryBuilder()->select("count(goods_id) as count")->from("msk_spec_goods_price")->where("goods_id = $goods_id")->execute()->fetch();
        if($count['count'] == 0) return false; // 没有使用规格方式 没必要更改总库存
        $store_count = $this->conn()->createQueryBuilder()->select("sum(store_count) as count")->from("msk_spec_goods_price")->where("goods_id = $goods_id")->execute()->fetch();
        $this->conn()->createQueryBuilder()
            ->update('msk_mall_goods')
            ->set('store_count',$store_count['count'])
            ->where("goods_id =".$goods_id)
            ->execute();
        return true;
        //M("Goods")->where("goods_id = $goods_id")->save(array('store_count'=>$store_count)); // 更新商品的总库存
    }



}