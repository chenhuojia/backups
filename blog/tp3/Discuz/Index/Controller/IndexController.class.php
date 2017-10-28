<?php
namespace Index\Controller;
use Think\Controller;
//use Discuz\Common\Controller\CommonController;
use think\Session;

class IndexController extends Controller {
   
    public function index(){         
        $data['tags']=self::getTags();
        $data['tag']=self::getTag();
        $this->assign('data',$data);
        $this->display('index');
       
    }
    
    protected function getTags(){
        $data=M('discuz_group')->where(array('is_show'=>1,'parent_id'=>array('gt',0)))->select();
        return $data;
    }
    
    protected function getTag(){
        $data=M('discuz_group')->where(array('is_show'=>1,'parent_id'=>0))->select();
        if ($data){
            foreach ($data as $k=>$v){
                $data[$k]['child']= M('discuz_group')->where(array('is_show'=>1,'parent_id'=>$v['group_id']))->select();
            }
        }
        return $data;
    }
    
   
}