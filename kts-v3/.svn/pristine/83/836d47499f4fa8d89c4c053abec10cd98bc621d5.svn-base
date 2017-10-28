<?php
/**
 * 用户基本信息CRUD相关
 * @author David
 *
 */
namespace User\Controller;

use Think\Controller;
use User\Util;


class IndexController extends Controller
{   

     /**
     * 个人中心
     */
     public function userInfo(){
        $user_id = I('get.user_id',0);
        $session_user_id=session("user_id"); //用户id
        $data=M('user')->alias('u')
                       ->join('left join kts_user_xq x on u.user_id = x.user_id')
                       ->field('u.user_id,phone,x.introduce')
                       ->where(array('u.user_id'=>$user_id))
                       ->find();
        $userMes = \User\Util\Util::GetUserAvatrAndNick($user_id);
        $data['avatar'] = $userMes['avatar'];
        $data['name'] = $userMes['name'];
        $data['share_num']=M("book_share")->where('user_id='.$user_id)->count();
        $data['comment_num'] = M("book_comment")->where('user_id='.$user_id)->count();
        $data['old_num'] = M("book_old")->where('user_id='.$user_id)->count();
        $data['follow_num']=M("topic_follow")->where('follow_user='.$user_id)->count();
        $data['booklist_num']=M("booklist")->where('user_id='.$user_id.' and is_show=1')->count();
        $is_follow = M('topic_follow')->where(array('follow_user' =>$user_id,'user_id'=>$session_user_id))->find();
        $data['is_follow'] = 0;
        if($is_follow) $data['is_follow'] = 1;
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
    }

     /**
      *我的分享
      * **/       
     public function shareBook(){
         $user_id = I('get.user_id',0);
         $skip=I('get.skip',0);
         $take=I('get.take',10);
         $data=M('book_share')
              ->field('share_id,user_id,book_id,book_name,author,cover_img,is_show,share_time')
              ->where(array('user_id'=>$user_id))
              ->order(array('share_id'=>'desc'))
              ->limit($skip,$take)
              ->select();
        foreach ($data as $k=>$v){
             $userMes = \User\Util\Util::GetUserAvatrAndNick($v['user_id']);
             $data[$k]['username'] = $userMes['name'];
             $data[$k]['user_avatar'] = $userMes['avatar'];
             $data[$k]['cover_img'] = C('QINIU_IMG_PATH').$data[$k]['cover_img'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
         
     }

    //出售的二手图书列表
    public function oldBookList()
    {     
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $user_id = I('get.user_id',0);
        $data=M('book_old')
          ->alias('o')
          ->join('LEFT JOIN kts_book as b ON b.book_id = o.book_id')
          ->where(array('b.isdelete'=>0,'b.type'=>0,'o.user_id'=>$user_id))
          ->field('b.book_id,b.type,b.name,b.price,b.author,b.cover_img,o.description')
          ->order(array('b.book_id'=>'desc'))
          ->limit($skip,$take)
          ->select();
        foreach ($data as $k => $v) {
          $userMes = \User\Util\Util::GetUserAvatrAndNick($user_id);
          $data[$k]['user_avatar'] = $userMes['avatar'];
          $data[$k]['cover_img'] = C('QINIU_IMG_PATH').$v['cover_img'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     /**
      *发布的书评
      * **/  
      public function bookComment(){
         $user_id = I('get.user_id',0);
         $skip=I('get.skip',0);
         $take=I('get.take',10);
         $where['b.user_id'] = array('EQ',$user_id);
         $where['b.fid'] = array('EQ',0);
         $data=M("book_comment")
            ->alias('b')
            ->where($where)
            ->field("b.comment_id,b.type,b.book_id,b.user_id,b.username,b.imageurl as user_avatar,b.comment_time,b.content,b.book_name,b.image as cover_img, b.author,b.grade,b.fid,b.likes,b.sums,b.is_secret,b.is_show,b.is_secret")
            ->order(array('comment_id'=>'desc'))
            ->limit($skip,$take)
            ->select();
        foreach ($data as $k=>$v){
             $userMes = \User\Util\Util::GetUserAvatrAndNick($data[$k]['user_id']);
             $data[$k]['user_avatar'] = $userMes['avatar'];
             $data[$k]['cover_img'] = C('QINIU_IMG_PATH').$data[$k]['cover_img'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
     }

     /**
     * 全部代金卷列表
     */
    public function couponList()
    {
        $skip=I('post.skip',0);
        $take=I('post.take',10);
        $data=M('coupon')->alias('s')
        ->field('s.id,s.createnum,s.name,s.money,s.integration,s.send_start_time,s.send_end_time,s.use_start_time,s.use_end_time')
        ->order(array('s.id'=>'desc'))
        ->limit($skip,$take)
        ->select();
        if ($data){
            $time = strtotime(date('Ymd')) + 86400;
            foreach ($data as $k=>$v){
                $data[$k]['is_out_time']=0;
                if ($v['send_end_time']<$time){
                    $data[$k]['is_out_time']=1;
                }
                $data[$k]['days']=floor(($data[$k]['use_end_time']-$data[$k]['use_start_time'])/3600/24);
            }
            $this->myApiPrint(200,'success',$data);
        }
        $this->myApiPrint(202,'没有数据');
    }

    /**
    * 获取积分列表
    * **/ 
    public function integralList(){
        $user_id = session("user_id"); //用户id
        $skip=I('get.skip',0);
        $take=I('get.take',10);
        $data = M('integration')
            ->where(array('is_deleted'=>0))
            ->field('id,source,number,update_time,icon,state,operate_type,identify')
            ->order('sorts')
            ->limit($skip,$take)
            ->select();
        foreach ($data as $key => $value) {
            $data[$key]['icon'] = C("QINIU_IMG_PATH").$value['icon'];
            $data[$key]['update_time']=date('m月d日 H:i:s',$value['update_time']);
            $is_use=M('integral_xq')->where(array('task_id'=>$value['id']))->find();
            if($is_use) $data[$key]['is_use'] =1;
            else $data[$key]['is_use'] =0;
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);        
    }

     /**
     * 消息推送列表
     */
    public function messageList()
    {   
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $user_id = session("user_id"); //用户id
        $data = M('message')
            ->field('message_id,type,title,content,addtime')
            ->order(array('message_id'=>'desc'))
            ->limit($skip,$take)
            ->select();
        foreach ($data as $key => $value) {
            $data[$key]['addtime']=date("m月d H:i",$value['addtime']);
            $is_read=M('user_message')->where(array('message_id'=>$value['message_id'],'user_id'=>$user_id))->find();
            if(!empty($is_read)) $data[$key]['is_read'] =1;
            else $data[$key]['is_read'] =0;
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
    }

    /**
     * 全部积分商品列表
     */
    public function integralGoodsList()
    {
        $skip=I('post.skip',0);
        $take=I('post.take',10);
        $data=M('integral_goods')->alias('s')
        ->field('s.id,s.name,s.cover_img,s.type,s.name,s.createnum,s.send_num,s.integration,s.send_start_time,s.send_end_time')
        ->order(array('id'=>'desc'))
        ->limit($skip,$take)
        ->select();
        if ($data){
            $time = strtotime(date('Ymd')) + 86400;
            foreach ($data as $k=>$v){
                $data[$k]['is_out_time']=0;
                $data[$k]['remaining ']= 0;
                $data[$k]['cover_img'] = C("QINIU_IMG_PATH").$v['cover_img'];
                if ($v['send_end_time']<$time){
                    $data[$k]['is_out_time']=1;
                }
                if ($data[$k]['createnum']>=$data[$k]['send_num']){
                    $data[$k]['remaining ']=$data[$k]['createnum']-$data[$k]['send_num'];
                }
            }
            $this->myApiPrint(200,'success',$data);
        }
        $this->myApiPrint(202,'没有数据');
    }






    


}

