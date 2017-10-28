<?php
namespace Read\Model;
use Think\Model;
use User\Util\Util;
/**
 * ModelName
 */
class TopicRecommendModel extends Model{
   
   // 自动完成
    protected $_auto=array(
        array('create_time','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );

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
        $result=$this
            ->alias('r')
            ->join('LEFT JOIN kts_topic as t ON t.topic_id = r.topic_id')
            ->where($map)
            ->field('t.topic_id,t.tag_id,t.user_id,t.title,t.addtime,t.content,t.imageurl,t.videourl,t.discuss_number')
            ->order($orderlist)
            ->limit($skip,$take)
            ->select();
        foreach ($result as $key => $value) {
          $image_arr= explode(';', $result[$key]['imageurl']);
          //查询图片
          if(is_array($image_arr)) $result[$key]['imageurl'] = C('QINIU_IMG_PATH').$image_arr[0];
          $result[$key]['addtime'] = friend_date($result[$key]['addtime']);
          $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
          $result[$key]['user_name'] = $userMes['name'];
          $result[$key]['user_avatar'] = $userMes['avatar'];

        }
        return $result;
    }







}
