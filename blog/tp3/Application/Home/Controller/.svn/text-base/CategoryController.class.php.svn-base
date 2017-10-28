<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\Controller\CommonController;
use think\Session;
use Home\Common\Controller\Page;

class CategoryController extends CommonController {
   
    public function index(){
        $cate_id=I('get.id');
        $page=I('get.page',0);
        $count    = M('article')->where(array('cate_id'=>$cate_id))->count();
        $Page=new Page($count,10);
        $page=$Page->show();
        $data['nav']=D('Category')->getAllCategory();
        $data['list']=self::getArticle($cate_id,$Page);
        $data['page']=$page;
        $this->assign('data',$data);
        $this->display();
       
    }
    
    
    private function getArticle($id,$Page=0){
       $mo=M('article');
       $data=$mo->alias('b')
        ->join("left join bk_article_images i on b.art_id= i.art_id")
        ->field("b.art_id,b.title,b.cate_id,b.introduction,b.discuss_total,b.approve_total,i.image_url,FROM_UNIXTIME(b.addtime, '%Y-%m-%d %H:%i:%s') as addtime")       
        ->where(array('b.is_show'=>1,'cate_id'=>$id))
        ->order(array('b.addtime'=>'desc'))
        ->limit($Page->firstRow,$Page->listRows)->select();
       return $data;
    }
 
   
}