<?php 
namespace Home\Model;
use Think\Model;
/**
 * 基础model
 */
class CategoryModel extends Model{
    
    
    public  function getAllCategory(){
        $data=$this->field('*')->where(array('parent_id'=>0,'is_show'=>1))->order(array('addtime'=>'desc'))->select();
        return $data;
    } 
    
}