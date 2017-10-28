<?php
namespace User\Model;
use Think\Model;
/**
 * ModelName
 */
class BooklistDiscussReplyApproveModel extends Model{
   
    // 自动验证
    protected $_validate=array(     
      array('reply_id','require','请填写回复评论id',1),
     );
   // 自动完成
    protected $_auto=array(
        array('addtime','time',3,'function'),
        array('user_id','checkuser',3,'callback'),
    );
    
   protected function checkuser(){
       return session('user_id');
   }

   /**
    *
    * @param unknown $user_id
    * @param unknown $discuss_id
    * @return \Think\false
    * ***/
   public function checkApprove($user_id,$discuss_id){
       return $this->execute('select * from kts_booklist_discuss_reply_approve where user_id= '.$user_id.' and reply_id='.$discuss_id);
   }
}
