<?php

use Common\Controller\ApiController;

    function comment_answer($type=0,$three_id,$three_fid,$auser_id=0,$auser_name,$acontent,$atype=0){  
        
        $data=M("answer")->field("id,user_id,user_name,user_content")->where(array('type'=>$type,'three_id'=>$three_fid))->find();
        if(!$data) return 0;
        $arr=array(
                 'fid'=>$data['id'],//父id
                 'type'=>$type,//类型：0话题,1书评
                 'three_id'=>$three_id,//话题评论|话题|书评id
                 'user_id'=>$data['user_id'],//发表人id
                 'user_name'=>$data['user_name'],//发表人的昵称
                 'user_content'=>$data['user_content'],//发表人发表的内容
                 'answer_user_id'=>$auser_id,//回复人id
                 'answer_user_name'=>$auser_name,//回复人昵称
                 'answer_content'=>$acontent,//回复人发的内容
                 'answer_time'=>time(),//回复时间
                 'answer_type'=>$atype//回复类型:0评论,1话题2书评
             );
        $answer=M('answer')->add($arr);  
        if($answer) return 1;
        else return 0;
     }

     function answer($type=0,$three_id=0,$user_id=0,$user_content,$user_name,$auser_id=0,$auser_name,$acontent,$atype=1){  
        
        $arr=array(
                 'fid'=>0,//父id
                 'type'=>$type,//类型：0话题,1书评
                 'three_id'=>$three_id,//话题评论|话题|书评id
                 'user_id'=>$user_id,//发表人id
                 'user_name'=>$user_name,//发表人的昵称
                 'user_content'=>$user_content,//发表人发表的内容
                 'answer_user_id'=>$auser_id,//回复人id
                 'answer_user_name'=>$auser_name,//回复人昵称
                 'answer_content'=>$acontent,//回复人发的内容
                 'answer_time'=>time()//回复时间
             );
        $answer=M('answer')->add($arr);  
        if($answer) return 1;
        else return 0;
     }

     /**
     * 友好时间显示
     * @param $time
     * @return bool|string
     */
    function friend_date($time)
    {
        if (!$time)
            return false;
        $fdate = '';
        $d = time() - intval($time);
        $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
        $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
        $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
        $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
        $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
        $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
        $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
        if ($d == 0) {
            $fdate = '刚刚';
        } else {
            switch ($d) {
                case $d < $atd:
                    $fdate = date('Y年m月d日', $time);
                    break;
                case $d < $td:
                    $fdate = '后天' . date('H:i', $time);
                    break;
                case $d < 0:
                    $fdate = '明天' . date('H:i', $time);
                    break;
                case $d < 60:
                    $fdate = $d . '秒前';
                    break;
                case $d < 3600:
                    $fdate = floor($d / 60) . '分钟前';
                    break;
                case $d < $dd:
                    $fdate = floor($d / 3600) . '小时前';
                    break;
                case $d < $yd:
                    $fdate = '昨天' . date('H:i', $time);
                    break;
                case $d < $byd:
                    $fdate = '前天' . date('H:i', $time);
                    break;
                case $d < $md:
                    $fdate = date('m月d日 H:i', $time);
                    break;
                case $d < $ld:
                    $fdate = date('m月d日', $time);
                    break;
                default:
                    $fdate = date('Y年m月d日', $time);
                    break;
            }
        }
        return $fdate;
    }


    function topic_integral_log($user_id,$descripte,$identify){  
         $integration=M('integration')->field('source,number')->where(array('identify'=>'login','is_deleted'=>0))->find();
         if(empty($integration)) return 0;
         $Integral=M('user');
         $Integral->startTrans();
         $data=$Integral->where(array('user_id'=>$user_id))->find();
         $result=$Integral->where(array('user_id'=>$user_id))->setInc('integral',$integration['number']);
         $after = $integration['number']+$data['integral'];
         if ($result){
             $arr=array(
                 'user_id'=>$user_id,
                 'update_time'=>time(),
                 'amount'=>$integration['number'],
                 'title'=>$integration['source'],
                 'description'=>date('Y-m-d').'绑定'.$descripte.'获取到积分'.$integration['number'],
                 'is_inc'=>1,
                 'before_change'=>$data['integral'],
                 'after_change'=>$after
             );
             $detail=M('integral_xq')->add($arr);            
         }
        if ($detail){ $Integral->commit();return 1;}
        else{$Integral->rollback();return 0;}
   }

   function add_topic_integral_log($user_id){  
         $integration=M('integration')->field('source,number')->where(array('identify'=>'write_topic','is_deleted'=>0))->find();
         if(empty($integration)) return 0;
         $Integral=M('user');
         $Integral->startTrans();
         $daystar=strtotime("today");//今日零点的时间戳
         $dayend=$daystar+"86400";//明日零点(今日24点）的时间戳
         $add_log =M('topic')->where(array('user_id'=>$user_id,'addtime'=>array('between',array($daystar,$dayend))))->count();
         if($add_log>3){
             return 0;
         }else{
             $data=$Integral->where(array('user_id'=>$user_id))->find();
             $result=$Integral->where(array('user_id'=>$user_id))->setInc('integral',$integration['number']);
             $after = $integration['number']+$data['integral'];
             if ($result){
                 $arr=array(
                     'user_id'=>$user_id,
                     'update_time'=>time(),
                     'amount'=>$integration['number'],
                     'title'=>$integration['source'],
                     'description'=>date('Y-m-d').'发帖获取到积分'.$integration['number'],
                     'is_inc'=>1,
                     'before_change'=>$data['integral'],
                     'after_change'=>$after
                 );
                 $detail=M('integral_xq')->add($arr);            
             }
             if ($detail){ $Integral->commit();return 1;}
             else{$Integral->rollback();return 0;}
         } 
          
   }

    function add_comment_integral_log($user_id){  
         $integration=M('integration')->field('source,number')->where(array('identify'=>'reply_topic','is_deleted'=>0))->find();
         if(empty($integration)) return 0;
         $Integral=M('user');
         $Integral->startTrans();
         $daystar=strtotime("today");//今日零点的时间戳
         $dayend=$daystar+"86400";//明日零点(今日24点）的时间戳
         $add_log =M('topic_comment')->where(array('user_id'=>$user_id,'addtime'=>array('between',array($daystar,$dayend))))->count();
         if($add_log>2){
             return 0;
         }else{
             $data=$Integral->where(array('user_id'=>$user_id))->find();
             $result=$Integral->where(array('user_id'=>$user_id))->setInc('integral',$integration['number']);
             $after = $integration['number']+$data['integral'];
             if ($result){
                 $arr=array(
                     'user_id'=>$user_id,
                     'update_time'=>time(),
                     'amount'=>$integration['number'],
                     'title'=>$integration['source'],
                     'description'=>date('Y-m-d').'回复帖子获取到积分'.$integration['number'],
                     'is_inc'=>1,
                     'before_change'=>$data['integral'],
                     'after_change'=>$after
                 );
                 $detail=M('integral_xq')->add($arr);            
             }
             if ($detail){ $Integral->commit();return 1;}
             else{$Integral->rollback();return 0;}
         } 
          
   }


   function getUserAvater($user_id){
       $userMes = \User\Util\Util::GetUserAvatrAndNick($user_id);
       return $userMes;
   }


     
 ?>