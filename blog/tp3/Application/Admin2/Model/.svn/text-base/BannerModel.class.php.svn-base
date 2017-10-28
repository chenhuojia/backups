<?php 
namespace Home\Model;
use Think\Model;
/**
 * 基础model
 */
class BannerModel extends Model{
           
    public  function getIndex(){
        $data=$this->field('*')->where(array('show_addr'=>'index','is_show'=>1))->order(array('addtime'=>'desc'))->select();
        return $data;
    } 
    
}