<?php
/**
 * 广告管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;

class SocialFeedController extends AdminController {

 //帖子
  public function index(){
      $id=I('get.id',0);
      $qiniu=C('QINIU');
      $data=M('topic')->find($id);
      if ($data){
          $data['user_avatar']=$qiniu.$data['user_avatar'];
          $img=explode(';',$data['imageurl']);
          foreach ($img as $k=>$v){
              $img[$k]=$qiniu.$v;
          }
          $data['imageurl']=$img;
      }else{
          $this->error('帖子不存在');
      } 
      $data['discuss']=self::getDicuss($id,$qiniu);
      $this->assign('data',$data);
      $this->display('social_feed');
  }
    
  
  private function getDicuss($id,$qiniu){
      $data=M('topic_comment')->where(array('topic_id'=>$id,'is_show'=>1,'fid'=>0))->order('addtime desc')->select();
      if ($data){
          foreach ($data as $k=>$v){
              $data[$k]['user_avatar']=$qiniu.$v['user_avatar'];
              $data[$k]['child']=self::getDicussChild($id, $v['comment_id'],$qiniu);
          }
      }
      return $data;
  }

  private function getDicussChild($id,$fid,$qiniu){
      $data=M('topic_comment')->alias('t')
        ->join('left join kts_user u on t.reply_user = u.user_id')
        ->join('left join kts_user_xq x on t.reply_user = x.user_id')
        ->where(array('t.topic_id'=>$id,'t.is_show'=>1,'t.fid'=>$fid))
        ->order('t.addtime desc')
        ->field('t.*,u.name as reply_name,x.imageurl as  reply_avatar')
        ->select();
      if ($data){
          foreach ($data as $k =>$v){
              $data[$k]['reply_avatar']=$qiniu.$v['reply_avatar'];
          }
      }
      return $data;
  }
  
 //书评
  public function book(){
      $id=I('get.id',0);
      $qiniu=C('QINIU');
      $data=M('book_comment')->field('*,sums as discuss_number,comment_time as addtime')->find($id);
      if ($data){
          $data['user_avatar']=$qiniu.$data['imageurl'];
          $img=explode(';',$data['imageurl']);
          foreach ($img as $k=>$v){
              $img[$k]=$qiniu.$v;
          }
          $data['imageurl']=$img;
          $data['like_num']=M('comment_click')->where(array('comment_id'=>$id))->getField('sum');
          $data['like_num']=$data['like_num']?$data['like_num']:0;
      }else{
          $this->error('帖子不存在');
      }
      $data['discuss']=self::getBookDicussChild($id,$qiniu);
      $this->assign('data',$data);
      $this->display('social_feed');
  }
  
  
  
  private function getBookDicussChild($id,$qiniu){
      $data=M('book_comment')->alias('t')
      ->join('left join kts_user u on t.reply = u.user_id')
      ->join('left join kts_user_xq x on t.reply = x.user_id')
      ->where(array('t.is_show'=>1,'t.fid'=>$id))
      ->order('t.comment_time desc')
      ->field('t.*,u.name as reply_name,x.imageurl as  reply_avatar,t.comment_time as addtime')
      ->select();
      if ($data){
          foreach ($data as $k =>$v){
              $data[$k]['user_avatar']=$qiniu.$v['imageurl'];
              $data[$k]['reply_avatar']=$qiniu.$v['reply_avatar'];
          }
      }
      return $data;
  }
  
  
  public function Bookreply(){
      $id=I('post.id');
      $content=I('post.content');
      $type=I('post.type');
     if ($type){
         $data=M('book_comment')->where(array('comment_id'=>$id,'fid'=>0))->find();
     }else {
         $data=M('book_comment')->find($id);
     }    
      if ($data){
          $arr=array(
              'type'=>$data['type'],
              'book_id'=>$data['book_id'],
              'user_id'=>0,
              'username'=>'admin',
              'imageurl'=>'qiushu_148212918554.png',
              'comment_time'=>$_SERVER['REQUEST_TIME'],
              'content'=>$content,
              'book_number'=>$data['book_number'],
              'author'=>$data['author'],
              'image'=>$data['image'],
              'grade'=>4,
              'reply'=>$data['user_id'],
          );
         if ($type){
             $arr['fid']=$id;
         }else {
             $arr['fid']=$data['fid'];
         }
        $result= M('book_comment')->add($arr);
      }
      if ($result){
          $this->ajaxReturn(1);
      }
      $this->ajaxReturn(0);
  }


} 