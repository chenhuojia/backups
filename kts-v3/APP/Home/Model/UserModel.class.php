<?php
namespace Home\Model;
use Think\Model;

/**
 * ModelName
 */
class UserModel extends Model{
   
     // 自动验证
    protected $_validate=array(
        array('phone','require','手机号码必须填写！'), // 验证字段必填
        array('name','require','用户昵称必须填写！'), // 验证字段必填
        array('password','require','用户密码必须填写！'), // 验证字段必填
     );

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
     * 查询某值是否存在
     * @param  [string] $map  查询的数据
     * @param  [string] $value  匹配的值
     * @return [string]  相关的值
     */
    public function checkValue($map)
    {
        $result=$this
               ->where($map)
               ->find();
        if($result){
          return true;
        }else{
           return false;
        }
       
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
     * 添加新数据
     */
    public function addData($data){
        // 对data数据进行验证
        if(!$data=$this->create()){
            // 验证不通过返回错误
            return false;
        }else{
            // 验证通过
            $result=$this->add($data);
            return $result;
        }
    }

    /**
     * 修改数据
     */
    public function editData($map,$data){
        // 对data数据进行验证
        if(!$data=$this->create()){
            // 验证不通过返回错误
            return false;
        }else{
            // 验证通过
            $result=$this
                ->where(array($map))
                ->save($data);
            return $result;
        }
    }




    


}
