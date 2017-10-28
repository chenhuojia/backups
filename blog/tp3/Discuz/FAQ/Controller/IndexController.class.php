<?php
namespace FAQ\Controller;
use Think\Controller;
use think\Session;
use Org\Util\Page;


class IndexController extends Controller {
   
    public function index(){
        $model=M('message_wall');
        $count=$model->where(array('is_show'=>1))->count();
        $Page=new Page($count,10);
        $data=$model->alias('s')
            ->join('left join bk_user u on s.user_id=u.user_id')
            ->where(array('s.is_show'=>1))
            ->field('s.id,s.user_id,u.name,s.title,s.content,s.addtime')
            ->limit($Page->firstRow,$Page->listRows)
            ->order('s.id desc')
            ->select();
        if ($data){
            $weekarray=array("日","一","二","三","四","五","六");
            foreach ($data as $k=>$v){
               $data[$k]['week']=$weekarray[date('w',$v['addtime'])]; 
               
            }
        }
        $this->page=$Page->show();
        $this->data=$data;
        $this->display('faq');
    }
    
    
    public function add(){
        $model=D('message_wall');
        if (!$model->create()){
            $this->ajaxReturn($model->getError());
        }else{
            $model->add();
            $this->ajaxReturn(1);
        } 
    }
  
    
    public function privaty(){
        $model=M('privacy');
        $count=$model->where(array('is_show'=>1))->count();
        $Page=new Page($count,10);
        $data=$model->alias('s')
        ->join('left join bk_user u on s.user_id=u.user_id')
        ->where(array('s.is_show'=>1))
        ->field('s.id,s.user_id,u.name,s.title,s.content,s.addtime')
        ->limit($Page->firstRow,$Page->listRows)
        ->order('s.id desc')
        ->select();
        if ($data){
            $weekarray=array("日","一","二","三","四","五","六");
            foreach ($data as $k=>$v){
                $data[$k]['week']=$weekarray[date('w',$v['addtime'])];                 
            }
        }else{
            $weekarray=array("日","一","二","三","四","五","六");
            $time=time();
            $data=array(
                array(
                    'week'=>$weekarray[date('w',$time)],
                    'addtime'=>$time,
                    'name'=>'家裕',
                    'content'=>'我想你！',
                ),
            ); 
        }
        $this->page=$Page->show();
        $this->data=$data;
        $this->display('private');
    }
}