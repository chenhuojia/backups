<?php
namespace Home\Logic;
use Think\Model;
/**
 * ModelName
 */
class TopicLogic extends Model{
    
    //首页获取话题的banner
    public function getTopicBanner($skip = 0,$take = 3)
    {
        $result=M('topic')
            ->alias('t')
            ->where(array('t.is_show'=>1,'t.imageurl'=>array('neq',''),'t.tag_id'=>array('in',array('1','2'))))
            ->field('t.topic_id,t.tag_id,t.user_id,t.title,t.addtime,t.content,t.imageurl,t.videourl,t.discuss_number,t.sorts')
            ->order(array('t.sorts'=>'desc','t.addtime'=>'desc'))
            ->limit($skip,$take)
            ->select();
        foreach ($result as $key => $value) {
          $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
          $result[$key]['user_name'] = $userMes['name'];
          $result[$key]['user_avatar'] = $userMes['avatar'];
          if(empty($result[$key]['imageurl'])){
                 $result[$key]['image_arr'] ="";
              }else{
                $image_arr= explode(';', $result[$key]['imageurl']);
                //查询图片
                $result[$key]['image_arr'] =C('QINIU_IMG_PATH').$image_arr[0];
          }
          unset($result[$key]['imageurl']);
          $result[$key]['addtime'] = friend_date($result[$key]['addtime']);
        }
        return $result;
    }

     //首页最热的话题
    public function readTop($skip = 0,$take = 2)
    {     
          $orderlist=array('sorts'=>'desc');
          $topic=M('topic')
                ->where(array('is_show'=>1))
                ->field('topic_id,title,addtime,content')
                ->order($orderlist)
                ->limit($skip,$take)
                ->select();
          return $topic;
    }


}
