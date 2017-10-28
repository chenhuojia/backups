<?php 
namespace Home\Model;
use Think\Model;
/**
 * 基础model
 */
class CategoryModel extends Model{
    
    protected $_validate = array(
        array('name','require','分类名称！'),
    );
    
    protected $_auto = array (
        array('addtime','time',1,'function'),
        array('parent_id','getParent',3,'callback'),
    );
    
    public function getParent(){
        return I('post.parent_id',0);
    }
    
    public  function getAllCategory(){
        $data=$this->field('*')->where(array('parent_id'=>0,'is_show'=>1))->order(array('addtime'=>'desc'))->select();
        return $data;
    } 
    
}