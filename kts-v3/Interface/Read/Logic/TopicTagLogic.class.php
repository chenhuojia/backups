<?php
namespace Read\Logic;
use Think\Model;
/**
 * ModelName
 */
class TopicTagLogic extends Model{
    
    /**
     * 获取某条数据
     * @param  [string] $map  查询的数据
     * @return [array]       一维数组
     */
    public function getOne($map)
    {
        $result=$this
            ->where($map)
            ->find();
        return $result;
    }

    /**
     * 所有的话题栏目
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @return [array]             [数组]
     */
    public function searchAllData($map)
    {

        $result=$this
            ->where($map)
            ->field('tag_id,tag_name,create_time')
            ->select();
        return $result;
    }


    /**
     * 根据查询获取某值
     * @param  [string] $map  查询的数据
     * @param  [string] $value  匹配的值
     * @return [string]  相关的值
     */
    public function getValue($map,$value)
    {
        $result=$this
               ->where($map)
               ->getField($value);
        return $result;
    }
    


}
