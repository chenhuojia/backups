<?php
namespace V1\Model;
use Think\Model;
/**
 * ModelName
 */
class TopicImageModel extends Model{

    /**
     * 获取某条数据
     * @param  [string] $map  查询的数据
     * @return [array]       一维数组
     */
    public function getOne($map)
    {
        $result=$this
            ->where($map)
            ->field('image_id,topic_id,imageurl')
            ->find();
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

    /**
     * 获取某个话题下全部图片
     * @param  [string] $data  查询的数据
     * @return [array]         数组
     */
    public function getAllData($data)
    {
        $result=$this
            ->where($data)
            ->field('image_id,topic_id,imageurl')
            ->select();
        return $result;
    }

    /**
     * 添加数据
     */
    public function addData($data){
        // 对data数据进行验证
        if(!$data=$this->create($data)){
            // 验证不通过返回错误
            return false;
        }else{
            // 验证通过
            $result=$this->add($data);
            return $result;
        }
    }

    


}
