<?php
namespace Read\Controller;

use Common\Controller\ApiController;

class ActController extends ApiController
{   
     /**
       * 话题评论点赞
       */
      public function topicCommentLike()
      {   
          $comment_id= I('get.comment_id',0); // 帖子
          $user_id= session('user_id'); //用户id
          $where['comment_id'] = $comment_id;
          $notesID = D("topicComment")->getValue($where,'comment_id');
          if(!$notesID) $this->myApiPrint(300,'相关评论不存在');
          $where['user_id'] = $user_id;//用户id
          $vaSA = D('topicCommentLikes')->getOne($where);
          if ($vaSA){  $this->myApiPrint(300,'该评论已经点过赞了');
          }else{
              //该帖子未赞过，写入数据
              $notelikes = D('topicCommentLikes')->addData($where);
              if (!$notelikes) $this->myApiPrint(300,'点赞失败，请稍后再试');
              // 帖子表更新数据(like_number+1)  
              $data =array('comment_id'=>$comment_id);
              $vaVi = D('topicComment')->setIncCommemtPara($data,'like_number');
              if (!$vaVi) $this->myApiPrint(300,'系统繁忙，请稍后再试');
              $this->myApiPrint(200,'点赞成功');
          }
      }

      /**
       * 用户关注
       */
      public function followUser()
      {   
          $follow_user= I('get.follow_user',0); // 被关注者
          $user_id= session('user_id'); //用户id
          $where['user_id'] = $follow_user;
          $notesID =M('user')->field('user_id')->where($where)->find();
          if(!$notesID) $this->myApiPrint(300,'您关注的用户已经不存在');
          $data = array('user_id'=>$user_id,'follow_user'=>$follow_user);
          $vaSA = D('topic_follow')->getOne($data);
          if ($vaSA){  $this->myApiPrint(300,'该用户您已经关注过了');
          }else{
              //该帖子未赞过，写入数据
              $notefollow = D('topic_follow')->addData($data);
              if (!$notefollow) $this->myApiPrint(300,'关注失败，请稍后再试');
              $this->myApiPrint(200,'关注成功');
          }
      }

       /**
         * 用户取消关注用户
         */
        public function toppicAway()
        {   
            
            $follow_user= I('get.follow_user',0); // 关注的ID
            $user_id= session('user_id'); //用户id
            $groupwhere['follow_user'] = $follow_user;
            $groupwhere['user_id'] = $user_id;
            $notesID = D('topic_follow')->getOne($groupwhere);
            if(!$notesID) $this->myApiPrint(300,'您未关注该用户，不用取消');
            $lastId=D("topic_follow")->where($groupwhere)->delete(); 
            if ($lastId) $this->myApiPrint(200,'取消关注成功');
            $this->myApiPrint(300,'取消关注失败，请稍后再试');
        }

       /**
         * 用户发表帖子
         * 
         */
        public function writeTopic()
        {   
            $data['user_id']= session('user_id'); //用户id
            $data['tag_id']= I('post.tag_id',0); // 栏目ID
            $data['title']= I('post.title'); 
            $data['content']= I('post.content'); 
            $data['imageurl']= I('post.imageurl',''); 
            $data['videourl']= I('post.videourl',''); 
            // 写入数据
            $data['user_name'] = ""; 
            $data['user_avatar'] = ""; 
            $return = D('topic')->addData($data);
            if ($return['status']==0) $this->myApiPrint($return['data']);
            add_topic_integral_log($data['user_id']);//发表帖子判断获取积分
            $this->myApiPrint(200,'发表成功');

        }

         /**
         * 评论帖子或者回复别人的评论
         */
        public function topicComment()
        {   
            $data['topic_id']= I('post.topic_id',0); // 帖子ID
            $data['fid']= I('post.fid',0); 
            $data['content']= I('post.content','');
            $data['user_id']= session('user_id'); //用户id  
            if(empty($data['content']))  $this->myApiPrint(300,'评论内容不为空');
            $notesID = D('topic')->where(array('topic'=>$data['topic_id']))->find();
            if(empty($notesID)) $this->myApiPrint(300,'该帖子不存在');
            // 评论写入数据
            $data['user_name'] = ""; 
            $data['user_avatar'] = session('avatar');
            $return = D('topicComment')->addData($data);
            if ($return['status']==0) $this->myApiPrint(300,$return['data']);
            if($data['fid']==0)//存在父id为0
            {   
                answer(0,$return['data'],$notesID['user_id'],$notesID['content'],$notesID['user_name'],$data['user_id'],$data['user_name'],$data['content']);
                // 问题表回答数+1
                $notedata= D('topic')->setIncTopicPara(array('topic_id'=>$data['topic_id']),'discuss_number');
                add_comment_integral_log($data['user_id']);//回复帖子获取到积分
            }else{
                comment_answer(0,$return['data'],$data['fid'],$data['user_id'],$data['user_name'],$data['content']);
                $notedata= D('topicComment')->setIncCommemtPara(array('comment_id'=>$data['fid']),'comment_number');
            }
            $this->myApiPrint(200,'发表成功');
        }
      /**
       * 帖子收藏
       */
      public function topicCollect()
      {
          $topic_id= I('get.topic_id',0); // 收藏的id
          $user_id= session('user_id'); //用户id
          $where['topic_id'] = $topic_id;
          $notesID = M("topic")->field('topic_id')->where($where)->find();
          if(!$notesID) $this->myApiPrint(300,'相关评论不存在');
          $where['user_id'] = $user_id;//用户id
          $vaSA = M('Collect')->where($where)->find();
          if ($vaSA){ 
              $cancel = M('Collect')->where($where)->delete();
              if (!$cancel) $this->myApiPrint(300,'取消收藏失败');
              $this->myApiPrint(200,'取消收藏成功');
          }else{
              $where['type'] = 2;
              $where['collect_time'] = time();
              $notelikes = M('Collect')->data($where)->add();
              if (!$notelikes) $this->myApiPrint(300,'收藏失败');
              $this->myApiPrint(200,'收藏成功');
          }
      }

      /**
       * 书评评论点赞
       */
      public function bookCommentLike()
      {   
          $comment_id= I('get.comment_id',0); 
          $book_id= I('get.book_id',0); 
          $book_number= I('get.book_number',0); 
          $type= I('get.type',0); 
          $user_id= session('user_id'); //用户id
          $name= ""; //用户名
          $where['comment_id'] = $comment_id;
          $notesID = M("book_comment")->where($where)->find();
          if(!$notesID) $this->myApiPrint(300,'相关评论不存在');
          $where['user_id'] = $user_id;//用户id
          $vaSA = M('book_comment_click')->field('is_show')->where($where)->find();
          if ($vaSA){  $this->myApiPrint(300,'该评论已经点过赞了');
          }else{
              $where['type'] = $type;
              $where['book_id'] = $book_id;
              $where['username'] = $name;
              $where['book_number'] = $book_number;
              $where['addtime'] = time();
              $notelikes = M('book_comment_click')->data($where)->add();
              if (!$notelikes) $this->myApiPrint(300,'点赞失败，请稍后再试');
              // 帖子表更新数据(like_number+1)  
             // $result=M('book_comment')->where('comment_id'=>$comment_id)->setInc('likes');
              $this->myApiPrint(200,'点赞成功');
          }
      }

      public function writeTopicHistory()
      {
            $data['user_id']= session('user_id'); //用户id
            $data['tag_id']= 3; // 栏目ID
            $data['title']= I('post.title'); 
            $data['content']= I('post.content'); 
            $data['imageurl']= I('post.imageurl',''); 
            $data['videourl']= I('post.videourl',''); 
            $ad_month= I('post.ad_month',0); 
            $lunar_month= I('post.lunar_month',0);
            $history_id= I('post.history_id',0);
            $calendar_year= I('post.calendar_year',0);
            // 写入数据
            $data['user_name'] = ""; 
            $data['user_avatar'] = ""; 
            $return = D('topic')->addData($data);
            if ($return['status']==0) $this->myApiPrint(300,$return['data']);
            $history=M('topic_history')->add(array('topic_id'=>$return['data'],'ad_month'=>$ad_month,'lunar_month'=>$lunar_month,'history_id'=>$history_id,'calendar_year'=>$calendar_year));
            if($history) add_topic_integral_log($data['user_id']);//发表帖子判断获取积分
            else $this->myApiPrint(300,'发表失败');
            $this->myApiPrint(200,'发表成功');
      }







      
    
    
    
}

