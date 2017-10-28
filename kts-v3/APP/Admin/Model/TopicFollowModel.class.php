<?php
namespace Admin\Model;
use Think\Model;
/**
 * ModelName
 */
class TopicFollowModel extends BaseModel{
   
    // 自动验证
    protected $_validate=array(
        array('follow_user','require','关注的用户必须填'), // 验证字段必填
     );
     // 自动完成
    protected $_auto=array(
        array('addtime','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );

    public function followList($map,$orderlist='',$limit=10){
        $count=$this
            ->alias('f')
            ->join('LEFT JOIN kts_user as u ON u.user_id = f.user_id')
            ->join('LEFT JOIN kts_user as u1 ON u1.user_id = f.follow_user')
            ->where($map)
            ->count();
        $page=new \Think\Page($count,$limit);
        // 获取分页数据
        $result=$this
            ->alias('t')
            ->join('LEFT JOIN kts_topic_tag_recommend as r ON t.tag_id = r.tag_id')
            ->join('LEFT JOIN kts_topic_tag_statistic as s ON t.tag_id = s.tag_id')
            ->where($map)
            ->field('t.tag_id,t.tag_name,tag_describe,t.create_time,t.is_show,s.discuss_number')
            ->order($orderlist)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();      
       
        $data=array(
            'list'=>$result,
            'page'=>$page->show()
            );
        //var_dump($data);die
        return $data;
    }

    public function TopicTagDetail($map){
        $result=$this
            ->alias('t')
            ->join('LEFT JOIN kts_topic_tag_recommend as r ON t.tag_id = r.tag_id')
            ->join('LEFT JOIN kts_topic_tag_statistic as s ON t.tag_id = s.tag_id')
            ->where($map)
            ->field('t.tag_id,t.tag_name,tag_describe,t.create_time,t.is_show,s.discuss_number')
            ->find();         
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
