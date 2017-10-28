<?php
namespace Cart\Logic;

use Think\Model;
/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class OrderLogic extends Model
{

  /**
   * 获取22位唯一订单号 
   * @return string
   * **/  
  private function MyOrderNo22(){
        $code  =date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $code .= randCodeM(22-strlen($code),1);
        if (M('order')->where("order_sn=$code")->find()){
            self::MyOrderNo22();
        }
        return $code;
    }
    
    
    /**
     *  购物车添加订单
     * @param type $user_id  用户id
     * @param type $address_id 地址id
     * @param type $shipping_code 物流编号
     * @param type $invoice_title 发票
     * @param type $coupon_id 优惠券id
     * @param type $car_price 各种价格
     * @return type $order_id 返回新增的订单id
     */
    public function addOrder($user_id,$address_id,$car_price,$coupon_id=0,$postscript=0)
    {   
        
        if (empty($car_price)) return array('status'=>-9,'msg'=>'购物车找不到该商品','result'=>'');
        $book_type=$car_price['book_type'];
        if ($book_type==1){
            $shop_id=$car_price['goods'][0]['shop_id'];
            $sell_id=0;
            $error=self::checkInventory($car_price['goods']);
            if ($error) return array('status'=>-9,'msg'=>'库存不足','result'=>$error);
        }else{
            $sell_id=$car_price['goods'][0]['shop_id'];
            $shop_id=0;
        } 
        $order_count = M('order')->where("user_id= $user_id and order_sn like '".date('Ymd')."%'")->count();
        if($order_count >= 50)
            return array('status'=>-9,'msg'=>'一天只能下50个订单','result'=>'');       
        $address = M('user_address')->where("address_id = $address_id")->find();
        $order_sn=self::MyOrderNo22();
        $data = array(
            'order_sn'          =>$order_sn, // 订单编号
            'user_id'           =>$user_id, // 用户id
            'payee'             =>$sell_id,
            'payee_shop'        =>$shop_id,         
            'goods_amount'      =>$car_price['price'],//'商品价格',
            'shipping_fee'      =>$car_price['shipping_price'],//'物流价格',
            'order_amount'      =>$car_price['total'],//订单总价
            'coupon_id'         =>$car_price['coupon_id'],//'使用优惠券',
            'coupon_money'      =>$car_price['coupon'],//'使用优惠券',
            'add_time'          =>$_SERVER['REQUEST_TIME'], // 下单时间
            'deadline'          =>900, // 下单时间
            'anum'              =>$car_price['anum'],
        );
        $order_id = M("order")->add($data);
        if(!$order_id)
            return array('status'=>-9,'msg'=>'添加订单失败','result'=>'');
        logOrder($order_id,'您提交了订单，请等待系统确认','提交订单',$user_id);
        self::addAddress($order_id, $address,$postscript);
        $a=self::addOrderGoods($car_price['goods'],$order_id,$book_type,$shop_id);
        // 如果应付金额为0  可能是余额支付 + 积分 + 优惠券 这里订单支付状态直接变成已支付
        if($data['order_amount'] == 0)
        {
            update_pay_status($data['order_sn'], 1);
        }
        // 2修改优惠券状态
        if($car_price['coupon_id'] > 0){
            $data3['user_id'] = $user_id;
            $data3['order_id'] = $order_id;
            $data3['use_time'] = $_SERVER['REQUEST_TIME'];
            $data3['user_number'] = 1;
            $data3['spare'] = 0;
            M('user_coupon')->where("id = $coupon_id")->save($data3);
        }
        // 4 删除已提交订单商品
        M('cart')->where("user_id = $user_id and selected = 1")->setField('selected',0);
        // 5 记录log 日志
        $data4['user_id'] = $user_id;
        $data4['user_money'] = 0;
        $data4['pay_points'] =0;
        $data4['change_time'] = time();
        $data4['desc'] = '下单消费';
        $data4['order_sn'] = $data['order_sn'];
        $data4['order_id'] = $order_id;
        ($data4['user_money'] || $data4['pay_points']) && M("user_account_log")->add($data4);       
        return $order_sn; // 返回新增的订单id
    }
    
    
    /**
     * 检测库存
     * **/ 
    private function checkInventory($data){
        $error=array();        
        foreach ($data as $k=>$v){
            $inventory=M('book_inventory')->where(array('book_id'=>$v['book_id']))->getField('inventory');
            if ($data[$k]['goods_number'] >= $inventory){
                $error[]=$v['book_id'];
            }
        }
        return $error;
    }
    
    /**
     * 添加收货地址
     * **/
    private function addAddress($order_id,$address,$postscript=0){
        $data=array(
            'info_id'=>$order_id,
            'consignee'=>$address['consignee'],
            'province'=>$address['province'],
            'city'=>$address['city'],
            'district'=>$address['area'],
            'street'=>$address['street'],
            'address'=>$address['address'],
            'mobile'=>$address['mobile'],
            'postscript'=>$postscript,
        );
        return M('order_info')->add($data);
    }
    
    /**
     * 购物车添加订单多个商品
     * **/
    private function addOrderGoods($data,$order_id,$book_type,$shop_id){
        foreach($data as $key => $val)
        {
            $data2['order_id']           = $order_id; // 订单id
            $data2['book_id']            = $val['book_id']; // 商品id
            $data2['type']               = $val['book_type'];
            $data2['book_name']          = $val['book_name']; // 商品名称
            $data2['shop_id']            = $val['shop_id'];
            $data2['shop_name']          = $val['shop_name'];
            $data2['book_desc']          = $val['book_desc'];
            $data2['goods_number']       = $val['goods_number']; // 购买数量
            $data2['book_number']        = $val['book_number'];
            $data2['book_thumb']         = $val['cover_img'];
            $data2['book_price']         = $val['goods_price']; // 商品价
            $data2['book_attr']          = $val['book_attr'];
            $data2['goods_fee']          = bcmul($val['goods_price'], $val['goods_number'],2);
            $order_goods_id              = M("order_goods")->data($data2)->add();
        }
    }

    
   /**
    * 立即购买订单
    * @param unknown $user_id
    * @param unknown $book_id
    * @param unknown $goods_number
    * @param unknown $address_id
    * @param number $coupon_id
    * @param number $postscript
    * @return Ambigous <multitype:number string , multitype:number string unknown >|multitype:number string |\Think\mixed***/
    public function addBuyNowOrder($user_id,$book_id,$goods_number,$address_id,$coupon_id=0,$postscript=0){
        $data=self::findBook($user_id,$book_id,$goods_number,$coupon_id);
        if ($data['error']==1) return $data;
        $book=$data['data'];
        $order_count = M('order')->where("user_id= $user_id and order_sn like '".date('Ymd')."%'")->count();
        if($order_count >= 50)
            return array('error'=>1,'msg'=>'一天只能下50个订单','data'=>'');
        $address = M('user_address')->where("address_id = $address_id")->find();
        if (empty($address)) return array('error'=>1,'msg'=>'收货地址不存在','data'=>'');
        $order_sn=self::MyOrderNo22();
        $order=array(
            'order_sn'          =>$order_sn, // 订单编号
            'user_id'           =>$user_id, // 用户id
            'payee'             =>$book['sell_id'],
            'payee_shop'        =>$book['shop_id'],
            'goods_amount'      =>$book['goods_price'],//'商品价格',
            'shipping_fee'      =>$book['shipping_price'],//'物流价格',
            'order_amount'      =>$book['order_amount'],//订单总价
            'coupon_id'         =>$coupon_id,//'使用优惠券',
            'coupon_money'      =>$book['coupon'],//'使用优惠券',
            'add_time'          =>$_SERVER['REQUEST_TIME'], // 下单时间
            'deadline'          =>900, // 下单时间
            'anum'              =>$goods_number,
        );
        $order_id = M("order")->add($order);
        if(!$order_id)
            return array('error'=>1,'msg'=>'添加订单失败','data'=>'');
        logOrder($order_id,'您提交了订单，请等待系统确认','提交订单',$user_id);
        self::addAddress($order_id, $address,$postscript);
        self::addOrdergoods2($order_id,$goods_number,$book);
        changeCoupon($user_id, $coupon_id);
        return array('error'=>0,'msg'=>'添加订单成功','data'=>$order_sn);
    }
    
    /**
     * 查找单本书信息
     * @param unknown $book_id
     * @param unknown $goods_number
     * @param number $coupon_id
     * @return multitype:number string |multitype:number string unknown***/
    private  function findBook($user_id,$book_id,$goods_number,$coupon_id=0){
        $book=M('book')->alias('b')
        ->join('left join kts_book_attr a on b.book_id=a.book_id')
        ->field('b.*,a.introduce as book_desc')
        ->where(array('b.book_id'=>$book_id))
        ->find();
        $book['book_attr']=0;
        if($book['type']==2){
            return array('error'=>1,'msg'=>'该系列书不进行购买，请从新选择购买，谢谢!');
        }elseif($book['type']==0){
            $old=M('book_old')->where(array('book_id'=>$book_id))->find();
            $book['book_attr']=$old['description'];
            $book['shop_name']=$old['user_name'];
            $book['shipping_price']=$old['shipping_price'];
            $book['sell_id']=$old['user_id'];
            $book['shop_id']=0;
        }elseif($book['type']==1){
            $inventory=M('book_inventory')->where(array('book_id'=>$book_id))->getField('inventory');
            $shop=M('shop')->where(array('shop_id'=>$book['shop_id']))->find();
            if ($goods_number >= $inventory){ return array('error'=>1,'msg'=>'该书库存不足，请选择其他书购买，谢谢!');}
            $book['sell_id']=0;
            $book['shop_id']=$shop['shop_id'];
            $book['shop_name']=$shop['shop_name'];
            $shipping=M('shop_shipping')->where(array('shop_id'=>$shop['shop_id'],'enabled'=>1))->getField('shipping_price');
            if ($shipping){
                $book['shipping_price']=$shipping;
            }else{
                $book['shipping_price']=0;
            } 
        }
       
        $book['goods_price']=bcmul($book['price'],$goods_number,2);
        $book['order_amount']=bcadd($book['goods_price'],$book['shipping_price'],2);
        $book['book_desc']=mb_substr($book['book_desc'],0,15,'utf-8');
        $book['coupon']=0;
        if($coupon_id){
            $coupon=getCouponMoney($user_id,$coupon_id);         
            if ($coupon){
                $ret=bccomp($coupon,$book['goods_price'],2);
                if ($ret==0 || $ret == 1){
                    $book['order_amount']=$book['shipping_price'];
                }elseif ($ret == -1){
                    $book['order_amount']=bcadd(bcsub($book['goods_price'],$coupon,2),$book['shipping_price'],2);
                }
                $book['coupon']=$coupon;
            }
        }
        return array('error'=>0,'msg'=>'','data'=>$book);
    }
    
    /**
     * 添加单个订单商品
     * **/
    private function addOrdergoods2($order_id,$goods_number,$book){
        if ($book['type']==1){
            $shop_id = $book['shop_id'];
            if (!M('shop_books')->where(array('book_id'=>$val['book_id'],'shop_id'=>$shop_id))->setInc('saless_num',$goods_number)){
                M('shop_books')->add(array('book_id'=>$book['book_id'],'shop_id'=>$shop_id,'saless_num'=>$goods_number,'addtime'=>time()));
            }
            M('book_inventory')->where(array('book_id'=>$book['book_id']))->setDec('inventory',$goods_number);
        }
        if (!M('book_add')->where(array('book_number'=>$book['book_number']))->setInc('sell_num',$goods_number)){
            M('book_add')->add(array('book_number'=>$book['book_number'],'sell_num'=>$goods_number));
        }
        $goods=array(
            'order_id'     =>$order_id,
            'book_id'      =>$book['book_id'],
            'type'         =>$book['type'],
            'book_name'    =>$book['name'],
            'book_desc'    =>$book['book_desc'],
            'shop_id'      =>$book['shop_id'],
            'shop_name'    =>$book['shop_name'],
            'goods_number' =>$goods_number,
            'book_thumb'   =>$book['cover_img'],
            'book_number'  =>$book['book_number'],
            'book_price'   =>$book['price'],
            'book_attr'    =>$book['book_attr'],
            'goods_fee'    =>$book['goods_price'],
        );
        return M('order_goods')->add($goods);
    }
}