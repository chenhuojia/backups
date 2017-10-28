<?php
namespace Read\Model;
use Think\Model;
use User\Util\Util;
/**
 * ModelName
 */
class TopicCommentModel extends BaseModel{
   
    // 自动验证
    protected $_validate=array(
        array('content','require','内容必须填写！'), // 验证字段必填
     );
    // 自动完成
    protected $_auto=array(
        array('addtime','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );

    /**
     * 某话题下所有的评论
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getAllData($map,$orderlist,$skip,$take)
    {
        $result = $this->alias('c')
            ->field('c.comment_id,c.topic_id,c.user_id,c.content,c.addtime,c.fid,c.comment_number,c.like_number')
            ->where($map)
            ->order($orderlist)
            ->limit($skip,$take)
            ->select();
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
        }
        return $result;
    }

   /**
     * 获取某条一级评论下的详情
     * @param  [string] $map  查询的数据
     * @return [array]       一维数组
     */
    public function getOneCommemtDetail($map)
    {
        $result= $this->alias('c')
            ->join('LEFT JOIN kts_topic as x ON c.topic_id = x.topic_id') 
            ->field('c.comment_id,c.topic_id,c.user_id,c.content,c.addtime,c.comment_number,c.like_number,
                x.title as topic_title')
            ->where($map)
            ->find();
        if(!empty($result)){
            $userMes = \User\Util\Util::GetUserAvatrAndNick($result['user_id']);
            $result['user_avatar'] = $userMes['avatar'];
            $result['user_name'] = $userMes['name'];
            $result['is_like'] = 0;
            //查询某话题下某用户已经关注
            $user_id = session('?user_id')?session('user_id'):0;//判断用户是否登录
            $likemap =array('user_id' =>$user_id,'comment_id'=>$result['comment_id']);
            $is_like = D('TopicCommentLikes')->getOne($likemap);
            if($is_like) $result['is_like'] = 1;
            $result['addtime'] = friend_date($result['addtime']);
        }
        return $result;
    }

    /**
     * 某话题下所有的评论
     * @param  [int] $comment_id [查询的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getAllDataV2($topic_id,$skip,$take)
    {
        $result = $this->alias('c')
            ->field('c.comment_id,c.user_id,c.topic_id,c.content,c.addtime,c.fid,c.like_number')
            ->where(array('c.is_show'=>array('eq',1),'fid'=>array('neq',0),'topic_id'=>array('eq',$topic_id)))
            ->limit($skip,$take)
            ->order('c.comment_id')
            ->select();
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
        return $result;
    }

   
    /**
     * 某话题下所有的评论加三级小栏目
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getAllDataTwo($map,$orderlist,$skip,$take)
    {
        $result = $this->alias('c')
            ->field('c.comment_id,c.topic_id,c.user_id,c.content,c.addtime,c.fid,c.comment_number,c.like_number')
            ->where($map)
            ->order($orderlist)
            ->limit($skip,$take)
            ->select();
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
            $result[$key]['comment_arr'] = $this->field('comment_id,user_id,content')->where(array('fid'=>$result[$key]['comment_id']))->limit(0,3)->select();
            foreach ($result[$key]['comment_arr'] as $k => $v) {
                 $userMes1 = \User\Util\Util::GetUserAvatrAndNick($v['user_id']);
                 $result[$key]['comment_arr'][$k]['user_name'] = $userMes1['name'];
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
    public function setIncCommemtPara($map,$para){
        // 对data数据进行验证
        if(!$data=$this->create($map)){
            // 验证不通过返回错误
            return false;
        }else{
            // 验证通过
            $result=$this
                ->where(array($map))
                ->setInc($para);
            return $result;
        }
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

    

    


}
