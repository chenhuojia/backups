<?php
namespace Read\Controller;

use Think\Controller;

class InfoController extends Controller
{   
    /**
     * 话题详情
     */
    public function topicDetail()
    {   
        $topic_id = I('get.topic_id',0);
    	$map=array('t.topic_id'=>$topic_id);
        $data = D('Topic')->getOneDetail($map);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
    }

    /**
     * 话题详情下的评论列表
     */
    public function topicDetailCommemtList()
    {   
        $topic_id = I('get.topic_id',0);
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $type = I('get.type',0);
        if($type =='0') $orderlist=array('c.comment_number'=>'desc');
        elseif($type =='1') $orderlist=array('c.addtime'=>'desc');
        else $this->myApiPrint('请按时间或者热度查询',300);
        $map=array('c.topic_id'=>$topic_id,'c.fid'=>0,'c.is_show'=>1);
        $data = D('TopicComment')->getAllDataTwo($map,$orderlist,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    /**
     * 话题详情下的评论详情
     */
    public function topicDetailCommemtDetail()
    {   
        $comment_id = I('get.comment_id',0);
        $map=array('c.comment_id'=>$comment_id,'c.is_show'=>1);
        $data = D('TopicComment')->getOneCommemtDetail($map);;
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    /**
     * 话题详情下的某一评论的二级评论列表
     */
    public function topicDetailCommemtSecondList()
    {   
        $comment_id = I('get.comment_id',0);
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $orderlist=array('c.addtime'=>'desc');
        $map=array('c.fid'=>$comment_id,'c.is_show'=>1);
        $data = D('TopicComment')->getAllData($map,$orderlist,$skip,$take);;
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    /**
     * 书评话题详情
     */
    public function bookDetail()
    {   
        $comment_id = I('get.comment_id',0);
        $map=array('b.comment_id'=>$comment_id);
        $BookLogic=D('Book','Logic');
        $data= $BookLogic->getOneDetail($map);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
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
        $comment_id = I('get.comment_id',0);
        $map=array('b.comment_id'=>$comment_id,'b.is_show'=>1);
        $BookLogic=D('Book','Logic');
        $data= $BookLogic->getBookCommemtDetail($map);
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
        $orderlist=array('b.comment_time'=>'desc');
        $map=array('b.fid'=>$comment_id,'b.is_show'=>1);
        $BookLogic=D('Book','Logic');
        $data= $BookLogic->getCommentAllData($map,$orderlist,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     
    
}

