<?php
namespace Read\Controller;

use Think\Controller;

class InfoV2Controller extends Controller
{   
    /**
     * 话题详情
     */
    public function topicDetail()
    {   
        $topic_id = I('get.topic_id',0);
        $map=array('t.topic_id'=>$topic_id);
        $TopicLogic=D('Topic','Logic');
        $data= $TopicLogic->getOneDetail($map);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
    }

     /**
     * 话题详情下的某一评论的二级评论列表
     */
    public function topicDetailCommemtSecondList()
    {   
        $topic_id = I('get.topic_id',0);
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $data = D('TopicComment')->getAllDataV2($topic_id,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     /**
     * 书评话题详情下的评论列表
     */
    public function bookDetailCommemtList()
    {   
        $comment_id = I('get.comment_id',0);
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $type = I('get.type',0);
        if($type =='0') $orderlist=array('b.sums'=>'desc');
        elseif($type =='1') $orderlist=array('b.comment_time'=>'desc');
        else $this->myApiPrint('请按时间或者热度查询',300);
        $map=array('b.comment_id'=>array('eq',$comment_id),'b.is_show'=>array('eq',1),'b.fid'=>array('eq',0));
        $BookLogic=D('Book','Logic');
        $data= $BookLogic->getCommentAllDataV2($map,$orderlist,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    /**
     * 书评话题详情下的评论详情
     */
    public function bookDetailCommemtDetail()
    {   
        $reply_id = I('get.reply_id',0);
        $map=array('r.reply_id'=>$reply_id,'r.is_show'=>1);
        $BookLogic=D('Book','Logic');
        $data= $BookLogic->getBookCommemtDetailV2($map);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     /**
     * 书评话题详情下的某一评论的二级评论列表
     */
    public function bookDetailCommemtSecondList()
    {   
        $comment_id = I('get.comment_id',0);
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $orderlist=array('b.reply_id'=>'desc');
        $map=array('b.comment_id'=>$comment_id,'b.is_show'=>1,'fid'=>array('neq',0));
        $BookLogic=D('Book','Logic');
        $data= $BookLogic->getScondAllDataV2($map,$orderlist,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    /**
     * 话题详情下的某一评论的二级评论列表
     */
    public function topicDetailCommemtSecondListV3()
    {   
        $topic_id = I('get.topic_id',0);
        $comment_id = I('get.comment_id',0);
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $CommentLogic=D('TopicComment','Logic');
        $data= $CommentLogic->getScondAllDataV3($topic_id,$comment_id,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    /**
     * 书评话题详情下的某一评论的二级评论列表
     */
    public function bookDetailCommemtSecondListV3()
    {   
        $comment_id = I('get.comment_id',0);
        $reply_id = I('get.reply_id',0);
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $BookLogic=D('Book','Logic');
        $data= $BookLogic->getScondAllDataV3($comment_id,$reply_id,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }


    
}

