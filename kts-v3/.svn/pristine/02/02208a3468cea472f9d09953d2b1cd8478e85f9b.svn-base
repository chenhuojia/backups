<?php
namespace V1\Model;
use Think\Model;
/**
 * ModelName
 */
class UserModel extends Model{
   
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
     * 某用户详情
     * @param  [array] $map        [查询的值]
     * @return [array]             [数组]
     */
    public function getOneDetail($map)
    {
        $result = $this->alias('u')
                   ->join('LEFT JOIN kts_user_xq v ON u.user_id = v.user_id')          
                   ->field('u.name,v.imageurl')
                   ->where($map)
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


    


}
