<?php
namespace Detial\Controller;
use Think\Controller;
use think\Session;


class IndexController extends Controller {
   
    public function index(){
        $id=I('get.post_id');
        $data['data']=self::getData($id);
        $data['tags']=self::getTags($id);
        $data['discuss']=self::getDiscuss($id);
        $this->assign('data',$data);
        $this->display('single');
    }
      
    protected function getData($id){
        $data=M('discuz_post')->alias('p')
        ->where(array('p.id'=>$id,'p.is_show'=>1))
        ->join('left join bk_discuz_group g on p.topic_id=g.group_id')
        ->field('p.*,g.name')
        ->find();
        return $data;
    }
    
    protected function getTags($id){
        $data=M('discuz_post_tags')->alias('p')
        ->where(array('p.post_id'=>$id,'p.is_show'=>1))
        ->join('left join bk_discuz_group g on p.group_id=g.group_id')
        ->order('p.sort desc')
        ->field('g.group_id,g.name')
        ->select();
        return $data;
    }
    
    
    private function getDiscuss($id,$Page=0){
        $mo=M('discuz_post_discuss');
        $data=$mo->alias('b')
        ->join("left join bk_user u on b.user_id=u.user_id")
        ->field("b.id,b.post_id,b.reply_id,b.parent_id,b.content,b.user_id,b.addtime,u.name,u.avatar")
        ->where(array('b.is_show'=>1,'post_id'=>$id,'parent_id'=>0))
        ->order(array('b.addtime'=>'desc'))
        ->limit(0,10)->select();
        if ($data){
            foreach ($data as $k=>$v){
                $data[$k]['addtime']=date("F d, Y \a\\t g:i a",$v['addtime']);
                if (empty($v['avatar'])){
                    $data[$k]['avatar']='/bolg/tp3/Public/images/timg.jpg';
                }
                $data[$k]['child']=self::getDiscussChild($id,$v['id']);
            }
        }
        return $data;
    }
    
    private function getDiscussChild($post_id,$id){
        if ($id<=0){return false;}
        $mo=M('discuz_post_discuss');
        $data=$mo->alias('b')
        ->join("left join bk_user u on b.user_id=u.user_id")
        ->field("b.id,b.post_id,b.reply_id,b.parent_id,b.content,b.user_id,b.addtime,u.name,u.avatar")
        ->where(array('b.is_show'=>1,'post_id'=>$post_id,'parent_id'=>$id))
        ->group('b.id')
        ->order(array('b.addtime'=>'desc'))
        ->select();
        if ($data){
            foreach ($data as $k=>$v){
                $data[$k]['addtime']=date("F d, Y \a\\t g:i a",$v['addtime']);
                if (empty($v['avatar'])){
                    $data[$k]['avatar']='/bolg/tp3/Public/images/timg.jpg';
                }
                $reply=M('user')->field('name,avatar')->find($v['reply_id']);
                $data[$k]['reply']=$reply;
            }
        }
        return $data;
    }
    
    
}