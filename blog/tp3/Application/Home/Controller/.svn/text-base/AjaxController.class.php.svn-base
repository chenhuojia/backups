<?php
namespace Home\Controller;
use Think\Controller;
use Org\Util\Date;
class AjaxController extends Controller {
 
    /**
     * ajax点赞
     ***/
    public function approve(){
        $art_id=I('post.art_id');
        $user=session('userInfo');
        $approve=D('approve');
        if(!empty($user)){
            $user_id=$user['user_id'];
            $addtime=self::today();            
            $data=$approve->where(array('art_id'=>$art_id,'user_id'=>$user_id,'addtime'=>$addtime))->find();
            if ($data){
                $this->ajaxReturn(4);
            }
            $user_id=$user['user_id'];     
            $result=$approve->add(array('art_id'=>$art_id,'user_id'=>$user_id,'addtime'=>$_SERVER['REQUEST_TIME']));
            if ($result){
               M('article')->where('art_id='.$art_id)->setInc('approve_total',1);
               $this->ajaxReturn(1); 
            }
            $this->ajaxReturn(0);
        }else{
            $addtime=self::today();            
            $data=$approve->where(array('art_id'=>$art_id,'user_id'=>0,'addtime'=>$addtime))->count();
            if ($data>100){
                $this->ajaxReturn(3);
            }
            $result=$approve->add(array('art_id'=>$art_id,'user_id'=>0,'addtime'=>$_SERVER['REQUEST_TIME']));
            if ($result){
                M('article')->where('art_id='.$art_id)->setInc('approve_total',1);
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }
    
    /**
     * 获取今天内时间
     **/
     private function today(){
         date_default_timezone_set("Asia/Shanghai");
         $time=date("Y-m-d 00:00:00");
         $deadline=date("Y-m-d 24:00:00");
         $news=strtotime($time);
         $dead=strtotime($deadline);
         return array(array('gt',$news),array('lt',$dead));
     }
    
     /**
      * ajax写评论
      * **/
     public function discuss(){
        $art_id=I('post.art_id');
        $discuss_id=I('post.discuss_id',0);
        $content=I('post.content');
        $user=session('userInfo');
        $discuss=D('discuss');
        $user_id=$user['user_id'];
        $time=$_SERVER['REQUEST_TIME'];
        $newtime=date("Y-m-d H:i:s",$time);
        $data=M('article')->find($art_id);
        if ($data){                    
            if ($user_id){
                if ($discuss_id){
                    $discussDate=$discuss->find($discuss_id);
                    $result=$discuss->add(array('art_id'=>$art_id,'art_title'=>$data['title'],'reply_id'=>$discussDate['user_id'],'user_id'=>$user_id,'parent_id'=>$discuss_id,'content'=>$content,'addtime'=>$time));                
                }else{
                    $result=$discuss->add(array('art_id'=>$art_id,'art_title'=>$data['title'],'user_id'=>$user_id,'content'=>$content,'addtime'=>$time));
                }
                if ($result && $discuss_id){
                    $arr=array('id'=>$result,'content'=>$content,'addtime'=>$newtime,'avatar'=>$user['avatar'],'name'=>$user['name']);
                    $this->ajaxReturn(array('state'=>1,'data'=>$arr));
                }elseif($result){
                    $arr=array('id'=>$result,'content'=>$content,'addtime'=>$newtime,'avatar'=>$user['avatar'],'name'=>$user['name']);
                    M('article')->where('art_id='.$art_id)->setInc('discuss_total',1);
                   $this->ajaxReturn(array('state'=>1,'data'=>$arr));
                }else{
                    $this->ajaxReturn(0);
                } 
            }else{
                if ($discuss_id){
                    $discussDate=$discuss->find($discuss_id);
                    $result=$discuss->add(array('art_id'=>$art_id,'art_title'=>$data['title'],'reply_id'=>$discussDate['user_id'],'user_id'=>0,'parent_id'=>$discuss_id,'content'=>$content,'addtime'=>$time));
                }else{
                    $result=$discuss->add(array('art_id'=>$art_id,'art_title'=>$data['title'],'user_id'=>0,'content'=>$content,'addtime'=>$time));
                }
                if ($result && $discuss_id){
                    $arr=array('id'=>$result,'content'=>$content,'addtime'=>$newtime,'avatar'=>'/bolg/tp3/Public/images/timg.jpg','name'=>'游客');
                    $this->ajaxReturn(array('state'=>1,'data'=>$arr));
                }elseif($result){
                    $arr=array('id'=>$result,'content'=>$content,'addtime'=>$newtime,'avatar'=>'/bolg/tp3/Public/images/timg.jpg','name'=>'游客');
                    M('article')->where('art_id='.$art_id)->setInc('discuss_total',1);
                    $this->ajaxReturn(array('state'=>1,'data'=>$arr));
                }else{
                    $this->ajaxReturn(0);
                }
            } 
        }else{
            $this->ajaxReturn(2);
        }  
     }
     
     
     protected function time_tran($the_time){
         $now_time = time();
         $dur = $now_time - $the_time;
         if($dur < 0){
             return $the_time;
         }else{
             if($dur < 60){
                 return $dur.'秒前';
             }elseif($dur < 3600){
                 return floor($dur/60).'分钟前';
             }elseif ($dur < 86400){
                 return floor($dur/3600).'小时前';
             }elseif($dur < 259200){
                 return floor($dur/86400).'天前';
             }else{
                 return date('Y-m-d',$the_time);
             }
         }
     }
     
}