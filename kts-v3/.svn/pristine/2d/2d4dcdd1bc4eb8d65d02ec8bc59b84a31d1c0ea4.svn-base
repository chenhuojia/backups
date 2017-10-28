<?php
/**
 * 定时任务
 *
 */
namespace Home\Controller;
use Think\Controller;
class ScriptController extends Controller{
    
    public function _initialize(){
       self::orderTime();
    }

    public function orderTime()
    {
     $order = M('order')->field('order_id,add_time,user_id')->where(array('order_status'=>array('ELT',1),'shipping_status'=>array('EQ',0),'pay_status'=>array('EQ',0)))->select();
     foreach ($order as $key => $value) {
        if($value['add_time']+15*60<=time()){//15分钟内
              M('order')->where(array('order_id'=>array('EQ',$value['order_id'])))->save(array('order_status'=>3));
              self::logOrder($value['order_id'],'超时取消订单','取消',$value['user_id']);
              
        }
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
    
   
}
//https://segmentfault.com/q/1010000002548618
//http://blog.csdn.net/qq_26329981/article/details/51583473
