<?php
namespace Home\Controller;
use Think\Controller;
class TopicController extends BaseController
{
      
     /**
       * 用户关注
       */
      public function toppicFollow()
      {   
          
          $follow_user= I('get.follow_user',0); // 帖子ID
          $user_id= $this->userID; //用户id
          $where['follow_user'] = $follow_user;
          $where['user_id'] = $user_id;//用户id
          $vaSA = D('topic_follow')->getOne($where);
          if ($vaSA)
          {
              //已点关注
              $this->error('该用户已经关注了'); 
          }
          else
          {
              //该组未关注，写入数据
              $follow = D('topic_follow')->addData($where);
              if (!$follow) $this->error('关注失败，请稍后再试'); 
              $this->success('登录成功', U('Home/Index/index')); 
          }
      }

}

