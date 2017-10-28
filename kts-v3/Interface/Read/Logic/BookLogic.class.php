<?php
namespace Read\Logic;
use Think\Model;
use User\Util\Util;
/**
 * ModelName
 */
class BookLogic extends Model{
    
    /**
     * 某话题详情
     * @param  [array] $map        [查询的值]
     * @return [array]             [数组]
     */
    public function getOneDetail($map)
    {

        $result=M("book_comment")
            ->alias('b')
            ->where($map)
            ->field("b.comment_id,b.type,b.book_id,b.user_id,b.imageurl as user_avatar,b.comment_time,b.content,b.book_name,b.image as cover_img,b.author,b.grade,b.fid,b.sums,b.likes,b.image")
            ->find();
        if(!empty($result)){
              $result['is_follow'] = 0;
              $result['is_collect'] = 0;
              $userMes = \User\Util\Util::GetUserAvatrAndNick($result['user_id']);
              $result['username'] = $userMes['name'];
              $result['user_avatar'] = $userMes['avatar'];
              $result['cover_img'] = C('QINIU_IMG_PATH').$result['cover_img'];
              //查询某话题下某用户已经关注
              $user_id = session('user_id')?session('user_id'):0;//判断用户是否登录
              $followmap =array('user_id' =>$user_id,'follow_user'=>$result['user_id']);
              $is_follow = D('TopicFollow')->getOne($followmap);
              if($is_follow) $result['is_follow'] = 1;
              $is_collect = M('collect')->where(array('user_id' =>$user_id,'book_id'=>$result['book_id'],'type'=>1))->find();
              if($is_collect) $result['is_collect'] = 1;
              $result['comment_time'] = friend_date($result['comment_time']);
          }
        return $result;
    }

    /**
     * 所有的话题栏目
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @return [array]             [数组]
     */
    public function searchAllData($map,$orderlist,$skip,$take)
    {

        $result=M("book_comment")
            ->alias('b')
            ->where($map)
            ->field("b.comment_id,b.type,b.book_id,b.user_id,b.comment_time,b.content,b.book_name,b.image as cover_img,b.author,b.grade,b.fid,b.likes,b.sums")
            ->order($orderlist)
            ->limit($skip,$take)
            ->select();
        foreach ($result as $k => $v) {
           $userMes = \User\Util\Util::GetUserAvatrAndNick($result[$k]['user_id']);
           $result[$k]['user_avatar'] = $userMes['avatar'];
           $result[$k]['username'] = $userMes['name'];
           $result[$k]['cover_img'] = C('QINIU_IMG_PATH').$v['cover_img'];
           $result[$k]['comment_time'] = friend_date($result[$k]['comment_time']);
        }
        return $result;
    }



    /**
     * 某话题下所有的评论
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getCommentAllData($map,$orderlist,$skip,$take)
    {
        $result = M("book_comment")
             ->alias('b')
             ->field("b.comment_id,b.user_id,b.username,b.imageurl as user_avatar,b.comment_time,b.content,b.fid,b.likes,b.sums")
             ->where($map)
             ->order($orderlist)
             ->limit($skip,$take)
             ->select();
        foreach ($result as $key => $value) {
            $userMes = \User\Util\Util::GetUserAvatrAndNick($result[$key]['user_id']);
            $result[$key]['user_avatar'] = $userMes['avatar'];
            $result[$key]['username'] = $userMes['name'];
            $result[$key]['is_like'] = 0;
            //查询某话题下某用户已经点赞
            $user_id = session('?user_id')?session('user_id'):0;//判断用户是否登录
            $likemap =array('user_id' =>$user_id,'comment_id'=>$result[$key]['comment_id']);
            $is_like = M('book_comment_click')->where($likemap)->find();
            if($is_like) $result[$key]['is_like'] = 1;
            $result[$key]['comment_time'] = friend_date($result[$key]['comment_time']);
        }
        return $result;
    }
    /**
     * 某话题下所有的评论
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getCommentAllDataTwo($map,$orderlist,$skip,$take)
    {
        $result = M("book_comment")
             ->alias('b')
             ->field("b.comment_id,b.user_id,b.username,b.imageurl as user_avatar,b.comment_time,b.content,b.fid,b.likes,b.sums")
             ->where($map)
             ->order($orderlist)
             ->limit($skip,$take)
             ->select();
        foreach ($result as $key => $value) {
            $userMes = \User\Util\Util::GetUserAvatrAndNick($result[$key]['user_id']);
            $result[$key]['user_avatar'] = $userMes['avatar'];
            $result[$key]['username'] = $userMes['name'];
            $result[$key]['is_like'] = 0;
            //查询某话题下某用户已经点赞
            $user_id = session('?user_id')?session('user_id'):0;//判断用户是否登录
            $likemap =array('user_id' =>$user_id,'comment_id'=>$result[$key]['comment_id']);
            $is_like = M('book_comment_click')->where($likemap)->find();
            if($is_like) $result[$key]['is_like'] = 1;
            $result[$key]['comment_time'] = friend_date($result[$key]['comment_time']);
            $result[$key]['comment_arr'] = M('book_comment')->field('comment_id,user_id,content')->where(array('fid'=>$result[$key]['comment_id']))->limit(0,3)->select();
            foreach ($result[$key]['comment_arr'] as $k => $v) {
                 $userMes1 = \User\Util\Util::GetUserAvatrAndNick($v['user_id']);
                 $result[$key]['comment_arr'][$k]['user_name'] = $userMes1['name'];
            }
        }
        return $result;
    }

    /**
     * 获取某条一级评论下的详情
     * @param  [string] $map  查询的数据
     * @return [array]       一维数组
     */
    public function getBookCommemtDetail($map)
    {
        $result= M("book_comment")
            ->alias('b')
            ->field("b.comment_id,b.type,b.book_id,b.user_id,b.username,b.imageurl as user_avatar,b.comment_time,b.content,b.book_name,b.author,b.grade,b.fid,b.sums,b.likes,b.image")
            ->where($map)
            ->find();
        if(!empty($result)){
            $userMes = \User\Util\Util::GetUserAvatrAndNick($result['user_id']);
            $result['user_avatar'] = $userMes['avatar'];
            $result['username'] = $userMes['name'];
            $result['image'] = C('QINIU_IMG_PATH').$result['image'];
            $result['is_like'] = 0;
            //查询某话题下某用户已经关注
            $user_id = session('?user_id')?session('user_id'):0;//判断用户是否登录
            $likemap =array('user_id' =>$user_id,'comment_id'=>$result['comment_id']);
            $is_like = M('book_comment_click')->where($likemap)->find();
            if($is_like) $result['is_like'] = 1;
            $result['pre'] = M('book_comment')->field("comment_id,content")->where(array('comment_id'=>$result['fid']))->find();
            $result['comment_time'] = friend_date($result['comment_time']);
        }
        return $result;
    }

     /**
     * 某话题下所有的评论
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getCommentAllDataV2($map,$orderlist,$skip,$take)
    {
        $result = M("book_comment_reply")
             ->alias('b')
             ->field("b.reply_id,b.comment_id,b.user_id,b.comment_time,b.content,b.fid,b.likes,b.sums")
             ->where($map)
             ->order($orderlist)
             ->limit($skip,$take)
             ->select();
        foreach ($result as $key => $value) {
            $userMes = \User\Util\Util::GetUserAvatrAndNick($result[$key]['user_id']);
            $result[$key]['user_avatar'] = $userMes['avatar'];
            $result[$key]['username'] = $userMes['name'];
            $result[$key]['is_like'] = 0;
            //查询某话题下某用户已经点赞
            $user_id = session('?user_id')?session('user_id'):0;//判断用户是否登录
            $likemap =array('user_id' =>$user_id,'reply_id'=>$result[$key]['reply_id']);
            $is_like = M('book_reply_like')->where($likemap)->find();
            if($is_like) $result[$key]['is_like'] = 1;
            $result[$key]['comment_time'] = friend_date($result[$key]['comment_time']);
            $result[$key]['comment_arr'] = M('book_comment_reply')->field('reply_id,comment_id,user_id,content')->where(array('is_show'=>1,'fid'=>$result[$key]['reply_id']))->limit(0,3)->select();
            foreach ($result[$key]['comment_arr'] as $k => $v) {
                 $userMes1 = \User\Util\Util::GetUserAvatrAndNick($v['user_id']);
                 $result[$key]['comment_arr'][$k]['user_name'] = $userMes1['name'];
            }
        }
        return $result;
    }

     /**
     * 获取某条一级评论下的详情
     * @param  [string] $map  查询的数据
     * @return [array]       一维数组
     */
    public function getBookCommemtDetailV2($map)
    {
        $result= M("book_comment_reply")
            ->alias('r')
            ->join('LEFT JOIN kts_book_comment as b ON b.comment_id = r.comment_id')
            ->field("r.reply_id,r.comment_id,r.comment_time,r.user_id,r.content,r.sums,r.likes,r.fid,b.comment_id,b.type,b.book_id,b.book_name,b.author,b.grade,b.image")
            ->where($map)
            ->find();
        if(!empty($result)){
            $userMes = \User\Util\Util::GetUserAvatrAndNick($result['user_id']);
            $result['user_avatar'] = $userMes['avatar'];
            $result['username'] = $userMes['name'];
            $result['image'] = C('QINIU_IMG_PATH').$result['image'];
            $result['is_like'] = 0;
            //查询某话题下某用户已经关注
            $user_id = session('?user_id')?session('user_id'):0;//判断用户是否登录
            $likemap =array('user_id' =>$user_id,'reply_id'=>$result['reply_id']);
            $is_like = M('book_reply_like')->where($likemap)->find();
            if($is_like) $result['is_like'] = 1;
            $result['pre'] = M('book_comment')->field("comment_id,content")->where(array('comment_id'=>$result['comment_id']))->find();
            $result['comment_time'] = friend_date($result['comment_time']);
        }
        return $result;
    }

    /**
     * 某话题下所有的评论
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getScondAllDataV2($map,$orderlist,$skip,$take)
    {
        $result = M("book_comment_reply")
             ->alias('b')
             ->field("b.reply_id,b.comment_id,b.user_id,b.comment_time,b.content,b.fid,b.likes,b.sums")
             ->where($map)
             ->order($orderlist)
             ->limit($skip,$take)
             ->select();
        foreach ($result as $key => $value) {
            $userMes = \User\Util\Util::GetUserAvatrAndNick($result[$key]['user_id']);
            $result[$key]['user_avatar'] = $userMes['avatar'];
            $result[$key]['username'] = $userMes['name'];
            $result[$key]['is_like'] = 0;
            //查询某话题下某用户已经点赞
            $user_id = session('?user_id')?session('user_id'):0;//判断用户是否登录
            $likemap =array('user_id' =>$user_id,'reply_id'=>$result[$key]['reply_id']);
            $is_like = M('book_reply_like')->where($likemap)->find();
            if($is_like) $result[$key]['is_like'] = 1;
            $result[$key]['comment_time'] = friend_date($result[$key]['comment_time']);
            $result[$key]['is_host'] = 0;
            if($result[$key]['user_id'] == $user_id)  $result[$key]['is_host'] = 1;
            $result[$key]['father'] =array();
            $result[$key]['father'] = M('book_comment_reply')->field('reply_id,comment_id,user_id,content')->where(array('reply_id'=>array('eq',$value['fid'])))->find();
            $userMes1 = \User\Util\Util::GetUserAvatrAndNick($result[$key]['father']['user_id']);
            $result[$key]['father']['user_name'] = $userMes1['name'];
            $result[$key]['father']['user_avatar'] = $userMes1['avatar'];
        }
        return $result;
    }

     /**
     * 某话题下所有的评论
     * @param  [array] $comment_id [书评的id]
     * @param  [string] $reply_id  [回复的id]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getScondAllDataV3($comment_id,$reply_id,$skip,$take)
    {
        $tmp = M("book_comment_reply")
             ->alias('b')
             ->field("b.reply_id,b.comment_id,b.user_id,b.comment_time,b.content,b.fid,b.likes,b.sums")
             ->where(array('b.comment_id'=>$comment_id,'b.is_show'=>1))
             ->order(array('b.reply_id'=>'desc'))
             ->select();
        $result=self::tree($tmp,$reply_id,0);
        foreach ($result as $key => $value) {
            $userMes = \User\Util\Util::GetUserAvatrAndNick($result[$key]['user_id']);
            $result[$key]['user_avatar'] = $userMes['avatar'];
            $result[$key]['username'] = $userMes['name'];
            $result[$key]['is_like'] = 0;
            //查询某话题下某用户已经点赞
            $user_id = session('?user_id')?session('user_id'):0;//判断用户是否登录
            $likemap =array('user_id' =>$user_id,'reply_id'=>$result[$key]['reply_id']);
            $is_like = M('book_reply_like')->where($likemap)->find();
            if($is_like) $result[$key]['is_like'] = 1;
            $result[$key]['comment_time'] = friend_date($result[$key]['comment_time']);
            $result[$key]['is_host'] = 0;
            if($result[$key]['user_id'] == $user_id)  $result[$key]['is_host'] = 1;
            $result[$key]['father'] =array();
            $result[$key]['father'] = M('book_comment_reply')->field('reply_id,comment_id,user_id,content')->where(array('reply_id'=>array('eq',$value['fid'])))->find();
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
                    if(is_array(self::tree($items, $v['reply_id'], $level+1))) $tree=array_merge($tree, self::tree($items, $v['reply_id'], $level+1));
                }
            }
        return $tree;
    }





}
