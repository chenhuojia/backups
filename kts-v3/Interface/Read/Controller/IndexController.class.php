<?php
namespace Read\Controller;

use Think\Controller;

class IndexController extends Controller
{   
    /**
     * 阅读会下的栏目列表
     */
    public function tagList()
    {   
    	$map['is_show']=1;
        $TagLogic=D('TopicTag','Logic');
        $data= $TagLogic->searchAllData($map);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

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
        $data = D('Topic')->getAllData($where,$orderlist,$skip,$take);
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
        $data = D('Topic')->getAllData($where,$orderlist,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     /**
     * 书评列表
     */
    public function BookCommentList()
    {   
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $search = I('get.search','');
        if(trim($search) !== ''){
             $where['b.book_name'] = array('like','%'.$search.'%');
        }
        $where['b.is_show'] = array('EQ','1');
        $where['b.user_id'] = array('NEQ',0);
        $where['b.fid'] = array('EQ',0);
        $orderlist=array('b.comment_time'=>'desc');
        $BookLogic=D('Book','Logic');
        $data= $BookLogic->searchAllData($where,$orderlist,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }


    /**
     * 历史栏目下的全部话题
     */
    public function topicHistoryList()
    {   
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $history_id = I('get.history_id',0);
        $search = I('get.search','');
        if(trim($search) !== ''){
             $where['b.title'] = array('like','%'.$search.'%');
        }
        if($history_id>0) $where['h.history_id'] = array('EQ',$history_id);
        $where['b.is_show'] = array('EQ','1');
        $where['b.tag_id'] = array('EQ','3');
        $orderlist=array('b.topic_id'=>'desc');
        $BookLogic=D('TopicHistory','Logic');
        $data= $BookLogic->searchAllData($where,$orderlist,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     /**
     * 获取某一随机的历史事件
     */
    public function historyRandom()
    {   
       $data=M('topic_history')
            ->alias('b')
            ->join('LEFT JOIN kts_history as h ON b.history_id = h.history_id')
            ->where(array('h.is_show'=>1))
            ->field('h.history_id,h.ad_year,h.lunar_year,h.calendar_name,h.dynasty,h.posthumous,h.era')
            ->find();
        if (empty($data)){
            $data=M('history')->where(array('is_show'=>1))->field('history_id,ad_year,lunar_year,calendar_name,dynasty,posthumous,era')->find(); }
        if(!empty($data)){
            $data['calendar'] = $data['dynasty'].$data['posthumous'].$data['era'];
            $this->myApiPrint(200,'success',$data);
        }else{
            $this->myApiPrint(202,'暂无数据');
        }
        
    }

   
   
 


    
    
    
}

