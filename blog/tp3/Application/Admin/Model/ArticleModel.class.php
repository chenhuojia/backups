<?php 
namespace Admin\Model;
use Think\Model;
/**
 * 基础model
 */
class ArticleModel extends Model{
           
    protected $_validate = array(
        array('cate_id','require','分类id！'),
        array('introduction','require','简介！'),
        array('title','require','标题！'),
        array('content','require','内容！'),
    );
    
    protected $_auto = array (
        array('addtime','time',1,'function'), 
    );
}