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
 * 系统缓存缓存管理
 * @param mixed $name 缓存名称
 * @param mixed $value 缓存值
 * @param mixed $options 缓存参数
 * @return mixed
 */
function cache($name, $value = '', $options = null) {
    static $cache = '';
    if (empty($cache)) {
        $cache = \Think\Cache::getInstance();
    }
    // 获取缓存
    if ('' === $value) {
        if (false !== strpos($name, '.')) {
            $vars = explode('.', $name);
            $data = $cache->get($vars[0]);
            return is_array($data) ? $data[$vars[1]] : $data;
        } else {
            return $cache->get($name);
        }
    } elseif (is_null($value)) {//删除缓存
        return $cache->remove($name);
    } else {//缓存数据
        if (is_array($options)) {
            $expire = isset($options['expire']) ? $options['expire'] : NULL;
        } else {
            $expire = is_numeric($options) ? $options : NULL;
        }
        return $cache->set($name, $value, $expire);
    }
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

/*
 * 产生随机字符
 * $length  int 生成字符传的长度
 * $numeric  int  , = 0 随机数是大小写字符+数字 , = 1 则为纯数字
*/
function randCodeM($length, $numeric = 0)
{
    $seed = base_convert(md5(print_r($_SERVER, 1) . microtime()), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    $hash = '';
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $seed[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * 简单对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 * @return String
 */
function myEncode($string = '')
{
    if(empty($string)) return '';
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split(C('PASS_KEY')) as $key => $value)
        $key < $strCount && $strArr[$key] .= $value;
    return str_replace(array('+','/'), array('-','_'), join('', $strArr));
}

/**
 * 简单对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 * @return String
 */
function myDecode($string = '')
{
    if(empty($string)) return '';
    $strArr = str_split(str_replace(array('-','_'),array('+','/'),  $string), 2);
    $strCount = count($strArr);
    foreach (str_split(C('PASS_KEY')) as $key => $value)
        $key <= $strCount && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}

/**
 * 用户数据 DES加密
 * @param String $str 需要加密的字串
 * @param String $skey 加密EKY
 * @return String
 */
function myDes_encode($str, $key)
{
    $va = \Think\Crypt\Driver\Des::encrypt($str, $key.C('PASS_KEY'));
    $va = base64_encode($va);
    return str_replace(array('+','/'), array('-','_'), $va);
}

/**
 * 用户数据 DES解密
 * @param String $str 需要解密的字串
 * @param String $skey 解密KEY
 * @return String
 */
function myDes_decode($str, $key)
{
    $str = str_replace(array('-','_'), array('+','/'), $str);
    $str = base64_decode($str);
    $va = \Think\Crypt\Driver\Des::decrypt($str, $key.C('PASS_KEY'));
    return trim($va);
}

//处理订单函数


    function check_mobile($mobile){
        if(preg_match('/1[34578]\d{9}$/',$mobile))
            return true;
        return false;
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
        $status_desc_arr = array('提交订单', '付款成功', '取消', '等待收货', '完成','退货','退款成功');
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
    function update_pay_status($order_sn,$pay_time,$pay_sn=0,$paytype=0,$pay_status = 2)
    {       
            $orderModel= M('order');
            $orderModel->startTrans();
            // 如果这笔订单已经处理过了
            $count = $orderModel->where("order_sn = '$order_sn' and pay_status = 0")->count();  // 看看有没已经处理过这笔订单  支付宝返回不重复处理操作
            if($count == 0) return 300;
            // 找出对应的订单
            $order =$orderModel->where("order_sn = '$order_sn'")->find();
            // 修改支付状态  已支付
            if ($paytype==1){
                $pay_id=2;
                $pay_name="支付宝";
            }elseif ($paytype==0){
                $pay_id=1;
                $pay_name="余额";
            }elseif ($paytype==2){
                $pay_id=3;
                $pay_name="微信";
            }
            $data=array(
                /* 'order_status'=>1,
                'confirm_time'=>$_SERVER['REQUEST_TIME'], */
                'pay_status'=>$pay_status,
                'pay_sn'=>$pay_sn,
                'pay_time'=>$pay_time,
                'pay_id'=>$pay_id,
                'pay_name'=>$pay_name,
                'deadline'=>0, 
            );
            if ($pay_sn){
                $data['pay_sn']=$pay_sn;
            }
            $a=$orderModel->where("order_sn = '$order_sn'")->save($data);
            if ($a)
            {
                $code=1;  
                $i=add_goods_count($order['order_id']);
                // 记录订单操作日志
                $h=logOrder($order['order_id'],'订单付款成功','付款成功',$order['user_id']);
                $d=1;
                if ($order['payee_shop']){
                    $d=update_shop_income($order['user_id'], $order['payee_shop'], $order['order_amount']);
                }
                if ($i && $h && $d){
                    $orderModel->commit();
                    return 200;
                }
            }            
            $orderModel->rollback();
            return 300;
    }
    
    function update_refund_status($order_sn,$pay_status = 4){
        $count = M('order')->where("order_sn = '$order_sn' and pay_status = 3")->count();  // 看看有没已经处理过这笔订单  支付宝返回不重复处理操作
        if($count == 0) return false;
        $order = M('order')->where("order_sn = '$order_sn'")->find();
        $a=M('order')->where("order_sn = '$order_sn'")->save(array('pay_status'=>4));
        if ($a){
            $tmp=logOrder($order['order_id'],'订单退款成功','退款成功',$order['user_id']);
            if ($tmp){$code=1;}
        }
        
    }

    // 减少对应商品的库存
     function minus_stock_one($book_id,$goods_number){
         $orderGoodsArr = M('book')->find($book_id);  
         $b=1;
         if ($orderGoodsArr['type']==1){
             $shop_id = $orderGoodsArr['shop_id'];               
             $b=M('book_inventory')->where(array('book_id'=>$book_id))->setDec('inventory',$goods_number);
         }        
         return $b;
     }
     
     // 减少对应商品的库存
     function minus_stock($order_id){
         $orderGoodsArr = M('order_goods')->where(array('order_id'=>$order_id))->select();
         if ($orderGoodsArr){
             foreach ($orderGoodsArr as $K=>$v){
                 if ($v['book_type']==1){
                     $book_id .=$v['book_id'].',';
                     $sql2 .= sprintf("WHEN %d THEN %s - %d ", $v['book_id'],'inventory',$v['goods_number']);
                 }
             }
         }
         $d=1;
         if ($book_id){
             $book_id=substr($book_id,0,-1);
             $d=M()->execute('update kts_book_inventory set inventory = case book_id '.$sql2.' end, WHERE book_id  in '."($book_id)");
         }
         return $d;
     }
     
     // 减少对应商品的库存
     function add_goods_count($order_id){
         $orderGoodsArr = M('order_goods')->where(array('order_id'=>$order_id))->select();      
         if ($orderGoodsArr){
             foreach ($orderGoodsArr as $K=>$v){
                 if ($v['type']==1){
                     $book_id .=$v['book_id'].',';
                     $goods_numbers= $v['goods_number']+$goods_numbers;
                     $sql3  .= sprintf("WHEN %d THEN  %s + %d ", $v['book_id'],'saless_num',$v['goods_number']);
                     //M('shop_books')->where(array('book_id'=>$v['book_id'],'shop_id'=>$shop_id))->setInc('saless_num',$v['goods_number']);
                 }
                 $books_id .=$v['book_id'].',';
                 $sql .=sprintf("WHEN %d THEN %s + %d ", $v['book_id'],'sell_num',$v['goods_number']);
             }
         }
         $d=1;
         if ($book_id){
             $book_id=substr($book_id,0,-1);
             $shop_id = $orderGoodsArr[0]['shop_id'];             
             M()->execute('update kts_shop_books set saless_num = case book_id '.$sql3.' end WHERE book_id  in '."($book_id)");           
         }
         $books_id=substr($books_id,0,-1);
         $d=M()->execute('update kts_book_add set sell_num = case book_id '.$sql.' end WHERE book_id  in '."($books_id)");
         return $d;
     }
     
     /**
      *余额流水
      ***/
     function account_log($user_id,$user_name,$order_id,$oreder_sn,$before,$after,$used,$inc=1,$g=1,$type=0){
         $data4['user_id'] = $user_id;
         $data4['before_money'] = $before;
         $data4['after_money'] = $after;
         $data4['change_money'] = $used;
         $data4['pay_points'] = 0;
         $data4['change_time'] = $_SERVER['REQUEST_TIME'];
         $data4['order_sn'] = $oreder_sn;
         $data4['order_id'] = $order_id;        
         $data4['is_inc']=$inc;
         $data4['title'] = '商品买入';
         $data4['pay_user_name'] = $user_name;
         $data4['type'] = $g;
         if ($inc){
             $data4['title'] = '商品卖出';
         }
         if ($type){
             $data4['title'] = '退款';
         }
         return M("user_money_log")->add($data4);

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
     
     
     
     
     function update_shop_income($user_id,$shop_id,$income,$exp=0){
         $shop=M('shop')->find($shop_id);
         $data=array(
             'user_id'=>$user_id,
             'shop_id'=>$shop_id,
             'shop_name'=>$shop['shop_name'],
             'income'=>$income,
             'add_time'=>$_SERVER['REQUEST_TIME'],
             'before'=>$shop['income_num'],
         );
         $data['after']=bcadd($shop['income_num'],$income,2);
         if ($exp){
             $data['income']=0;
             $data['expend']=$income;
             $data['after']=bcsub($shop['income_num'],$income,2);
             M('shop')->where(array('shop_id'=>$shop_id))->setDec('income_num',$income);
         }else{
             M('shop')->where(array('shop_id'=>$shop_id))->setInc('income_num',$income);
         }         
         return M('shop_income')->add($data);
     }
     
     

     /**
      * 支付完成修改订单
      * $order_sn 订单号
      * $pay_status 默认1 为已支付
      */
     function update_shop_pay_status($order_sn,$pay_time,$pay_sn=0,$paytype=0,$pay_status = 2)
     {
     
         // 如果这笔订单已经处理过了
         $count = M('shop_cash_deposit')->where("order_sn = '$order_sn' and pay_status = 0")->count();  // 看看有没已经处理过这笔订单  支付宝返回不重复处理操作
         if($count == 0) return false;
         // 找出对应的订单
         $order = M('shop_cash_deposit')->where("order_sn = '$order_sn'")->find();
         // 修改支付状态  已支付
         if ($paytype==1){
             $pay_id=2;
             $pay_name="支付宝";
         }elseif ($paytype==0){
             $pay_id=1;
             $pay_name="余额";
         }elseif ($paytype==2){
             $pay_id=3;
             $pay_name="微信";
         }
         $data=array(
             /* 'order_status'=>1,
              'confirm_time'=>$_SERVER['REQUEST_TIME'], */
             'pay_status'=>$pay_status,
             'pay_sn'=>$pay_sn,
             'pay_time'=>$pay_time,
             'pay_id'=>$pay_id,
             'pay_name'=>$pay_name,
         );
         if ($pay_sn){
             $data['pay_sn']=$pay_sn;
         }
         $a=M('shop_cash_deposit')->where("order_sn = '$order_sn'")->save($data);
         if ($a)
         {
             $code=1;
             // 记录订单操作日志
             $tmp=logShopOrder($order_sn,'提交保证金','付款成功',$order['user_id']);
             return $tmp;
         }
         if ($tmp>0){
             return $code;
         }
     
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
     function logShopOrder($order_sn,$action_note,$status_desc,$user_id = 0)
     {
         $status_desc_arr = array('提交订单', '付款成功', '取消', '等待收货', '完成');
         if(!in_array($status_desc, $status_desc_arr))
             return false;
         $order = M('shop_cash_deposit')->where("order_sn = $order_sn")->find();
         $action_info = array(
             'order_sn'        =>$order_sn,
             'action_user'     =>$user_id,
             'order_status'    =>$order['order_status'],
             'pay_status'      =>$order['pay_status'],
             'action_note'     => $action_note,
             'status_desc'     =>$status_desc, //''
             'log_time'        =>$_SERVER['REQUEST_TIME'],
         );
         return M('shop_cash_deposit_action')->add($action_info);
     }
 ?>