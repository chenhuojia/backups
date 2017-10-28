<?php
namespace Discuz\Common\Controller;
use Think\Controller;
class CommonController extends Controller {
    
    public function _initialize(){
       self::public_assign();
    }

   
    public function public_assign(){    
       /*  $data=D('Category')->getAllCategory();
        $banner=D('banner')->getIndex();
        $this->assign('nav',$data);
        $this->assign('banner',$banner);
        $this->assign('discuss',self::newDiscuss()); */
    }

    protected function newDiscuss(){
        $mo=M('discuss');
        $data=$mo->alias('b')
        ->join("left join bk_user u on b.user_id=u.user_id")
        ->field("b.id,b.art_id,b.art_title,b.parent_id,b.grade,b.content,b.user_id,b.addtime,u.name,u.avatar")
        ->where(array('b.is_show'=>1,'parent_id'=>0))
        ->order(array('b.addtime'=>'desc'))
        ->limit(0,5)->select();
        if ($data){
            foreach ($data as $k=>$v){              
                $data[$k]['addtime']=self::time_tran($v['addtime']);
            }
        }
        return $data;
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