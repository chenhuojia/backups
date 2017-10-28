<?php
namespace Read\Logic;
use Think\Model;
use User\Util\Util;
/**
 * ModelName
 */
class TopicCommentLogic extends Model{
    
     /**
     * 某话题下所有的评论
     * @param  [int] $topic_id   [话题的id]
     * @param  [int] $comment_id  [回复的id]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getScondAllDataV3($topic_id,$comment_id,$skip,$take)
    {
        $tmp = $this->alias('c')
            ->field('c.comment_id,c.user_id,c.topic_id,c.content,c.addtime,c.fid,c.like_number')
            ->where(array('c.is_show'=>array('eq',1),'topic_id'=>array('eq',$topic_id)))
            ->order(array('c.comment_id'=>'desc'))
            ->select();
        $result=self::tree($tmp,$comment_id,0);
        foreach ($result as $key => $value) {
            $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
            $result[$key]['user_name'] = $userMes['name'];
            $result[$key]['user_avatar'] = $userMes['avatar'];
            $result[$key]['is_like'] = 0;
            //查询某话题下某用户已经点赞
            $user_id = session('?user_id')?session('user_id'):0;//判断用户是否登录
            $likemap =array('user_id' =>$user_id,'comment_id'=>$result[$key]['comment_id']);
            $is_like = D('TopicCommentLikes')->getOne($likemap);
            if($is_like) $result[$key]['is_like'] = 1;
            $result[$key]['addtime'] = friend_date($result[$key]['addtime']);
            $result[$key]['is_host'] = 0;
            if($result[$key]['user_id'] == $user_id)  $result[$key]['is_host'] = 1;
            $result[$key]['father'] =array();
            $result[$key]['father'] = M('topic_comment')->field('comment_id,user_id,content')->where(array('comment_id'=>array('eq',$value['fid'])))->find();
            $userMes1 = \User\Util\Util::GetUserAvatrAndNick($result[$key]['father']['user_id']);
            $result[$key]['father']['user_name'] = $userMes1['name'];
            $result[$key]['father']['user_avatar'] = $userMes1['avatar'];
        }
        $result=array_slice($result,$skip,$take);
        return $result;
    }


    static public function tree($items,$f_id=0,$level=0)
    {    
        $tree = array();
        foreach ($items as &$v) {
                if ($v['fid'] == $f_id){
                    $tree[] = $v;
                    if(is_array(self::tree($items, $v['comment_id'], $level+1))) $tree=array_merge($tree, self::tree($items, $v['comment_id'], $level+1));
                }
            }
        return $tree;
    }





}
