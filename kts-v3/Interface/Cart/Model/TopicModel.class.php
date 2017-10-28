<?php
namespace V1\Model;
use Think\Model;
/**
 * ModelName
 */
class TopicModel extends Model{
   
    // 自动验证
    protected $_validate=array(
        array('tag_id','require','请选择相关话题栏目！'), // 验证字段必填
        array('content','require','内容必须填写！'), // 验证字段必填
     );
   // 自动完成
    protected $_auto=array(
        array('addtime','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
        array('updtime','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );
    

    /**
     * 所有的话题
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
            ->alias('t')
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
     * 某话题详情
     * @param  [array] $map        [查询的值]
     * @param  [string] $user_id   [相关用户]
     * @return [array]             [数组]
     */
    public function getOneDetail($map,$user_id)
    {

        $result=$this
            ->alias('t')
            ->join('LEFT JOIN kts_topic_tag as r ON t.tag_id = r.tag_id')
            ->join('LEFT JOIN kts_topic_comment_statistic as c ON t.topic_id = c.topic_id')
            ->join('LEFT JOIN kts_topic_likes_statistic as l ON t.topic_id = l.topic_id')
            ->join('LEFT JOIN kts_user as u ON t.user_id = u.user_id')
            ->join('LEFT JOIN kts_user_xq as x ON t.user_id = x.user_id')
            ->where($map)
            ->field('t.topic_id,t.addtime,t.content,u.name,x.imageurl as user_image,c.discuss_number,l.like_number,r.tag_name')
            ->find();

          $result['is_like'] = 0;
          //查询某话题下某用户已经点赞
          $likesmap =array('user_id' =>$user_id,'topic_id'=>$result['topic_id']);
          $is_like = D('topic_likes')->getOne($likesmap);
          if($is_like){
            $result['is_like'] = 1;
          }
          //查询某话题下的图片
          $data =array('topic_id'=>$result['topic_id']);
          $imageAll = D('topic_image')->getAllData($data);
          $result['imageAll'] = $imageAll;
        
         return $result;
    }

    /**
     * 根据排序查询获取某值
     * @param  [string] $map  查询的数据
     * @param  [string] $value  匹配的值
     * @return [string]  相关的值
     */
    public function getOrderList($map,$user_id,$skip,$take)
    {
        $result=$this
            ->alias('t')
            ->join('LEFT JOIN kts_user as u ON t.user_id = u.user_id')
            ->join('LEFT JOIN kts_user_xq as x ON t.user_id = x.user_id')
            ->where($map)
            ->field('t.user_id as follow_user,u.name,x.imageurl as user_image,SUM(t.is_show) as total_topic')
            ->group('t.user_id')
            ->order('total_topic desc')
            ->limit($skip,$take)
            ->select();
        foreach ($result as $key => $value) {
          $result[$key]['is_follow'] = 0;
          //查询某话题下某用户已经关注
          $likesmap =array('user_id' =>$user_id,'follow_user'=>$result[$key]['follow_user']);
          $is_follow = D('topic_follow')->getOne($likesmap);
          if($is_follow){
            $result[$key]['is_follow'] = 1;
          }
        }
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
