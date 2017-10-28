<?php
namespace Detial\Controller;
use Think\Controller;
use think\Session;


class AjaxController extends Controller {
   
    /**
     * 添加评论
     ***/
    public function addDiscuss(){
        
        $post_id=I('post.post_id');
        $discuss_id=I('post.discuss_id',0);
        $content=I('post.content');
        $user=session('userInfo');
        $discuss=D('discuz_post_discuss');
        $user_id=$user['user_id'];
        $time=$_SERVER['REQUEST_TIME'];
        $newtime=date("Y-m-d H:i:s",$time);
        $data=M('discuz_post')->find($post_id);
        if ($data){
            if ($user_id){
                if ($discuss_id){
                    $discussDate=$discuss->find($discuss_id);                    
                    if ($discussDate['parent_id']==0){
                        $discuss_id=$discuss_id;
                        $rep=M('user')->where('user_id='.$discussDate['user_id'])->find();
                        $rep_name=$rep['name'];
                    }else{                        
                        $discuss_id=$discussDate['parent_id'];
                        $rep=M('user')->where('user_id='.$discussDate['reply_id'])->find();
                        $rep_name=$rep['name'];
                    }
                    $result=$discuss->add(array('post_id'=>$post_id,'reply_id'=>$discussDate['user_id'],'user_id'=>$user_id,'parent_id'=>$discuss_id,'content'=>$content,'addtime'=>$time));
                }else{
                    $result=$discuss->add(array('post_id'=>$post_id,'user_id'=>$user_id,'content'=>$content,'addtime'=>$time));
                }
                if ($result && $discuss_id){
                    $arr=array('id'=>$result,'content'=>$content,'addtime'=>$newtime,'avatar'=>$user['avatar'],'name'=>$user['name'],'replay_name'=>$rep_name);
                    $this->ajaxReturn(array('state'=>1,'data'=>$arr));
                }elseif($result){
                    $arr=array('id'=>$result,'content'=>$content,'addtime'=>$newtime,'avatar'=>$user['avatar'],'name'=>$user['name']);
                    M('discuz_post')->where('id='.$post_id)->setInc('discuss_num',1);
                    $this->ajaxReturn(array('state'=>1,'data'=>$arr));
                }else{
                    $this->ajaxReturn(0);
                }
            }else{
                 $this->ajaxReturn(3);
            }
        }else{
            $this->ajaxReturn(2);
        }
    }
    
}