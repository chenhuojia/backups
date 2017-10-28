<?php
/**
 * 社区小组管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;
use Think\Page;

class   TopicController extends AdminController {
    
    /**
     * 话题帖子列表
     */
    public function tagList($n = '15')
    {   
        header("Content-type:text/html;charset=utf-8");
        $tag_name = I('get.tag_name');
        $where=array();
        //过滤筛选条件
        if ($tag_name)
        {
            $where['tag_name']=array('like','%'.$tag_name.'%');
        }
        $data = D("topic_tag")->TopicTagList($where,10);
        $this->page = $data["page"];
        $this->list = $data["data"];
        $this->display();
    }

    /**
     * 添加帖子栏目
     */
    public function tagAdd()
    {   
        header("Content-type:text/html;charset=utf-8");
        if($_POST){
            $where["tag_name"] = I('post.tag_name');
            $where["tag_describe"] = I('post.tag_describe');
            $where["user_id"] = I('post.user_id',1);
            $where["create_time"] = time();
            $lastId = D("topic_tag")->addData($where);
            if($lastId){
                $this->addLog('tag_name='.$_POST['tag_name'].'&tag_id='.$lastId,1);// 记录操作日志
                $this->success('新增话题栏目成功', U('Admin/Topic/tagList'));
            }else{
                $this->addLog('tag_name='.$_POST['tag_name'].'&tag_id='.$lastId,0);// 记录操作日志
                $this->error('新增话题栏目失败');
            }
        }else{
            $this->display();
        }
      
       
    }

    /**
     * 修改帖子栏目
     */
    public function tagEdit($tag_id='0')
    {   
        header("Content-type:text/html;charset=utf-8");
        if($_POST){
            $where["tag_name"] = I('post.tag_name');
            $where["tag_describe"] = I('post.tag_describe');
            $where["is_show"] = I('post.is_show');
            $map1['tag_id'] = $tag_id;
            $lastId = D("topic_tag")->editData($map1,$where);
            if($lastId){
                $this->addLog('tag_name='.$_POST['tag_name'].'&tag_id='.$lastId,1);// 记录操作日志
                $this->success('修改话题栏目成功', U('Admin/Topic/tagList'));
            }else{
                $this->addLog('tag_name='.$_POST['tag_name'].'&tag_id='.$lastId,0);// 记录操作日志
                $this->error('修改话题栏目失败');
            }
        }else{
            $this->classType = D("topic_tag")->TopicTagDetail($tag_id);
            $this->display();
        }
        
    }


    /**
     * 删除帖子栏目
     */
    public function delTag()
    {
        $tag_id=I('get.tag_id',0);
        if ($tag_id){
           $data=D("topic_tag")->delTag($tag_id);
           if($data){
               $this->success('SUCCESS');
           }
           $this->error('flase');
        }
    }
    
    
    
    
    /**
     * 帖子列表
     */
    public function topicList($n = '10')
    {
        $groupType = I('get.groupType');
        $userName = I('get.userName');
        $startTime = strtotime(I('get.startTime'));
        $endTime = strtotime(I('get.endTime'));
        //过滤筛选条件
        $where=array();
        if($groupType!='all' && $groupType!=null)
        {
            $where['t.tag_id']=$groupType;
        }
        if ($userName)
        {
            $where['t.user_name']=array('like','%'.$userName.'%');
        }
        if ($endTime && $startTime)
        {
            $where['t.addtime']=array('between',array($startTime,$endTime));
        }
        
        $data = D("topic")->TopicLists($where,$n);
        $this->Page = $data["page"];
        $this->list = $data["list"];

        //查询栏目
        $data=D('topic_tag')->where('is_show=1')->select();
        $this->info=$data;
        $this->display();
    }

     /**
     * 帖子详情展示
     */
    public function topicDet($topic_id='0')
    { 
        $map['t.topic_id'] = $topic_id ;
        $data = D("topic")->getOneDetail($map);
        $this->topic = $data;
        $img=explode(";",$data['imageurl']);
        foreach ($img as $k =>$v){
            $img[$k]=C('QINIU').$v;
        }
        
        $this->image = $img;
        $this->display();
    }

      /**
     * 添加帖子
     */
    public function topicAdd()
    {   
        header("Content-type:text/html;charset=utf-8");
        if($_POST){
            $where["tag_id"] = I('post.tag_id');
            $where["content"] = I('post.content');
            $where["user_id"] = I('post.user_id',0);
            $img=I('post.img'); if (empty($img)) $this->error('请上传封面');
            if ($where["user_id"]){
                $user=M('user')->alias('u')->join('left join kts_user_xq x on u.user_id=x.user_id')->where('u.user_id='.$where['user_id'])->field('u.*,x.*')->find();                
            }else{
                $this->error('请选择用户');
            } 
            if (!$user){
                $this->error('用户不存在');
            }
            $where['user_name']=$user['name'];
            $where['user_avatar']=$user['imageurl']?$user['imageurl']:'qiushu_148213223834.png';
            $where['addtime']=$_SERVER['REQUEST_TIME'];
            $where['title']=I('post.title');
            $imgs=implode(';',$img);
            $where['imageurl']=$imgs;
            $lastId=M('topic')->add($where);
            if($lastId){
                M('topic_tag')->where(array('tag_id'=>$where['tag_id']))->setInc('post_num',1);                
                $this->addLog('tag_id='.$where['tag_id'].'&topic_id='.$lastId,1);// 记录操作日志
                $this->success('新增帖子成功', U('Admin/Topic/topicList'));
            }else{
                $this->addLog('tag_id='.$where['tag_id'].'&topic_id='.$lastId,0);// 记录操作日志
                $this->error('新增帖子失败');
            }
        }else{

            //查询栏目
            // $where1['is_show']=1;
            // $this->info=D('topic_tag')->where($where1)->select();            
            $data['tag']=D('topic_tag')->select();
            $data['user']=M('user')->where(array('is_show'=>0))->select();
            $this->info=$data;
            $this->display();
        }
      
       
    }


     /**
     * 修改帖子信息
     */
    public function topicEdit($topic_id='0')
    {
        if (IS_POST)
        {
            //$where["tag_id"] = I('post.tag_id',0);
            $where["title"] = I('post.title');
            $where["content"] = I('post.content');
            $where["is_show"] = I('post.is_show');
            $where["updtime"] = $_SERVER['REQUEST_TIME'];
            //$map1['topic_id'] = $topic_id;
            $lastId = D("topic")->where(array('topic_id'=>$topic_id))->save($where);
            if ($lastId !== false) {
                $this->addLog('name='.$_POST['title'].'&topic_id='.$lastId,1);// 记录操作日志
                $this->success('编辑帖子成功', U('Admin/Topic/topicList'));
            }else{
                $this->addLog('name='.$_POST['title'].'&topic_id='.$lastId,0);// 记录操作日志
                $this->error('编辑帖子失败');
            }
        }
        else
        {   
            $map['t.topic_id'] = $topic_id ;
            $this->topicmessage = D('topic')->getOneDetail($map);
            $this->image = $this->topicmessage["imageurl"];
            //查询栏目
            // $where1['is_show']=1;
            // $this->info=D('topic_tag')->where($where1)->select();
            $data=D('topic_tag')->select();
            $data['user']=M('user')->where(array('is_show'=>0))->select();
            $this->info=$data;
            $this->display();
        }
    }

     /**
     * 修改帖子状态
     */
    public function topicStatus($topic_id='0',$tag_id='0',$status=0)
    {       
            header("Content-type:text/html;charset=utf-8");
            //$data['is_show'] = $status;
            $where['topic_id'] = $topic_id;
            $lastId=D("topic")->where($where)->setField('is_show',$status);
            if ($lastId) {
                if($status==0){
                    //统计栏目的总数减1
                    M("topic_tag")->where(array('tag_id'=>$tag_id))->setDec('discuss_number');
                }else{
                    //统计栏目的总数+1
                    M("topic_tag")->where(array('tag_id'=>$tag_id))->setInc('discuss_number');
                }
               
                $this->addLog('is_show='.$status.'&topic_id='.$lastId,1);// 记录操作日志
                $this->success('改变话题状态成功', U('Admin/Topic/topicList'));
            }else{
                $this->addLog('is_show='.$status.'&topic_id='.$lastId,0);// 记录操作日志
                $this->error('改变话题状态失败');
            }
    }

    /**
     * 推荐帖子功能
     * **/
    public function rec(){
        $id=I('post.id',0);
        $val=I('post.val');
        if ($val==1){
            $data=array('topic_id'=>$id,'addtime'=>$_SERVER['REQUEST_TIME']);
            $result=M('topic_recommend')->add($data);
          
        }else{
           $result= M('topic_recommend')->where(array('topic_id'=>$id))->delete();          
        } 
        if($result){
            $this->ajaxReturn(1);
            $this->addLog('is_show='.$_POST['status'].'&topic_id='.$id,1);
        }       
        $this->addLog('is_show='.$_POST['status'].'&topic_id='.$id,0);// 记录操作日志
        $this->ajaxReturn(0);
    }

     

    /**
     * 推荐帖子列表
     ***/
   public function recList(){
       $type=I('get.type','all');
       if ($type=='all'){
          $where=array(); 
       }elseif ($type==1){
           $where=array('type'=>1);
       }elseif ($type==2){
           $where=array('type'=>2);
       }
       $total=M('topic_recommend')->where($where)->count();
       $Page=New Page($total,10);
       $data=M('topic_recommend')->where($where)->limit($Page->firstRow,$Page->listRows)->order(array('sorting'=>'desc','addtime'=>'desc'))->select();
       if ($data){
           foreach ($data as $k=>$v){
               if ($v['type']==1){
                 $tmp=M('topic')->alias('t')
                   ->join('left join kts_topic_tag tt on t.tag_id = tt.tag_id ')
                   ->field('t.*,tt.tag_name')
                   ->find($v['topic_id']);
                 $data[$k]['info']=$tmp;
               }if ($v['type']==2){
                   $tmp=M('book_comment')->alias('t')                   
                   ->field('t.*')
                   ->find($v['topic_id']);
                   $data[$k]['info']=$tmp;
               }
               
           }
       }
       $this->list=$data;
       $this->page=$Page->show();
       $this->display('recTopicList');
   }

   /**
    * 取消推荐
    * **/
   public function delRec(){
     $id=I('post.id');
     $data=M('topic_recommend')->delete($id);
     $this->addLog('is_show='.$_POST['status'].'&topic_id='.$id,1);// 记录操作日志
     $this->ajaxReturn($data);
   }

   /**
    * 更改排序
    * **/
   public function changesort(){
       $id=I('post.id');
       $sort=I('post.sort');
       $data=M('topic_recommend')->where(array('recommend_id'=>$id))->setField('sorting',$sort);
       $this->addLog('is_show='.$_POST['status'].'&recommend_id='.$id,1);// 记录操作日志
       $this->ajaxReturn($data);
   }



   



} 