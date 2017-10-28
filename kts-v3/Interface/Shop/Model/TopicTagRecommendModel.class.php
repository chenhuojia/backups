<?php
namespace V1\Model;
use Think\Model;
/**
 * ModelName
 */
class TopicTagRecommendModel extends Model{
   
   // 自动完成
    protected $_auto=array(
        array('create_time','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );
    /**
     * 所有的推荐的话题栏目
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getAllData($map,$orderlist,$skip,$take)
    {   
        $result=$this
            ->alias('r')
            ->join('LEFT JOIN kts_topic_tag as s ON r.tag_id = s.tag_id')
            ->where($map)
            ->field('r.tag_id,s.tag_name,s.create_time')
            ->order($orderlist.' desc')
            ->limit($skip,$take)
            ->select();
        return $result;
    }

    





}
