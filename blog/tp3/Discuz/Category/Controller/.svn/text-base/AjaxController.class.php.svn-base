<?php
namespace Category\Controller;
use Think\Controller;
use think\Session;

class AjaxController extends Controller {
   
    public function getChildTags(){
        $id=I('post.group_id');
        $data=M('discuz_group')->where(array('parent_id'=>$id))->select();
        $this->ajaxReturn($data);
       
    }
    
  
}