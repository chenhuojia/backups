<?php
namespace Cart\Controller;

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
        $cart=D('cart','Logic');
        $order=D('order','Logic');
        if ($address_id==0){$this->myApiPrint(300,'请选择收货地址');}
        $goods=$cart->cartSelectedList($user_id);
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
                    $a=M('order')->where(array('order_id'=>$v['order_id']))->setField('deadline',(900-($_SERVER['REQUEST_TIME']-$v['add_time'])));
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
                ->field('b.book_id,b.type,b.name as book_name,b.price,a.introduce as book_desc,b.shop_id')
                ->where(array('b.book_id'=>$book_id))
                ->find();       
        if($book['type']==2){
                 $this->myApiPrint(300,'该系列书不进行购买，请重新选择购买，谢谢!');
            }elseif($book['type']==0){
                if($book['user_id']==$user_id) $this->myApiPrint(300,'不能购买自己的书，请重新选择购买，谢谢!');
                $old=M('book_old')->where(array('book_id'=>$book_id))->find();
                $book['shop_id']=$old['user_id'];
                $book['shop_name']=$old['user_name'];
                $book['shipping_price']=$old['shipping_price'];
            }elseif($book['type']==1){
                if (check_myself($user_id, $book_id,2,$book['shop_id'])) $this->myApiPrint(300,'不能购买自己的书，请重新选择购买，谢谢!');
                $inventory=M('book_inventory')->where(array('book_id'=>$book_id))->getField('inventory');
                $shop=M('shop')->where(array('shop_id'=>$book['shop_id']))->find(); 
                if ($goods_number >= $inventory){$this->myApiPrint(300,'该书库存不足，请选择其他书购买，谢谢!');}
                $book['shop_id']=$shop['shop_id'];
                $book['shop_name']=$shop['shop_name'];
                $book['shipping_price']=0;
            } 
        $book['book_desc']=mb_substr($book['book_desc'],0,15,'utf-8');
        $book['goods_number']=$goods_number;
        $book['total']=bcadd(bcmul($book['price'],$goods_number,2),$book['shipping_price'],2);
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
    

    
}

