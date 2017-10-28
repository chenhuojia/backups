<?php
namespace Discuz\Controller;
use Think\Controller;
use Discuz\Common\Controller\CommonController;
use think\Session;

class IndexController extends CommonController {
   
    public function index(){         
        $data['ra']=self::recommend();
        $data['ral']=self::recommendList();
        $data['hot']=self::hotList();
        $this->assign('data',$data);
        $this->display('index');
       
    }
    
    
    protected function recommend(){
        $mo=M('article_recommend');
        $data=$mo->alias('b')
        ->join("left join bk_article a on b.art_id= a.art_id")
        ->join("left join bk_article_images i on b.art_id= i.art_id")
        ->field("b.art_id,a.title,a.cate_id,a.introduction,a.discuss_total,a.approve_total,i.image_url,FROM_UNIXTIME(b.addtime, '%Y-%m-%d') as addtime")       
        ->where(array('a.is_show'=>1,'b.is_rec'=>1,'i.type'=>1))
        ->order(array('b.sort'=>'desc'))
        ->limit(0,10)->select();
        return $data;
        
    }
    
    protected function recommendList(){
        $mo=M('article_recommend');
        $data=$mo->alias('b')       
        ->field('b.art_id,b.title')
        ->where(array('b.is_rec'=>1))
        ->order(array('b.sort'=>'desc'))
        ->limit(0,10)->select(); 
        return $data;  
    }
    
    protected function hotList(){
        $mo=M('article_hot');
        $data=$mo->alias('b')
        ->field('b.cate_id,b.art_id,b.cate_name,b.title')
        ->order(array('b.hot'=>'desc'))
        ->limit(0,10)->select();
        return $data;
    }
    
   
}