<?php
namespace V1\Model;
use Think\Model;
/**
 * ModelName
 */
class TopicFollowModel extends Model{
    
    // 自动完成
    protected $_auto=array(
        array('addtime','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );

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
     * 关注人所发的话题
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [string] $user_id   [用户的id]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getAllData($map,$orderlist,$user_id,$skip,$take)
    {
        
        $result=$this
            ->alias('f')
            ->join('LEFT JOIN kts_topic as t ON t.user_id = f.follow_user')
            ->join('LEFT JOIN kts_topic_tag as r ON t.tag_id = r.tag_id')
            ->join('LEFT JOIN kts_topic_comment_statistic as c ON t.topic_id = c.topic_id')
            ->join('LEFT JOIN kts_topic_likes_statistic as l ON t.topic_id = l.topic_id')
            ->join('LEFT JOIN kts_user as u ON t.user_id = u.user_id')
            ->join('LEFT JOIN kts_user_xq as x ON t.user_id = x.user_id')
            ->where($map)
            ->field('t.topic_id,t.addtime,t.content,u.name,x.imageurl as user_image,c.discuss_number,l.like_number,r.tag_name')
            ->order($orderlist)
            ->limit($skip,$take)
            ->select();
        foreach ($result as $key => $value) {
          $result[$key]['is_like'] = 0;
          //查询某话题下某用户已经点赞
          $likesmap =array('user_id' =>$user_id,'topic_id'=>$result[$key]['topic_id']);
          $is_like = D('topic_likes')->getOne($likesmap);
          if($is_like){
            $result[$key]['is_like'] = 1;
          }
          //查询某话题下的图片
          $imagemap =array('topic_id'=>$result[$key]['topic_id']);
          $magevalue = 'imageurl';
          $result[$key]['topic_image'] = D('topic_image')->getValue($imagemap,$magevalue);
          if($result[$key]['topic_image']==null){
            $result[$key]['topic_image'] ="";
          }
        }
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
