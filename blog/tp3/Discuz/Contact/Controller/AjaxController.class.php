<?php
namespace Contact\Controller;
use Think\Controller;
use think\Session;

class AjaxController extends Controller {
   
    public function getChildren(){
        $topic_id=I('post.topic_id');
        if (empty($topic_id)) $this->ajaxReturn(array('code'=>300,'msg'=>'话题不对'));
        $data=M('discuz_group')->where(array('parent_id'=>$topic_id,'is_show'=>1))->select();
        if ($data) $this->ajaxReturn(array('code'=>200,'msg'=>$data));
        $this->ajaxReturn(array('code'=>300,'msg'=>'话题不对'));
        
    }
    
  
}