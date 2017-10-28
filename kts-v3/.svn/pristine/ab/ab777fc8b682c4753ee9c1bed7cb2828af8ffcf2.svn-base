<?php
namespace Read\Controller;

use Think\Controller;

class IndexV2Controller extends Controller
{   
    
    /**
     * 某一栏目下的全部话题
     */
    public function topicList()
    {   
    	$skip = I('get.skip',0);
        $take = I('get.take',10);
        $tag_id = I('get.tag_id',0);
        $search = I('get.search','');
        if(trim($search) !== ''){
             $where['t.title'] = array('like','%'.$search.'%');
        }
        $where['t.is_show'] = array('EQ','1');
        $where['t.tag_id'] = array('EQ',$tag_id);
    	$orderlist=array('t.sorts'=>'desc','t.addtime'=>'desc');
        $TopicLogic=D('Topic','Logic');
        $data= $TopicLogic->getAllData($where,$orderlist,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    /**
     * 推荐的话题
     */
    public function RecommendTopicList()
    {   
    	$skip = I('get.skip',0);
        $take = I('get.take',10);
        $tag_id = I('get.tag_id',0);
        $search = I('get.search','');
        if(trim($search) !== ''){
             $where['t.title'] = array('like','%'.$search.'%');
        }
        $where['t.is_show'] = array('EQ','1');
        $where['t.tag_id'] = array('EQ',$tag_id);
        $orderlist=array('t.sorts'=>'desc','t.addtime'=>'desc');
        $TopicLogic=D('Topic','Logic');
        $data= $TopicLogic->getAllData($where,$orderlist,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

   
   
 


    
    
    
}

