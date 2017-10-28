<?php
/**
 * 问答管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;
use Think\Page;

class AttrController extends AdminController {

      private static $result=NULL;    
    /**
     * 评论列表
     */
     public function evalList()
    {       
        $comment_id=I('get.comment_id');
        $data=$this->commentReply($comment_id);
        $this->data=$data;
//         print_r($data);
        //$a=$this->arr14($data['sub']);
        $this->display();
        
    } 
    
   public function answerList(){
       
       
       $this->display();
   }

    
    
    
    public function arr14($data){
        static $ret=array();        
        foreach ($data as $k=>$v){
            if (!empty($data[$k]['sub'])){                
                $this->arr($data[$k]['sub']);
                $ret[]=$v['sub'];
            }
        }        
        return $ret;
    }

    
    
    public function commentList(){
        $book_id=I('request.book_id');
        $ret=M('book')->field('book_number')->find($book_id);
        $where['c.book_number']=$ret['book_number'];        
        $where['fid']=0;
        $where['c.is_show']=1;
        $comment=M('book_comment');
        $count=M('book_comment')->where(array('fid'=>0,'is_show'=>1,'book_number'=>$ret['book_number']))->count();
        $Page=new Page($count,3);
        $data['comment']=$comment->alias('c')
            ->join('left join kts_user as u on c.user_id =u.user_id')
            ->join('left join kts_user_xq as xq on c.user_id =xq.user_id')
            ->where($where)
            ->order('c.comment_time desc')
            ->field('u.user_id,c.comment_id,c.comment_time,c.content,c.grade,u.name as username,xq.imageurl as head_img,c.fid')
            ->limit($Page->firstRow,$Page->listRows)
            ->select();
        foreach ($data['comment'] as $k=>$v){
            self::$result=0;
            $data['comment'][$k]['comment_time']=date('n月d日 H:i',$v['comment_time']); 
            $data['comment'][$k]['click_total']=$this->click($v['comment_id']);
            $data['comment'][$k]['comment_total']=$this->count($v['comment_id']);
        }        
        /*$data['comment_avg']=round($comment->alias('c')->where($where)->avg('grade'));
        foreach ($data['comment'] as $k => $v){
            self::$result=0;
            $data['comment'][$k]['sub']=$this->commentReply($v['comment_id']);
        
        } */
        $data['page']=$Page->show();
        //print_r($data);
        $this->ajaxReturn($data);
    }

    public function commentReply($comment_id){
        $data=D('book_comment')->alias('c')
             ->join('left join kts_user as u on c.user_id =u.user_id')
             ->join('left join kts_user_xq as xq on c.user_id =xq.user_id')
             ->where(array('c.is_show'=>1,'c.comment_id'=>$comment_id))             
             ->field('c.comment_id,u.user_id,c.comment_time,c.content,c.grade,u.name as username,xq.imageurl,c.fid')
             ->find(); 
        $data['sub']=$this->personalComment($comment_id);
        $data['replycount']=self::$result;
        $data['click']=$this->click($comment_id);
        return $data;
    
    }
    
    
    public function personalComment($comment_id){
        $where['c.fid']=$comment_id;
        $where['c.is_show']=1;
        $data=D('book_comment')->alias('c')
                ->join('left join kts_user as u on c.user_id =u.user_id')
                ->join('left join kts_user_xq as xq on c.user_id =xq.user_id')
                ->where($where)
                ->order('c.comment_time desc')
                ->field('c.comment_id,u.user_id,c.comment_time,c.content,c.grade,u.name as username,xq.imageurl,c.fid')
                ->select();
         foreach ($data as $k=>$v){
            $data[$k]['comment_time']=date('n月d日 H:i',$v['comment_time']);
            if ($data[$k]['user_id']==0){
                $data[$k]['username']=session('userName');
            }
            if ($data[$k]['fid']!=0){
                self::$result++;
                $data[$k]['sub']=$this->personalComment($data[$k]['comment_id']);
                
            }
        }
        return $data; 
    }
    
    public function count($comment_id){
        $where['c.fid']=$comment_id;
        $where['c.is_show']=1;
        $data=D('book_comment')->alias('c')
        ->where($where)
        ->select();
        foreach ($data as $k=>$v){          
            if ($data[$k]['fid']!=0){
                $data[$k]['sub']=$this->personalComment($data[$k]['comment_id']);
                self::$result++;
            }
        }
        return self::$result;
    }
    
    public function click($comment_id)
    {
        $tmp=M('book_comment')->field('user_id')->find($comment_id);
        $user_id=$tmp['user_id'];
        $where=array('comment_id'=>$comment_id,'fid'=>$user_id,'is_show'=>1);
        $data['count']=M('book_click')->where($where)->count();
        return $data['count'];
    }
    
    /**
     * 查看点赞
     */
    public function questionList()
    {
        $clicks = M('book_click');
        $book_id= I('get.book_id');
        $type =I('get.type');
        $count = $clicks->where('book_id = %d and type = %d and is_show=1',$book_id,$type)->count();
        $Page = new \Think\Page($count,15);
        
        //$data=$clicks->where('book_id = %d and is_show = %d',$book_id,$is_show)->field('username,addtime,user_img')->select();  
        $data['attr']=$clicks->where('book_id = %d and type=%d and is_show=1 ',$book_id,$type)->order('id desc')
        ->field('book_id,user_id,username,user_img,addtime')
        ->limit($Page->firstRow.','.$Page->listRows)->select(); 
        foreach ($data['attr'] as $k =>$v){
            if ($data['attr'][$k]['user_img']==null){
                $data['attr'][$k]['user_img']='Public/Upload/User/10016.gif';
            }
        }               
        $Page=$Page->show();
        $data['page']=$Page;
        //print_r($data);
        $this->ajaxReturn($data); 
        //$this->ajaxReturn($book_id);
    }

    /**
     * 删除评论
     */
    public function commentDel($comment_id,$list=0)
    {
        $comment = M('book_comment');
        $ret=$this->getfid($comment_id);       
        $data=$this->delAll($comment_id);
        $where['comment_id'] = $comment_id;
        $id=$comment->field('book_id')->find($comment_id);
        if ($data)
        {
            if (!$list){
                $this->success('删除成功', U('Admin/Book/BookDet?book_id='.$id['book_id']));
            }else{
                $this->success('删除成功', U('Admin/Attr/evalList?comment_id='.$ret[0]));
            }            
        }
        else
        {

            $this->error('删除失败');
        }
    }
    
    
    public  function delAll($comment_id){
        $data=array(
            'is_show'=>0,
        );
        $a=M('book_comment')->where(array('comment_id'=>$comment_id))->save($data);
        $data=M('book_comment')->where(array('fid'=>$comment_id))->find();        
        if ($data){
            $this->delAll($data['comment_id']);
        }
        return $a;
    }
    
    public function getfid($comment_id){
        $data=M('book_comment')->where()->find($comment_id);
        if ($data['fid']!=0){
            $fid=$this->getfid($data['fid']);
            $fid[]=$data['fid'];
        }
        return $fid;
    }
    
    
    public function getCity(){
        $where['father']=I('post.father_id');
        //$where['open']=0;
        $data=M('city')->where($where)->field('city_id,city')->select();
        if ($data){
            $this->ajaxReturn($data);
        }
    }
    
    public function commentAdd(){
        $data['fid']=I('post.comment_id');
        $book=M('book_comment')->find($data['fid']);
        $data['content']=I('post.content');
        $data['book_id']=$book['book_id'];
        $data['book_number']=$book['book_number'];
        $data['comment_time']=time();
        $id=M('book_comment')->add($data);
       if ($id){
           $this->ajaxReturn($data);
       }
    }
    
    
    public function findPress(){
        $searchKey=I('get.searchkey');
        $searchKey=urldecode($searchKey);
        $where['name']=array('like', '%'.$searchKey.'%');
        $data=M('press')->where($where)->field('p_id,name')->limit(20)->select();
        if ($data){
            $this->ajaxReturn($data);
        }
    }
} 