<?php
namespace Read\Model;
use Think\Model;
/**
 * 基础model
 */
class BaseModel extends Model{

   /**
     * 添加数据
     */
    public function addData($data){
        // 对data数据进行验证
        if(!$data=$this->create($data)){
            // 验证不通过返回错误
            $returndata['status'] = 0;
            $returndata['data'] = $this->getError();
            return $returndata;
        }else{
            // 验证通过
            $result=$this->add($data);
            $returndata['status'] = 1;
            $returndata['data'] = $result;
            return $returndata;
        }
    }
   /**
     * 修改数据
     */
    public function editData($map,$data){
        // 对data数据进行验证
        if(!$data=$this->create($data)){
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
