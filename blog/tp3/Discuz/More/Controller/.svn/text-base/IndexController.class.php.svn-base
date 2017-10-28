<?php
namespace More\Controller;
use Think\Controller;
use think\Session;
use Org\Util\Page;

class IndexController extends Controller {
   
    public function elements(){

        $this->display('elements');
       
    }
    
    public function full(){
        $title=I('get.title');
        $user_id=I('get.user_id');
        $where['p.is_show']=1;
        if ($title){
            $where['p.title']=array('like',"%$title%");
        }if ($user_id){
            $where['p.user_id']=$user_id;
        }
        $model=M('discuz_post');
        $count=$model->alias('p')->where($where)->count();
        $Page=new Page($count,10);
        $data['list']=self::getPost($where,$Page->firstRow,$Page->listRows);
        $data['page']=$Page->show();
        $this->data=$data;
        $this->display('full-width');
         
    }
    
    public function page(){
    
        $this->display('page');
         
    }
    
    protected function getPost($where,$page=0,$take=10){
        $data=M('discuz_post')->alias('p')
        ->where($where)
        ->join('left join bk_discuz_group g on p.topic_id=g.group_id')
        ->join('left join bk_user u on p.user_id=u.user_id')
        ->order(array('p.addtime'=>'desc'))
        ->field('p.*,g.name,u.name as user_name')
        ->limit($page,$take)
        ->select();
        return $data;
    }
 
    
  
}