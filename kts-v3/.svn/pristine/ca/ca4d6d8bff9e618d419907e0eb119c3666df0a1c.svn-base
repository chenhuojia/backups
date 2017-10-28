<?php
namespace Read\Logic;
use Think\Model;
/**
 * ModelName
 */
class TopicHistoryLogic extends Model{
    
   
    /**
     * 某话题下所有的评论
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function searchAllData($map,$orderlist,$skip,$take)
    {
        $result = M("topic")
             ->alias('b')
             ->join('LEFT JOIN kts_topic_history as h ON h.topic_id = b.topic_id') 
             ->field('b.topic_id,b.tag_id,b.user_id,b.title,b.addtime,b.content,b.imageurl,b.videourl,b.discuss_number')
             ->where($map)
             ->order($orderlist)
             ->limit($skip,$take)
             ->select();
        foreach ($result as $key => $value) {
          $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
          $result[$key]['user_name'] = $userMes['name'];
          $result[$key]['user_avatar'] = $userMes['avatar'];
          if(empty($result[$key]['imageurl'])){
            $result[$key]['imageurl'] ="";
          }else{
             $image_arr= explode(';', $result[$key]['imageurl']);
             //查询图片
             if(is_array($image_arr)) $result[$key]['imageurl'] = C('QINIU_IMG_PATH').$image_arr[0];
          }
          $result[$key]['addtime'] = friend_date($result[$key]['addtime']);
        }
        return $result;
    }
    


}
