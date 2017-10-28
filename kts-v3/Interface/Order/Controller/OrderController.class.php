<?php
namespace Order\Controller;

use Think\Controller;
use Common\Controller\ApiController;


class OrderController extends ApiController
{   

    /**
     * 提交订单
     * **/
    public function addOrder(){
        $user_id=session('user_id');
        $address_id=I('post.address_id',0);
        $liuyan=I('post.liuyan',0);
        $coupon_id=I('post.coupon_id',0);
        $cart_id=$_POST['data'];
        $cart_id=json_decode($cart_id,true);
        if (empty($cart_id)) $this->myApiPrint(300,'请选择购物车提交');
        M('cart')->where(array('user_id'=>$user_id))->setField('selected',0);
        $gods=$cart_id[0]['book_id']?M('shop_books')->where(array('is_show'=>0))->getField('book_id',true):array();
        $cart_ids=null;
        $sql2=null;   
        $sql='update kts_cart set goods_number = case cart_id ';
        foreach ($cart_id as $k => $v) { 
            if (in_array($v['cart_id'],$gods)){
                $this->myApiPrint(300,'部分产品已下架');
            }           
            $cart_ids .=$v['cart_id'].',';
            $sql .= sprintf("WHEN %d THEN %d ", $v['cart_id'], $v['goods_number']);
            $sql2 .= sprintf("WHEN %d THEN %d ", $v['cart_id'],1);
        }
        $sql .=' end, selected = case cart_id '.$sql2;
        $cart_ids=substr($cart_ids,0,-1);
        $sql .= "END WHERE cart_id IN ($cart_ids);";              
        M()->execute($sql);       
        $cart=D('cart','Logic');
        $order=D('order','Logic');
        if ($address_id==0){$this->myApiPrint(300,'请选择收货地址');}
        $goods=$cart->cartSelectedList($user_id);
        if (empty($goods)) $this->myApiPrint(300,'购物车没有此商品，请先添加商品');
        $userInfo=M('user_address')->find($address_id);
        $goods['coupon']=0.00;
        $goods['coupon_id']=$coupon_id;
        if ($coupon_id){
            $coupon=getCouponMoney($user_id, $coupon_id);
            if ($coupon){
                $ret=bccomp($coupon,$goods['price'],2);
                if ($ret==0 || $ret == 1){
                    $goods['total']=$goods['shipping_price'];
                }elseif ($ret == -1){
                    $goods['total']=bcadd(bcsub($goods['price']-$coupon,2),$goods['shipping_price'],2);
                }
                $goods['coupon']=$coupon;
            }
        }
        $order_id=$order->addOrder($user_id,$address_id,$goods,$coupon_id,$liuyan);
        if(is_array($order_id)) $this->myApiPrint(300,$order['msg'],$order['result']);
        $this->myApiPrint(200,'success',$order_id);
    }
     
    
    /**
     * 检测剩余支付时间 超时则自动失效
     * **/
    public function checkPay(){
        $data=M('order')->where(array('pay_status'=>0,'order_status'=>array('not in','2,3,4')))->select();
        $a=false;
        if ($data){
            foreach ($data as $k=>$v){
                if ($v['deadline']==0){
                    $a=M('order')->where(array('order_id'=>$v['order_id']))->setField('order_status',2);
                }else{
                    $a=M('order')->where(array('order_id'=>$v['order_id']))->setField('deadline',(1800-($_SERVER['REQUEST_TIME']-$v['add_time'])));
                }
            }
        }
        $this->myApiPrint($a);
    }
 
    
    /**
     * 立即购买
     ***/
    public function buyNow(){
        $user_id=session('user_id');
        $book_id=I('post.book_id');
        $goods_number=I('post.goods_number',1);
        $book=M('book')->alias('b')
                ->join('left join kts_book_attr a on b.book_id=a.book_id')
                ->field('b.book_id,b.type,b.name as book_name,b.cover_img,b.price,a.introduce as book_desc,b.user_id,b.shop_id')
                ->where(array('b.book_id'=>$book_id))
                ->find();       
        if($book['type']==2){
                 $this->myApiPrint(300,'该系列书不进行购买，请从新选择购买，谢谢!');
            }elseif($book['type']==0){
                if($book['user_id']== $user_id ) $this->myApiPrint(300,'不能购买自己的书，请重新选择购买，谢谢!');
                $old=M('book_old')->where(array('book_id'=>$book_id))->find();
                $book['shop_id']=$old['user_id'];
                $book['shop_name']=$old['user_name'];
                $book['shipping_price']=$old['shipping_price'];
                $book['book_attr']=$old['description'];
            }elseif($book['type']==1){
                if (check_myself($user_id, $book_id,2,$book['shop_id'])) $this->myApiPrint(300,'不能购买自己的书，请重新选择购买，谢谢!');
                $inventory=M('book_inventory')->where(array('book_id'=>$book_id))->getField('inventory');
                $shop=M('shop')->where(array('shop_id'=>$book['shop_id']))->find(); 
                if ($goods_number >= $inventory){$this->myApiPrint(300,'该书库存不足，请选择其他书购买，谢谢!');}
                $book['shop_id']=$shop['shop_id'];
                $book['shop_name']=$shop['shop_name'];
                $book['book_attr']="";
                $shipping=M('shop_shipping')->where(array('shop_id'=>$shop['shop_id'],'enabled'=>1))->getField('shipping_price');
                if ($shipping){
                    $book['shipping_price']=$shipping;
                }else{
                    $book['shipping_price']=0;
                }
            } 
        $book['book_desc']=mb_substr($book['book_desc'],0,15,'utf-8');
        $book['cover_img']=C('QINIU_IMG_PATH').$book['cover_img'];
        $book['goods_number']=$goods_number;
        $book['total']=bcadd(bcmul($book['price'],$goods_number,2),$book['shipping_price'],2);
        $address=M('user_address')->where(array('user_id'=>$user_id,'is_default'=>1))->find();
        if (empty($address)) $address=null;
        
        $book['address']=$address;
        $this->myApiPrint(200,'success',$book);
    }
    
    /**
     * 立即购买下单
     * **/
    public function buyNowAddOrder(){
        $user_id=session('user_id');
        $book_id=I('post.book_id');
        $goods_number=I('post.goods_number',1);
        $address_id=I('post.address_id',0);
        $liuyan=I('post.liuyan',0);
        $coupon_id=I('post.coupon_id',0);
        $order=D('order','Logic');
        if($goods_number <= 0) $this->myApiPrint(300,'请填写购买数量！');
        $book=$order->addBuyNowOrder($user_id, $book_id, $goods_number,$address_id,$coupon_id,$liuyan);
        if ($book['error']==1){ $this->myApiPrint(300,$book['msg']);}
        if ($book['error']==0) 
            $this->myApiPrint(200,$book['msg'],$book['data']);
    }
    

    /**
     * 根据order_sn查收货地址
     * **/
   public function findUserAddress(){
       $order_sn=I('post.order_sn');
       $data=M('order')->alias('a')
             ->join('left join kts_order_info o on a.order_id = o.info_id')
             ->where(array('a.order_sn'=>$order_sn))
             ->field('a.order_id,a.order_sn,o.*')
             ->find();
       if ($data) $this->myApiPrint(200,'success',$data);
       $this->myApiPrint(300,'fail');
   } 
   
   
   
   /**
    * 根据order_sn查订单创建时间
    * **/
   public function findOrderTime(){
       $order_sn=I('post.order_sn');
       $data=M('order')
       ->where(array('order_sn'=>$order_sn))
       ->getField('add_time');
       if ($data) $this->myApiPrint(200,'success',$data);
       $this->myApiPrint(300,'fail');
   }

   
   function abcd(){
       $display_order = array(
           1 => 4,
           2 => 1,
           3 => 2,
           4 => 3,
           5 => 9,
           6 => 5,
           7 => 8,
           8 => 9
       );
       $ids = implode(',', array_keys($display_order));
       $sql = "UPDATE categories SET display_order = CASE id ";
       foreach ($display_order as $id => $ordinal) {
           $sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);
       }
       $sql .= "END WHERE id IN ($ids)";
       echo $sql;
   }
}

