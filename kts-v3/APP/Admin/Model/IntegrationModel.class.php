<?php
namespace Admin\Model;
use Think\Model;
class IntegrationModel extends Model{

    protected $_validate=array(
        array('source','require','积分来源必须!'),
        array('number','number','数值必填!'),
        array('is_deleted','require','请选择是否展示')
    );
    
/*     protected $_auto=array(
        array('is_deleted','gettype',3,'function'),
    ); */
    
    
    private function gettype(){
        return I('post.is_deleted');
    }
}