<?php
namespace Shop\Model;
use Think\Model;
/**
 * ModelName
 */
class ShopApplyModel extends Model{
    
    protected $_validate = array(
        array('legal','require','请填写法定代表人！',1),
        array('phone','/1[34578]\d{9}$/','店铺联系电话格式错误！',1,'regex'),
        array('shop_name','require','请填写店铺名称！',1),
        array('shop_address','require','请填写店铺注册地址！',1),
        array('business_license','require','请填写工商营业执照编号！',1),
        array('bl_time','require','请填写工商营业执照有效期限',1),
        array('business_license_img','require','请填写工商营业执照原件',1),
        array('pbln','require','请填写出版物经营许可证编号！',1),
        array('pbln_time','require','请填写出版物经营许可证有效期限',1),
        array('pbln_img','require','请填写出版物经营许可证',1),
    );
    
    protected $_auto = array(        
        array('user_id','getUserId',1,'callback'),
        array('addtime','getTime',1,'callback'),
        array('order_sn','getOrderSn',1,'callback'),
    );
    
    
    function getUserId(){        
        return session('user_id');
    }
    
    function getTime(){
        return $_SERVER['REQUEST_TIME'];
    }
    
    function getOrderSn(){
        return MyOrderNo22();
    }
}