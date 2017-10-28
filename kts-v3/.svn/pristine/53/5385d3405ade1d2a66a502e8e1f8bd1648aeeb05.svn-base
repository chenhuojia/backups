<?php
namespace Admin\Model;
use Think\Model;
use Think\Page;

/**
 * ModelName
 */
class TopicTagModel extends BaseModel{
   
    // 自动验证
    protected $_validate=array(
        array('tag_name','require','栏目名称必须填写！'), // 验证字段必填
        array('tag_describe','require','栏目内容必须填写！'), // 验证字段必填
     );
     // 自动完成
    protected $_auto=array(
        array('create_time','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );

    /**
     * 话题列表
     * **/
    public function TopicTagList($where,$pagesize){
        $total=$this->where($where)->count();
        $Page = new Page($total,$pagesize);
        $page=$Page->show();
        $data=$this->where($where)->order('create_time desc')->limit($Page->firstRow,$Page->listRows)->where('is_show=1')->select();
        $tmp=array(
            'page'=>$page,
            'data'=>$data,
        );
        return $tmp;
    }

    /**
     *详情 
     **/
    
    public function TopicTagDetail($map){
        $result=$this->find();         
        return $result;
    }

    /**
     * 添加数据
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
        if(!$result=$this->create()){
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

    
    /**
     * 删除数据
     */
    public function delTag($tag_id){
        if ($tag_id>0){
            return  $this->where(array('tag_id'=>$tag_id))->setField('is_show',0);
        }else{
            return false;
        }
    }

}
