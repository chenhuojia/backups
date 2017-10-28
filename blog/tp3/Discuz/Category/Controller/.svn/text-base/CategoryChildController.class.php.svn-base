<?php
namespace Category\Controller;
use Think\Controller;
use think\Session;
use Org\Util\Page;


class CategoryChildController extends Controller {
   
    public function index(){
        $id=I('get.group_id');
        $totalRows=M('discuz_post')->where(array('group_id'=>$id,'is_show'=>1))->count();
        $Page=new Page($totalRows,10);
        $page=$Page->show();
        $data['list']=self::getPost($id,$Page->firstRow,$Page->listRows);
        $data['page']=$page;
        $this->assign('data',$data);
        $this->display('articles-list');
    }
    
  
    protected function getPost($id,$page=0,$take=10){
        $data=M('discuz_post')->alias('p')
        ->where(array('p.group_id'=>$id,'p.is_show'=>1))
        ->join('left join bk_discuz_group g on p.topic_id=g.group_id')
        ->order(array('p.addtime'=>'desc'))
        ->field('p.*,g.name')
        ->limit($page,$take)
        ->select();
        return $data;
    }
    
}