<?php
namespace Shop\Model;
use Think\Model;
/**
 * ModelName
 */
class ShopShippingModel extends Model{
    
    protected $_validate = array(
           array('shipping_name','require','请填写模块名称！',1),
           array('deliver_address','require','请填写发货地址！',1),
           array('deliver_time','require','请填写发货时间！',1,),
           array('pricing_mode','require','请选择计价方式',1,),
           array('delivery_mode','require','请选择运送方式',1,),
       );
    
    protected $_auto = array(        
        array('shop_id','getShopId',1,'callback'),
        array('addtime','getTime',1,'callback'),
        array('shipping_price','getShippingPrice',1,'callback'),
    );
    
    
    function getShopId(){
        $user_id=session('user_id');        
        return M('shop')->where(array('user_id'=>$user_id))->getField('shop_id');
    }
    
    function getTime(){
        return $_SERVER['REQUEST_TIME'];
    }
    
    function getShippingPrice(){
        return I('post.shipping_price',0);
    }
}