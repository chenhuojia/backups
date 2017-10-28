<?php
namespace Category\Controller;
use Think\Controller;
use think\Session;

class IndexController extends Controller {
   
    public function index(){
        
        $group_id=I('get.group_id',0);
        $data['groupchild']=self::getChild($group_id);
        $this->assign('data',$data);
        $this->display('categoryList');
       
    }
    
    
    protected function getChild($id){
       return M('discuz_group')->where(array('parent_id'=>$id,'is_show'=>1))->select();
    }
  
}