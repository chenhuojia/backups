<?php
namespace User\Model;
use Think\Model;
/**
 * ModelName
 */
class BooklistDiscussReplyModel extends Model{
   
    // 自动验证
    protected $_validate=array(     
      array('discuss_id','require','请填写评论id',1),
      array('content','require','请填写回复内容',1),
     );
   // 自动完成
    protected $_auto=array(
        array('addtime','time',3,'function'),
        array('user_id','checkuser',3,'callback'),
        array('fid','checkfid',3,'callback'),
    );
    
   protected function checkuser(){
       return session('user_id');
   }

   protected function checkfid(){
       return I('post.fid',0);
   }
   
   /**
    * 回复书单评论
    * @param unknown $booklist_id
    * @param unknown $skip
    * @param unknown $take
    * @param number $user_id
    * @return number[]|string[]***/
   public function bookListDiscussReply($booklist_id,$skip,$take,$user_id=0){
       $data=$this
       ->field('id as reply_id,discuss_id,content,discuss_num,approve_num,addtime,user_id')
       ->where(array('discuss_id'=>$booklist_id,'fid'=>0))
       ->limit($skip,$take)
       ->order('addtime desc')
       ->select();
       if ($data){
           foreach ($data as $k=>$v){
                $data[$k]['is_approve']=0;
                if ($user_id){
                $approve=M()->execute('select * from kts_booklist_discuss_reply_approve where user_id='.$user_id.' and reply_id = '.$v['reply_id']);
                $data[$k]['is_approve']=$approve?1:0;
                }
               $user= \User\Util\Util::GetUserAvatrAndNick($v['user_id']);
               $data[$k]['user_name'] = $user['name'];
               $data[$k]['user_avatar'] = $user['avatar'];
               $data[$k]['addtime'] = date('m月d日 H:i',$v['addtime']);
           }
             return array('code'=>200,'msg'=>$data);
       }
       return array('code'=>300,'msg'=>'书单暂时没有评论');
   }
}
