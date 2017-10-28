<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\Controller\CommonController;
use think\Session;
use Home\Common\Controller\Page;

class DetialController extends CommonController {
   
    public function detial(){
        //$img=self::getImg(1);
        $id=I('get.id');
        $page=I('get.page',0);
        $Page=new Page($count,10);
        $page=$Page->show();
        $data['detial']=self::getDetial($id);
        $data['img']=self::getImg($id)[0];
        $data['discuss']=self::getDiscuss($id,$Page); 
        $user=session('userInfo');
        $data['is_approve']=0;
        if ($user){
            $approve=self::getApprove($id,$user['user_id']);
            if ($approve){
                $data['is_approve']=1;
            } 
        }
        $this->assign('data',$data);
        $this->display();
    }
      
    private function getImg($id){
        return M('article_images')->where(array('art_id'=>$id))->order('type desc')->select();
    }
    private function getDetial($id){
        return M('article')->where(array('art_id'=>$id))->find();
    }
    
    private function getDiscuss($id,$Page=0){
        $mo=M('discuss');
        $data=$mo->alias('b')
        ->join("left join bk_user u on b.user_id=u.user_id")
        ->field("b.id,b.art_id,b.art_title,b.reply_id,b.parent_id,b.grade,b.content,b.user_id,b.addtime,u.name,u.avatar")
        ->where(array('b.is_show'=>1,'art_id'=>$id,'parent_id'=>0))
        ->order(array('b.addtime'=>'desc'))
        ->limit($Page->firstRow,$Page->listRows)->select();
        if ($data){
            foreach ($data as $k=>$v){              
                $data[$k]['addtime']=self::time_tran($v['addtime']);
                if (empty($v['avatar'])){
                    $data[$k]['avatar']='/bolg/tp3/Public/images/timg.jpg';
                }
                $data[$k]['child']=self::getDiscussChild($v['id']);
            }
        }
        return $data;
    }
    
    private function getDiscussChild($id){
        if ($id<=0){return false;}
        $mo=M('discuss');
        $data=$mo->alias('b')
            ->join("left join bk_user u on b.user_id=u.user_id")
            ->field("b.id,b.art_id,b.art_title,b.reply_id,b.parent_id,b.grade,b.content,b.user_id,b.addtime,u.name,u.avatar")
            ->where(array('b.is_show'=>1,'parent_id'=>$id))
            ->group('b.id')
            ->order(array('b.addtime'=>'desc'))
            ->select();
        if ($data){
            foreach ($data as $k=>$v){
                $data[$k]['addtime']=self::time_tran($v['addtime']);
                if (empty($v['avatar'])){
                    $data[$k]['avatar']='/bolg/tp3/Public/images/timg.jpg';
                }
                $reply=M('user')->field('name,avatar')->find($v['reply_id']);
                $data[$k]['reply']=$reply;
            }
        }
        return $data;
    }
    

    private function getApprove($id,$user_id=0){
        return M('approve')->where(array('art_id'=>$id,'user_id'=>$user_id,'is_approve'=>1))->find();
    }
    
}