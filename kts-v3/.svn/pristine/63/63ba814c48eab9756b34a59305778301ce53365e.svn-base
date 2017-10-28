<?php
namespace User\Model;
use Think\Model;
/**
 * ModelName
 */
class UserAddressModel extends Model{
    
    protected $_validate = array(
        array('consignee','require','收货人不能为空',1),
        array('mobile','/1[34578]\d{9}$/','手机号码格式有误',1,'regex'),
        array('consignee','require','收货人不能为空',1),
        array('province','require','所在省份不能为空',1),
        array('city','require','所在城市不能为空',1),
        array('area','require','所在地区不能为空',1),
    );
    
    // protected $_auto = array(        
    //     array('user_id','getUserId',1,'callback'),
    // );
    
    
    // function getUserId(){        
    //     return session('user_id');
    // }
    
}