<?php
namespace Read\Logic;
use Think\Model;
/**
 * ModelName
 */
class TopicLogic extends Model{
    
    /**
     * 所有的话题
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getAllData($map,$orderlist,$skip,$take)
    {
        $result=M('topic')
            ->alias('t')
            ->where($map)
            ->field('t.topic_id,t.tag_id,t.user_id,t.title,t.addtime,t.content,t.imageurl,t.videourl,t.discuss_number,t.sorts')
            ->order($orderlist)
            ->limit($skip,$take)
            ->select();
        foreach ($result as $key => $value) {
          $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
          $result[$key]['user_name'] = $userMes['name'];
          $result[$key]['user_avatar'] = $userMes['avatar'];
          if(empty($result[$key]['imageurl'])){
                 $result[$key]['image_arr'] =array();
              }else{
                $image_arr= explode(';', $result[$key]['imageurl']);
                //查询图片
                foreach ($image_arr as $k => $v) {
                      if($image_arr[$k]!=null){
                        $result[$key]['image_arr'][] = C('QINIU_IMG_PATH').$image_arr[$k];
                      }
                }
          }
          $result[$key]['addtime'] = friend_date($result[$key]['addtime']);
        }
        return $result;
    }

     /**
     * 某话题详情
     * @param  [array] $map        [查询的值]
     * @return [array]             [数组]
     */
    public function getOneDetail($map)
    {

        $result=M('topic')
            ->alias('t')
            ->where($map)
            ->field('t.topic_id,t.tag_id,t.user_id,t.title,t.addtime,t.content,t.imageurl,t.videourl,t.discuss_number')
            ->find();
        if(!empty($result)){
              $result['is_follow'] = 0;
              $result['is_collect'] = 0;
              $userMes = \User\Util\Util::GetUserAvatrAndNick($result['user_id']);
              $result['user_name'] = $userMes['name'];
              $result['user_avatar'] = $userMes['avatar'];
              //查询某话题下某用户已经关注
              $user_id = session('user_id')?session('user_id'):0;//判断用户是否登录
              $followmap =array('user_id' =>$user_id,'follow_user'=>$result['user_id']);
              $is_follow = D('TopicFollow')->getOne($followmap);
              if($is_follow) $result['is_follow'] = 1;
              $is_collect = M('collect')->where(array('user_id' =>$user_id,'topic_id'=>$result['topic_id'],'type'=>2))->find();
              if($is_collect) $result['is_collect'] = 1;
              if(empty($result['imageurl'])){
                 $result['image_arr'] =array();
              }else{
                $image_arr= explode(';', $result['imageurl']);
                //查询图片
                foreach ($image_arr as $k => $v) {
                      if($image_arr[$k]!=null){
                        $result['image_arr'][] = C('QINIU_IMG_PATH').$image_arr[$k];
                      }
                }
              }
              $result['recommend_topic'] = M('topic')->where(array('tag_id'=>$result['tag_id'],'topic_id'=>array('NEQ',$result['topic_id'])))->field('topic_id,tag_id,title')->order(array('discuss_number'=>'desc'))->limit(0,5)->select();
              $result['introduce'] = M('user_xq')->where(array('user_id'=>$result['user_id']))->getField('introduce');
             unset($result['imageurl']);
             $result['addtime'] = friend_date($result['addtime']);
          }
        return $result;
    }


    


}
