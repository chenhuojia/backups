<?php
namespace V1\Model;
use Think\Model;
/**
 * ModelName
 */
class TopicRrecommendModel extends Model{
   
   // 自动完成
    protected $_auto=array(
        array('create_time','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );
    
    public function getAllData($map,$skip,$take)
    {

        $result=$this
            ->alias('r')
            ->field('r.t_r_id,s.tag_name,s.create_time')
            ->join('LEFT JOIN kts_topic_tag as s ON r.t_t_id = s.t_t_id')
            ->select();
        return $result;
    }


}
