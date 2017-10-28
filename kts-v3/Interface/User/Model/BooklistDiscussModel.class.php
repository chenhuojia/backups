<?php
namespace User\Model;
use Think\Model;
/**
 * ModelName
 */
class BooklistDiscussModel extends Model{
   
    // 自动验证
    protected $_validate=array(     
      array('booklist_id','require','请填写书单id',1),
      array('content','require','请填写评论内容',1),
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
    * 书单评论
    * @param unknown $booklist_id
    * @param unknown $skip
    * @param unknown $take
    * @param number $user_id
    * @return number[]|string[]***/
   public function bookListDiscuss($booklist_id,$skip,$take,$user_id=0){
       $data=$this
       ->field('id as disucss_id,booklist_id,content,discuss_num,approve_num,addtime,user_id')
       ->where(array('booklist_id'=>$booklist_id,'is_show'=>1))
       ->limit($skip,$take)
       ->order('addtime desc')
       ->select();
       if ($data){
           $child=array();
           foreach ($data as $k=>$v){
               $data[$k]['is_approve']=0;
               if ($user_id){
                   $approve=M()->execute('select * from kts_booklist_discuss_approve where user_id='.$user_id.' and discuss_id = '.$v['disucss_id']);
                   $data[$k]['is_approve']=$approve?1:0;
               }
               $child=M()->query('select user_id,content from kts_booklist_discuss_reply where fid=0 and discuss_id = '.$v['disucss_id'].'  order by addtime desc limit 0,3');
               if (!empty($child)){
                   foreach ($child as $kk=>$vv){
                       $user= \User\Util\Util::GetUserAvatrAndNick($vv['user_id']);
                       $child[$kk]['user_name'] = $user['name'];
                   }
               }
               $data[$k]['child']=$child;
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
