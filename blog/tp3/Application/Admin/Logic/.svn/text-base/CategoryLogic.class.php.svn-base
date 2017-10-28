<?php
namespace Admin\Logic;
/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
use Think\Model;
use Think\Page;
class CategoryLogic  extends Model{
    
    public static function getList($list=10){
        $article=M('category');
        $count=$article->count();
        $Page=new Page($count,$list);
        $data=$article
        ->field('*')
        ->limit($Page->firstRow,$Page->listRows)
        ->order('sort desc')
        ->select();
        return array('page'=>$Page,'data'=>$data);     
    }
    
 
}