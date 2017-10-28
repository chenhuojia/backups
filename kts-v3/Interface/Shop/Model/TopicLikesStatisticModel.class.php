<?php
namespace V1\Model;
use Think\Model;
/**
 * ModelName
 */
class TopicLikesStatisticModel extends Model{
   
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
     * 添加数据
     */
    public function addData($data){
        // 对data数据进行验证
        if(!$data=$this->create($data)){
            // 验证不通过返回错误
            return $this->getError();
        }else{
            // 验证通过
            $result=$this->add($data);
            return $result;
        }
    }

    


}
