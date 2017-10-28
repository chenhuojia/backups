<?php

use Common\Controller\ApiController;
/**
 * 浮点数舍去指定位数小数点部分。全舍不入
 * @param $n float浮点值
 * @param $len 截取长度字数
 * @return string 截取后的值
 */
    function sub_float($n,$len)
    {
        stripos($n, '.') && $n= (float)substr($n,0,stripos($n, '.')+$len+1);
        return $n;
    }



/**
 * 生成随机字符串
 * @param int       $length  要生成的随机字符串长度
 * @param string    $type    随机码类型：0，数字+大小写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
 * @return string
 */
function randCode($length = 5, $type = 0) {
    $arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
    if ($type == 0) {
        array_pop($arr);
        $string = implode("", $arr);
    } elseif ($type == "-1") {
        $string = implode("", $arr);
    } else {
        $string = $arr[$type];
    }
    $count = strlen($string) - 1;
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $string[mt_rand(0, $count)];
    }
    return $code;
}





    /**
     * 查看某个用户购物车中商品的数量
     * @param type $user_id
     * @param type $session_id
     * @return type 购买数量
     */
    function cart_goods_num($user_id = 0)
    {
        $where= "user_id = $user_id ";
        // 查找购物车数量
        $cart_count =  M('cart')->where($where)->count();
        $cart_count = $cart_count ? $cart_count : 0;
        return $cart_count;
    }
    
    function get_arr_column($arr, $key_name)
    {
        $arr2 = array();
        foreach($arr as $key => $val){
            $arr2[] = $val[$key_name];
        }
        return $arr2;
    }
    
    
    function get_user_info($user_id_or_name,$type = 0,$oauth=''){
        $map = array();
        if($type == 0)
            $map['user_id'] = $user_id_or_name;
        if($type == 1)
            $map['email'] = $user_id_or_name;
        if($type == 2)
            $map['mobile'] = $user_id_or_name;
        if($type == 3){
            $map['openid'] = $user_id_or_name;
            $map['oauth'] = $oauth;
        }
        $user = M('users_address')->where($map)->find();
        return $user;
    }
    
    function changeCoupon($user_id,$coupon_id){
        if($coupon_id > 0){
            $data3['user_id'] = $user_id;
            $data3['order_id'] = $order_id;
            $data3['use_time'] = $_SERVER['REQUEST_TIME'];
            $data3['user_number'] = 1;
            $data3['spare'] = 0;
            return M('user_coupon')->where("id = $coupon_id")->save($data3);
        }
        return 0;
    }
    
    
    
    /**
     * 获取用户可以使用的优惠券
     * @param type $user_id  用户id
     * @param type $coupon_id 优惠券id
     * 直接返回result
     */
    function getCouponMoney($user_id,$coupon_id)
    {   $six=6*30*24*60*60;
        $coupon=M('user_coupon');
        $couponlist = $coupon->alias('u')
            ->join('left join kts_coupon c on u.c_id= c.id')
            ->where(array('u.id'=>$coupon_id,'u.user_id'=>$user_id,'u.use_time'=>0))
            ->find();  // 获取用户的优惠券
        if ($couponlist['dead_time'] > ($couponlist['add_time']+$six)){
            $menoy=$couponlist['money'];
        }else{
            $menoy=0.00;
        }       
        return $menoy;
    }
    
    
    
    /**
     * 订单操作日志
     * 参数示例
     * @param type $order_id  订单id
     * @param type $action_note 操作备注
     * @param type $status_desc 操作状态  提交订单, 付款成功, 取消, 等待收货, 完成
     * @param type $user_id  用户id 默认为管理员
     * @return boolean
     */
    function logOrder($order_id,$action_note,$status_desc,$user_id = 0)
    {
        $status_desc_arr = array('提交订单', '付款成功', '取消', '等待收货', '完成','退货');
        if(!in_array($status_desc, $status_desc_arr))
          return false;    
        $order = M('order')->where("order_id = $order_id")->find();
        $action_info = array(
            'order_id'        =>$order_id,
            'action_user'     =>$user_id,
            'order_status'    =>$order['order_status'],
            'shipping_status' =>$order['shipping_status'],
            'pay_status'      =>$order['pay_status'],
            'action_note'     => $action_note,
            'status_desc'     =>$status_desc, //''
            'log_time'        =>time(),
        );
        return M('order_action')->add($action_info);
    }
    
    
    
    
    /**
     * 支付完成修改订单
     * $order_sn 订单号
     * $pay_status 默认1 为已支付
     */
    function update_pay_status($user_id,$order_sn,$paytype=0,$pay_status = 2)
    {       
            
            // 如果这笔订单已经处理过了
            $count = M('order')->where("order_sn = '$order_sn' and pay_status = 0")->count();  // 看看有没已经处理过这笔订单  支付宝返回不重复处理操作
            if($count == 0) return false;
            // 找出对应的订单
            $order = M('order')->where("order_sn = '$order_sn'")->find();
            // 修改支付状态  已支付
            $a=M('order')->where("order_sn = '$order_sn'")->save(array('order_status'=>1,'confirm_time'=>$_SERVER['REQUEST_TIME'],'pay_status'=>$pay_status,'pay_time'=>$_SERVER['REQUEST_TIME'],'pay_id'=>'1','pay_name'=>'余额支付'));
            if ($a)
                $code=1;
            // 减少对应商品的库存
            $b=minus_stock($order['order_id']);
            
            //$c=account_log($user_id,$order_id,$used);            
            // 记录订单操作日志
            $tmp=logOrder($order['order_id'],'订单付款成功','付款成功',$order['user_id']);
            if ($tmp>0){
                return $code;
            }
            
    }
    

    // 减少对应商品的库存
     function minus_stock($order_id){
         $orderGoodsArr = M('order_goods')->where("order_id = ". $order_id)->select();
         foreach($orderGoodsArr as $key => $val)
         {   
             if ($val['book_type']==1){
                 $shop_id = $val['shop_id'];
                 if (!$c=M('shop_books')->where(array('book_id'=>$val['book_id'],'shop_id'=>$shop_id))->setInc('saless_num',$val['goods_number'])){
                     $c=M('shop_books')->add(array('book_id'=>$val['book_id'],'shop_id'=>$shop_id,'saless_num'=>$val['goods_number'],'addtime'=>time()));                     
                 }                
                 M('book_inventory')->where(array('book_id'=>$val['book_id']))->setDec('inventory',$val['goods_number']);
             }
             if (!$d=M('book_add')->where(array('book_number'=>$val['book_number']))->setInc('sell_num',$val['goods_number'])){
                 $d=M('book_add')->add(array('book_number'=>$val['book_number'],'sell_num'=>$val['goods_number']));
             }
         }
     }
     
     /**
      *余额流水
      ***/
     function account_log($user_id,$order_id,$oreder_sn,$before,$after,$used,$inc=1){
         $data4['user_id'] = $user_id;
         $data4['before_money'] = $before;
         $data4['after_money'] = $after;
         $data4['change_money'] = $used;
         $data4['pay_points'] = 0;
         $data4['change_time'] = $_SERVER['REQUEST_TIME'];
         $data4['order_sn'] = $oreder_sn;
         $data4['order_id'] = $order_id;
         $data4['desc'] = '下单消费';
         $data4['is_inc']=$inc;
         if ($inc){
             $data4['desc'] = '购买收入';
         }
         return M("user_money_log")->add($data4);

     }
     
     function money_log($user_id,$order_id,$used){ 
         $userinfo=M('user')->find($user_id);
         $order=M('order_info')->find($order_id);
         $data1=array(
             'user_id'=>$user_id,
             'update_time'=>time(),
             'title' =>'商品买入',
             'amount'  =>$used,
             'is_inc' =>0,
         );
         $data2=array(
             'user_id'=>$order['payee'],
             'update_time'=>time(),
             'title' =>'商品卖出',
             'description' =>'来自'.$userinfo['name'].'的付款',
             'amount'  =>$used,
             'payee' =>$user_id,
             'is_inc' =>1,
         );
         if ($order['payee']!=0){            
             $sell=M('user')->where(array('user_id'=>$order['payee']))->getField('name'); 
             $data1['description']='给'.$sell.'的付款';
             $data1['payee']=$order['payee'];
             $data2['user_id']=$order['payee'];
             $data2['payee']=$user_id;
             $tmp=M('money')->where(array('user_id'=>$order['payee']))->setInc('num',$used);
             if (!$tmp) M('money')->add(array('user_id'=>$order['payee'],'num'=>$used));
             
         }if($order['payee_shop']!=0){
             $shop_user=M('shop')
                 ->where(array('shop_id'=>$order['payee_shop']))
                 ->field('user_id,shop_name')
                 ->find();
             $tmp=M('money')->where(array('user_id'=>$shop_user['user_id']))->setInc('num',$used);
             if (!$tmp) M('money')->add(array('user_id'=>$shop_user['user_id'],'num'=>$used));
             
             $data1['description']='给'.$shop_user['shop_name'].'店铺的付款';
             $data1['shop_id']=$order['payee_shop'];
             $data2['user_id']=$shop_user['user_id'];
             $data2['payee']=$user_id;
             $data2['shop_id']=$order['payee_shop'];
         } 
         $a=M('money_xq')->add($data1);
         $a=M('money_xq')->add($data2);
        return  $a;  
     }
     
     
  function integral_log($user_id,$title,$description,$amount,$is_inc=1,$add=1){  
             $Integral=M('user');
             $Integral->startTrans();
             $data=$Integral->where(array('user_id'=>$user_id))->find();
             if ($add){
                    $result=$Integral->where(array('user_id'=>$user_id))->setInc('integral',$amount);
             }else {
                 if ($data['integral'] < $amount) return array('status'=>-1,'msg'=>'你的可用积分为'.$data['integral']);
                 $result=$Integral->where(array('user_id'=>$user_id))->setDec('integral',$amount);       
             }
             if ($result){
                 $arr=array(
                     'user_id'=>$user_id,
                     'update_time'=>time(),
                     'amount'=>$amount,
                     'title'=>$title,
                     'before_change'=>$data['integral'],
                     'after_change'=>($data['integral']+$amount),
                     'description'=>$description,
                     'is_inc'=>$is_inc,
                 );
                 $data=M('integral_xq')->add($arr);            
             }
             if ($data){ $Integral->commit();return 1;}
             else{$Integral->rollback();return 0;}
     }
     
     
     
     
     function get_skipping($order_id){
         $where=array(
             'order_id'=>$order_id,
             'type'=>0,
         );
         //$user_goods=M('order_goods')->where($where)->field('')->select();
         
     }
     
 ?>