<?php
/**
 * 用户基本信息CRUD相关
 * @author David
 *
 */
namespace User\Controller;

use Common\Controller\ApiController;
use User\Util;

class OrderController extends ApiController
{
    /*
     * 订单列表
     */
    public function myOrderList()
    {  
       $skip=I('get.skip',0);
       $take=I('get.take',10);
       $user_id=session('user_id');
       $type=I('get.type',0);
       switch ($type) {
           case '0': $where ="user_id=$user_id and order_status!=4";
             break;//全部
           case '1': $where ="user_id=$user_id and order_status!=4 and order_status in (0,1) and shipping_status=0 and pay_status=0";
             break;//代付款
          case '2': $where ="user_id=$user_id and order_status!=4 and order_status=1 and shipping_status in (0,1) and pay_status=2";
             break;//待收货
          case '3': $where ="user_id=$user_id and order_status!=4 and  order_status=5 and shipping_status=2 and pay_status=2";
             break;//待评价
          case '4': $where ="user_id=$user_id and order_status!=4 and order_status=1 and shipping_status in (0,1,3) and pay_status in (3,4)";
             break;//待退款
           default: $this->myApiPrint(300,'参数错误');
             break;
       }
       $data = M('order')
              ->field('order_id,order_status,shipping_status,pay_status,order_amount,order_sn,shop_name,payee,payee_shop,anum')
              ->where($where)->order('order_id desc')->limit($skip,$take)->select();
       foreach ($data as $key => $value) {
          $data[$key]['goods'] = M('order_goods')
                 ->field('order_id,book_id,book_thumb,book_name,book_attr,shop_id,shop_name,publish_price,book_price')
                 ->where(array('is_show'=>1,'order_id'=>$data[$key]['order_id']))->order('order_id desc')->select();
          foreach ($data[$key]['goods'] as $k => $v) {
             $data[$key]['goods'][$k]['book_thumb']= C("QINIU_IMG_PATH").$v['book_thumb'];
          }
          $data[$key]['common_status'] = "";//订单app显示上的状态
          if($data[$key]['order_status']<=1){//订单未确认、已下单
               $data[$key]['btn'] = array('取消订单','付款');
               $data[$key]['common_status'] = "已下单";
          }elseif($data[$key]['order_status']==3) {//已取消订单
               $data[$key]['btn'] = array('删除订单');
               $data[$key]['common_status'] = "已取消";
          }elseif($data[$key]['order_status']==2) {//订单无效
               $data[$key]['common_status'] = "订单无效";
               $data[$key]['btn'] = array('删除订单');
          }elseif($data[$key]['order_status']==5) {//已完成待评价
               $data[$key]['common_status'] = "待评价";
               $data[$key]['btn'] = array('联系商家');
          }elseif($data[$key]['order_status']==6) {//已评价
               $data[$key]['common_status'] = "已评价";
               $data[$key]['btn'] = array('删除订单','联系商家');
          }
          if($data[$key]['pay_status']==1 && $data[$key]['order_status']==1) {//付款中
               $data[$key]['common_status'] = "付款中";
               $data[$key]['btn'] = array('联系商家');
          }elseif($data[$key]['pay_status']==2 && $data[$key]['order_status']==1) {//已付款
               $data[$key]['common_status'] = "已付款";
               $data[$key]['btn'] = array('联系商家','提醒发货');
          }elseif($data[$key]['pay_status']==3 && $data[$key]['order_status']==1) {//退款中
               $data[$key]['common_status'] = "退款中";
               $data[$key]['btn'] = array('联系商家','取消退款');
          }elseif($data[$key]['pay_status']==4 && $data[$key]['order_status']==1) {//已退款
               $data[$key]['common_status'] = "退款成功";
               $data[$key]['btn'] = array('联系商家');
          }
          if($data[$key]['shipping_status']==0 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==2) {//未发货
               $data[$key]['common_status'] = "未发货";
               $data[$key]['btn'] =array('确认收货','延长收货','查看物流');
          }elseif($data[$key]['shipping_status']==1 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==2) {//已发货
               $data[$key]['common_status'] = "已发货";
               $data[$key]['btn'] = array('确认收货','延长收货','查看物流');
          }elseif($data[$key]['shipping_status']==2 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==2) {//已收货
               $data[$key]['common_status'] = "交易成功";
               $data[$key]['btn'] = array('评价','申请退货','删除订单');
          }elseif($data[$key]['shipping_status']==3 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==2) {//备货中
               $data[$key]['common_status'] = "备货中";
               $data[$key]['btn'] = array('联系商家');
          }
          unset($data[$key]['order_status']);
          unset($data[$key]['shipping_status']);
          unset($data[$key]['pay_status']);
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     /**
     * 查看某一条订单的信息
     */
    public function orderDetail()
    {   
        $user_id=session('user_id');
        $order_id=I("get.order_id",0);
        $data = M("order")->alias('o')
                ->join('LEFT JOIN kts_order_info as r ON o.order_id = r.info_id')
                ->field('o.order_id,o.order_status,o.shipping_status,o.shipping_fee,o.pay_status,o.shop_name,o.order_amount,o.order_sn,o.payee,o.payee_shop,o.anum,o.add_time,r.consignee,r.province,r.city,r.street,r.address,r.mobile,r.postscript')
                ->where(array('user_id'=>$user_id,'o.order_id'=>$order_id))->find(); 
        if ($data){
          $data['goods'] = M('order_goods')
               ->field('order_id,book_id,book_thumb,publish_price,book_price,book_name,book_attr')
               ->where(array('user_id'=>$user_id,'order_id'=>$data['order_id']))->order('order_id desc')->select();
          foreach ($data['goods'] as $k => $v) {
             $data['goods'][$k]['book_thumb']= C("QINIU_IMG_PATH").$v['book_thumb'];
          }
          $data['common_status'] = "";//订单app显示上的状态
          if($data['order_status']<=1){//订单未确认、已下单
               $data['common_status'] = "已下单";
          }elseif($data['order_status']==3) {//已取消订单
               $data['common_status'] = "已取消";
          }elseif($data['order_status']==2) {//订单无效
               $data['common_status'] = "订单无效";
          }elseif($data['order_status']==5) {//已完成待评价
               $data['common_status'] = "待评价";
          }elseif($data['order_status']==6) {//已评价
               $data['common_status'] = "已评价";
          }
          if($data['pay_status']==1 && $data['order_status']==1) {//付款中
               $data['common_status'] = "付款中";
          }elseif($data['pay_status']==2 && $data['order_status']==1) {//已付款
               $data['common_status'] = "已付款";
          }elseif($data['pay_status']==3 && $data['order_status']==1) {//退款中
               $data['common_status'] = "退款中";
          }elseif($data['pay_status']==4 && $data['order_status']==1) {//已退款
               $data['common_status'] = "退款成功";
          }
          if($data['shipping_status']==0 && $data['order_status']==1 && $data['pay_status']==2) {//未发货
               $data['common_status'] = "未发货";
          }elseif($data['shipping_status']==1 && $data['order_status']==1 && $data['pay_status']==2) {//发货中
               $data['common_status'] = "发货中";
          }elseif($data['shipping_status']==2 && $data['order_status']==1 && $data['pay_status']==2) {//已发货
               $data['common_status'] = "交易成功";
          }elseif($data['shipping_status']==3 && $data['order_status']==1 && $data['pay_status']==2) {//备货中
               $data['common_status'] = "备货中";
          }
          unset($data['order_status']);
          unset($data['shipping_status']);
          unset($data['pay_status']);
          $this->myApiPrint(200,'success',$data);
        }else{
            $this->myApiPrint(300,'暂无数据');
        } 

    }
    
    /**
     * 删除订单
     */
    public function delOrder()
    {   
        $order_id = I("post.order_id"); // 订单的 id
        $user_id=session('user_id');
        $data['order_status'] = 5;
        $order = M("order")->field("order_status,shipping_status,pay_status")->where(array('order_id'=>$order_id,'user_id'=>$user_id))->find();
        if(empty($order)) $this->myApiPrint(300,'该订单不存在，不用删除');
        else if($order['order_status']==4) $this->myApiPrint(300,'该订单已删除,勿重复操作');
        else if($order['shipping_status']==1) $this->myApiPrint(300,'该订单正在发货,请与商家联系');
        else if($order['shipping_status']==3) $this->myApiPrint(300,'该订单正在备货中,请与商家联系');
        else if($order['pay_status']==1) $this->myApiPrint(300,'该订单正在付款,请稍后操作');
        else if($order['pay_status']==3) $this->myApiPrint(300,'该订单正在退款中,请稍后操作');
        else   $result = M("order")->where(array('order_id'=>$order_id,'user_id'=>$user_id))->save(array('order_status'=>4)); 
        if($result){
          M("order_action")->data(array('order_id'=>$order_id,'action_user'=>$user_id,'order_status'=>4,'shipping_status'=>$order['shipping_status'],'pay_status'=>$order['pay_status'],'action_note'=>'删除订单','log_time'=>time()))->add();
          $this->myApiPrint(200,'删除成功');
        }else{ $this->myApiPrint(300,'删除失败');}
    }
    /**
     * 取消订单
     */
    public function removeOrder()
    {   
        $order_id=I("post.order_id",0);
        $user_id=session('user_id');
        //$notesID = M("order_info")->where($where)->find();
        $order = M("order")->field("order_status,shipping_status,pay_status")->where(array('order_id'=>$order_id,'user_id'=>$user_id))->find();
        if(empty($order)) $this->myApiPrint(300,'该订单不存在，不用取消');
        else if($order['order_status']==3) $this->myApiPrint(300,'该订单已取消,勿重复操作');
        else if($order['order_status']==4) $this->myApiPrint(300,'该订单已删除');
        else if($order['shipping_status']==1) $this->myApiPrint(300,'该订单正在发货,请与商家联系');
        else if($order['shipping_status']==3) $this->myApiPrint(300,'该订单正在备货中,请与商家联系');
        else if($order['pay_status']==1) $this->myApiPrint(300,'该订单正在付款,请稍后操作');
        else if($order['pay_status']==2) $this->myApiPrint(300,'该订单已付款,请稍后操作');
        else if($order['pay_status']==3) $this->myApiPrint(300,'该订单正在退款中,请稍后操作');
        else if($order['pay_status']==4) $this->myApiPrint(300,'该订单退款完成,请直接删除订单即可');
        else   $result = M("order")->where(array('order_id'=>$order_id,'user_id'=>$user_id))->save(array('order_status'=>3)); 
        $goods=M('order_goods')->where(array('order_id'=>$order_id))->field('book_id,goods_number')->select();     
        foreach ($goods as $v){
            M('book_inventory')->where(array('book_id'=>$v['book_id']))->setInc('inventory',$v['goods_number']);
        }        
        if ($result){
              logOrder($order_id,'您取消了订单','取消订单',$user_id);
            $this->myApiPrint(200,'取消成功');
        }else{
            $this->myApiPrint(300,'失败');
        } 

    }

    /**
     * 提醒订单发货
     */
    public function orderRemind()
    {
        $order_id = I("post.order_id"); // 订单的 id
        $user_id=session('user_id');
        $remind = M("remind")->where(array('content'=>$order_id,'customer_user'=>$user_id,'type'=>1))->find();
        if(!empty($remind)) $this->myApiPrint(300,'您已经提醒过了');
        $where['shop_id']=I("post.shop_id");
        $where['customer_user'] =  $user_id;
        $where['customer_name'] =  session('name');
        $where['customer_phone']=  session('phone');
        $where['content']= $order_id;  
        $where['addtime']= time();
        $where['title']= "用户【".$where['customer_name']."】请求发货";  //用户请求发送
        $result = M("remind")->add($where); 
        if ($result){
            $this->myApiPrint(200,'提醒成功');
        }else{
           $this->myApiPrint(300,'提醒失败');
        } 

    }

    /**
     *延长收货
     */
    public function extendDelivery()
    {   
        $order_id = I("post.order_id"); // 订单的 id
        $user_id=session('user_id');
        $order = M("order")->where(array('order_id'=>$order_id,'user_id'=>$user_id))->find();
        if(empty($order)) $this->myApiPrint(300,'该订单不存在，不用取消');
        else if($order['is_extend']==1) $this->myApiPrint(300,'该订单已延长收货过了,勿重复操作');
        else $result =M('order')->where(array('order_id'=>$order_id,'user_id'=>$user_id))->save(array('is_extend'=>1));
        if ($result) $this->myApiPrint(200,'延长成功');
        else $this->myApiPrint(300,'延长失败');

    }

    /**
     *申请退货
     */
    public function returnDelivery()
    {   
        $where['order_id'] = I("post.order_id"); // 订单的 id
        $where['user_id']=session('user_id');
        $refund = M("order_refund")->field("is_agree,is_show")->where(array('order_id'=>$where['order_id'],'user_id'=>$where['user_id']))->find();
        if($refund['is_agree']==2) $this->myApiPrint(300,'商家正在处理,请耐心等待');
        if($refund['is_agree']==1) $this->myApiPrint(300,'商家已经同意过您之前的退款申请,请联系商家');
        if($refund && $refund['is_show']==0) { 
          logOrder($order_id,'申请退货再次提交','退货',$user_id);
          M("order_refund")->where(array('order_id'=>$where['order_id'],'user_id'=>$where['user_id']))->save(array('is_show'=>1));
          $this->myApiPrint(200,'您的申请已经提交,请耐心等待');
        }
        $where['shop_id']=I("post.shop_id");
        $where['goods_return']=I("post.goods_return",1);
        $where['is_agree']=2;
        $where['reason'] = I("post.reason");
        $where['apply_price']= I("post.apply_price");   
        $where['addtime']= time();
        $result = M("order_refund")->add($where); 
        if ($result){
          logOrder($order_id,'申请退货再次提交','退货',$user_id);$this->myApiPrint(200,'您的申请已经提交,请耐心等待');} 
        else {$this->myApiPrint(300,'提交失败');}

    }

    

    /**
     *订单评价
     */
    public function orderEvaluate()
    {   
        
        // $comment ='{"order_id":1,"book_id":"1","book_id":"1","username":"名字","email":"141646@qq.com","content":"评论","deliver_rank":"3","img":"http://www.baidu.com;http://www.baidu.com;http://www.baidu.com;","goods_rank":"3","service_rank":"3"}';
        $comment=json_decode($comment,true);
        if($comment['content']==""){
            $this->myApiPrint(300,'评论内容不可为空！');
        }
        $where['user_id']=session('user_id');
        $orderInfo = M("order_comment")->where(array('user_id'=>$where['user_id'],'order_id'=>$comment['order_id']))->find();
        if(!empty($orderInfo)) $this->myApiPrint(300,'该订单已经评论过了！');
        $order=M('order')->where(array('order_status'=>1,'pay_status'=>2,'shipping_status'=>2))->find($comment['order_id']);
        if (!$order) $this->myApiPrint(300,'订单尚未收货');
        $where['order_id'] = $comment['order_id']; // 订单的 id
        $where['book_id']=$comment['book_id'];
        $where['username']="";
        $where['content']=$comment['content'];
        $where['deliver_rank'] = $comment['deliver_rank'];
        $where['add_time'] = time();
        $where['ip_address'] = $_SERVER["REMOTE_ADDR"];
        $where['img']= $comment['img'];   
        $where['goods_rank']= $comment['goods_rank'];
        $where['service_rank']= $comment['service_rank'];
        $result = M("order_comment")->add($where); 
        if ($result){
            M("order")->where(array('order_id'=>$comment['order_id'],'user_id'=>$where['user_id']))->save(array('order_status'=>5)); 
            logOrder($comment['order_id'],'完成订单评价','订单评价',$where['user_id']);
            $this->myApiPrint(200,'评论成功');
        }else{
            $this->myApiPrint(300,'评论失败');
        } 

    }

    /**
     *评价列表
     */
    public function orderCommentList()
    {   
        $where['book_id'] = I('get.book_id');
        $skip = I('get.skip');
        $take = I('get.take');
        $data=M('order_comment')
                    ->where($where)
                    ->limit($skip,$take)
                    ->select();
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     /**
     *申请退款
     */
    public function refund(){
        if(IS_POST){
            $order_id=I('post.order_id');
            $user_id=session('user_id');
            $goods_return=I('post.goods_return',0);
            $apply_price=I('post.apply_price',0);
            $reason=I('post.reason');
            $instructions=I('post.instructions');
            if (!$apply_price) $this->myApiPrint(300,'请输入金额');
            if (!$reason) $this->myApiPrint(300,'请输入退款原因');
            $order=M('order')->where(array('order_status'=>1,'pay_status'=>2))->find($order_id);
            if (!$order) $this->myApiPrint('订单有误',300);
            if ($apply_price > $order['order_amount'])   $this->myApiPrint(300,'请输入正确的退款金额');
            $refund = M("order_refund")->field("is_agree,is_show")->where(array('order_id'=>$order_id,'user_id'=>$user_id))->find();
            if($refund['is_agree']==2) $this->myApiPrint(300,'商家正在处理,请耐心等待');
            if($refund['is_agree']==1) $this->myApiPrint(300,'商家已经同意过您之前的退款申请,请联系商家');
            if($refund && $refund['is_show']==0) { 
              $two=M("order_refund")->where(array('order_id'=>$order_id,'user_id'=>$user_id))->save(array('is_show'=>1));
              if($two){
                    logOrder($order_id,'申请退款再次提交','申请退款',$user_id);
                    $this->myApiPrint(200,'您的申请已经提交,请耐心等待');
              }else{
                   $this->myApiPrint(300,'申请失败');
              }
            }
            $data=array(
                'order_id'=>$order_id,
                'reason'=>$reason,
                'user_id'=>$user_id,
                'goods_return'=>$goods_return,
                'apply_price'=>$apply_price,
                'addtime'=>time(),
                'instructions'=>$instructions,
            );
            if ($order['payee']==0 && $order['payee_shop'] !=0){
                $data['shop_id']=$order['payee_shop'];
            }elseif ($order['payee'] != 0 && $order['payee_shop'] == 0){
                $data['sell_id']=$order['payee'];
            }
            $result=M('order_refund')->add($data);
            if ($result) {
              logOrder($order_id,'申请退款提交','申请退款',$user_id);
              $this->myApiPrint(200,'申请成功,请耐心等待');
            }else{
              $this->myApiPrint(300,'申请失败');
            } 
        }
    }

     /**
     * 确认收货
     */
    public function confirmReceipt()
    {   
        $order_id = I("post.order_id"); // 订单的 id
        $user_id=session('user_id');
        $order = M("order")->field("order_status,shipping_status,pay_status")->where(array('order_id'=>$order_id,'user_id'=>$user_id))->find();
        if(empty($order)) $this->myApiPrint(300,'该订单不存在');
        else if($order['order_status']==2) $this->myApiPrint(300,'该订单无效');
        else if($order['order_status']==3) $this->myApiPrint(300,'该订单已经取消');
        else if($order['order_status']==4) $this->myApiPrint(300,'该订单已删除,勿重复操作');
        else if($order['shipping_status']==0) $this->myApiPrint(300,'该订单未发货,请与商家联系');
        else if($order['shipping_status']==2) $this->myApiPrint(300,'该订单已收货了');
        else if($order['shipping_status']==3) $this->myApiPrint(300,'该订单正在备货中,请与商家联系');
        else if($order['pay_status']==0) $this->myApiPrint(300,'该订单未付款');
        else if($order['pay_status']==1) $this->myApiPrint(300,'该订单正在付款,请稍后操作');
        else if($order['pay_status']==4) $this->myApiPrint(300,'该订单已经退款,暂不支持该操作');
        else if($order['pay_status']==3) $this->myApiPrint(300,'该订单正在退款中,请稍后操作');
        else   $result = M("order")->where(array('order_id'=>$order_id,'user_id'=>$user_id))->save(array('shipping_status'=>2)); 
        if($result){
          M("order_action")->data(array('order_id'=>$order_id,'action_user'=>$user_id,'order_status'=>$order['order_status'],'shipping_status'=>2,'pay_status'=>$order['pay_status'],'action_note'=>'确认收货','log_time'=>time()))->add();
          $this->myApiPrint(200,'确认收货成功');
        }else{ $this->myApiPrint(300,'确认收货失败');}
    }

     /**
     *取消退款
     */
    public function cancellationRefund()
    {   
        $where['order_id'] = I("post.order_id"); // 订单的 id
        $where['user_id']=session('user_id');
        $refund = M("order_refund")->field("is_agree")->where(array('order_id'=>$where['order_id'],'user_id'=>$where['user_id']))->find();
        if(empty($refund)) $this->myApiPrint(300,'该订单没提交过申请退款');
        if($refund['is_agree']==1) $this->myApiPrint(300,'商家已经同意过您之前的退款申请,请联系商家');
        $result = M("order_refund")->where(array('order_id'=>$where['order_id'],'user_id'=>$where['user_id']))->save(array('is_show'=>0)); 
        if ($result) {
          logOrder($where['order_id'],'取消退款提交','取消退款',$where['user_id']);
          $this->myApiPrint(200,'您的申请退款已经取消');}
        else {$this->myApiPrint(300,'提交失败');}

    }

    /*****************二手书书主操作**********************/
     //设置二手订单的货运
    public function setOrderShipping()
    {   
        $order_id = I("post.order_id",0); // 订单的 id
        $user_id=session('user_id');
        $invoice_no = I("post.invoice_no",'');
        if(empty($invoice_no)) $this->myApiPrint(300,'发货订单号不为空');
        $order = M("order")->field("user_id,order_status,shipping_status,pay_status")->where(array('order_id'=>$order_id,'payee'=>$user_id,'order_status'=>array('neq',4)))->find();
        if(empty($order)) $this->myApiPrint(300,'该订单不存在或者您没有该权限');
        if(($order['order_status']==1) && ($order['shipping_status']==0) && ($order['pay_status']==2)){
            $result = M("order")->where(array('order_id'=>$order_id))->save(array('shipping_status'=>1,'invoice_no'=>$invoice_no)); 
            if($result){
              M("order_action")->data(array('order_id'=>$order_id,'action_user'=>$order['user_id'],'order_status'=>$order['order_status'],'shipping_status'=>1,'pay_status'=>$order['pay_status'],'action_note'=>'发货中','log_time'=>time()))->add();
              $this->myApiPrint(200,'确认收货成功');
            }else{ $this->myApiPrint(300,'确认收货失败');}
        }else{
          $this->myApiPrint(300,'该订单尚不满足发货条件');
        }
    }

   /**
     *二手书书主同意退款
     */
    public function OderCancellationRefund()
    {   
        $order_id = I("post.order_id"); // 订单的 id
        $user_id=session('user_id');
        $refund = M("order_refund")->field("is_agree,user_id")->where(array('order_id'=>$order_id,'sell_id'=>$user_id))->find();
        if(empty($refund)) $this->myApiPrint(300,'该订单没提交过申请退款');
        if($refund['is_agree']==1) $this->myApiPrint(300,'同意过退款申请了');
        if($refund['is_agree']==0) $this->myApiPrint(300,'拒绝过退款申请了');
        $result = M("order_refund")->where(array('order_id'=>$order_id,'sell_id'=>$user_id))->save(array('is_agree'=>1,'replytime'=>time())); 
        if ($result) {
          M("order")->where(array('order_id'=>$order_id))->save(array('pay_status'=>4));
          logOrder($order_id,'卖主同意退款','退款成功',$refund['user_id']);
          $this->myApiPrint(200,'已经同意同款');}
        else {$this->myApiPrint(300,'提交失败');}

    }

   /**
     *二手书书主不同意退款
     */
    public function OderUnCancellationRefund()
    {   
        $order_id = I("post.order_id"); // 订单的 id
        $user_id=session('user_id');
        $reply_price = I("post.reply_price",0);
        $reply = I("post.reply",'');
        $refund = M("order_refund")->field("is_agree,user_id")->where(array('order_id'=>$order_id,'sell_id'=>$user_id))->find();
        if(empty($refund)) $this->myApiPrint(300,'该订单没提交过申请退款');
        if($refund['is_agree']==1) $this->myApiPrint(300,'同意过退款申请了');
        if($refund['is_agree']==0) $this->myApiPrint(300,'拒绝过退款申请了');
        $result = M("order_refund")->where(array('order_id'=>$order_id,'sell_id'=>$user_id))->save(array('is_agree'=>0,'reply'=>$reply,'reply_price'=>$reply_price,'replytime'=>time())); 
        if ($result) {
          //M("order")->where(array('order_id'=>$order_id))->save(array('pay_status'=>5));
          logOrder($order_id,'卖主拒绝退款','拒绝退款',$refund['user_id']);
          $this->myApiPrint(200,'拒绝退款');}
        else {$this->myApiPrint(300,'提交失败');}

    }

    //退款页面信息
    public function refundInfo()
    {   
        $order_id = I("post.order_id"); // 订单的 id
        $data = M('order')
              ->field('order_id,order_status,user_id,shipping_status,pay_status,order_amount,order_sn,shop_name,payee,payee_shop,anum')
              ->where(array('order_id'=>$order_id))
              ->find();
         if(!empty($data)){
             $data['refund'] = M('order_refund')->field('reason,addtime')->where(array('order_id'=>$order_id))->find();
             $userMes = \User\Util\Util::GetUserAvatrAndNick($data['user_id']);
             $data['username'] = $userMes['name'];
             $data['user_avatar'] = $userMes['avatar'];
             $this->myApiPrint(200,'成功',$data);
         }else{
            $this->myApiPrint(202,'暂无数据');
         }
         
    }
    

 

}

