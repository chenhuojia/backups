<?php
/**
 * 社区小组管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;
use Think\Page;

class   BookCommentController extends AdminController {



   /**
    * 书评
    * **/
   public function bDList(){
       $bookname=I('get.bookName');
       $booknumber=I('get.bookNumber');
       $username=I('get.userName');
       $startTime=I('get.startTime');
       $endTime=I('get.endTime');
       $where=array('fid'=>0);
       if ($bookname){
           $where['book_name']=array('like', '%'.$bookname.'%');
       }
       if ($booknumber){
           $where['book_number']=$booknumber;
       }
       if($username){
           $where['username']=array('like', '%'.$username.'%');
       }
       if ($startTime && $endTime){
           $where['comment_time']=array(array('lt',strtotime($startTime),array('gt',strtotime($endTime))));
       }
       $data=M('book_comment')->where($where)->select();
       if ($data){
           foreach ($data as $k=>$v){
               $data[$k]['image']=C('QINIU').$v['image'];
           }
       }
       $this->list=$data;
       $this->display('bookDiscussList');
   }
    


   /**
    * 更改书评状态
    * **/
   public function changestatus(){
      $id=I('get.id');
      $val=I('get.status');
      $data=M('book_comment')->where(array('comment_id'=>$id))->setField('is_show',$val);
      if ($data){
          $this->addLog('is_show='.$_POST['status'].'&comment_id='.$id,1);// 记录操作日志
          $this->success('改变书评状态成功');
      }else{
          $this->addLog('is_show='.$_POST['status'].'&comment_id='.$id,0);// 记录操作日志
          $this->error('改变书评状态失败');
      } 
   }
   

   /**
    * 添加书评
    * **/

   public function addComment(){
       $data['user']=M('user')->where(array('is_show'=>0))->select();
       $this->info=$data;
       $this->display('commentAdd');
   }


   /**
    * 书评详情
    * **/
   public function book(){
       $id=I('get.book_id',0);
       $qiniu=C('QINIU');
       $book=M('book')->find($id);
       $data['list']=M('book_comment')->field('*')->where(array('book_id'=>$id,'fid'=>0))->select();
       if ($data){
           foreach ($data['list'] as $k=>$v){
               $data['list'][$k]['user_avatar']=$qiniu.$data['imageurl'];
               $like_num=M('comment_click')->where(array('comment_id'=>$v['comment_id']))->getField('sum');
               $data['list'][$k]['like_num']=$like_num?$like_num:0;
               $data['list'][$k]['child']=self::getBookDicussChild($v['comment_id'],$qiniu);
           }
       }else{
           $this->error('没有评论');
       }
       $book['cover_img']=C('QINIU').$book['cover_img'];
       $data['book']=$book;
       $this->assign('data',$data);
       $this->display('social_feed');
   }
   
   
   
   private function getBookDicussChild($id,$qiniu){
       $data=M('book_comment')->alias('t')
       //->join('left join kts_user u on t.reply = u.user_id')
       //->join('left join kts_user_xq x on t.reply = x.user_id')
       ->where(array('t.is_show'=>1,'t.fid'=>$id))
       ->order('t.comment_time desc')
      // ->field('t.*,u.name as reply_name,x.imageurl as  reply_avatar,t.comment_time as addtime')
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
               M('book_comment')->where(array('comment_id'=>$id,'fid'=>0))->setInc('sums');
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